<?php

namespace Config;

use CodeIgniter\Config\BaseConfig; 
class Feature extends BaseConfig
{ 

    // set true untuk auto route
    public bool $autoRoutesImproved = false; 
    public bool $oldFilterOrder = false; 
    public bool $limitZeroAsAll = true; 
    public bool $strictLocaleNegotiation = false;
}