<?php

namespace App\Controllers;

class User extends BaseController {
    public function index() {
        return view('protected/user/index');
    } 
    public function order() {
        return view('protected/user/order');
    } 
}