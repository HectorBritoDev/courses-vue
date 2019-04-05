<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //ELIMINAMOS LAS CARPETAS DE COURSES Y USERS
        Storage::deleteDirectory('courses');
        Storage::deleteDirectory('users');
//CREAMOS LAS CARPETAS DE COURSES Y USERS
        Storage::makeDirectory('courses');
        Storage::makeDirectory('users');

        //CREAMOS LOS ROLES
        factory(\App\Role::class, 1)->create(['name' => 'admin']);
        factory(\App\Role::class, 1)->create(['name' => 'teacher']);
        factory(\App\Role::class, 1)->create(['name' => 'student']);

        //TODOS EN LA PLATAFORMA, A PESAR DEL ROL QUE TENGAN, TAMBIEN SERAN ESTUDIANTES

        //CREAMOS EL USUARIO ADMIN
        factory(\App\User::class, 1)->create([
            'name' => 'admin',
            'email' => 'admin@dev.com',
            'password' => bcrypt(123),
            'role_id' => \App\Role::ADMIN,
        ])

            ->each(function (\App\User $user) {
                factory(\App\Student::class, 1)->create(['user_id' => $user->id]);
            });

        //GENERAMOS 10 USUARIOS (PROFESORES)
        factory(\App\User::class, 10)->create()
            ->each(function (\App\User $user) {
                factory(\App\Student::class, 1)->create(['user_id' => $user->id]);
                factory(\App\Teacher::class, 1)->create(['user_id' => $user->id]);
            });
        //GENERAMOS 50 USUARIOS (ESTUDIANTES)
        factory(\App\User::class, 50)->create()
            ->each(function (\App\User $user) {
                factory(\App\Student::class, 1)->create(['user_id' => $user->id]);
            });

        //GERENAMOS LOS NIVELES
        factory(\App\Level::class, 1)->create(['name' => 'Beginner']);
        factory(\App\Level::class, 1)->create(['name' => 'Intermediate']);
        factory(\App\Level::class, 1)->create(['name' => 'Advance']);
        //GENERAMOS LAS CATEGORIAS
        factory(\App\Category::class, 5)->create();

        //GENERAMOS LOS CURSOS

        factory(\App\Course::class, 50)
            ->create()
            ->each(function (\App\Course $c) {

                $c->goals()->saveMany(factory(\App\Goal::class, 2)->create());
                $c->goals()->saveMany(factory(\App\Requirement::class, 4)->create());
            });
    }
}
