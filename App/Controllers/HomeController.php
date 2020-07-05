<?php

namespace App\Controllers;

use System\Controller;


class HomeController extends Controller
{
    public function index()
    {
        // echo assets('images/logo.png');
        // echo $this->url->link('/home');
        // $users = $this->load->model('Users');
        // pre($users->all());
        // pre($this->db->where('id !=?', 2)->fetchAll('users'));
        // echo $this->db->rows();
        // pre($this->db->fetchAll('users'));
        // $users = $this->db->select('*')->from('users')->orderBy('id')->fetchAll();

        // pre($users);
       
        // $this->db->data([
        //     'email' => 'hasan',
        //     'status'  => 'enabled',

        // ])->insert('users')->lastId();
        // $this->db->query('INSERT INTO users SET email=? , status=?' , 'med@gmail.com' , 'disabled');
        // $user = $this->db->query('SELECT * FROM users ORDER BY id')->fetchAll();
        
        // pre($user);
        // $this->db->data('email', 'judo@gmail.com')
        //         ->where('id= ?', 1)
        //         ->update('users');
       
        
    }
}













?>