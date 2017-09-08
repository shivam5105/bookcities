<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class States extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	public function create($postdata = array()){
		if(empty($postdata)){
			 return false;
		}else{			
			$this->db->set($postdata);
			if(!$this->db->insert('states')){
				$this->session->set_flashdata($this->db->error());
				return false;
			}else{
				$id = $this->db->insert_id();
				$q = $this->db->get_where('states', array('id' => $id));
				return $q->row();
			};
		}
	}
	public function update($id, $data = array()){
		$this->db->where('id', $id);
		return $this->db->update('states', $data);
	}
	public function get_state($contition = array()){
		$q = $this->db->get_where('states', $contition);
		return $q->row();
	}
	public function get_states($contition = array(), $select = array(), $order = "name", $limit = null, $start = null){
		$order = $this->input->get('sort') ? $this->input->get('sort') : $order;
		$orderby = $this->input->get('order') ? $this->input->get('order') : 'ASC';
		$sort = trim($order . ' ' . $orderby);

		$this->db->select($select);
		$this->db->where($contition);
		$this->db->limit($limit, $start);
		$this->db->order_by($sort);
		
		$query = $this->db->get('states');
		return $query->result();
	}
	public function total_states($select = array()){
		$this->db->select($select);
		
		$query = $this->db->get('states');
		return $query->num_rows();
	}
	public function count_states($contition = array(), $select = array(), $order = "", $limit = null, $start = null){
		$this->db->select($select);
		$this->db->where($contition);
		$this->db->limit($limit, $start);
		$this->db->order_by($order);
		
		$query = $this->db->get('states');
		return $query->num_rows();
	}
}