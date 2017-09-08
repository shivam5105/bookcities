<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cities extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	public function create($postdata = array()){
		if(empty($postdata)){
			 return false;
		}else{			
			$this->db->set($postdata);
			if(!$this->db->insert('cities')){
				$this->session->set_flashdata($this->db->error());
				return false;
			}else{
				$id = $this->db->insert_id();
				$q = $this->db->get_where('cities', array('id' => $id));
				return $q->row();
			};
		}
	}
	public function update($id, $data = array()){
		$this->db->where('id', $id);
		return $this->db->update('cities', $data);
	}
	public function get_state($contition = array()){
		$q = $this->db->get_where('cities', $contition);
		return $q->row();
	}
	public function get_cities($contition = array(), $select = array(), $order = "name", $limit = null, $start = null){
		$order = $this->input->get('sort') ? $this->input->get('sort') : $order;
		$orderby = $this->input->get('order') ? $this->input->get('order') : 'ASC';
		$sort = trim($order . ' ' . $orderby);

		$this->db->select($select);
		$this->db->where($contition);
		$this->db->limit($limit, $start);
		$this->db->order_by($sort);
		
		$query = $this->db->get('cities');
		return $query->result();
	}
	public function total_cities($select = array()){
		$this->db->select($select);
		
		$query = $this->db->get('cities');
		return $query->num_rows();
	}
	public function count_cities($contition = array(), $select = array(), $order = "", $limit = null, $start = null){
		$this->db->select($select);
		$this->db->where($contition);
		$this->db->limit($limit, $start);
		$this->db->order_by($order);
		
		$query = $this->db->get('cities');
		return $query->num_rows();
	}
	public function get_non_empty_cities($condition = array(), $select = array(), $order = "city.name", $limit = null, $start = null, $return = 'records'){
		$order = $this->input->get('sort') ? $this->input->get('sort') : $order;
		$orderby = $this->input->get('order') ? $this->input->get('order') : 'ASC';
		$sort = trim($order . ' ' . $orderby);
		if(trim($return) == "" || trim($return) == null)
		{
			$return = 'records';
		}
		$condition['store.status'] = 1;

		$this->db->select($select);
		$this->db->from('cities as city');
		$this->db->join('stores as store', 'store.city = city.id', 'left');
		/*$this->db->join('states as state', 'state.id = store.state', 'left');*/
		$this->db->where($condition);
		$this->db->where("store.city is NOT NULL");
		$this->db->group_by("store.city");
		if($limit == 'count'){
			$query = $this->db->get();
			return $query->num_rows();
		}else{
			$this->db->limit($limit, $start);
			$this->db->order_by($sort);
		
			$query = $this->db->get();
			if($return == 'count')
			{
				$cities = $query->num_rows();
			}
			else
			{
				$cities = $query->result();
			}
			return $cities;
		}
	}
}