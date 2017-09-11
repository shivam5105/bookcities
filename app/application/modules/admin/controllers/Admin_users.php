<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_users extends Admin_Controller {
	function __construct(){
		parent::__construct();
		$this->layout->setLayout('admin');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->users->check_access_permission();
	}
	public function index(){
		redirect("/admin/admin_users/manage");
	}
	public function manage(){
		$this->layout->set_title("Users");

		$condition 	= array("id != " => 1);
		$table		= "users";

		$total_records = $this->mydb->get_total_records($table, $condition);

		$this->config->load("pagination");
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config["total_rows"] = $total_records;
		$config["base_url"] = base_url($this->uri->segment(1).'/'. $this->uri->segment(2).'/'. $this->uri->segment(3));
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->data["pagination"] = $this->pagination->create_links();
        $this->data['skipped_records'] = $page;
		$this->data['users'] = $this->mydb->get_all($table, $condition, array(), "first_name ASC, last_name", $config["per_page"], $page);
		$this->data['total_records'] = $total_records;
		$this->layout->view('admin-users/home', $this->data);
	}
	public function create(){
		$this->layout->set_title("Create New User");

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email', 'User Email', 'trim|required|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('cnf_password', 'Confirm Password', 'trim|required|matches[password]');

		if ($this->form_validation->run() == true)
		{
			$postdata = $this->input->post();
			$postdata = trim_data($postdata);
			
			$email_arr 	= @explode("@", $postdata['email']);
			$username	= $email_arr[0];

			$user = $this->users->register($username, $postdata);
			if($user)
			{
				$this->session->set_flashdata('success', "User created successfully!");
				redirect('admin/admin-users/create/');
			}
			else
			{
				$this->session->set_flashdata('error', "Something went wrong. Please try again");
			}
		}
		$this->layout->view('admin-users/create', $this->data);
	}
	public function edit($id = null){
		$table		= "users";

		$condition 	= array('id'=>$id);

		$user = $this->mydb->get($table,$condition);
		if($user)
		{
			$this->layout->set_title("Edit User");
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

			if ($this->form_validation->run() == true)
			{
				$postdata = $this->input->post();
				$postdata = trim_data($postdata);
				
				$email_arr 	= @explode("@", $postdata['email']);
				$username	= $email_arr[0];

				$user_id =  $id;

				$user = $this->users->update($user_id, $postdata);

				if($user)
				{
					$this->session->set_flashdata('success', "User updated successfully!");
					redirect('admin/admin-users/edit/'.$user_id);
				}
				else
				{				
					$this->session->set_flashdata('error', "Something went wrong. Please try again");
				}
				$user = $this->mydb->get($table,$condition);
			}
			$this->data['user'] = $user;
			$this->layout->view('admin-users/edit', $this->data);
		}else{
			$this->session->set_flashdata('error', "User not found!");
			redirect('admin/admin-users/');
		}
	}
	public function change_password($id){
		$table		= "users";
		$condition 	= array('id'=>$id);

		$user = $this->mydb->get($table,$condition);
		if($user)
		{
			$this->layout->set_title("Edit User");
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('cnf_password', 'Confirm Password', 'trim|required|matches[password]');

			if ($this->form_validation->run() == true)
			{
				$postdata = $this->input->post();
				$postdata = trim_data($postdata);
				$user_id =  $id;
				$postdata['new_pwd'] = $postdata['password'];
				
				unset($postdata['password']);
				unset($postdata['cnf_password']);

				$user = $this->users->updatepassword($user_id, $postdata, false);

				if($user)
				{
					$this->session->set_flashdata('success', "User password updated successfully!");
					redirect('admin/admin-users/');
				}
				else
				{				
					$this->session->set_flashdata('error', "Something went wrong. Please try again");
				}
				$user = $this->mydb->get($table,$condition);
			}
			$this->data['user'] = $user;
			$this->layout->view('admin-users/change-password', $this->data);
		}else{
			$this->session->set_flashdata('error', "User not found!");
			redirect('admin/admin-users/');
		}
	}
	public function delete($id){
		$deleted = $this->mydb->delete('users', $id);
		if($deleted)
		{
			$this->session->set_flashdata('success', "User deleted successfully!");
		}
		else
		{
			$this->session->set_flashdata('error', "Something went wrong. Please try again");
		}
		redirect('admin/admin-users/');
	}
}
