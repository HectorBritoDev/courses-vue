<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mail\CourseApproved;
use App\Mail\CourseRejected;
use App\VueTables\EloquentVueTables;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function courses()
    {
        return view('admin.courses');
    }

    public function coursesJson()
    {
        if (request()->ajax()) {
            $vueTables = new EloquentVueTables;
            $data = $vueTables->get(new Course, ['id', 'name', 'status'], ['reviews']);
            return response()->json($data);
        }
        return abort(401);
    }

    public function updateCurrentStatus()
    {
        if (request()->ajax()) {
            $course = Course::findOrFail(request('courseId'));

            if ((int) $course->status !== Course::PUBLISHED && // EL CURSO ESTA EN UN ESTADO DIFERENTE DE PUBLICADO
                !$course->previous_approved && //EL CURSO NUNCA HA SIDO APROBADO
                request('status') === Course::PUBLISHED) { //EL ESTADO QUE SE QUIERE ACTUALIZAR ES APROBAADO

                $course->previous_approved = true;
                Mail::to($course->teacher->user)->send(new CourseApproved($course));
            }

            if ((int) $course->status !== Course::REJECTED && // EL CURSO ESTA EN UN ESTADO DIFERENTE DE RECHAZADO
                !$course->previous_rejected && //EL CURSO NUNCA HA SIDO RECHAZADO
                request('status') === Course::REJECTED) { //EL ESTADO QUE SE QUIERE ACTUALIZAR ES RECHAZADO
                $course->previous_rejected = true;
                Mail::to($course->teacher->user)->send(new CourseRejected($course));
            }

            $course->status = request('status');

            $course->save();

            return response()->json(['msg' => 'ok']);

        }
        return abort(401);
    }

    public function student()
    {
        return view('admin.students');
    }

    public function teachers()
    {
        return view('admin.teachers');
    }

}
