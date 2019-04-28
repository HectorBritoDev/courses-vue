<?php

namespace App\Http\Controllers;

class SubscriptionController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) { //MIDDLEWARE CREADO MANUALMENTE NO POR ARTISAN MAKE

            if (auth()->user()->subscribed('main')) { //COMPROBAMOS SI EL USUARIO ESTA SUSCRITO A LA PLATAFORMA
                return redirect('/')->with('message', ['warning', __('Actualmente estas suscrito a otro plan')]);
            }
            return $next($request);
        })
            ->only('plans', 'processSubscription'); //SOLO SE APLICA A ESTOS METODOS
    }
    public function plans()
    {
        return view('subscriptions.plans');
    }

    public function processSubscription()
    {
        $token = request('stripeToken');
        //dd(request()->all());
        try {
            if (request()->has('coupon')) {
                request()->user()->newSubscription('main', request('type'))
                    ->withCoupon(request('coupon'))
                    ->create($token);
            } else {
                request()->user()->newSubscription('main', request('type'))
                    ->create($token);
            }

            return redirect(route('subscriptions.admin'))
                ->with('message', ['success', __('La subscripción se ha llevado a cabo correctamente')]);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return back()->with('message', ['danger', $error]);
        }
    }

    public function admin()
    {
        $subscriptions = auth()->user()->subscriptions;
        return view('subscriptions.admin', compact('subscriptions'));
    }

    public function resume()
    {
        $subscriptions = auth()->user()->subscriptions(request('plan'));
        if ($subscriptions->cancelled() && $subscriptions->onGracePeriod()) {
            request()->user()->subscription(request('plan'))->resume();
            return back()->with('message', ['success', __('Haz reanudado tu suscripción correctamente')]);
        }
        return back();
    }
    public function cancel()
    {
        $subscriptions = auth()->user()->subscriptions;
        auth()->user()->subscription(request('plan'))->cancel();
        return back()->with('message', ['success', __('La suscripción se ha cancelado correctamente')]);
    }
}
