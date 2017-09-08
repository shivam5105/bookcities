<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends Admin_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('states');
		$this->load->model('countries');
		$this->load->model('cities');
	}
	public function index(){
		$this->layout->view('pages/home');
	}
	public function appsettings() {
		$table = "app_settings";
		$response_data['appsettings'] = $this->mydb->get($table);
		
		if(@$_GET['nojson'] == 1)
		{
			echo "<pre>";
			print_r($response_data);
			echo "</pre>";
			die;
		}

		$json = json_encode($response_data);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
		die;
	}
	public function countries($id = null){
		$condition = array();
		$response_data = array();
		if(!empty($id))
		{
			$condition['id'] = $id;
		}
		$response_data = array();

		if(isset($_GET['hide_empty']) && trim($_GET['hide_empty']) > 0)
		{
			$total_countries = $this->countries->get_non_empty_countries($condition, array('country.*'), "name", "count");
		}
		else
		{
			$total_countries = $this->countries->total_countries();
		}

		if(isset($_GET['per_page']) && trim($_GET['per_page']) > 0)
		{
			$page = 0;
			
			if(isset($_GET['page']) && trim($_GET['page']) > 1)
			{
				$page = ($_GET['page'] - 1);
			}
			$skip_records = ($page * $_GET["per_page"]);

			if(isset($_GET['hide_empty']) && trim($_GET['hide_empty']) > 0)
			{
				$query_countries = $this->countries->get_non_empty_countries($condition, array('country.*'), "country.name", $_GET["per_page"], $skip_records,'count');
				$countries = $this->countries->get_non_empty_countries($condition, array('country.*'), "country.name", $_GET["per_page"], $skip_records);
			}
			else
			{
				$query_countries = $this->countries->count_countries($condition, array(), "name", $_GET["per_page"], $skip_records);
				$countries 		= $this->countries->get_countries($condition, array(), "name", $_GET["per_page"], $skip_records);
			}
		}
		else
		{
			if(isset($_GET['hide_empty']) && trim($_GET['hide_empty']) > 0)
			{
				$query_countries = $this->countries->get_non_empty_countries($condition, array('country.*'), "country.name", null, null,'count');

				$countries = $this->countries->get_non_empty_countries($condition, array('country.*'), "country.name");
			}
			else
			{
				$query_countries = $this->countries->count_countries($condition);
				$countries = $this->countries->get_countries($condition);
			}
		}
		
		$response_data['total_countries'] 	= $total_countries;
		$response_data['query_countries'] 	= $query_countries;
		$response_data['countries'] 		= $countries;

		if(@$_GET['nojson'] == 1)
		{
			echo "<pre>";
			print_r($response_data);
			echo "</pre>";
			die;
		}

		$json = json_encode($response_data);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
		die;
	}
	public function states($id = null){
		$condition = array();
		$response_data = array();
		if(!empty($id))
		{
			$condition['id'] = $id;
		}
		$country_id = !empty($_GET['country']) ? $_GET['country'] : "";
		if(!empty($country_id) && trim($country_id) != "all")
		{
			$condition['country_id'] = $country_id;
		}
		$total_states = $this->states->total_states();

		if(isset($_GET['per_page']) && trim($_GET['per_page']) > 0)
		{
			$page = 0;
			
			if(isset($_GET['page']) && trim($_GET['page']) > 1)
			{
				$page = ($_GET['page'] - 1);
			}
			$skip_records = ($page * $_GET["per_page"]);

			$query_states = $this->states->count_states($condition, array(), "name", $_GET["per_page"], $skip_records);
			$states = $this->states->get_states($condition, array(), "name", $_GET["per_page"], $skip_records);
		}
		else
		{
			$query_states = $this->states->count_states($condition);
			$states = $this->states->get_states($condition);
		}

		$response_data['total_states'] 	= $total_states;
		$response_data['query_states'] 	= $query_states;
		$response_data['states'] 		= $states;
		if(!empty($id) && !empty($states) && count($states) > 0)
		{
			$state_country_id 			= $states[0]->country_id;
			$response_data['country'] 	= $this->countries->get_countries(array('id' => $state_country_id));
		}

		if(@$_GET['nojson'] == 1)
		{
			echo "<pre>";
			print_r($response_data);
			echo "</pre>";
			die;
		}

		$json = json_encode($response_data);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
		die;
	}
	public function cities($id = null){

		$condition = array();
		$response_data = array();
		if(!empty($id))
		{
			$condition['id'] = $id;
		}
		$state_id = !empty($_GET['state']) ? $_GET['state'] : "";

		if(!empty($state_id) && trim($state_id) != "all")
		{
			$condition['state_id'] = $state_id;
		}
		$country_id = !empty($_GET['country']) ? $_GET['country'] : "";
		
		if(!empty($country_id) && trim($country_id) != "all")
		{
			$condition['country_id'] = $country_id;
		}
		if(isset($_GET['hide_empty']) && trim($_GET['hide_empty']) > 0)
		{
			$total_cities = $this->cities->get_non_empty_cities($condition, array('city.*'), "city.name", "count");
		}
		else
		{
			$total_cities = $this->cities->total_cities();
		}

		if(isset($_GET['per_page']) && trim($_GET['per_page']) > 0)
		{
			$page = 0;

			if(isset($_GET['page']) && trim($_GET['page']) > 1)
			{
				$page = ($_GET['page'] - 1);
			}
			$skip_records = ($page * $_GET["per_page"]);

			if(isset($_GET['hide_empty']) && trim($_GET['hide_empty']) > 0)
			{
				$query_cities = $this->cities->get_non_empty_cities($condition, array('city.*'), "city.name", $_GET["per_page"], $skip_records,'count');
				$cities = $this->cities->get_non_empty_cities($condition, array('city.*'), "city.name", $_GET["per_page"], $skip_records);
			}
			else
			{
				$query_cities = $this->cities->count_cities($condition, array(), "name", $_GET["per_page"], $skip_records);
				$cities = $this->cities->get_cities($condition, array(), "name", $_GET["per_page"], $skip_records);
			}
		}
		else
		{
			if(isset($_GET['hide_empty']) && trim($_GET['hide_empty']) > 0)
			{
				$query_cities = $this->cities->get_non_empty_cities($condition, array('city.*'), "city.name", null, null,'count');

				$cities = $this->cities->get_non_empty_cities($condition, array('city.*'), "city.name");
			}
			else
			{
				$query_cities = $this->cities->count_cities($condition);
				$cities = $this->cities->get_cities($condition);
			}
		}
		$response_data['total_cities'] 	= $total_cities;
		$response_data['query_cities'] 	= $query_cities;
		$response_data['cities'] 		= $cities;

		if(!empty($id) && !empty($cities) && count($cities) > 0)
		{
			$city_country_id 			= $cities[0]->country_id;
			$city_state_id 				= $cities[0]->state_id;
			$response_data['state'] 	= $this->states->get_states(array('id' => $city_state_id));
			$response_data['country'] 	= $this->countries->get_countries(array('id' => $city_country_id));
		}

		if(@$_GET['nojson'] == 1)
		{
			echo "<pre>";
			print_r($response_data);
			echo "</pre>";
			die;
		}

		$json = json_encode($response_data);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
		die;
	}
	public function stores($store_id = null){
		$condition 		= array("status"=>1);
		$response_data 	= array();
		$table			= "stores";

		$total_stores 	= $this->mydb->get_total_records($table, $condition);

		$country_name = !empty($_GET['country_name']) ? $_GET['country_name'] : "";
		if(!empty($country_name) && trim($country_name) != "all")
		{
			$countries = $this->countries->get_countries(array("name"=>$country_name));
			if(!empty($countries))
			{
				$_GET['country'] = $countries[0]->id;
			}
		}
		$state_name = !empty($_GET['state_name']) ? $_GET['state_name'] : "";
		if(!empty($state_name) && trim($state_name) != "all")
		{
			$state_cond = array("name"=>$state_name);

			if(!empty($_GET['country']))
			{
				$state_cond['country_id'] = $_GET['country'];
			}
			$states = $this->states->get_states($state_cond);
			if(!empty($states))
			{
				$_GET['state'] = $states[0]->id;
			}
		}
		$city_name = !empty($_GET['city_name']) ? $_GET['city_name'] : "";
		if(!empty($city_name) && trim($city_name) != "all")
		{
			$city_cond = array("name"=>$city_name);

			if(!empty($_GET['country']))
			{
				$city_cond['country_id'] = $_GET['country'];
			}
			if(!empty($_GET['state']))
			{
				$city_cond['state_id'] = $_GET['state'];
			}
			$cities = $this->cities->get_cities($city_cond);
			if(!empty($cities))
			{
				$_GET['city'] = $cities[0]->id;
			}
		}

		if(!empty($store_id) && trim($store_id) != "all")
		{
			$condition['id'] = $store_id;
		}
		$state_id = !empty($_GET['state']) ? $_GET['state'] : "";
		if(!empty($state_id) && trim($state_id) != "all")
		{
			$condition['state'] = $state_id;
		}
		$country_id = !empty($_GET['country']) ? $_GET['country'] : "";
		if(!empty($country_id) && trim($country_id) != "all")
		{
			$condition['country'] = $country_id;
		}
		$city_id = !empty($_GET['city']) ? $_GET['city'] : "";
		if(!empty($city_id) && trim($city_id) != "all")
		{
			$condition['city'] = $city_id;
		}
		$zipcode = !empty($_GET['zipcode']) ? $_GET['zipcode'] : "";
		if(!empty($zipcode) && trim($zipcode) != "all")
		{
			$condition['zipcode'] = $zipcode;
		}

		if(isset($_GET['per_page']) && trim($_GET['per_page']) > 0)
		{
			$page = 0;			
			if(isset($_GET['page']) && trim($_GET['page']) > 1)
			{
				$page = ($_GET['page'] - 1);
			}
			$skip_records = ($page * $_GET["per_page"]);
			
			$query_stores = $this->mydb->get_query_records($table, $condition, array(), "id", $_GET["per_page"], $skip_records);
			$stores = $this->mydb->get_all($table, $condition, array(), "id", $_GET["per_page"], $skip_records);
		}
		else
		{
			$query_stores 	= $this->mydb->get_total_records($table, $condition);
			$stores = $this->mydb->get_all($table, $condition);
		}

		$response_data['total_stores'] 	= $total_stores;
		$response_data['query_stores'] 	= $query_stores;
		$response_data['stores'] 		= $stores;

		$response_data['bookcategories'] = array();
		$response_data['store_image_dir'] = base_url()."uploads/store_images/";

		if(!empty($store_id) && !empty($stores) && count($stores) > 0)
		{
			$stores_country_id 			= $stores[0]->country;
			$stores_state_id 			= $stores[0]->state;
			$stores_city_id 			= $stores[0]->city;
			$response_data['city'] 		= $this->cities->get_cities(array('id' => $stores_city_id));
			$response_data['state'] 	= $this->states->get_states(array('id' => $stores_state_id));
			$response_data['country'] 	= $this->countries->get_countries(array('id' => $stores_country_id));
		}

		if(is_array($response_data['stores']) && count($response_data['stores']) > 0)
		{
			/*$store_img_cond = array();
			if(!empty($store_id) && trim($store_id) != "all")
			{
				$store_img_cond = array('store_id'=>$store_id);
			}
			$response_data['store_images'] 	= $this->mydb->get_all('store_images',$store_img_cond,'','id');*/
			$response_data['bookcategories'] = $this->mydb->get_all('book_categories');
		}

		if(@$_GET['nojson'] == 1)
		{
			echo "<pre>";
			print_r($response_data);
			echo "</pre>";
			die;
		}

		$json = json_encode($response_data);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
		die;
	}
	public function bookscategories()
	{
		$condition 		= array();
		$response_data 	= array();
		$table			= "book_categories";

		$total_categories = $this->mydb->get_total_records($table, $condition);

		$response_data['total_categories'] 	= $total_categories;
		$response_data['query_categories'] 	= $total_categories;
		$response_data['bookscategories'] 	= $this->mydb->get_all($table, $condition);

		$json = json_encode($response_data);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
		die;
	}
	public function reg_token()
	{
		$table = "app_installed_devices";

		if((!isset($_POST['current_token']) && !isset($_POST['platform'])) || (empty($_POST['current_token']) || empty($_POST['platform'])))
		{
			$response_data['response'] 	= false;
			$response_data['message'] 	= "All parameters are required.";
			$json = json_encode($response_data);
			header('content-type: application/json; charset=utf-8');
			echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
			die;
		}
		if(!isset($_POST['prev_token']))
		{
			$_POST['prev_token'] = "0";
		}

		$record_row = $this->mydb->get($table, array("device_token" => $_POST['current_token'], "platform_name" => $_POST['platform']));
		
		if($record_row)
		{
			if(!empty($_POST['prev_token']) && $_POST['prev_token'] != "0" && !empty($_POST['platform']))
			{
				$condition = array(
						"device_token" 	=> $_POST['prev_token'],
						"platform_name" => $_POST['platform']
					);
				$this->mydb->conditional_delete($table, $condition);
			}

			$response_data['response'] 	= true;
			$response_data['message'] 	= "Device already registered.";
			$json = json_encode($response_data);
			header('content-type: application/json; charset=utf-8');
			echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
			die;
		}
		else
		{
			if($_POST['prev_token'] == "" || $_POST['prev_token'] == "0")
			{
				/* Add New Token. App is installed first time on the device*/
				
				$data = array(
						"platform_name" => $_POST['platform'],
						"device_token" 	=> $_POST['current_token'],
						"createdon" 	=> time()
					);
				$result = $this->mydb->create($table, $data);
				
				if($result)
				{
					$response_data['response'] 	= true;
					$response_data['message'] 	= "Device added successfully.";
					$json = json_encode($response_data);
					header('content-type: application/json; charset=utf-8');
					echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
					die;
				}
			}
			else
			{
				$data = array(
						"platform_name" => $_POST['platform'],
						"device_token" 	=> $_POST['current_token'],
						"createdon" 	=> time()
					);
				$condition = array(
						"device_token" 	=> $_POST['prev_token'],
						"platform_name" => $_POST['platform']
					);
				$result = $this->mydb->conditional_update($table, $condition, $data);
				
				if($result)
				{
					$response_data['response'] 	= true;
					$response_data['message'] 	= "Device updated successfully.";
					$json = json_encode($response_data);
					header('content-type: application/json; charset=utf-8');
					echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
					die;
				}
			}
		}
	}

	public function reg_token_test()
	{
		$this->load->view('api/reg_token_test');
	}
	public function new_store_notification_test()
	{
		$store_id 		= $this->input->post("store_id");
		$platform_name 	= $this->input->post("platform_name");
		$device_token 	= $this->input->post("device_token");

		if(!empty($store_id) && !empty($platform_name) && !empty($device_token))
		{
			$this->load->model('PushNotifications');
			$data_arr[] =  (object) array(
					'platform_name' => $platform_name,
					'device_token' => $device_token
				);
			$this->PushNotifications->newStoreAdded($store_id, 'test', $data_arr);
		}

		$data['stores'] 		= $this->mydb->get_all('stores', array("status" => "1"), array(), 'name');
		$data['store_id'] 		= $store_id;
		$data['platform_name'] 	= $platform_name;
		$data['device_token'] 	= $device_token;

		$this->load->view('api/new_store_notification_test', $data);
	}
}