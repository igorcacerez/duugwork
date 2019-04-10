<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 26/03/2019
 * Time: 18:29
 */

namespace Controller;

use Sistema\Controller as CI_controller;


class Principal extends CI_controller
{
    function __construct()
    {
    }


    public function index()
    {
        parent::view("index");
    }
    

} // END::Class Principal