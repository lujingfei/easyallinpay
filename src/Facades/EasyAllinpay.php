<?php
namespace Geoff\EasyAllinpay\Facades;

use Illuminate\Support\Facades\Facade;

class EasyAllinpay extends Facade{
    protected static function getFacadeAccessor()
    {
        return 'easyallinpay';
    }
}