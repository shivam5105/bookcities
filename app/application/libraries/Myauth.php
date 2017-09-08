<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Myauth {
    var $_user;
    public function __construct(){
		$this->load->model('users');
    }
	public function __get($var){
		return get_instance()->$var;
	}
	public function __call($method, $arguments){
		if (!method_exists( $this->users, $method) ){
			throw new Exception('Undefined method MyAuth::' . $method . '() called');
		}
		if($method == 'create_user'){
			return call_user_func_array(array($this, 'register'), $arguments);
		}
		if($method=='update_user'){
			return call_user_func_array(array($this, 'update'), $arguments);
		}
		return call_user_func_array( array($this->users, $method), $arguments);
	}
	public function logged_in(){
		return (bool) $this->session->userdata('user');	
	}
	public function admin_logged_in(){
		return (bool) $this->session->userdata('admin');	
	}
	public function exit_admin_sessions(){
		$this->session->unset_userdata('admin');
	}
	public function exit_user_sessions(){
		$this->session->unset_userdata('user');
	}
	public function logout($msg = null, $redirect = null){
		$this->session->unset_userdata('user');
		$this->session->unset_userdata('admin');
		//$this->session->sess_destroy();
		if($msg == null){
			$this->session->set_flashdata('success', 'You logged out successfully.');
		}else{
			$this->session->set_flashdata('success', $msg);
		}
		if($redirect == null){
			redirect('auth/login');
		}else{
			redirect($redirect);
		}
	}
}