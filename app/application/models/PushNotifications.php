<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PushNotifications extends CI_Model {
	public function __construct(){
		$this->load->database();
	}
    public function resetNewStoreNotifications()
    {
		$app_table 	= "app_installed_devices";

		$data = array(
				"new_store_notification_sent" => "0"
			);
		$this->mydb->conditional_update($app_table, null, $data);
    }
    public function newStoreAdded($store_id, $mode = null, $test_data = array())
    {
    	if(trim($_SERVER['SERVER_NAME']) == 'localhost')
    	{
    		return true;
    	}
    	if($mode != "test")
    	{
    		$this->resetNewStoreNotifications();
    	}
		$condition 		= array('id'=>$store_id);
		$store_table	= "stores";
		$store 			= $this->mydb->get($store_table,$condition);
		$app_table 		= "app_installed_devices";
		if($store)
		{
			$store_checked = false;

			if($store->new_store_notification_sent == "0" && $store->status == "1")
			{
				$store_checked = true;
			}
			if($mode == "test")
			{
				$store_checked = true;
			}
			if($store_checked)
			{
				$city_id		= $store->city;
				$city_table		= "cities";
				$city 			= $this->mydb->get($city_table, array('id'=>$city_id));

	    		$appleData['title'] 			= "New store added!";
				$appleData['body'] 				= $store->name.' in '.$city->name.' is now on Book Cities app.';
				$appleData['action-loc-key'] 	= "View";
				$appleCustomData['store_id'] 	= $store->id;
				$appleCustomData['store_name'] 	= $store->name;
				$appleCustomData['city_name'] 	= $city->name;

				if($mode == 'test')
				{
					$record_rows = $test_data;
				}
				else
				{
					$record_rows = $this->mydb->get_all($app_table, array("new_store_notification_sent" => "0"), array(), 'id');
				}
				if($record_rows)
				{
					foreach ($record_rows as $key => $row)
					{
						$platform_name 	= $row->platform_name;

						if(strtolower(trim($platform_name)) == 'ios')
						{
							$appleData['token'] = $row->device_token;

		    				$response = $this->applePushNotification($appleData, $appleCustomData);

		    				if($mode == null)
		    				{
								$condition = array(
										"id" => $row->id,
									);

			    				if($response['status'])
			    				{
									$data = array(
											"new_store_notification_sent" => time()
										);
									$this->mydb->conditional_update($app_table, $condition, $data);
								}
								else
								{
									$this->mydb->conditional_delete($app_table, $condition);
								}
							}
						}
					}
				}
				if($mode == null)
				{
					$data = array(
							"new_store_notification_sent" => time()
						);
					$condition = array(
							"id" => $store_id,
						);
					$this->mydb->conditional_update($store_table, $condition, $data);
				}
			}
		}
    }
    private function applePushNotification($data, $customData)
    {
		//$vHost 			= 'gateway.sandbox.push.apple.com';
		$vHost 			= 'gateway.push.apple.com';

		$vPort 			= 2195;
		$vCert 			= APPPATH.'../assets/certificates/Certificates-Production.pem';
		$vPassphrase 	= 'pass@123';
		$vToken 		= $data['token'];

		$vAlert = array(
				"title" 			=> $data['title'],
				"body" 				=> $data['body'],
				"action-loc-key" 	=> $data['action-loc-key']
			);

		unset($data['title']);
		unset($data['body']);
		unset($data['action-loc-key']);
		unset($data['token']);
		
		/*$vBadge = 1;*/

		$vSound = 'default';

		// Create the message content that is to be sent to the device.
		$vBody 			= $data;
		$vBody['aps'] 	= array (
			'alert' => $vAlert,
			'sound' => $vSound,
		);
		/*$vBody['aps']['badge'] = $vBadge;*/

		foreach ($customData as $key => $value)
		{
			$vBody['aps'][$key] = $value;
		}

		// Encode the body to JSON.
		$vBody = json_encode ($vBody);

		// Create the Socket Stream.
		$vContext = stream_context_create ();
		stream_context_set_option ($vContext, 'ssl', 'local_cert', $vCert);

		// Remove this line if you would like to enter the Private Key Passphrase manually.
		stream_context_set_option ($vContext, 'ssl', 'passphrase', $vPassphrase);

		try
		{
			// Open the Connection to the APNS Server.
			$vSocket = stream_socket_client ('ssl://'.$vHost.':'.$vPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $vContext);

			// Check if we were able to open a socket.
			if (!$vSocket)
			{
				return array("status" => false, "msg" => "APNS Connection Failed: $error $errstr");
			}
			// Build the Binary Notification.
			$vMsg = chr (0) . chr (0) . chr (32) . pack ('H*', $vToken) . pack ('n', strlen ($vBody)) . $vBody;

			// Send the Notification to the Server.
			$vResult = fwrite ($vSocket, $vMsg, strlen ($vMsg));

			// Close the Connection to the Server.
			fclose ($vSocket);

			if ($vResult)
			{
				return array("status" => true, "msg" => "Delivered Message to APNS");
			}
			else
			{
				return array("status" => false, "msg" => "Could not Deliver Message to APNS");
			}
		}
		catch (Exception $e) 
		{
			return array("status" => false, "msg" => "Caught exception: ".$e->getMessage());
		}
    }
}