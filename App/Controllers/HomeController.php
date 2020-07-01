<?php

namespace App\Controllers;

use System\Controller;


class HomeController extends Controller
{
    public function index()
    {
       
        // $this->db->data([
        //     'email' => 'hasan',
        //     'status'  => 'enabled',

        // ])->insert('users')->lastId();
        // $this->db->query('INSERT INTO users SET email=? , status=?' , 'med@gmail.com' , 'disabled');
        // $user = $this->db->query('SELECT * FROM users WHERE id = ?', 2)->fetch();
        
        // pre($user);
        $this->db->data('email', 'judo@gmail.com')
                ->where('id= ?', 1)
                ->update('users');
       
    }
}













?>