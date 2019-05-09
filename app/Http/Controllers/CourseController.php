<?php

namespace App\Http\Controllers;

use App\Course;
use App\Helpers\Helper;
use App\Http\Requests\CourseRequest;
use App\Mail\NewStudentInCourse;
use App\Review;
use Illuminate\Support\Facades\Mail;

class CourseController extends Controller
{
    public function show(Course $course)
    {

        /*NO SE USA Course::with porque se va a traer todos los registros de la BD, solo queremos el que accedio el usuario
        Dicho modelo ya esta en $course, así que usamos ese.

        SI SE USA WITH EN VEZ DE LOAD NO VA A FUNCIONAR, PORQUE WITH SE UTILIZA ES EN EL MOMENTO DE LA CONSULTA
        LOAD SE UTILIZA CUANDO LA CONSULTA YA FUE HECHA Y QUIERES CARGAR DATOS O RELACIONES ADICIONALES*/

        $course->load([

            //EN DADO CASO QUE QUERAMOS PARAMETROS ADICIONALES O SOBREESCRIBIR LOS QUE YA PUSIMOS EN EL MODEL
            'category' => function ($q) {
                $q->select('id', 'name');
            },
            'goals' => function ($q) {
                $q->select('id', 'course_id', 'goal');
            },
            'level' => function ($q) {
                $q->select('id', 'name');
            },
            'requirements' => function ($q) {
                $q->select('id', 'course_id', 'requirement');
            },

            //ACCEDER A TODAS LAS  VALORACIONES DEL CURSO Y DENTRO DE ELLAS A LOS USUARIOS A LOS QUE PERTENECE
            'reviews.user',
            //DATOSDEL PROFESOR QUE DA EL CURSO
            'teacher',
        ])->get();

        $related = $course->relatedCourses();

        return view('courses.detail', compact('course', 'related'));
    }

    public function inscribe(Course $course)
    {
        // return new NewStudentInCourse($course, 'admin');
        $course->students()->attach(auth()->user()->student->id);

        Mail::to($course->teacher->user)->send(new NewStudentInCourse($course, auth()->user()->name));

        return back()->with('message', ['success', __('Inscrito correctamente al curso')]);
    }

    public function subscribed()
    {
        //TAMBIEN SIRVE auth()->user()->student->courses
        //PERO ESTA FORMA ES NUEVA Y POR ESO SE USA.
        $courses = Course::whereHas('students', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->get();

        return view('courses.subscribed', compact('courses'));
    }

    public function addReview()
    {

        Review::create([
            'user_id' => auth()->id(),
            'course_id' => request('course_id'),
            'rating' => (int) request('rating_input'),
            'comment' => request('message'),
        ]);

        return back()->with('message', ['success', __('Muchas gracias por valorar el curso')]);
    }

    public function create()
    {
        $course = new Course;
        $btnText = __('Enviar curso para revisión');

        return view('courses.form', compact('course', 'btnText'));
    }

    public function store(CourseRequest $course_request)
    {
        $picture = Helper::uploadFile('picture', 'courses');
        $course_request->merge(['picture' => $picture]);
        $course_request->merge(['teacher_id' => auth()->user()->teacher->id]);
        $course_request->merge(['status' => Course::PENDING]);
        //   dd($course_request->except('_token'));
        Course::create($course_request->input());

        return back()->with('message', ['success', __('Curso enviado correctmente, recibirá un correo con cualquier información')]);
    }
}
