<?php

require_once ("secure_area.php");

class Home extends Secure_area {

    protected $controller_name;

    function __construct() {
        parent::__construct();
        $this->controller_name = strtolower($this->uri->segment(1));
    }

    function index() {
        // $this->output->enable_profiler(TRUE);
        $data['controller_name'] = $this->controller_name;
        $data['title'] = 'home_home';
        $this->twiggy->set($data);
        $this->twiggy->display('home');
//        $this->load->view("home", $data);
    }

    function logout() {
        $this->Employee->logout();
    }

}