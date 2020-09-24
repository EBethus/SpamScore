<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SpamScore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EBethus\SpamScore\SpamScore::class;
    }
}
