<?php


namespace Juanparati\Sendinblue\Facades;

use Illuminate\Support\Facades\Facade;


class Template extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Juanparati\Sendinblue\Template::class;
    }
}