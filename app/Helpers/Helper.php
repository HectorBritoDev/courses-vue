<?php

namespace App\Helpers;

class Helper
{

    //La Key en nuestro caso es 'PICTURE' que es el nombre del campo en el formulario
    //El Path en donde queremos guardar
    public static function uploadFile($key, $path)
    {
        request()->file($key)->store($path);
        return request()->file($key)->hashName();
    }
}
