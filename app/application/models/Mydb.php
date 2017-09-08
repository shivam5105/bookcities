<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mydb extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	public function get_all($table, $condition = array(), $select = array(), $order = "name", $limit = null, $start = null){

		$order = $this->input->get('sort') ? $this->input->get('sort') : $order;
		$orderby = $this->input->get('order') ? $this->input->get('order') : 'ASC';
		$sort = trim($order . ' ' . $orderby);

		$this->db->select($select);
		$this->db->where($condition);
		$this->db->limit($limit, $start);
		$this->db->order_by($sort);
		
		$query = $this->db->get($table);
		return $query->result();
	}
	
	public function get_total_records($table, $condition = array(), $select = array()){

		$this->db->select($select);
		$this->db->where($condition);
		
		$query = $this->db->get($table);
		return $query->num_rows();
	}

	public function get_query_records($table, $condition = array(), $select = array(), $order = "name", $limit = null, $start = null){

		$order = $this->input->get('sort') ? $this->input->get('sort') : $order;
		$orderby = $this->input->get('order') ? $this->input->get('order') : 'ASC';
		$sort = trim($order . ' ' . $orderby);

		$this->db->select($select);
		$this->db->where($condition);
		$this->db->limit($limit, $start);
		$this->db->order_by($sort);
		
		$query = $this->db->get($table);
		return $query->num_rows();
	}
	
	public function get($table, $condition = array(), $select = array(), $order = "name", $limit = null){
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($condition);
		$this->db->limit(1);
		
		$query = $this->db->get();
		return $query->row();
	}
	public function create($table, $data = ''){
		if($data == ''){
			 return false;
		}else{
			$this->db->set($data);
			if(!$this->db->insert($table)){
				return false;
			}else{
				$id = $this->db->insert_id();
				$q = $this->db->get_where($table, array('id' => $id));
				return $q->row();
			};
		}
	}	
	public function conditional_update($table, $condition = null, $data = array()){
		if($condition != null)
		{
			$this->db->where($condition);
		}
		return $this->db->update($table, $data);
	}
	public function update($table, $id = null, $data = array()){
		if($id != null)
		{
			$this->db->where('id', $id);
		}
		return $this->db->update($table, $data);
	}
	public function delete($table, $id){
		$this->db->where('id', $id);
		return $this->db->delete($table); 
	}
	public function conditional_delete($table, $condition = null){
		if($condition != null)
		{
			$this->db->where($condition);
			return $this->db->delete($table);
		}
		else
		{
			return false;
		}
	}
}