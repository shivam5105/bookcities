<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	public function get_users($condition = array(), $select = array(), $order = "", $limit = null){
		$this->db->select($select);
		$this->db->from('users');
		$this->db->where($condition);
		$this->db->limit($limit);
		$this->db->order_by($order);
		
		$query = $this->db->get();
		return $query->result();
	}
	public function get_user($condition = array(), $select = array(), $order = ""){
		$this->db->select($select);
		$this->db->from('users');
		$this->db->where($condition);
		$this->db->order_by($order);
		$this->db->limit(1);
		
		$query = $this->db->get();
		return $query->row();
	}
	public function update($id, $data = array()){
		$this->db->where('id', $id);
		return $this->db->update('users', $data);
	}
	public function update_cuser($id, $data = array()){
		$this->db->where('id', $id);
		$update = $this->db->update('users', $data);
		if($update){
			$q = $this->db->get_where('users', array('id' => $id));
			$user = $q->row();
			unset($user->password, $user->created_at);
			$user = (array) $user;
			$this->session->set_userdata('user', $user);
			return true;
		}
	}
	public function register($username, $postdata = array()){
		if($this->is_user_exist($username)) return false;
		else{
			$pass = password_hash($postdata['password'], PASSWORD_BCRYPT);
			unset($postdata['password']);
			unset($postdata['cnf_password']);			
			$hash = md5($username);
			$data = array(
				'username'=>$username,
				'act_code'=>$hash,
				'password'=>$pass
			);
			$userdata = array_merge($data, $postdata);
			$this->db->set($userdata);
			if(!$this->db->insert('users')){
				$this->session->set_flashdata($this->db->error());
				return false;
			}else{
				$id = $this->db->insert_id();
				$q = $this->db->get_where('users', array('id' => $id));
				$user = $q->row();
				unset($user->password, $user->created_at);
				return $user;
			};
		}
	}
	public function updatepassword($user_id, $data = array(), $oldpwd = true){
		$query = $this->db->where(array('id' => $user_id, 'role !='=> 1))->limit(1)->get('users');
		if ($query->num_rows() === 1){
			$user = $query->row();
			if($oldpwd){
				$chkpass = password_verify($data['old_pwd'], $user->password);
				if(!$chkpass){
					$this->session->set_flashdata('error', "Incorrect current password");
					return false;
				}else{
					$pass = password_hash($data['new_pwd'], PASSWORD_BCRYPT);
					$this->db->where('id', $user_id);
					$update = $this->db->update('users', array('password' => $pass));
					return true;
				}
			}else{
				$pass = password_hash($data['new_pwd'], PASSWORD_BCRYPT);
				$this->db->where('id', $user_id);
				$update = $this->db->update('users', array('password' => $pass));
				return true;
			}
		}else{
			$this->session->set_flashdata('error', "Invalid user");
			return false;
		}
	}
	public function is_user_exist($identity){
		$query = $this->db
		->where(array('email' => $identity))
		->or_where(array('username' => $identity))
		->limit(1)
		->get('users');
		if ($query->num_rows() === 1)
			return $query->row();
		else 
			return false;
	}
	public function login($identity, $password)
	{
		return false;
		/*$query = $this->db
		->where(array('email' => $identity, 'role !='=>2))
		->or_where(array('username' => $identity))
		->limit(1)
		->get('users');
		if ($query->num_rows() === 1){
			$user = $query->row();
			$chkpass = password_verify($password, $user->password);
			if(!$chkpass){
				return false;
			}else{
				unset($user->password, $user->created_at, $user->act_code);
				$user = (array) $user;
				$this->session->set_userdata('user', $user);
				return true;
			}
		}else{
			return false;
		}*/
	}
	public function admin_login($identity, $password){
		$query = $this->db
					->where(array('email' => $identity, 'status'=>1))
					->limit(1)
					->get('users');

		if ($query->num_rows() === 1){
			$admin = $query->row();
			$chkpass = password_verify($password, $admin->password);
			if(!$chkpass){
				return false;
			}else{
				unset($admin->password, $admin->created_at, $admin->act_code);
				$admin = (array) $admin;
				$this->session->set_userdata('admin', $admin);
				return true;
			}
		}else{
			return false;
		}		
	}
	function get_loggedin_data()
	{
		$get_userdata 	= $this->session->get_userdata();
		$loggedin_data 	= $get_userdata['admin'];
		return $loggedin_data;
	}
	function check_access_permission()
	{
		$loggedin_data = $this->get_loggedin_data();
		$loggedin_user_role = $loggedin_data['role'];
		if($loggedin_user_role == 2)
		{
			$this->session->set_flashdata('error', "Oops! You are not authorised to perform this task.");
			redirect("/admin/");
		}
	}
}