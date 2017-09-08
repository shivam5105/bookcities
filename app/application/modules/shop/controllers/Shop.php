<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends Admin_Controller {
	function __construct(){
		parent::__construct();
		$this->layout->setLayout('registration');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->library('email');
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

		$smtp_config['protocol']	= 'smtp';
		$smtp_config['smtp_host']	= 'smtp.bookcities.org';
		$smtp_config['smtp_port']	= '587';
		$smtp_config['smtp_timeout']= '30';
		$smtp_config['smtp_user']	= 'contact@bookcities.org';
		$smtp_config['smtp_pass']	= 'ajG~04h5';
		$smtp_config['charset']		= 'utf-8';
		$smtp_config['newline']		= "\r\n";
		$smtp_config['wordwrap'] 	= TRUE;
		$smtp_config['mailtype'] 	= 'html';

		$this->smtp_config 			= $smtp_config;		
	}
	public function index(){
		redirect("shop/email_login_form");
	}
	public function email_login_form(){
		$this->layout->set_title("Shop registration");
		$this->form_validation->set_rules('email_form', 'Email', 'required|valid_email|is_unique[users.email]');

		$this->email->initialize($this->smtp_config);

		if ($this->form_validation->run() == true)
		{
			$this->load->model('Email_login');
			$email_login 	= $this->input->post('email_form');
			$number_otp 	= $this->input->post('number_otp');

			$res = $this->Email_login->insert_email();

			if($res == false)
			{
				$this->Email_login->update_otp($email_login, array('otp_number' => $number_otp));
			}
			if(!empty($email_login) && !empty($number_otp))
			{
				$otp_url = base_url();
				$this->email->from('contact@bookcities.org', 'Book Cities');
				$this->email->to($email_login);
				$this->email->subject('One-time password');
				ob_start();
				?>
				Your one-time password is: <?php echo $number_otp; ?> <br />
				Please enter one-time password in this url <?php echo $otp_url.'shop/otp_login_form?user_email='.$email_login; ?> <br /><br />

				This password is valid 1 hour. <br />
				To request a new password re-enter your e-mail here: <?php echo $otp_url.'shop/email_login_form'; ?> <br /><br />

				Book Cities<br />
				contact@bookcities.org<br />
				www.bookcities.org<br />
				<?php
				$message = ob_get_clean();
				ob_implicit_flush(true);

				$this->email->message($message);
				$this->email->send();
				$this->session->set_flashdata('success', "OTP sent on your email address.");
			}
			redirect("/shop/email_login_form");
		}
		$this->layout->view('/shop/email_login_view', $this->data);
	}
	public function otp_login_form(){
		$this->layout->set_title("Shop registration");
		$user_email = $this->input->get('user_email');
		if(!isset($user_email) || empty($user_email))
		{
			redirect('/shop/email_login_form');
		}
		$this->data['user_email'] = $user_email;
		$this->form_validation->set_rules('otp_form', 'OTP', 'required');
		if ($this->form_validation->run() == true)
		{
			$this->load->model('Email_login');
			$otp_check = $this->Email_login->otp_check();
			$otp_number = $this->input->post("otp_form");

			if($otp_check["response"] == true)
			{
				$newdata = array(
                   'user_email' 	=> $user_email,
                   'otp_verified'	=> true,
                   'user_id'		=> $otp_check["id"],
               );
				$this->session->set_userdata($newdata);
				redirect("/shop/registration");
			}
			else if($otp_check["response"] == false)
			{
				$msg = $otp_check["msg"];
				if(trim($msg) == "OTP doesn't exists!")
				{
					$msg = "Wrong OTP Entered.";
				}
				$this->session->set_flashdata('error', $msg);

				redirect("/shop/otp_login_form?user_email=".$user_email);
			}
		}
		$this->layout->view('/shop/otp_login_view', $this->data);
	}
	public function registration(){
		if(!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] != true || !isset($_SESSION['user_email']) || empty($_SESSION['user_email']))
		{
			$this->session->set_flashdata('error', "Something went wrong. Please try again.");
			if(!isset($_SESSION['user_email']) || empty($_SESSION['user_email']))
			{
				redirect("/shop/email_login_form");
			}
			else
			{
				redirect("/shop/otp_login_form?user_email=".$_SESSION['user_email']);
			}
		}
		$email_id=$_SESSION['user_email'];
		$table_row_count = $this->mydb->get_all('stores',array('user_email'=>$_SESSION['user_email']));
	    // $query = $this->db->query('SELECT * FROM stores where user_email="$email_id"');
		// $count=$query->num_rows();
		if(!$table_row_count)
		{
			$this->layout->set_title("Shop registration");
			$condition 	= array();
			$table		= "stores";
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

				$store_data['createdon'] = time();

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

				$address 	 = $store_data['name']." ".$store_data['address']." ".$city." ".$state." ".$country." ".$store_data['zipcode'];
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

				$store = $this->mydb->create($table,$store_data);
				if($store)
				{
					$store_id = $store->id;

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

					$this->session->set_flashdata('success', "Shop registration done successfully!");
					$this->email->initialize($this->smtp_config);
					$this->email->from('contact@bookcities.org', 'bookcities.org');
					$this->email->to($_SESSION['user_email']);
					$this->email->subject('Thank you for your submission');
					$this->email->message('<img src="http://bookcities.org/app/assets/images/logo-new.png"/><br/><br/><br/>Thank you for your submission. The content will be verified as soon as possible. To make changes on your entry, request a new password by re-entering your e-mail here:<br/>
						http://bookcities.org/app/shop/email_login_form <br/>
						Thank you in advance for you collaboration.<br/>
						Sincerely yours <br/>
						Anna Haas from Book Cities App <br/>
						contact@bookcities.org <br/>
						www.bookcities.org');
					$this->email->send();
					$this->session->set_flashdata('success', "Thank you for your submission.");
					/*redirect('admin/bookscategories/edit/'.$store->id);*/
					$this->session->unset_userdata(array('user_email','otp_verified', 'user_id'));
					redirect('/shop/email_login_form');
				}
				else
				{
					$this->session->set_flashdata('error', "Something went wrong. Please try again");
				}
			}

			$this->data['countries'] 	= $this->mydb->get_all('countries');
			/*$this->data['states'] 		= $this->mydb->get_all('states', array('country_id'=>231));*/
			$this->data['states'] 		= array();
			$this->data['cities']		= array();
			$this->data['emaiid'] = $this->mydb->get_all('stores',array('user_email'=>$_SESSION['user_email']));		
			$this->data['bookcategories'] = $this->mydb->get_all('book_categories');
			$this->data['deals_in'] = $this->deals_in;
			$this->layout->view('/shop/registration', $this->data);
		}
		else
		{
			$this->layout->set_title("Edit Store");
			$table_id = $this->mydb->get('stores',array('user_email'=>$_SESSION['user_email']));
			$store_id=$table_id->id;
			//  $email 	= $_SESSION['user_email'];
			//  $condition 	= array('user_email'=>$email);
			// $store_id 	= $store_id1;
			$condition 	= array('id'=>$store_id);
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
						unset($store_data[$day]);
					}

					$store = $this->mydb->update($table,$store_id,$store_data);
					if($store)
					{
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
						redirect('/shop/email_login_form');
						//redirect('admin/stores/');
					}
					else
					{
						$this->session->set_flashdata('error', "Something went wrong. Please try again");
					}
				}

				$this->data['bookcategories'] 	= $this->mydb->get_all('book_categories');
				// $this->data['id'] 	= $this->mydb->get_all('stores',array('user_email'=>$_SESSION['user_email']));
				// $store_id=$this->data['id'];
				$this->data['store_images'] 	= $this->mydb->get_all('store_images',array('store_id'=>$store->id),'','id');
				$this->data['store'] 			= $store;
				$this->data['deals_in'] 		= $this->deals_in;

				$this->data['countries'] 	= $this->mydb->get_all('countries');
				$this->data['states'] 		= $this->mydb->get_all('states', array('country_id'=>$store->country));
				$this->data['cities']		= $this->mydb->get_all('cities', array('state_id'=>$store->state));

				$this->layout->view('/shop/registration', $this->data);
			}
			else
			{
				$this->session->set_flashdata('error', "Store not found!");
				redirect('/shop/registration');
			}
		}
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

	public function registration1() {
		$this->layout->set_title("Edit Store");
		
		$email 	= $_SESSION['user_email'];
		$condition 	= array('user_email'=>$email);
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
				if($store_data)
				{
					$store_data = trim_data($store_data);

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
							$store_data[$day.'_from_hr'] 	= 0;
							$store_data[$day.'_from_mins'] 	= 0;
							$store_data[$day.'_to_hr'] 		= 0;
							$store_data[$day.'_to_mins'] 	= 0;
						}
						unset($store_data[$day]);
					}

					$store = $this->mydb->update($table,$store_id,$store_data);
					if($store)
					{
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
						redirect('/shop/email_login_form');
						//redirect('admin/stores/');
					}
					else
					{
						$this->session->set_flashdata('error', "Something went wrong. Please try again");
					}
				}
			}

			$this->data['bookcategories'] 	= $this->mydb->get_all('book_categories');
			// $this->data['id'] 	= $this->mydb->get_all('stores',array('user_email'=>$_SESSION['user_email']));
			// $store_id=$this->data['id'];
			$this->data['store_images'] 	= $this->mydb->get_all('store_images',array('store_id'=>$store->id),'','id');
			$this->data['store'] 			= $store;
			$this->data['deals_in'] 		= $this->deals_in;

			$this->data['countries'] 	= $this->mydb->get_all('countries');
			$this->data['states'] 		= $this->mydb->get_all('states', array('country_id'=>$store->country));
			$this->data['cities']		= $this->mydb->get_all('cities', array('state_id'=>$store->state));

			$this->layout->view('/shop/registration', $this->data);
		}
		else
		{
			$this->session->set_flashdata('error', "Store not found!");
			redirect('admin/stores/');
		}
	}


}


