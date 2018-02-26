<?php


namespace Juanparati\Sendinblue\Facades;

use Illuminate\Support\Facades\Facade;


class SMS extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Juanparati\Sendinblue\SMS::class;
    }
}