<?php

namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->response->setHeader('name', 'Mido');

        

        $data['my_name'] = 'Mohammed';
       return $this->view->render('home', $data);

      
    }
}













?>