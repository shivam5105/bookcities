<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Email_login extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->otp_expiry_limit = 3600; /* Set OTP Expiry to 1 Hr from createdon*/
	}
    public function insert_email()
    {
		$email_login = trim($this->input->post('email_form'));
		$number_otp = trim($this->input->post('number_otp'));

		$query 	= $this->db->where(array('email' => $email_login))->limit(1)->get('email_login');
		if ($query->num_rows() === 1)
		{
			/*return $query->row();*/
			return false;
		}
		else
		{
			$time = time();
			$otp_expiry = $time + $this->otp_expiry_limit;

		   	$data = array(
				'email' 		=> $email_login,
				'otp_number'	=> $number_otp,
				'otp_expiry'	=> $otp_expiry,
				'createdon'		=> $time
			);		
			return $this->db->insert('email_login', $data);
		}		    
    }
	public function update_otp($email, $data = array()){
		$email = trim($email);
		$this->db->where('email', $email);

		$time 		= time();
		$otp_expiry = $time + $this->otp_expiry_limit;

		$data['otp_expiry'] = $otp_expiry;
		$data['createdon'] 	= $time;

		return $this->db->update('email_login', $data);
	}
    public function otp_check()
    {
		$number_otp = $this->input->post('otp_form');
		$email_id = trim($this->input->post('otp_email'));
		$this->db->where('otp_number',$number_otp);
		$this->db->where('email',$email_id);
		$query = $this->db->get('email_login');

		if ($query->num_rows() > 0)
		{
			$records = $query->result();

			foreach ($records as $key => $record)
			{
				$otp_expiry = $record->otp_expiry;
				if($otp_expiry <= time())
				{
					return array("response" => false, "msg" => "OTP has expired!");
				}
				else
				{
					return array("response" => true, "msg" => "OTP successfully varified!");
				}
			}
		}
		else
		{
			return array("response" => false, "msg" => "OTP doesn't exists!");
		}		
	}
}