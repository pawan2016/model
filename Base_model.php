<?php

class Base_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		 $this->conn_id='';
    }
    //echo $this->db->last_query();
    public function check_existent($table, $where) {
        $query = $this->db->get_where($table, $where);
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
	
	
	public function table_exists($table) {
	if($this->db->table_exists($table))
	{
		return true;
	}else{
		
		return false;
	}
	}
	public function escape_str($str, $like = FALSE)
	 {
	  if(is_array($str))
	  {
	   foreach ($str as $key => $val)
		  {
		$str[$key] = $this->escape_str($val, $like);
		  }

		  return $str;
		 }

	  $str = is_object($this->conn_id) ? mysql_real_escape_string($str, $this->conn_id) : addslashes($str);

	  // escape LIKE condition wildcards
	  if ($like === TRUE)
	  {
	   return str_replace(array($this->_like_escape_chr, '%', '_'),
		  array($this->_like_escape_chr.$this->_like_escape_chr, $this->_like_escape_chr.'%', $this->_like_escape_chr.'_'),
		  $str);
	  }

	  return $str;
	 }
	
    public function insert_one_row($table, $data) {
        
        return $this->db->insert($table, $data);

    }

    public function insert_multiple_row($table, $data) {   //insert multiple row in one time...
        return $this->db->insert_batch($table, $data);
    }

    public function get_max_record_withalias($table, $columname, $alias) {
        $this->db->select_max($columname, $alias);
        $query = $this->db->get($table);
        return $query->row();
    }

    public function get_record_by_id($table, $data) {  //  retrun only one row.
        $query = $this->db->get_where($table, $data);
        //if(!empty($column_name)&&!empty($ordery_by)){
        //$this->db->order_by($column_name,$ordery_by);
        //}
        return $query->row();
    }

    public function get_all_record_by_condition($table, $data) {  //  retrun only one row.
        $query = $this->db->get_where($table, $data);
        //echo $this->db->last_query();
        return $query->result();
    }

   
    public function get_all_record_by_id($table, $where, $column_name = null, $ordery_by = null) {// retrun only one or many record
        if (!empty($column_name) && !empty($ordery_by)) {
            $this->db->order_by($column_name, $ordery_by);
        }
        $query = $this->db->get_where($table, $where);

        return $query->result();
    }

    public function get_all_record_by_id_row($table, $where, $column_name = null, $ordery_by = null) {// retrun only one or many record 
        $query = $this->db->get_where($table, $where);
        if (!empty($column_name) && !empty($ordery_by)) {
            $this->db->order_by($column_name, $ordery_by);
        }
        return $query->row();
    }

    public function get_last_insert_id() {
        return $this->db->insert_id();
    }

    public function update_record_by_id($table, $data, $where) {
        return $this->db->update($table, $data, $where);
    }

    public function update_record_by_id1($table, $data, $where) {
        $this->db->update($table, $data, $where);
        return $this->db->affected_rows();
    }

    public function countrow($table) {
        return $this->db->count_all($table);
    }
    
   
    public function count_row_by_ids($table, $param) {
        return $this->db->count_all($table, $param);
    }

	public function get_all_records($table) {  //  retrun all rows.
        $query = $this->db->get($table);
        return $query->result();
    }
	
	
    public function get_all_record_by_in($table, $colum, $wherein) {
        $this->db->where_in($colum, $wherein);
        $res = $this->db->get($table);
        return $res->result();
    }
	
	public function get_all_record_by_in_join($table1,$table2,$colum,$join_condition,$Select_field, $wherein,$Join_type){
		
		$this->db->select($Select_field); // Select field
		$this->db->from($table1); // from Table1
		$this->db->join($table2,$join_condition,$Join_type); // Join table1 with table2 based on the foreign key
		$this->db->where_in($colum,$wherein); // Set Filter
		$res = $this->db->get();
		return $res->result();
	}
	
	
    public function delete_record_by_id($table, $where) {
        $this->db->delete($table, $where);
		return $this->db->affected_rows();
    }

   
    public function delete_record_by_in($table, $colum, $wherein) {
        $this->db->where_in($colum, $wherein);
        $this->db->delete($table);
		return $this->db->affected_rows();
    }
	public function set_initial_product($product_id)
	{
		$office_id=$this->session->userdata('office_id');
		$office_operation_type=$this->session->userdata('office_operation_type');
		$tableInitial='inventory_'.$office_operation_type.'_initial_stock_'.$office_id;
		$arr_initial_stock = $this->db->get_where('inventory_'.$office_operation_type.'_initial_stock_'.$office_id,array('product_id'=>$product_id))->result();
		if(empty($arr_initial_stock))
		{
			$this->db->insert($tableInitial,array('initial_stock_status'=>'1','product_id'=>$product_id,'initial_stock_quantity'=>'0'));
		}
		else{
			$this->db->update($tableInitial,array('initial_stock_status'=>'1'),array('product_id'=>$product_id));
		}
		
	}
	
	function update_data($update= array())
	 {
		   if(isset($update['table']) && !empty($update['table']) && isset($update['data']) && !empty($update['data']) && isset($update['where']) && !empty($update['where'])){
	   		    
				$this->db->where('user_id',$update['where']);
				$query =  $this->db->update($update['table'],$update['data']);	
				if(isset($query) && !empty($query)){
					echo $this->db->last_query();					
					return true;
				}else{			
				return false;
				}		   
     	   }else{
			
				return false;
		   }
	}
	 function select_data_otp()
	{ 	
		$this->db->select('user_id,user_otp_time');
		$query = $this->db->get('users_master');  
		//echo $this->db->last_query();
        return $query->result();   
      
	}
	
	public function insertActivity($action,$activity,$page_name)
	{
		$user_id = $this->session->userdata('user_id');
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$createdOn = date("Y-m-d H:i:s");
		$insertData = array( 'user_id'=>$user_id,
							 'user_agent'=>$user_agent,
							 'ip_address'=>$ip_address,
							 'activity'=>$activity,
							 'action'=>$action,
							 'page_name'=>$page_name,
							 'createdOn'=>$createdOn
							);
		$this->db->insert('users_activity_master',$insertData);
	}
   
}
