<?php

use Faker\Generator as Faker;

$factory->define(App\Review::class, function (Faker $faker) {
    return [
        'course_id' => \App\Course::all()->random()->id,
        'rating' => $faker->randomFloat(2, 1, 5), //PRIMERO EL NUMERO DE DECIMALES, LUEGO EL VALOR MÍNIMO Y EL VALOR MÁXIMO

    ];
});
