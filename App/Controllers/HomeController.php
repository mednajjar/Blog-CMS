<?php

namespace App\Controllers;

use system\Controller;

class HomeController extends Controller
{
    public function index()
    {
       echo $this->session->get('name');
    }
}













?>