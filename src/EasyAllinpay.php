<?php

namespace Geoff\EasyAllinpay;

use Illuminate\Session\SessionManager;
use Illuminate\Config\Repository;

class EasyAllinpay
{

    /*
     * @var SessionManager
     */
    protected $session;

    /*
     * @var ConfigRespository
     */
    protected $config;

    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }
}