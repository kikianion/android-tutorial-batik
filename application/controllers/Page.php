<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->helper('url');
        echo "frame index";
    }

    public function load($name)
    {
		    $this->load->view("page/".$name);
            // echo $name;
    }

    public function listFramePageContent($frame, $page)
    {
        $this->load->helper('directory');

        $data = array();
        $frame = urldecode($frame);
        $page = urldecode($page);

        $frames = directory_map('assets/images/frame/' . $frame . "/" . $page, 1);
        foreach ($frames as $key => $val) {
            if (strpos($val, "/") === false) {
                $data[] = $val;
            }
        }

        $res['result'] = 'ok';
        $res['data'] = $data;

        header('Content-Type: application/json');
        echo json_encode($res);
    }
}
