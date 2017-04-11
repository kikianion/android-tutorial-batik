<?php

class Debug extends CI_Controller
{

    public function index()
    {
        $this->load->helper('directory');

        $res = $frames = directory_map('.', 1);
        echo json_encode($res);
    }
}

?>