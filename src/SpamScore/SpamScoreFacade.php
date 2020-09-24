<?php
namespace EBethus\SpamScore;

use Illuminate\Support\Facades\Facade;

class SpamScoreFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SpamScore::class;
    }
}
