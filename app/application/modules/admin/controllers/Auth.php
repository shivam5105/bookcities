<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends Admin_Controller {
	function __construct(){
		parent::__construct();
		$this->layout->setLayout('admin');
		$action = $this->router->fetch_method();
		if ($this->myauth->admin_logged_in() && $action != 'logout'){
			$this->data['admin'] = $this->session->userdata('admin');
			redirect('admin');
		}
	}
	function index(){
		$this->myauth->exit_user_sessions();
		$this->layout->set_title("Login");
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == true)
		{
			$admin = $this->users->admin_login($this->input->post('email'), $this->input->post('password'));
			if ($admin){
				$this->data['admin'] = $this->session->userdata('admin');
				$this->session->set_flashdata('success', "Welcome to MyLifeAs");
				redirect('admin');
			}else{
				$this->session->set_flashdata('error', "Email / password not correct.");
				redirect('admin/auth');
			}
		}
		$this->layout->view('auth/login', $this->data);
	}
	function logout(){
		$this->myauth->logout("You logged out successfully.", '/admin/auth');
	}
}
