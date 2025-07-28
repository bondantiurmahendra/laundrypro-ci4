<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig; 
class Autoload extends AutoloadConfig
{
    /** 
     * @var array<string, list<string>|string>
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH,
        'Midtrans'    => ROOTPATH . 'vendor/midtrans/midtrans-php/Midtrans',
    ];

    /** 
     * @var array<string, string>
     */
    public $classmap = [];

    /** 
     * @var list<string>
     */
    public $files = [];

    /** 
     * @var list<string>
     */
    public $helpers = [];
}