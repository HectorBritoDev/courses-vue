<?php

namespace App\Http\Controllers;

use App\User;
use App\Student;
use App\Mail\MessageToStudent;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{

    public function students()
    {
        //Muestra los estudiantes y a cuales de mis cursos esta suscrito.
        $students = Student::with('user', 'courses.reviews')->whereHas('courses', function ($q) {
            $q->where('teacher_id', auth()->user()->teacher->id)
                ->select('courses.id', 'teacher_id', 'name')
                ->withTrashed();
        })->get();

        $actions = 'students.dataTables.actions';
        //dd($students);
        return DataTables::of($students)->addColumn('actions', $actions)->rawColumns(['actions', 'courses_formatted'])->make(true);
    }

    public function sendMessageToStudent()
    {
        $info = request('info'); //Recuperamos la información que envia Ajax
        $data = [];
        parse_str($info,$data); // Parseamos (pasamos la información ya formateada) la información de $info en $data
        $user= User::findOrFail($data['user_id']);
        try {
            Mail::to($user)->send(new MessageToStudent(auth()->user()->name, $data['message']));
            $success = true;
            //dd($user);
        } catch (\Throwable $th) {
            $success = false;
        }
        return response()->json(['data'=>$success]);
    }

    public function courses()
    {

    }
}
