<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books_categories extends Admin_Controller {
	function __construct(){
		parent::__construct();
		$this->layout->setLayout('admin');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->users->check_access_permission();
	}
	public function index(){
		redirect("/admin/books_categories/manage");
	}
	public function manage(){
		$this->layout->set_title("Book Categories");

		$condition 	= array();
		$table		= "book_categories";

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
		$this->data['bookcategories'] = $this->mydb->get_all($table, $condition, array(), "name", $config["per_page"], $page);
		$this->data['total_records'] = $total_records;

		$this->layout->view('books-categories/home', $this->data);
	}
	public function create(){
		$this->layout->set_title("Create New Book Category");

		$this->form_validation->set_rules('name', 'Book Category', 'trim|required|is_unique[book_categories.name]');

		if ($this->form_validation->run('book-category') == true)
		{
			$category_data = $this->input->post();
			$category_data = trim_data($category_data);

			$category_data['createdon'] = time();
			$category = $this->mydb->create('book_categories',$category_data);
			if($category){
				$this->session->set_flashdata('success', "Book category created successfully!");
				/*redirect('admin/books_categories/edit/'.$category->id);*/
				redirect('admin/books_categories/');
			}
			else
			{
				$this->session->set_flashdata('error', "Something went wrong. Please try again");
			}
		}
		$this->layout->view('books-categories/create', $this->data);
	}
	public function edit($id = null){
		$table		= "book_categories";

		$condition 	= array('id'=>$id);

		$category = $this->mydb->get($table,$condition);
		if($category){
			$this->layout->set_title("Edit '".$category->name."' Book Category");
			$this->form_validation->set_rules('name', 'Category Name', 'trim|required');
			if ($this->form_validation->run('book-category') == true){
				$category_data = $this->input->post();
				$category_data = trim_data($category_data);
				$category_id =  $id;
				$category = $this->mydb->update($table,$category_id, $category_data);
				if($category){
					$this->session->set_flashdata('success', "Book category updated successfully!");
				}else{				
					$this->session->set_flashdata('error', "Something went wrong. Please try again");
				}
				$category = $this->mydb->get($table,$condition);
			}
			$this->data['category'] = $category;
			$this->layout->view('books-categories/edit', $this->data);
		}else{
			$this->session->set_flashdata('error', "Book category not found!");
			redirect('admin/books_categories/');
		}
	}
	public function delete($id){
		$deleted = $this->mydb->delete('book_categories', $id);
		if($deleted)
		{
			$this->session->set_flashdata('success', "Book category deleted successfully!");
		}
		else
		{
			$this->session->set_flashdata('error', "Something went wrong. Please try again");
		}
		redirect('admin/books_categories/');
	}
}
