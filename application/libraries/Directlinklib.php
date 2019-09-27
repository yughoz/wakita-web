<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DirectLinkLib {


 	var $urlTarget = "";
    // function __construct()
    // {
    //     parent::__construct();
    //     // is_login();
    //     date_default_timezone_set('Asia/Jakarta');
    //     $this->startRes = time();
    //     // $this->apiToken     = "";
    //     // $this->urlTarget    = "";
    //     $this->load->model('Contact_model');

    // }
    function file($url)
    {
    	echo "ini url : ".$url;
    	return false;
    }
    function create_link($url)
    {
    	// echo "ini url : ".$url;
    	$pars = parse_url($url);
    	$path = $this->urlTarget.$pars['path'];



    	// echo $this->urlTarget;
    	// echo $pars['path'];die();

    	return $path;
    }
}
?>