<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config = array(
	'signup' => array(
		array(
			'field' => 'first_name',
			'label' => 'First Name',
			'rules' => 'required'
		),
		array(
			'field' => 'last_name',
			'label' => 'Last Name',
			'rules' => 'required'
		),
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'required|valid_email|is_unique[users.email]'
		),
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'required|min_length[8]|max_length[20]'
		),
		array(
			'field' => 'cnf_password',
			'label' => 'Confirm Password',
			'rules' => 'required|matches[password]'
		)
	),
	'password' => array(
		array(
			'field' => 'old_pwd',
			'label' => 'Current Password',
			'rules' => 'required|min_length[8]|max_length[20]'
		),
		array(
			'field' => 'new_pwd',
			'label' => 'New Password',
			'rules' => 'required|min_length[8]|max_length[20]'
		),
		array(
			'field' => 'cnew_pwd',
			'label' => 'Confirm Password',
			'rules' => 'required|matches[new_pwd]'
		)
	),
	'user_update' => array(
		array(
			'field' => 'first_name',
			'label' => 'First Name',
			'rules' => 'required'
		),
		array(
			'field' => 'last_name',
			'label' => 'Last Name',
			'rules' => 'required'
		)
	),
	'book-category' => array(
		array(
			'field' => 'name',
			'label' => 'Category Name',
			'rules' => 'required'
		)
	)
);