<?php

namespace App\Policies;

use App\Course;
use App\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    //Suscribirse = pagar para tener acceso a la plataforma y los cursos.

    // SI UN USUARIO SE PUEDE SUSCRIBIR O NO A UN CURSO
    public function opt_for_course(User $user, Course $course)
    {
        //SI EL USUARIO NO ES PROFESOR O NO ES EL QUE CREO EL CURSO VA A TENER QUE SUSCRIBIRSE A LA PLATADORMA (PAGANDO UN PLAN)
        //dd($user->teacher);
        return !$user->teacher || $user->teacher->id !== $course->teacher_id;
    }

    //SOLO SE PUEDE SUSCRIBIR DIFERENTES DE ADMINISTRADOR Y  USUARIOS QUE NO ESTAN SUSCRITOS
    public function subscribe(User $user)
    {
        //SI ERES ADMINISTRADOR O UN USUARIO QUE YA PAGO, NO NECESITAS UN PLAN DE SUBSCRIPCION
        return $user->role_id !== Role::ADMIN && !$user->subscribed('main');
    }

    public function inscribe(User $user, Course $course)
    {
        //Comprueba a travez de contains si alguno de los estudiantes inscritos al curso es el usuario que esta autenticado
        //hace uso de la relacion muchos a muchos :)
        return !$course->students->contains($user->student->id);
    }
    public function review(User $user, Course $course)
    {
        //Comprueba a travez de contains si alguno de los estudiantes inscritos al curso es el usuario que esta autenticado
        //hace uso de la relacion muchos a muchos :)
        return !$course->reviews->contains('user_id', $user->id);
    }
}
