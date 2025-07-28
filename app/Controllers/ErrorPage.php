<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ErrorPage extends Controller
{
    public function notFound()
    {
        // custom logic here (log, redirect, dsb.)
        return view('errors/custom_404');
    }
}