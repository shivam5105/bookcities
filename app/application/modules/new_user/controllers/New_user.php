<?php defined('BASEPATH') OR exit('No direct script access allowed');
class New_user extends CI_Controller {
	
	public function index(){
		$this->load->view('user_new');
	}
	public function comment(){
		echo "bisht";
	}	
}