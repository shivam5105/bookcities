<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends Admin_Controller {
	function __construct(){
		parent::__construct();
		$this->layout->setLayout('admin');
		$this->load->library('pagination');
	}
	public function index(){
		$this->layout->view('pages/home');
	}
	public function addcity()
	{
		$this->users->check_access_permission();
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		if ($this->form_validation->run() == true)
		{
			$post_data = $this->input->post();
			$post_data = trim_data($post_data);

			if(trim($post_data['country_other']) != "" && $post_data['country'] < 0)
			{
				$country = trim($post_data['country_other']);
				$new_country = $this->mydb->create("countries",array("name" => $country));
				$post_data['country'] = @$new_country->id;
			}
			else
			{
				$country_row = $this->mydb->get("countries", array("id" => $post_data['country']));
				$country 	 = $country_row->name;
			}
			if(trim($post_data['state_other']) != "" && $post_data['state'] < 0)
			{
				$state = trim($post_data['state_other']);
				$new_state = $this->mydb->create("states",array("name" => $state, "country_id" => $post_data['country']));
				$post_data['state'] = @$new_state->id;
			} else {
				$state_row 	= $this->mydb->get("states", array("id" => $post_data['state']));
				$state 	 	= $state_row->name;
			}
			$city = trim($post_data['city']);
			/*if(trim($post_data['city_latitude']) == "" || trim($post_data['city_longitude']) == "")
			{*/
				$address 	 = $city." ".$state." ".$country;
				$lat_longArr = get_lat_long($address);

	            $post_data['city_latitude']   = $lat_longArr['lat'];
	            $post_data['city_longitude']  = $lat_longArr['long'];
	        /*}*/
	        $add_data = array(
	        			"name" => $city,
	        			"state_id" => $post_data['state'],
	        			"country_id" => $post_data['country'],
	        			"city_latitude" => $post_data['city_latitude'],
	        			"city_longitude" => $post_data['city_longitude'],
	        			);

			$new_city = $this->mydb->create("cities",$add_data);
			$post_data['city'] = @$new_city->id;
		}

		$this->data['countries'] = $this->mydb->get_all('countries');
		/*$this->data['states'] 	 = $this->mydb->get_all('states', array('country_id'=>231));*/
		$this->data['states'] 	 = array();

		$this->layout->view('pages/addcity', $this->data);
	}
	public function editcity()
	{
		$this->users->check_access_permission();
		$city_id = !empty($_GET['id']) ? $_GET['id'] : "";

		if($city_id > 0)
		{
			if($this->input->post())
			{
				$this->form_validation->set_rules('country', 'Country', 'trim|required');
				$this->form_validation->set_rules('state', 'State', 'trim|required');
				$this->form_validation->set_rules('city', 'City', 'trim|required');
				if ($this->form_validation->run() == true)
				{
					$post_data = $this->input->post();
					$post_data = trim_data($post_data);

					if(trim($post_data['country_other']) != "" && $post_data['country'] < 0)
					{
						$country = trim($post_data['country_other']);
						$new_country = $this->mydb->create("countries",array("name" => $country));
						$post_data['country'] = @$new_country->id;
					}
					else
					{
						$country_row = $this->mydb->get("countries", array("id" => $post_data['country']));
						$country 	 = $country_row->name;
					}
					if(trim($post_data['state_other']) != "" && $post_data['state'] < 0)
					{
						$state = trim($post_data['state_other']);
						$new_state = $this->mydb->create("states",array("name" => $state, "country_id" => $post_data['country']));
						$post_data['state'] = @$new_state->id;
					}
					else
					{
						$state_row 	= $this->mydb->get("states", array("id" => $post_data['state']));
						$state 	 	= $state_row->name;
					}
					$city = trim($post_data['city']);
					/*if(trim($post_data['city_latitude']) == "" || trim($post_data['city_longitude']) == "")
					{*/
						$address 	 = $city." ".$state." ".$country;
						$lat_longArr = get_lat_long($address);

			            $post_data['city_latitude']   = $lat_longArr['lat'];
			            $post_data['city_longitude']  = $lat_longArr['long'];
			        /*}*/
			        $add_data = array(
			        			"name" => $city,
			        			"state_id" => $post_data['state'],
			        			"country_id" => $post_data['country'],
			        			"city_latitude" => $post_data['city_latitude'],
			        			"city_longitude" => $post_data['city_longitude'],
			        			);

					$new_city = $this->mydb->update("cities", $city_id, $add_data);
					$this->session->set_flashdata('success', "City updated successfully!");
					redirect('admin/pages/editcity?id='.$city_id);
					die;
				}
			}
			$city_row				 = $this->mydb->get("cities", array("id" => $city_id));
			$this->data['city'] 	 = $city_row;
			$this->data['countries'] = $this->mydb->get_all('countries');
			$this->data['states'] 	 = $this->mydb->get_all('states', array('country_id'=>$city_row->country_id));

			$this->layout->view('pages/editcity', $this->data);
		}
		else
		{
			$this->session->set_flashdata('error', "Oops!! city not exists.");
			redirect('admin/pages/cities/');
		}
	}
	public function deletecity()
	{
		$this->users->check_access_permission();
		$id = !empty($_GET['id']) ? $_GET['id'] : "";

		if($id > 0)
		{
			$deleted = $this->mydb->delete('cities', $id);
			if($deleted)
			{
				$this->mydb->conditional_delete('stores', array('city'=>$id));
				$this->session->set_flashdata('success', "City deleted successfully!");
			}
			else
			{
				$this->session->set_flashdata('error', "Something went wrong. Please try again");
			}
		}
		else
		{
			$this->session->set_flashdata('error', "No city selected to delete.");
		}
		redirect('admin/pages/cities/');
	}
	public function appsettings(){
		$this->users->check_access_permission();
		$table = "app_settings";
		$this->form_validation->set_rules('info_text', 'Info Text', 'trim|required');
		if ($this->form_validation->run() == true)
		{
			$app_settings = $this->input->post();
			$app_settings = trim_data($app_settings);

			$update = $this->mydb->update($table,null,$app_settings);
			if($update)
			{
				$this->session->set_flashdata('success', "App info updated successfully!");
			}
		}
		$this->data['app_data'] = $this->mydb->get($table);
		$this->layout->view('pages/appsettings',$this->data);
	}
	public function countries(){
		$this->users->check_access_permission();
		$this->load->model('countries');
		$this->layout->set_title("All Countries");
		
		$condition = array();

		$total_countries = $this->countries->total_countries();

		$this->config->load("pagination");
		$config["per_page"] = 20;
		$config["uri_segment"] = 4;
		$config["total_rows"] = $total_countries;
		$config["base_url"] = base_url($this->uri->segment(1).'/'. $this->uri->segment(2).'/'. $this->uri->segment(3));
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->data["pagination"] = $this->pagination->create_links();
        $this->data['skipped_records'] = $page;
		
		$this->data['countries'] = $this->countries->get_countries($condition, array(), "name", $config["per_page"], $page);

		$this->data['total_countries'] = $total_countries;

		$this->layout->view('pages/countries', $this->data);
	}
	public function states($country_id = null){
		$this->users->check_access_permission();
		$this->load->model('states');
		$this->load->model('countries');

		$condition = array();
		if(!empty($country_id) && trim($country_id) != "all")
		{
			$condition['country_id'] = $country_id;
		}

		$this->layout->set_title("All States");
		$total_states = $this->states->total_states();
		$count_states = $this->states->count_states($condition);

		$segment4 = $this->uri->segment(4);
		if(empty($country_id))
		{
			$segment4 = "all";
		}

		$this->config->load("pagination");
		$config["per_page"] = 20;
		$config["uri_segment"] = 5;
		$config["total_rows"] = (!empty($country_id) && trim($country_id) != "all") ? $count_states : $total_states;
		$config["base_url"] = base_url($this->uri->segment(1).'/'. $this->uri->segment(2).'/'. $this->uri->segment(3).'/'. $segment4 );
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $this->data["pagination"] = $this->pagination->create_links();
        $this->data['skipped_records'] = $page;
		
		$this->data['states'] 		= $this->states->get_states($condition, array(), "name", $config["per_page"], $page);
		
		$this->data['countries'] 	= $this->countries->get_countries();
		$this->data['total_states'] = $total_states;
		$this->data['count_states'] = $count_states;
		$this->data['country_id'] 	= $country_id;

		$this->layout->view('pages/states', $this->data);
	}
	public function cities($state_id = null){
		$this->users->check_access_permission();
		$this->load->model('states');
		$this->load->model('countries');
		$this->load->model('cities');

		$condition = array();
		if(!empty($state_id) && trim($state_id) != "all")
		{
			$condition['state_id'] = $state_id;
		}
		$this->layout->set_title("All Cities");
		$total_cities = $this->cities->total_cities();
		$count_cities = $this->cities->count_cities($condition);

		$segment4 = $this->uri->segment(4);
		if(empty($state_id))
		{
			$segment4 = "all";
		}

		$this->config->load("pagination");
		$config["per_page"] = 20;
		$config["uri_segment"] = 5;
		$config["total_rows"] = (!empty($state_id) && trim($state_id) != "all") ? $count_cities : $total_cities;
		$config["base_url"] = base_url($this->uri->segment(1).'/'. $this->uri->segment(2).'/'. $this->uri->segment(3).'/'. $segment4 );
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $this->data["pagination"] = $this->pagination->create_links();
        $this->data['skipped_records'] = $page;
		
		$this->data['cities'] 		= $this->cities->get_cities($condition, array(), "id", $config["per_page"], $page);
		$this->data['total_cities'] = $total_cities;
		$this->data['count_cities'] = $count_cities;
		$this->data['states'] 		= $this->states->get_states();
		$this->data['countries'] 	= $this->countries->get_countries();
		$this->data['state_id'] 	= $state_id;

		$this->layout->view('pages/cities', $this->data);
	}
	public function get_states($country_id = null){
		$this->load->model('states');

		$condition = array();
		if(!empty($country_id) && trim($country_id) != "all")
		{
			$condition['country_id'] = $country_id;
		}

		$states = $this->states->get_states($condition, array(), "name");
		echo json_encode($states);
	}
	public function get_cities($state_id = null){
		$this->load->model('cities');

		$condition = array();
		if(!empty($state_id) && trim($state_id) != "all")
		{
			$condition['state_id'] = $state_id;
		}

		$cities = $this->cities->get_cities($condition, array(), "name");
		echo json_encode($cities);
	}
	public function editcountry()
	{
		$this->users->check_access_permission();
		$country_id = !empty($_GET['id']) ? $_GET['id'] : "";

		if($country_id > 0)
		{
			if($this->input->post())
			{
				$this->form_validation->set_rules('name', 'Country', 'trim|required');
				$this->form_validation->set_rules('sortname', 'Country Short Name', 'trim|required');
				$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
				if ($this->form_validation->run() == true)
				{
					$post_data = $this->input->post();
					$post_data = trim_data($post_data);

					$post_data['country_code'] = "+".str_ireplace("+", "", $post_data['country_code']);


					$new_city = $this->mydb->update("countries", $country_id, $post_data);
				}
				$this->session->set_flashdata('success', "Country updated successfully!");
				redirect('admin/pages/editcountry?id='.$country_id);
				die;
			}
			$this->data['country'] = $this->mydb->get("countries", array("id" => $country_id));
			$this->layout->view('pages/editcountry', $this->data);
		}
		else
		{
			$this->session->set_flashdata('error', "Oops!! country not exists.");
			redirect('admin/pages/countries/');
		}
	}
	public function deletecountry()
	{
		$this->users->check_access_permission();
		$id = !empty($_GET['id']) ? $_GET['id'] : "";

		if($id > 0)
		{
			$deleted = $this->mydb->delete('countries', $id);
			if($deleted)
			{
				$this->session->set_flashdata('success', "Country deleted successfully!");
				$this->mydb->conditional_delete('states', array('country_id'=>$id));
				$this->mydb->conditional_delete('cities', array('country_id'=>$id));
				$this->mydb->conditional_delete('stores', array('country'=>$id));
			}
			else
			{
				$this->session->set_flashdata('error', "Something went wrong. Please try again");
			}
		}
		else
		{
			$this->session->set_flashdata('error', "No country selected to delete.");
		}
		redirect('admin/pages/countries/');
	}
	public function updatecitydb()
	{
		$this->users->check_access_permission();
		$this->load->model('cities');
		$this->load->model('states');
		$this->load->model('countries');
		$condition = array();
		$condition['country_id'] = 0;

		$cities = $this->cities->get_cities($condition, array(), "name");
		foreach ($cities as $key => $city)
		{
			$city_id 	= $city->id;
			$city_name 	= $city->name;
			$state_id 	= $city->state_id;

			$condition = array();
			$condition['id'] = $state_id;

			$state 		= $this->states->get_states($condition, array(), "name");
			$state_name = $state[0]->name;
			$country_id = $state[0]->country_id;

			$condition = array();
			$condition['id'] = $country_id;

			$country 		= $this->countries->get_countries($condition, array(), "name");
			$country_name	= $country[0]->name;

			$city_address 	= $city_name." ".$state_name." ".$country_name;

			$lat_longArr = get_lat_long($city_address);

            $post_data['city_latitude']   = $lat_longArr['lat'];
            $post_data['city_longitude']  = $lat_longArr['long'];

	        $add_data = array(
	        			"country_id" => $country_id,
	        			"city_latitude" => $post_data['city_latitude'],
	        			"city_longitude" => $post_data['city_longitude'],
	        			);

			$new_city = $this->mydb->update("cities", $city_id, $add_data);
		}
	}
}