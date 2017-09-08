<?php
class App_Controller extends CI_Controller {
	public $_user;
	function __construct(){
		parent::__construct();
		$this->load->library(array('myauth','form_validation'));
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		
//		$this->data['footer_links'] = $this->navigation->get_nav_tree(array('position'=>0));
		$this->data['uri_params'] = $this->input->get();
		if($this->session->flashdata('success')){
			$this->data['flash']['status'] = 'success';
			$this->data['flash']['msg'] = $this->session->flashdata('success');
		}elseif($this->session->flashdata('error')){
			$this->data['flash']['status'] = 'error';			
			$this->data['flash']['msg'] = $this->session->flashdata('error');
		}else{
			$this->data['flash']['status'] = 'note';			
			$this->data['flash']['msg'] = $this->session->flashdata('note');
		}
		$excluded = array('api','auth','emails','shop');
		$controller = $this->router->fetch_class();
		if (!$this->myauth->logged_in() && !in_array($controller, $excluded)){
			redirect('auth/login');
		}else{
			$this->data['cuser'] = $this->_user = $this->session->userdata('user');
		}
	}
}
class Admin_Controller extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library(array('myauth','form_validation'));
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		
		$this->data['uri_params'] = $this->input->get();
		if($this->session->flashdata('success')){
			$this->data['flash']['status'] = 'success';
			$this->data['flash']['msg'] = $this->session->flashdata('success');
		}elseif($this->session->flashdata('error')){
			$this->data['flash']['status'] = 'error';			
			$this->data['flash']['msg'] = $this->session->flashdata('error');
		}else{
			$this->data['flash']['status'] = 'note';
			$this->data['flash']['msg'] = $this->session->flashdata('note');
		}
		
		$excluded = array('api','auth','emails','shop');
		$controller = $this->router->fetch_class();
		if (!$this->myauth->admin_logged_in() && !in_array($controller, $excluded)){
			redirect('admin/auth');
		}else{
			$this->data['admin'] = $this->session->userdata('admin');
		}
	}
}