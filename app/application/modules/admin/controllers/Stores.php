<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stores extends Admin_Controller {
	function __construct(){
		parent::__construct();
		$this->layout->setLayout('admin');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->deals_in = array(
			/*'db-fields-name' => 'label'*/
			"is_new_books" => "New Books",
			"is_used_books" => "Used Books",
			"is_museumshops" => "Plus",
			);

		$this->opening_days = array(
			"mon",
			"tue",
			"wed",
			"thurs",
			"fri",
			"sat",
			"sun"
			);
		$this->store_max_images = 4;

	    $this->store_images = "uploads/store_images/";
	    if(!file_exists($this->store_images))
	    {
	    	@mkdir($this->store_images, 0777,true);
	    }
	}
	public function index(){
		//$this->users->check_access_permission();
		redirect("/admin/stores/manage");
	}
	public function manage(){
		//$this->users->check_access_permission();

		$this->layout->set_title("All Stores");

		$condition 	= array();
		$table		= "stores";
		if(isset($_GET['status']) && trim($_GET['status']) != "")
		{
			$condition['status'] = $_GET['status'];
		}
$loggedin_data = $this->users->get_loggedin_data();
$loggedin_user_role = $loggedin_data['role'];
		if($loggedin_user_role!=1){
					$condition['added_by_id'] = $loggedin_data['id'];
	
		}
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
		$this->data['stores'] = $this->mydb->get_all($table, $condition, array(), "name", $config["per_page"], $page);
		$this->data['total_records'] = $total_records;

		$this->data['bookcategories'] = $this->mydb->get_all('book_categories');

		$this->layout->view('stores/home', $this->data);
	}
	public function emptylatlong(){
		//$this->users->check_access_permission();
		$this->layout->set_title("All Stores - Empty Latitude & Longitude");

		$condition 	= array();
		$table		= "stores";
		$condition['latitude'] = "";
		if(isset($_GET['status']) && trim($_GET['status']) != "")
		{
			$condition['status'] = $_GET['status'];
		}

		if(isset($_GET['status']) && trim($_GET['status']) != "")
		{
			$condition['status'] = $_GET['status'];
		}
		$loggedin_data = $this->users->get_loggedin_data();
		$loggedin_user_role = $loggedin_data['role'];
				if($loggedin_user_role!=1){
							$condition['added_by_id'] = $loggedin_data['id'];
			
				}
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
		$this->data['stores'] = $this->mydb->get_all($table, $condition, array(), "name", $config["per_page"], $page);
		$this->data['total_records'] = $total_records;

		$this->data['bookcategories'] = $this->mydb->get_all('book_categories');

		$this->layout->view('stores/shopemptylatlong', $this->data);
	}
	public function create(){
	
		$this->layout->set_title("Create New Store");
		
		$condition 	= array();
		$table		= "stores";
$loggedin_data = $this->users->get_loggedin_data();
$loggedin_user_role = $loggedin_data['role'];

		$this->form_validation->set_rules('name', 'Shop Name', 'trim|required');
		$this->form_validation->set_rules('address', 'Street', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('zipcode', 'Zip code', 'trim|required');
		/*$this->form_validation->set_rules('website', 'Website', 'trim|required');*/

		if ($this->form_validation->run() == true)
		{
			$store_data = $this->input->post();
			$store_data = trim_data($store_data);

			$books_categories  = @$store_data['books_categories'];
			unset($store_data['books_categories']);
			$store_data['books_category_ids	'] = ":".@implode(":", $books_categories).":";

			$store_data['createdon'] = time();

			if(!isset($store_data['on_holiday']) || empty($store_data['on_holiday']))
			{
				$store_data['on_holiday'] = 0;
			}
			if(!empty($store_data['holiday_from']))
			{
				$store_data['holiday_from'] = strtotime($store_data['holiday_from']);
			}
			if(!empty($store_data['holiday_to']))
			{
				$store_data['holiday_to'] = strtotime($store_data['holiday_to']);
			}

			for ($img=1; $img <= $this->store_max_images; $img++)
			{
				if(isset($_FILES['image'.$img]))
			    {
			        $validextensions 	= array("jpeg", "jpg", "png", "gif");
			        $ext 				= explode('.', basename($_FILES['image'.$img]['name']));
			        $file_extension 	= strtolower(end($ext));
			        $image_name 		= md5(uniqid()).".".$ext[count($ext) - 1];
			        $target_path 		= $this->store_images.$image_name;
			        
			        if (in_array($file_extension, $validextensions))
			        {
			            if (move_uploaded_file($_FILES['image'.$img]['tmp_name'], $target_path))
			            {
							$store_data['image'.$img] = $image_name;
			            }
			        }
				}
			}
			if(trim($store_data['country_other']) != "" && $store_data['country'] < 0)
			{
				$country = $store_data['country_other'];
				$new_country = $this->mydb->create("countries",array("name" => $country));
				$store_data['country'] = @$new_country->id;
			}
			else
			{
				$country_row = $this->mydb->get("countries", array("id" => $store_data['country']));
				$country 	 = $country_row->name;
			}
			if(trim($store_data['state_other']) != "" && $store_data['state'] < 0)
			{
				$state = $store_data['state_other'];
				$new_state = $this->mydb->create("states",array("name" => $state, "country_id" => $store_data['country']));
				$store_data['state'] = @$new_state->id;
			}
			else
			{
				$state_row 	= $this->mydb->get("states", array("id" => $store_data['state']));
				$state 	 	= $state_row->name;
			}
			if(trim($store_data['city_other']) != "" && $store_data['city'] < 0)
			{
				$city = trim($store_data['city_other']);
				$address 	 = $city." ".$state." ".$country;
				$lat_longArr = get_lat_long($address);

		        $add_data = array(
		        			"name" => $city,
		        			"state_id" => $store_data['state'],
		        			"country_id" => $store_data['country'],
		        			"city_latitude" => $lat_longArr['lat'],
		        			"city_longitude" => $lat_longArr['long'],
		        			);
				$new_city = $this->mydb->create("cities",$add_data);
				$store_data['city'] = @$new_city->id;
			}
			else
			{
				$city_row 	= $this->mydb->get("cities", array("id" => $store_data['city']));
				$city 	 	= $city_row->name;
			}

			$address = $store_data['name']." ".$store_data['address']." ".$city." ".$state." ".$country." ".$store_data['zipcode'];

			$lat_longArr = get_lat_long($address);
			$store_data['latitude'] = $lat_longArr['lat'];
			$store_data['longitude']= $lat_longArr['long'];
			
			unset($store_data['country_other']);
			unset($store_data['state_other']);
			unset($store_data['city_other']);

			foreach ($this->opening_days as $key => $day)
			{
				if(empty($store_data[$day]))
				{
					/* Reset opening times if day is unselected */
					$store_data[$day.'_from_hr'] 			= 0;
					$store_data[$day.'_from_mins'] 			= 0;
					$store_data[$day.'_to_hr'] 				= 0;
					$store_data[$day.'_to_mins'] 			= 0;
					$store_data[$day.'_lunch_from_hr'] 		= 0;
					$store_data[$day.'_lunch_from_mins'] 	= 0;
					$store_data[$day.'_lunch_to_hr'] 		= 0;
					$store_data[$day.'_lunch_to_mins'] 		= 0;
				}
				unset($store_data[$day]);
			}
            if($loggedin_user_role!=1){
				$store_data['status']=0;
				$store_data['added_by_id']=$loggedin_data['id'];
				
			}
			$store = $this->mydb->create($table,$store_data);
			if($store)
			{
				$store_id = $store->id;

				$this->load->model('PushNotifications');
				$this->PushNotifications->newStoreAdded($store_id);

			    /*if(isset($_FILES['file']))
			    {
				    for ($i = 0; $i < count($_FILES['file']['name']); $i++)
				    {
				        $validextensions 	= array("jpeg", "jpg", "png", "gif");
				        $ext 				= explode('.', basename($_FILES['file']['name'][$i]));
				        $file_extension 	= strtolower(end($ext));
				        $image_name 		= md5(uniqid()).".".$ext[count($ext) - 1];
				        $target_path 		= $this->store_images.$image_name;
				        
				        if (in_array($file_extension, $validextensions))
				        {
				            if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path))
				            {
								$store_img_data['store_id'] 	= $store_id;
								$store_img_data['file_name'] 	= $image_name;
								$store_img_data['createdon'] 	= time();
								$store_images = $this->mydb->create('store_images',$store_img_data);
				            }
				        }
				    }
				}*/

				$this->session->set_flashdata('success', "Store created successfully!");
				/*redirect('admin/bookscategories/edit/'.$store->id);*/
				redirect('admin/stores/');
			}
			else
			{
				$this->session->set_flashdata('error', "Something went wrong. Please try again");
			}
		}

		$this->data['countries'] 	= $this->mydb->get_all('countries');
		/*$this->data['states'] 		= $this->mydb->get_all('states', array('country_id'=>231));*/
		$this->data['states']		= array();
		$this->data['cities']		= array();

		$this->data['bookcategories'] = $this->mydb->get_all('book_categories');
		$this->data['deals_in'] = $this->deals_in;
		$this->layout->view('stores/create', $this->data);
	}
	public function edit($id = null) {
		//$this->users->check_access_permission();
		$this->layout->set_title("Edit Store");
		
		$store_id 	= $id;
		$condition 	= array('id'=>$store_id);
		
		$loggedin_data = $this->users->get_loggedin_data();
		$loggedin_user_role = $loggedin_data['role'];

		if($loggedin_user_role!=1){
			$condition['added_by_id'] = $loggedin_data['id'];

		}
		$table		= "stores";

		$store = $this->mydb->get($table,$condition);
		if($store)
		{
			$this->form_validation->set_rules('name', 'Shop Name', 'trim|required');
			$this->form_validation->set_rules('address', 'Street', 'trim|required');
			$this->form_validation->set_rules('country', 'Country', 'trim|required');
			$this->form_validation->set_rules('state', 'State', 'trim|required');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('zipcode', 'Zip code', 'trim|required');
			$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
			$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
			/*$this->form_validation->set_rules('website', 'Website', 'trim|required');*/

			if ($this->form_validation->run() == true)
			{
				$store_data = $this->input->post();
				$store_data = trim_data($store_data);
				$current_status = $store_data['status'];
				$previous_status = $store->status;

				if(!isset($store_data['on_holiday']) || empty($store_data['on_holiday']))
				{
					$store_data['on_holiday'] = 0;
				}
				if(!empty($store_data['holiday_from']))
				{
					$store_data['holiday_from'] = strtotime($store_data['holiday_from']);
				}
				if(!empty($store_data['holiday_to']))
				{
					$store_data['holiday_to'] = strtotime($store_data['holiday_to']);
				}

				$books_categories  = @$store_data['books_categories'];
				unset($store_data['books_categories']);
				$store_data['books_category_ids	'] = ":".@implode(":", $books_categories).":";
				
				foreach($this->deals_in as $key => $value)
				{
					if(!isset($store_data[$key]) || $store_data[$key] != 1)
					{
						$store_data[$key] = 0;
					}
				}

				for ($img=1; $img <= $this->store_max_images; $img++)
				{
					if(isset($_FILES['image'.$img]) && trim($_FILES['image'.$img]['name']) != "")
				    {
				        $validextensions 	= array("jpeg", "jpg", "png", "gif");
				        $ext 				= explode('.', basename($_FILES['image'.$img]['name']));
				        $file_extension 	= strtolower(end($ext));
				        $image_name 		= md5(uniqid()).".".$ext[count($ext) - 1];
				        $target_path 		= $this->store_images.$image_name;
				        
				        if (in_array($file_extension, $validextensions))
				        {
				            if (move_uploaded_file($_FILES['image'.$img]['tmp_name'], $target_path))
				            {
								$store_data['image'.$img] = $image_name;
				            }
				        }
					}
					else
					{
						$store_data['image'.$img] = $store_data['cur_image'.$img];
					}
					unset($store_data['cur_image'.$img]);
				}
				if(trim($store_data['country_other']) != "" && $store_data['country'] < 0)
				{
					$country = $store_data['country_other'];
					$new_country = $this->mydb->create("countries",array("name" => $country));
					$store_data['country'] = @$new_country->id;
				}
				else
				{
					$country_row = $this->mydb->get("countries", array("id" => $store_data['country']));
					$country 	 = $country_row->name;
				}
				if(trim($store_data['state_other']) != "" && $store_data['state'] < 0)
				{
					$state = $store_data['state_other'];
					$new_state = $this->mydb->create("states",array("name" => $state, "country_id" => $store_data['country']));
					$store_data['state'] = @$new_state->id;
				}
				else
				{
					$state_row 	= $this->mydb->get("states", array("id" => $store_data['state']));
					$state 	 	= $state_row->name;
				}

				if(trim($store_data['city_other']) != "" && $store_data['city'] < 0)
				{
					$city = trim($store_data['city_other']);
					$address 	 = $city." ".$state." ".$country;
					$lat_longArr = get_lat_long($address);

			        $add_data = array(
			        			"name" => $city,
			        			"state_id" => $store_data['state'],
			        			"country_id" => $store_data['country'],
			        			"city_latitude" => $lat_longArr['lat'],
			        			"city_longitude" => $lat_longArr['long'],
			        			);
					$new_city = $this->mydb->create("cities",$add_data);
					$store_data['city'] = @$new_city->id;
				}
				else
				{
					$city_row 	= $this->mydb->get("cities", array("id" => $store_data['city']));
					$city 	 	= $city_row->name;
				}

				/*$address 	 = $store_data['name']." ".$store_data['address']." ".$city." ".$state." ".$country." ".$store_data['zipcode'];
				$lat_longArr = get_lat_long($address);
				$store_data['latitude'] = $lat_longArr['lat'];
				$store_data['longitude']= $lat_longArr['long'];*/

				unset($store_data['country_other']);
				unset($store_data['state_other']);
				unset($store_data['city_other']);

				foreach ($this->opening_days as $key => $day)
				{
					if(empty($store_data[$day]))
					{
						/* Reset opening times if day is unselected */
						$store_data[$day.'_from_hr'] 			= 0;
						$store_data[$day.'_from_mins'] 			= 0;
						$store_data[$day.'_to_hr'] 				= 0;
						$store_data[$day.'_to_mins'] 			= 0;
						$store_data[$day.'_lunch_from_hr'] 		= 0;
						$store_data[$day.'_lunch_from_mins'] 	= 0;
						$store_data[$day.'_lunch_to_hr'] 		= 0;
						$store_data[$day.'_lunch_to_mins'] 		= 0;
					}
					if(empty($store_data[$day.'_by_appointment']))
					{
						/* Reset appointment if appointment is unselected */
						$store_data[$day.'_by_appointment']	= 0;
					}
					unset($store_data[$day]);
				}
				
					if($loggedin_user_role!=1){
						if($previous_status==0){
							 $store_data['status']=0;
						}else if($previous_status==1){
							 $store_data['status']=2;

						}
						
					}
				$store = $this->mydb->update($table,$store_id,$store_data);
				
				if($store)
				{
					if($current_status == 1 && $current_status != $previous_status)
					{
						$this->load->model('PushNotifications');
						$this->PushNotifications->newStoreAdded($store_id);
					}

				    /*if(isset($_FILES['file']))
				    {
					    for ($i = 0; $i < count($_FILES['file']['name']); $i++)
					    {
					        $validextensions 	= array("jpeg", "jpg", "png", "gif");
					        $ext 				= explode('.', basename($_FILES['file']['name'][$i]));
					        $file_extension 	= strtolower(end($ext));
					        $image_name 		= md5(uniqid()).".".$ext[count($ext) - 1];
					        $target_path 		= $this->store_images.$image_name;
					        
					        if (in_array($file_extension, $validextensions))
					        {
					            if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path))
					            {
									$store_img_data['store_id'] 	= $store_id;
									$store_img_data['file_name'] 	= $image_name;
									$store_img_data['createdon'] 	= time();
									$store_images = $this->mydb->create('store_images',$store_img_data);
					            }
					        }
					    }
					}*/

					$this->session->set_flashdata('success', "Store updated successfully!");
					$store = $this->mydb->get($table,$condition);
					redirect('admin/stores/edit/'.$store->id);
					//redirect('admin/stores/');
				}
				else
				{
					$this->session->set_flashdata('error', "Something went wrong. Please try again");
				}
			}

			$this->data['bookcategories'] 	= $this->mydb->get_all('book_categories');
			$this->data['store_images'] 	= $this->mydb->get_all('store_images',array('store_id'=>$store_id),'','id');
			$this->data['store'] 			= $store;
			$this->data['deals_in'] 		= $this->deals_in;

			$this->data['countries'] 	= $this->mydb->get_all('countries');
			$this->data['states'] 		= $this->mydb->get_all('states', array('country_id'=>$store->country));
			$this->data['cities']		= $this->mydb->get_all('cities', array('state_id'=>$store->state));

			$this->layout->view('stores/edit', $this->data);
		}
		else
		{
			$this->session->set_flashdata('error', "Store not found!");
			redirect('admin/stores/');
		}
	}
	public function delete($id){
		//$this->users->check_access_permission();
		$loggedin_data = $this->users->get_loggedin_data();
		$loggedin_user_role = $loggedin_data['role'];
				$condition 	= array();

				if($loggedin_user_role!=1){
							$condition['id'] =$id;

							$condition['added_by_id'] = $loggedin_data['id'];
							
							$deleted = $this->mydb->conditional_delete('stores',$condition);

				}else{
									$deleted = $this->mydb->delete('stores', $id);

					
				}
		
		if($deleted)
		{
			$this->session->set_flashdata('success', "Store deleted successfully!");
		}
		else
		{
			$this->session->set_flashdata('error', "Something went wrong. Please try again");
		}
		redirect('admin/stores/');
	}
	/*public function deletestoreimg($id){
		$this->users->check_access_permission();
		$table 		= 'store_images';
		$store_img	= $this->mydb->get($table,array('id'=>$id));
		$deleted 	= $this->mydb->delete($table, $id);

		if($deleted)
		{
			@unlink($this->store_images.$store_img->file_name);
			$response_data = array('success'=>true, "msg"=>"Image deleted successfully!");
		}
		else
		{
			$response_data = array('success'=>false, "msg"=>"Something went wrong. Please try again");
		}
		$json = json_encode($response_data);
		echo $json;
		die;
	}*/
	public function deleteimage($store_id, $img_no){
		$table 		= 'stores';
		if($store_id > 0 && $img_no > 0)
		{
			$store	= $this->mydb->get($table,array('id'=>$store_id));
			@unlink($this->store_images.$store->{'image'.$img_no});

			$store_data['image'.$img_no] = "";
			$store = $this->mydb->update($table,$store_id,$store_data);
			$response_data = array('success'=>true, "msg"=>"Image deleted successfully!");
		}
		else
		{
			$response_data = array('success'=>false, "msg"=>"Something went wrong. Please try again");
		}
		$json = json_encode($response_data);
		echo $json;
		die;
	}
}
