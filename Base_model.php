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
	
	
	/*Added By Pankaj Gupta Table Of Sanchi*/
	// _createsanchiProcurementTable is used for Sanchi procurement..
	public function _createsanchiProcurementTable($office_id)
	{
			 // Createde By Raw Material Receipt Table
		$fields = array(
						'raw_material_receipt_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'received_from'=> array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'raw_material_receipt_reference_number'=> array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'receipt_date' => array(
									  'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'raw_material_id' => array(
									'type' => 'BIGINT',
							  ),
						'raw_material_description' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'narration' => array(
									'type' => 'TEXT',
							  ),
						'raw_material_receipt_status' => array(
									 'type' => 'ENUM("0","1","2","3")',
									'default' => '1',
									'null' => false,
						  ),
						'added_by' => array(
							'type' => 'BIGINT'
						),
						
						'added_date' => array(
							 'type' => 'DATETIME'
						  ),
						'access_level_status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '1',
								'null' => false,
						  ),
						  'authorized_by' => array(
									'type' => 'BIGINT'
						  ),
						  'authorize_date' => array(
									 'type' => 'DATETIME'
						  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('raw_material_receipt_id', TRUE);
		$this->dbforge->create_table('raw_material_receipt_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE raw_material_receipt_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		// raw material receipt product table
		$fields = array(
						'raw_material_receipt_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'raw_material_receipt_id'=> array(
									 'type' => 'BIGINT'
							  ),
						'brick_number'=> array(
									 'type' => 'BIGINT'
							  ),
						'raw_material_weight' => array(
							'type' => 'DOUBLE',
							 'constraint' => '13,3',
							
							),
						'raw_material_receipt_product_status' => array(
									 'type' => 'ENUM("0","1","2","3")',
									'default' => '1',
									'null' => false,
						  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('raw_material_receipt_product_id', TRUE);
		$this->dbforge->create_table('raw_material_receipt_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE raw_material_receipt_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		 // Createde By  Work Order TAble Table
		
		$fields = array(
						'work_order_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'vendor_id'=> array(
									 'type' => 'BIGINT',
									 'default' => NULL,
							  ),
						'work_order_reference_number' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'work_order_date' => array(
									'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'balance_bank_guarantee' => array(
									 'type' => 'DOUBLE',
									 'constraint' => '13,2',
							  ),
						'remarks' => array(
								'type' => 'TEXT'
							),
						'work_order_status' => array(
									 'type' => 'ENUM("0","1","2","3")',
									'default' => '1',
									'null' => false,
							),
						'added_by' => array(
								'type' => 'BIGINT'
							),
						
						'added_date' => array(
							 'type' => 'DATETIME'
						),
						'access_level_status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '1',
								'null' => false,
						),
						'authorized_by' => array(
								'type' => 'BIGINT'
						),
						'authorize_date' => array(
								 'type' => 'DATETIME'
						),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('work_order_id', TRUE);
		$this->dbforge->create_table('work_order_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE work_order_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		// Createde By  Work Order TAble By Product Table
		
		$fields = array(
						'work_order_item_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'work_order_id'=> array(
									 'type' => 'BIGINT'
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'description' => array(
									 'type' => 'TEXT',
							  ),
						'delivery_date' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '100',
							
						),
						'weight' => array(
							'type' => 'DOUBLE',
							 'constraint' => '13,2',
							
						),
						'quantity' => array(
							'type' => 'BIGINT'
							
						),
						'fabrication_charges' => array(
								'type' => 'DOUBLE',
							 'constraint' => '14,2',
						),
						'tolerance' => array(
							'type' => 'BIGINT'
							
						),
						'other_charges' => array(
								'type' => 'DOUBLE',
							 'constraint' => '14,2',
							
						),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('work_order_item_product_id', TRUE);
		$this->dbforge->create_table('work_order_item_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE work_order_item_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		// Add Raw Material Issued
		
		
		$fields = array(
						'raw_material_issue_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'raw_material_id'=> array(
									 'type' => 'BIGINT'
							  ),
						'vendor_id'=> array(
									 'type' => 'BIGINT'
							  ),
						'work_order_id' => array(
									 'type' => 'BIGINT'
							  ),
						'raw_material_issue_reference_number' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
						),
						'issue_date' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
						),
						'total_bank_gurantee_available' => array(
							'type' => 'DOUBLE',
							'constraint' => '13,2',
						),
						'notional_price' => array(
							'type' => 'DOUBLE',
							'constraint' => '13,2',
						),
						'outstanding_by_value' => array(
							'type' => 'DOUBLE',
							'constraint' => '13,2',
						),
						'outstanding_by_weight' => array(
							'type' => 'DOUBLE',
							'constraint' => '13,3',
						),
						'total_outstanding_by_value' => array(
							'type' => 'DOUBLE',
							'constraint' => '15,2',
							'default' => '0.00',
						),
						'total_outstanding_by_weight' => array(
							'type' => 'DOUBLE',
							'constraint' => '16,3',
							'default' => '0.000',
						),
						'narration' => array(
							 'type' => 'TEXT',
						),
						'total_weight_925' => array(
							'type' => 'DOUBLE',
							 'constraint' => '13,3',
						),
						'total_weight_999' => array(
							 'type' => 'DOUBLE',
							 'constraint' => '13,3',
						),
						'raw_material_issue_status' => array(
									 'type' => 'ENUM("0","1","2","3")',
									'default' => '1',
									'null' => false,
						  ),
						'added_by' => array(
							'type' => 'BIGINT'
						),
						
						'added_date' => array(
							 'type' => 'DATETIME'
						  ),
						'access_level_status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '1',
								'null' => false,
						  ),
						  'authorized_by' => array(
									'type' => 'BIGINT'
						  ),
						  'authorize_date' => array(
									 'type' => 'DATETIME'
						  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('raw_material_issue_id', TRUE);
		$this->dbforge->create_table('raw_material_issue_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE raw_material_issue_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		 // Raw Material  For Product Table 
		 
		
			$fields = array(
						'raw_material_issue_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'raw_material_issue_id'=> array(
									 'type' => 'BIGINT'
							  ),
						'raw_material_id' => array(
									 'type' => 'BIGINT'
							  ),
						'raw_material_receipt_id' => array(
									 'type' => 'BIGINT'
							  ),
						'raw_material_receipt_product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'brick_number' => array(
									 'type' => 'BIGINT'
							  ),
						'notional_price' => array(
								'type' => 'DOUBLE',
								'constraint' => '13,2',
							),
						'weight' => array(
								'type' => 'DOUBLE',
								'constraint' => '13,3',
							),						
						'creator_id' => array(
										 'type' => 'BIGINT'
								  ),
						'createdOn' => array(
										 'type' => 'DATETIME'
								  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('raw_material_issue_product_id', TRUE);
		$this->dbforge->create_table('raw_material_issue_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE raw_material_issue_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		//Received_finish_goods  
		
		$fields = array(
							'received_finish_goods_id' => array(
							'type' => 'BIGINT',
							'unsigned' => TRUE,
							'auto_increment' => TRUE
							),
							'received_date' => array(
							'type' => 'VARCHAR',
							'constraint' => '200',
							),
							'received_finish_goods_reference_number' => array(
							'type' => 'VARCHAR',
							'constraint' => '200',
							),
							'vendor_id'=> array(
							'type' => 'BIGINT'
							),
							'work_order_id' => array(
							'type' => 'BIGINT'
							),
							'total_all_quantity' => array(
							'type' => 'BIGINT',
							),
							'narration' => array(
							'type' => 'TEXT',
							),
							'total_weight_925' => array(
							'type' => 'DOUBLE',
							'constraint' => '13,3',

							),
							'total_weight_999' => array(
							'type' => 'DOUBLE',
							'constraint' => '13,3',

							),
							'total_quantity' => array(
							'type' => 'BIGINT'

							),
							'raw_material_issue_status' => array(
							'type' => 'ENUM("0","1","2","3")',
							'default' => '1',
							'null' => false,
							),
							'added_by' => array(
							'type' => 'BIGINT'
							),

							'added_date' => array(
							'type' => 'DATETIME'
							),
							'access_level_status' => array(
							'type' => 'ENUM("0","1")',
							'default' => '1',
							'null' => false,
							),
							'authorized_by' => array(
							'type' => 'BIGINT'
							),
							'authorize_date' => array(
							'type' => 'DATETIME'
							),
							'received_finish_goods_status' => array(
							'type' => 'ENUM("0","1")',
							'default' => '1',
							'null' => false,
							),
							'creator_id' => array(
							'type' => 'BIGINT'
							),
							'createdOn' => array(
							'type' => 'DATETIME'
							),
							'updatedOn' => array(
							'type' => 'TIMESTAMP'
							),
							);

							$this->dbforge->add_field($fields);
							$this->dbforge->add_key('received_finish_goods_id', TRUE);
							$this->dbforge->create_table('received_finish_goods_'.$office_id, TRUE);
							
							$this->db->query("Alter TABLE received_finish_goods_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		
		// Received Finish Goods For Product
		
		$fields = array(
						'received_finish_goods_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
						),
						'received_finish_goods_id'=> array(
								 'type' => 'BIGINT'
				       ),
						'work_order_id' => array(
						     	 'type' => 'BIGINT'
						),
						'product_id' => array(
						     	 'type' => 'BIGINT'
						),
						'product_name' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
						),
						'ordered_quantity' => array(
							 'type' => 'BIGINT'
							
						),
						'total_received_quantity' => array(
							 'type' => 'BIGINT'
							
						),
						'received_quantity' => array(
							 'type' => 'BIGINT'
							
						),
						'pending_quantity' => array(
							 'type' => 'BIGINT'
							
						),
						'received_weight' => array(
							'type' => 'DOUBLE',
							 'constraint' => '13,3',
							
						),
						'total_weight' => array(
							'type' => 'DOUBLE',
							 'constraint' => '13,3',
							
						),			
                          'material_receipt_note_status' => array(
									 'type' => 'ENUM("0","1","2","3","4","5","6","7")',
									'default' => '1',
									'null' => false,
						  ),										
						'creator_id' => array(
									 'type' => 'BIGINT'
					   ),
						'createdOn' => array(
									 'type' => 'DATETIME'
					    ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('received_finish_goods_product_id', TRUE);
		$this->dbforge->create_table('received_finish_goods_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE received_finish_goods_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		// Issued To Assing
		
		
		$fields = array(
						'issue_to_assaying_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'vendor_id'=> array(
									 'type' => 'BIGINT'
							  ),
						'issue_assaying_reference_number' => array(
									 'type' => 'VARCHAR',
							        'constraint' => '200',
							  ),
						'issue_date' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
						),
						'from_store' => array(
						     	 'type' => 'VARCHAR',
							 'constraint' => '200',
						),
						'issued_to' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
						),
						'narration' => array(
							 'type' => 'TEXT',
							
						),
						'total_weight' => array(
							'type' => 'DOUBLE',
							 'constraint' => '13,3',
							
						),
						'total_quantity' => array(
								'type' => 'BIGINT',
							
						),
						'issue_assaying_status' => array(
									 'type' => 'ENUM("0","1","2","3")',
									'default' => '1',
									'null' => false,
						  ),
						'added_by' => array(
							'type' => 'BIGINT'
						),
						
						'added_date' => array(
							 'type' => 'DATETIME'
						  ),
						'access_level_status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '1',
								'null' => false,
						  ),
						  'authorized_by' => array(
									'type' => 'BIGINT'
						  ),
						  'authorize_date' => array(
									 'type' => 'DATETIME'
						  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
					    ),
						'status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '0',
								'null' => false,
					    ),
						
						'createdOn' => array(
									 'type' => 'DATETIME'
					    ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
						 ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('issue_to_assaying_id', TRUE);
		$this->dbforge->create_table('issue_to_assaying_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE issue_to_assaying_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		
		
		
		 // Issued TO Assing  For Product Table 
		   /* comment
		   material_receipt_note  =>receive_finish_goods_refrens_number
		   */
		
			$fields = array(
						'issue_to_assaying_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
						),
						'issue_to_assaying_id'=> array(
								 'type' => 'BIGINT'
				       ),
					 
						'material_receipt_note' => array(
									 'type' => 'BIGINT'
					   ),
						'product_id' => array(
									 'type' => 'BIGINT'
					   ),
					   'product_name' => array(
									 'type' => 'TEXT'
					   ),
						'weight' => array(
								'type' => 'DOUBLE',
							 'constraint' => '13,3',
						),	
						'quantity' => array(
								'type' => 'BIGINT',
						),						
						'creator_id' => array(
									 'type' => 'BIGINT'
					   ),
					   	'status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '0',
								'null' => false,
					    ),
						'createdOn' => array(
									 'type' => 'DATETIME'
					    ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('issue_to_assaying_product_id', TRUE);
		$this->dbforge->create_table('issue_to_assaying_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE issue_to_assaying_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		
		
		 // Createde By   BG Details   TAble Table
		
		    	$fields = array(
						'bank_guarantee_details_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'vendor_id'=> array(
									 'type' => 'BIGINT',
									 'default' => NULL,
							  ),
						'bank_guarantee_number' => array(
									 'type' => 'BIGINT'
							  ),
						'bank_guarantee_details_reference_number' => array(
									 'type' => 'VARCHAR',
								 'constraint' => '200',
							  ),
						'bank_guarantee_date' => array(
									'type' => 'VARCHAR',
									 'constraint' => '100',
									
							  ),
					   'bank_guarantee_value_amount' => array(
								'type' => 'DOUBLE',
						     	 'constraint' => '13,3',
						  ),
						
						   'confirmation_ref' => array(
								 'type' => 'VARCHAR',
								 'constraint' => '200',
						  ),
						  'confirmation_1_received_from' => array(
								 'type' => 'VARCHAR',
								 'constraint' => '200',
						  ),
						  'confirmation_2_received_from' => array(
								 'type' => 'VARCHAR',
								 'constraint' => '200',
						  ),
						  'bank_guarantee_type' => array(
								 'type' => 'VARCHAR',
								 'constraint' => '200',
						  ),
						  'bank_guarantee_purpose' => array(
								 'type' => 'VARCHAR',
								 'constraint' => '200',
						  ),
						  'issuing_bank' => array(
								 'type' => 'VARCHAR',
								 'constraint' => '200',
						  ),
						    'bank_branch' => array(
								 'type' => 'VARCHAR',
								 'constraint' => '200',
						  ),
						    'expiry_date' => array(
								 'type' => 'DATE',
								
						  ),
						    'claim_date' => array(
						    	 'type' => 'DATE',
						  ),
						    'narration' => array(
									 'type' => 'TEXT'
						  ),
						   'bank_guarantee_status' => array(
								 'type' => 'ENUM("0","1")',
								 'default' => '0',
						  ),
						   'bank_guarantee_details_status' => array(
								 'type' => 'ENUM("0","1")',
								 'default' => '1',
						  ),
						  'added_by' => array(
							'type' => 'BIGINT'
						),
						
						'added_date' => array(
							 'type' => 'DATETIME'
						  ),
						'access_level_status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '1',
								'null' => false,
						  ),
						  'authorized_by' => array(
									'type' => 'BIGINT'
						  ),
						  'authorize_date' => array(
									 'type' => 'DATETIME'
						  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('bank_guarantee_details_id', TRUE);
		$this->dbforge->create_table('bank_guarantee_details_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE  bank_guarantee_details_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		// Received from Assing  Table
		    $fields = array(
						'receive_from_assaying_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'vendor_id'=> array(
									 'type' => 'BIGINT'
							  ),
						'receive_from_assaying_reference_number' => array(
										'type' => 'VARCHAR',
							            'constraint' => '200',
							  ),
						'receive_date' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
						),
						'form_store' => array(
						     	'type' => 'VARCHAR',
							 'constraint' => '200',
						),
						'receive_form' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
						),
						'narration' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
						),
						'total_weight' => array(
							'type' => 'DOUBLE',
						     'constraint' => '13,3',
							
						),
						  'added_by' => array(
							'type' => 'BIGINT'
						),
						
						'added_date' => array(
							 'type' => 'DATETIME'
						  ),
						'access_level_status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '1',
								'null' => false,
						  ),
						  'authorized_by' => array(
									'type' => 'BIGINT'
						  ),
						  'authorize_date' => array(
									 'type' => 'DATETIME'
						  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
						  ),
							'status' => array(
							 'type' => 'ENUM("0","1")',
							'default' => '0',
							'null' => false,
				     	),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('receive_from_assaying_id', TRUE);
		$this->dbforge->create_table('receive_from_assaying_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE receive_from_assaying_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
	
		
		 // Received from Assing   For Product Table 
		 
		
			$fields = array(
						'receive_from_assaying_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
						),
						'receive_from_assaying_id'=> array(
							 'type' => 'BIGINT'
				       ),
						'material_receipt_note' => array(
						      'type' => 'BIGINT'
					   ),
					   'product_id' => array(
						      'type' => 'BIGINT'
					   ),
					   'vendor_id'=> array(
									 'type' => 'BIGINT'
					  ),
						'product_id' => array(
						      'type' => 'BIGINT'
					   ),
					   'product_description' => array(
							 'type' => 'VARCHAR',
							'constraint' => '200',
					   ),
						'accepted_and_rejected' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
						),	
						'cornet_weight' => array(
							    'type' => 'DOUBLE',
						     	 'constraint' => '13,3',
						),
						'process_loss' => array(
							     'type' => 'DOUBLE',
						     	 'constraint' => '13,3',
						),						
						'creator_id' => array(
									 'type' => 'BIGINT'
					   ),
					   	'status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '0',
								'null' => false,
					    ),
						'createdOn' => array(
									 'type' => 'DATETIME'
					    ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('receive_from_assaying_product_id', TRUE);
		$this->dbforge->create_table('receive_from_assaying_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE receive_from_assaying_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		// Issue TO HallmArking
			$fields = array(
						'issue_to_hallmarking_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
					  ),
						'vendor_id'=> array(
									 'type' => 'BIGINT'
					  ),
						'issue_to_hallmarking_reference_number' => array(
								'type' => 'VARCHAR',
							 'constraint' => '200',
					  ),
						'issue_date' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
					  ),
						'from_store' => array(
						     	'type' => 'VARCHAR',
							 'constraint' => '200',
					  ),
						'issued_to' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
					  ),
						'narration' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
					  ),
						'total_weight' => array(
						        'type' => 'DOUBLE',
						     	 'constraint' => '13,3',
							
					  ),
						'total_quantity' => array(
							 'type' => 'BIGINT'
							
					 ),
					 	  'added_by' => array(
							'type' => 'BIGINT'
						),
						
						'added_date' => array(
							 'type' => 'DATETIME'
						  ),
						'access_level_status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '1',
								'null' => false,
						  ),
						  'authorized_by' => array(
									'type' => 'BIGINT'
						  ),
						  'authorize_date' => array(
									 'type' => 'DATETIME'
						  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
					 ),
					 	'status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '0',
								'null' => false,
					    ),
						'createdOn' => array(
									 'type' => 'DATETIME'
				     ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
				     ),
					);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('issue_to_hallmarking_id', TRUE);
		$this->dbforge->create_table('issue_to_hallmarking_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE issue_to_hallmarking_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
	
		
		 // Issue To Hallmarking    For Product Table 
		 
		
			$fields = array(
						'issue_to_hallmarking_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
						),
						'issue_to_hallmarking_id'=> array(
							 'type' => 'BIGINT'
				       ),
						'material_receipt_note' => array(
						      'type' => 'BIGINT'
					   ),
						'product_id' => array(
						      'type' => 'BIGINT'
					   ),
					   'receive_total_weight' => array(
							    'type' => 'DOUBLE',
						       'constraint' => '13,3',
					   ),
							
					  'receive_quantity' => array(
							 'type' => 'BIGINT'
						),							
						'creator_id' => array(
									 'type' => 'BIGINT'
					    ),
							'status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '0',
								'null' => false,
					    ),
						'createdOn' => array(
									 'type' => 'DATETIME'
					    ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
					    ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('issue_to_hallmarking_product_id', TRUE);
		$this->dbforge->create_table('issue_to_hallmarking_product_'.$office_id, TRUE);
		$this->db->query("Alter TABLE issue_to_hallmarking_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
	
	//Receive from  hallmarking
	
			$fields = array(
						'receive_from_hallmarking_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
					  ),
						'vendor_id'=> array(
									 'type' => 'BIGINT'
					  ),
						'receive_from_hallmarking_reference_number' => array(
							  'type' => 'VARCHAR',
							 'constraint' => '200',
					  ),
						'receive_date' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
					  ),
						'from_store' => array(
						     	'type' => 'VARCHAR',
							 'constraint' => '200',
					  ),
						'receive_form' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
					  ),
						'narration' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '200',
							
					  ),
						'total_weight' => array(
							    'type' => 'DOUBLE',
						       'constraint' => '13,3',
					  ),
						'total_quantity' => array(
							 'type' => 'BIGINT'
					 ),
					   'added_by' => array(
							'type' => 'BIGINT'
						),
						
						'added_date' => array(
							 'type' => 'DATETIME'
						  ),
						'access_level_status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '1',
								'null' => false,
						  ),
						  'authorized_by' => array(
									'type' => 'BIGINT'
						  ),
						  'authorize_date' => array(
									 'type' => 'DATETIME'
						  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
					 ),
					 	'status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '0',
								'null' => false,
					    ),
						'createdOn' => array(
									 'type' => 'DATETIME'
				     ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
				     ),
					);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('receive_from_hallmarking_id', TRUE);
		$this->dbforge->create_table('receive_from_hallmarking_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE receive_from_hallmarking_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		 //  Receive From Hallmarking    For Product Table 
		 
		
			$fields = array(
						'receive_to_hallmarking_product_id' => array(
						'type' => 'BIGINT',
						'unsigned' => TRUE,
						'auto_increment' => TRUE
						),
						'receive_from_hallmarking_id'=> array(
						'type' => 'BIGINT',
						),
						'material_receipt_note' => array(
						'type' => 'BIGINT',
						),
						'product_id' => array(
						'type' => 'BIGINT',
						),
						'product_name' => array(
						'type' => 'VARCHAR',
						'constraint' => '200',
						),	
						'received_weight' => array(
						'type' => 'DOUBLE',
						'constraint' => '13,3',
						),
						'received_quantity' => array(
						'type' => 'BIGINT'
						),				
						'creator_id' => array(
						'type' => 'BIGINT'
						),
							'status' => array(
								 'type' => 'ENUM("0","1")',
								'default' => '0',
								'null' => false,
					    ),
						'createdOn' => array(
						'type' => 'DATETIME'
						),
						'updatedOn' => array(
						'type' => 'TIMESTAMP'
						),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('receive_to_hallmarking_product_id', TRUE);
		$this->dbforge->create_table('receive_to_hallmarking_product_'.$office_id, TRUE);
		$this->db->query("Alter TABLE receive_to_hallmarking_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		//Return of Rejected Materia Add Table
		$fields = array(
					'return_of_rejected_material_id' => array(
					'type' => 'BIGINT',
					'unsigned' => TRUE,
					'auto_increment' => TRUE
					),
					'vendor_id'=> array(
					'type' => 'BIGINT'
					),
					'return_of_rejected_material_reference_number' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					),
					'return_date' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',

					),
					'form_store' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					),
					'narration' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					),
				  'added_by' => array(
						'type' => 'BIGINT'
					),
					'added_date' => array(
					'type' => 'DATETIME'
					),
					'access_level_status' => array(
					'type' => 'ENUM("0","1")',
					'default' => '1',
					'null' => false,
					),
					'authorized_by' => array(
					'type' => 'BIGINT'
					),
					'authorize_date' => array(
					'type' => 'DATETIME'
					),
					'creator_id' => array(
					'type' => 'BIGINT'
					),
					'status' => array(
							 'type' => 'ENUM("0","1")',
							'default' => '0',
							'null' => false,
					),
					'createdOn' => array(
					'type' => 'DATETIME'
					),
					'updatedOn' => array(
					'type' => 'TIMESTAMP'
					),
					);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('return_of_rejected_material_id', TRUE);
		$this->dbforge->create_table('return_of_rejected_material_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE return_of_rejected_material_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		 // Return of Rejected Materia    For Product Table 
			$fields = array(
						'return_of_rejected_material_product_id' => array(
						'type' => 'BIGINT',
						'unsigned' => TRUE,
						'auto_increment' => TRUE
						),
						'return_of_rejected_material_id'=> array(
						'type' => 'BIGINT'
						),
						'material_receipt_note' => array(
						'type' => 'BIGINT'
						),
						'product_id' => array(
						'type' => 'BIGINT'
						),
						'product_name' => array(
						'type' => 'VARCHAR',
						'constraint' => '200',
						),
						'total_weight' => array(
						'type' => 'DOUBLE',
						'constraint' => '13,3',
						),
						'scrap' => array(
						'type' => 'DOUBLE',
						'constraint' => '13,3',
						),				
						'creator_id' => array(
						'type' => 'BIGINT'
						),
						'status' => array(
						'type' => 'ENUM("0","1")',
						'default' => '0',
						'null' => false,
						),
						'createdOn' => array(
						'type' => 'DATETIME'
						),
						'updatedOn' => array(
						'type' => 'TIMESTAMP'
						),
					);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('return_of_rejected_material_product_id', TRUE);
		$this->dbforge->create_table('return_of_rejected_material_product_'.$office_id, TRUE);
		$this->db->query("Alter TABLE return_of_rejected_material_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		 	//Material Issue For Polishing Add Table
		$fields = array(
					'material_issue_for_polishing_id' => array(
					'type' => 'BIGINT',
					'unsigned' => TRUE,
					'auto_increment' => TRUE
					),
					'vendor_id'=> array(
					'type' => 'BIGINT'
					),
					'material_issue_for_polishing_reference_number' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					),
					'issue_date' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					),
					'material_issue_for_polishing_purpose' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					),
					'narration' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					), 	
					'in_house_issue' => array(
					  'type' => 'BIGINT'
					), 	
					'bank_guarantee_available' => array(
					   'type' => 'DOUBLE',
						'constraint' => '13,2',
					),
					'total_quantity' => array(
					  'type' => 'BIGINT'
					), 	
					'total_weight_925' => array(
					   'type' => 'DOUBLE',
						'constraint' => '13,3',
					), 	
					'total_weight_999' => array(
					    'type' => 'DOUBLE',
						'constraint' => '13,3',
					),
					
				   'added_by' => array(
						'type' => 'BIGINT'
					),
					'added_date' => array(
					'type' => 'DATETIME'
					),
					'access_level_status' => array(
					'type' => 'ENUM("0","1")',
					'default' => '1',
					'null' => false,
					),
					'authorized_by' => array(
					'type' => 'BIGINT'
					),
					'authorize_date' => array(
					'type' => 'DATETIME'
					),
					'creator_id' => array(
					'type' => 'BIGINT'
					),
					'status' => array(
							 'type' => 'ENUM("0","1")',
							'default' => '0',
							'null' => false,
					),
					'createdOn' => array(
					'type' => 'DATETIME'
					),
					'updatedOn' => array(
					'type' => 'TIMESTAMP'
					),
					);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('material_issue_for_polishing_id', TRUE);
		$this->dbforge->create_table('material_issue_for_polishing_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE material_issue_for_polishing_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		 // Return of Rejected Materia    For Product Table 
			$fields = array(
						'material_issue_for_polishing_product_id' => array(
						'type' => 'BIGINT',
						'unsigned' => TRUE,
						'auto_increment' => TRUE
						),
						'material_issue_for_polishing_id'=> array(
						'type' => 'BIGINT'
						),
						'barcode_number' => array(
						  'type' => 'VARCHAR',
					      'constraint' => '200',
						),
						'barcode_id' => array(
							'type' => 'BIGINT'
						),
						'material_issue_for_polishing_inventory_code' => array(
						  'type' => 'VARCHAR',
					      'constraint' => '200',
						),
						'material_issue_for_polishing_product_name' => array(
						  'type' => 'VARCHAR',
					      'constraint' => '200',
						),
						'material_issue_for_polishing_fineness' => array(
						'type' => 'DOUBLE',
						'constraint' => '13,3',
						),
						'material_issue_for_polishing_quantity' => array(
							'type' => 'BIGINT'
						),	
						'material_issue_for_polishing_weight' => array(
						'type' => 'DOUBLE',
						'constraint' => '13,3',
						),				
						'creator_id' => array(
						'type' => 'BIGINT'
						),
						'material_issue_number_status' => array(
						'type' => 'ENUM("0","1","2","3")',
						'default' => '1',
						'null' => false,
						),
						'status' => array(
						'type' => 'ENUM("0","1")',
						'default' => '0',
						'null' => false,
						),
						'createdOn' => array(
						'type' => 'DATETIME'
						),
						'updatedOn' => array(
						'type' => 'TIMESTAMP'
						),
					);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('material_issue_for_polishing_product_id', TRUE);
		$this->dbforge->create_table('material_issue_for_polishing_product_'.$office_id, TRUE);
		$this->db->query("Alter TABLE material_issue_for_polishing_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		/* Material Received after Polishing  */
    
		$fields = array(
							'material_receive_after_polishing_id' => array(
							'type' => 'BIGINT',
							'unsigned' => TRUE,
							'auto_increment' => TRUE
							),
							'vendor_id'=> array(
							'type' => 'BIGINT'
							),
							'material_receive_after_polishing_reference_number' => array(
							'type' => 'VARCHAR',
							'constraint' => '200',
							),
							'material_receive_after_polishing_receive_date' => array(
							'type' => 'VARCHAR',
							'constraint' => '200',
							),
							'material_issue_for_polishing_id' => array(
							'type' => 'BIGINT'
							),
							'narration' => array(
							'type' => 'VARCHAR',
							'constraint' => '200',
							), 	

							'total_quantity' => array(
							'type' => 'BIGINT'
							), 	
							'total_weight_925' => array(
							'type' => 'DOUBLE',
							'constraint' => '13,3',
							), 	
							'total_weight_999' => array(
							'type' => 'DOUBLE',
							'constraint' => '13,3',
							),
							'added_by' => array(
							'type' => 'BIGINT'
							),
							'added_date' => array(
							'type' => 'DATETIME'
							),
							'access_level_status' => array(
							'type' => 'ENUM("0","1")',
							'default' => '1',
							'null' => false,
							),
							'authorized_by' => array(
							'type' => 'BIGINT'
							),
							'authorize_date' => array(
							'type' => 'DATETIME'
							),
							'creator_id' => array(
							'type' => 'BIGINT'
							),
							'status' => array(
							'type' => 'ENUM("0","1")',
							'default' => '0',
							'null' => false,
							),
							'createdOn' => array(
							'type' => 'DATETIME'
							),
							'updatedOn' => array(
							'type' => 'TIMESTAMP'
							),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('material_receive_after_polishing_id', TRUE);
		$this->dbforge->create_table('material_receive_after_polishing_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE material_receive_after_polishing_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		 // Material Receive After Polishing   For Product Table 
			$fields = array(
								'material_receive_after_polishing_product_id' => array(
								'type' => 'BIGINT',
								'unsigned' => TRUE,
								'auto_increment' => TRUE
								),
								'material_receive_after_polishing_id'=> array(
								'type' => 'BIGINT'
								),

								'material_issue_for_polishing_product_id' => array(
								'type' => 'BIGINT'
								),
								'material_receive_after_polishing_product_name' => array(
								'type' => 'VARCHAR',
								'constraint' => '200',
								),

								'material_receive_after_polishing_quantity' => array(
								'type' => 'BIGINT'
								),	
								'material_receive_after_polishing_weight' => array(
								'type' => 'DOUBLE',
								'constraint' => '13,3',
								),

								'material_receive_after_polishing_narration' => array(
								'type' => 'VARCHAR',
								'constraint' => '200',
								),		
                              	'material_receive_after_polishing_oldcode' => array(
								'type' => 'VARCHAR',
								'constraint' => '200',
								),										
								'creator_id' => array(
								'type' => 'BIGINT'
								),
								'status' => array(
								'type' => 'ENUM("0","1")',
								'default' => '0',
								'null' => false,
								),
								'createdOn' => array(
								'type' => 'DATETIME'
								),
								'updatedOn' => array(
								'type' => 'TIMESTAMP'
								),
							);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('material_receive_after_polishing_product_id', TRUE);
		$this->dbforge->create_table('material_receive_after_polishing_product_'.$office_id, TRUE);
		$this->db->query("Alter TABLE material_receive_after_polishing_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
	}
	
	
	
	public function _createShowRoomInvoiceTable($office_operation_type,$office_id)
	{
	//  create invoice showroom table
		$fields = array(
						'invoice_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'invoice_date' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
							  
						'invoice_upload_document' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'invoice_type' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '50',
							  ),
					   'showrom_invoice_narrative' => array(
									 'type' => 'TEXT',
							  ),
						'customer_transaction_id' => array(
									  'type' => 'VARCHAR',
									 'constraint' => '60',
							  ),
						'invoice_number' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'customer_id' => array(
									 'type' => 'BIGINT'
							  ),
						'total_amount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'surcharge_on_vat' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'amount_received' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'adjustment' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
					    'transaction' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'reason' => array(
									 'type' => 'TEXT'
							  ),
						'narration' => array(
							 'type' => 'TEXT'
						),
						'amount_refunded' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '100',
						),
						'manual_invoice_number' => array(
							 'type' => 'VARCHAR',
							 'constraint' => '255',
						),
						'is_deleted' => array(
							 'type' => 'TINYINT',
						),
							'delete_by_user' => array(
							 'type' => 'BIGINT',
						),
							'deleted_date' => array(
							 'type' => 'DATETIME',
						),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('invoice_id', TRUE);
		$this->dbforge->create_table('invoice_'.$office_operation_type.'_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE invoice_".$office_operation_type."_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		//  create invoice showroom product table
		$fields = array(
						'invoice_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'invoice_id' => array(
									 'type' => 'BIGINT'
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'weight' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'qunatity' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'rate' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'discount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'tax' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'entry_tax' => array(
									 'type' => 'DECIMAL',
									 'constraint' => '10,2',
									 'default' => '0.00'
							  ),
						'net_amount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'tcs_amount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'tcs_percent' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'is_deleted' => array(
							 'type' => 'TINYINT',
						),
						'delete_by_user' => array(
							 'type' => 'BIGINT',
						),
						'deleted_date' => array(
							 'type' => 'DATETIME',
						),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('invoice_product_id', TRUE);
		$this->dbforge->create_table('invoice_'.$office_operation_type.'_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE invoice_".$office_operation_type."_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		//  create invoice showroom product serial number table
		$fields = array(
						'invoice_product_serial_number_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'invoice_id' => array(
									 'type' => 'BIGINT'
							  ),
						'invoice_product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'is_deleted' => array(
							 'type' => 'TINYINT',
						),
							'delete_by_user' => array(
							 'type' => 'BIGINT',
						),
							'deleted_date' => array(
							 'type' => 'DATETIME',
						),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('invoice_product_serial_number_id', TRUE);
		$this->dbforge->create_table('invoice_'.$office_operation_type.'_product_serial_number_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE invoice_".$office_operation_type."_product_serial_number_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		
		
		// create invoice payment mode table
		$fields = array(
						'invoice_payment_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'invoice_id' => array(
									 'type' => 'BIGINT'
									
							  ),
						'payment_type' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'payment_amount' => array(
									 'type' => 'DECIMAL',
									 'constraint' => '10,2',
							  ),
						'bank_name' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'card_cheque_number' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '150',
							  ),
						'card_issuing_bank' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '150',
							  ),
						'cheque_release' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),	  
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'is_deleted' => array(
							 'type' => 'TINYINT',
						),
						'delete_by_user' => array(
							 'type' => 'BIGINT',
						),
						'deleted_date' => array(
							 'type' => 'DATETIME',
						),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('invoice_payment_id', TRUE);
		$this->dbforge->create_table('invoice_'.$office_operation_type.'_payment_mode_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE invoice_".$office_operation_type."_payment_mode_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		// buy back table for showroom
		
		$fields = array(
						'purchase_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'purchase_date' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'purchase_type' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '50',
							  ),
						'purchase_number' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'customer_id' => array(
									 'type' => 'BIGINT'
							  ),
						'total_amount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'amount_paid' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'refunded_amount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'adjustment_amount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('purchase_id', TRUE);
		$this->dbforge->create_table('purchase_'.$office_operation_type.'_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE purchase_".$office_operation_type."_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		//  create invoice showroom product table
		$fields = array(
						'purchase_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'purchase_id' => array(
									 'type' => 'BIGINT'
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'weight' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'qunatity' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'rate' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'net_amount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'tcs_amount' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('purchase_product_id', TRUE);
		$this->dbforge->create_table('purchase_'.$office_operation_type.'_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE purchase_".$office_operation_type."_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
	}
	
	public function _createShowRoomInventoryTable($office_operation_type,$office_id)
	{
		//  create inventory initial stock table
		
		$fields1 = array(
						'initial_stock_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'initial_stock_quantity' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'initial_stock_serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'initial_stock_starting_store_date' => array(
									 'type' => 'DATETIME'
							  ),
						'initial_stock_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
						'access_level_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
						'authorized_by' => array(
									 'type' => 'BIGINT',
									 'default' => NULL,
							  ),
						'authorized_date' => array(
									 'type' => 'DATETIME',
									 'default' => NULL,
							  ),
						'added_by' => array(
									 'type' => 'BIGINT'
							  ),
						'added_date' => array(
									 'type' => 'DATETIME'
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields1);
		$this->dbforge->add_key('initial_stock_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_initial_stock_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_initial_stock_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		//create inventory initial stock product serial number table
		$fields2 = array(
						'initial_product_serial_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'initial_stock_id' => array(
									 'type' => 'BIGINT',
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'initial_product_serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'initial_product_serial_number_status' => array(
									'type' => 'ENUM("0","1","2")',
									'default' => '0',
									'null' => false,
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields2);
		$this->dbforge->add_key('initial_product_serial_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_initial_stock_product_serial_number_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_initial_stock_product_serial_number_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
	
		//  create inventory showroom stock receipt table
		$fields3 = array(
						'stock_receipt_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'stock_transfer_id' => array(
									 'type' => 'BIGINT'
							  ),
						'stock_receipt_date' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'stock_transfer_number' => array(
									 'type' => 'TEXT'
							  ),
						'stock_receipt_number' => array(
									 'type' => 'TEXT',
							  ),
						'stock_receipt_from' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'stock_transfer_date' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'stock_transfer_status' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'access_level_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
						'narration' => array(
									 'type' => 'TEXT',
							  ),
						'authorized_by' => array(
									 'type' => 'BIGINT',
									 'default' => NULL,
							  ),
						'authorized_date' => array(
									 'type' => 'DATETIME',
									 'default' => NULL,
							  ),
						'added_by' => array(
									 'type' => 'BIGINT'
							  ),
						'added_date' => array(
									 'type' => 'DATETIME'
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields3);
		$this->dbforge->add_key('stock_receipt_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_stock_receipt_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_stock_receipt_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		//  create inventory showroom stock receipt product table
		$fields4 = array(
						'stock_receipt_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'stock_receipt_id' => array(
									 'type' => 'BIGINT'
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'weight' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'stock_transferred' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'total_stock_received' => array(
									 'type' => 'BIGINT',
							  ),
						'stock_received' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'stock_pending' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'stock_transferStatus' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields4);
		$this->dbforge->add_key('stock_receipt_product_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_stock_receipt_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_stock_receipt_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		//  create inventory showroom stock receipt product serial number table
		$fields4 = array(
						'stock_receipt_serial_number_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'stock_receipt_id' => array(
									 'type' => 'BIGINT'
							  ),
						'stock_receipt_product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'stock_receipt_product_serial_number_status' => array(
									'type' => 'TINYINT'
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields4);
		$this->dbforge->add_key('stock_receipt_serial_number_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_stock_receipt_product_serial_number_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_stock_receipt_product_serial_number_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		//create inventory stock transfer/issue table
		
		$fields5 = array(
						'stock_transfer_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'stock_transfer_date' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'stock_transfer_number' => array(
									 'type' => 'TEXT'
							  ),
						'stock_transfer_to_office_id' => array(
									 'type' => 'BIGINT'
							  ),
						'stock_transfer_from' => array(
									'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'stock_transfer_narration' => array(
									 'type' => 'TEXT'
									 
							  ),
							  'stock_transfer_mode' => array(
									 'type' => 'TEXT'
									 
							  ),
							  'stock_transfer_mode_number' => array(
									 'type' => 'TEXT'
									 
							  ),
							  'stock_transferStatus' => array(
									 'type' => 'TEXT'
							  ),
							  'access_level_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
							  'stock_transfer_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
						'authorized_by' => array(
									 'type' => 'BIGINT',
									 'default' => NULL,
							  ),
						'authorized_date' => array(
									 'type' => 'DATETIME',
									 'default' => NULL,
							  ),
						'added_by' => array(
									 'type' => 'BIGINT'
							  ),
						'added_date' => array(
									 'type' => 'DATETIME'
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields5);
		$this->dbforge->add_key('stock_transfer_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_stock_transfer_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_stock_transfer_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		
		//create inventory stock transfer/issue product table
	
		$fields6 = array(
						'stock_transfer_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'stock_transfer_id' => array(
									 'type' => 'BIGINT'
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'stock_transfer_product_serial_number' => array(
									 'type' => 'TEXT'
							  ),
							  'stock_transfer_product_weight' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
							  'stock_transfer_product_quantity' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
							  'stock_transfer_product_remarks' => array(
									 'type' => 'TEXT',
							  ),
							  'stock_transfer_product_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
							  'access_level_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields6);
		$this->dbforge->add_key('stock_transfer_product_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_stock_transfer_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_stock_transfer_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		//create inventory stock transfer/issue product serial number table
	
		$fields6 = array(
						'stock_transfer_product_serial_number_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'stock_transfer_id' => array(
									 'type' => 'BIGINT'
							  ),
						'stock_transfer_product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'stock_transfer_product_serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'stock_transfer_product_serial_number_status' => array(
							 'type' => 'TINYINT'
						),
						'access_level_status' => array(
							 'type' => 'ENUM("0","1")',
							'default' => '0',
							'null' => false,
						),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields6);
		$this->dbforge->add_key('stock_transfer_product_serial_number_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_stock_transfer_product_serial_number_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_stock_transfer_product_serial_number_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
	}
	
	
	public function _createInventoryProductStockReceiptTable($office_operation_type,$office_id){
		
		//create inventory product stock receipt table
		
		$fields1 = array(
						'product_stock_receipt_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'vendor_id' => array(
									 'type' => 'BIGINT'
							  ),
						'product_stock_receipt_date' => array(
									 'type' => 'VARCHAR',
									 'constraint'=>'20',
							  ),
						'product_stock_receipt_number' => array(
									 'type' => 'TEXT'
							  ),
						'product_stock_receipt_work_order_no' => array(
									 'type' => 'TEXT'
							  ),
						'product_stock_receipt_work_order_status' => array(
									'type' => 'VARCHAR',
									 'constraint' => '25',
							  ),
						'reason_for_closing_workorder' => array(
									 'type' => 'TEXT'
									 
							  ),
							  'product_stock_receipt_status' => array(
									  'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
									 
							  ),
							  'access_level_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
						'authorized_by' => array(
									 'type' => 'BIGINT',
									 'default' => NULL,
							  ),
						'authorized_date' => array(
									 'type' => 'DATETIME',
									 'default' => NULL,
							  ),
						'added_by' => array(
									 'type' => 'BIGINT'
							  ),
						'added_date' => array(
									 'type' => 'DATETIME'
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields1);
		$this->dbforge->add_key('product_stock_receipt_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_product_stock_receipt_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_product_stock_receipt_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		//create inventory stock transfer/issue product table
	
		$fields2 = array(
						'stock_product_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'product_stock_receipt_id' => array(
									 'type' => 'BIGINT'
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
							  'stock_product_quantity' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
							  'stock_product_weight' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
							   'stock_product_qty_received' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'stock_product_serial_number' => array(
									 'type' => 'TEXT'
							  ),
							  'stock_product_net' => array(
									'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
							  
							  'stock_product_remarks' => array(
									 'type' => 'TEXT'
							  ),
							  'stock_product_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
							  'access_level_status' => array(
									 'type' => 'ENUM("0","1")',
									'default' => '0',
									'null' => false,
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields2);
		$this->dbforge->add_key('stock_product_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_product_stock_receipt_product_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_product_stock_receipt_product_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		
		//create inventory stock transfer/issue product serial number table for vendor
	
		$fields2 = array(
						'stock_product_serial_number_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'product_stock_receipt_id' => array(
									 'type' => 'BIGINT'
							  ),
						'stock_product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'stock_product_serial_number' => array(
									 'type' => 'TEXT'
							  ),
						'stock_product_serial_number_status' => array(
							 'type' => 'ENUM("0","1","2")',
							'default' => '0',
							'null' => false,
						),
						'access_level_status' => array(
							 'type' => 'ENUM("0","1")',
							'default' => '0',
							'null' => false,
						),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields2);
		$this->dbforge->add_key('stock_product_serial_number_id', TRUE);
		$this->dbforge->create_table('inventory_'.$office_operation_type.'_product_stock_receipt_p_s_n_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_".$office_operation_type."_product_stock_receipt_p_s_n_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		
	}
	
	public function _createCurrentStockTable($office_id)
	{
		//  create current stock table
		$fields = array(
						'current_stock_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'initial_stock_id'=> array(
									 'type' => 'BIGINT',
									 'default' => NULL,
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'product_current_stock' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'current_stock_status' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '100',
							  ),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('current_stock_id', TRUE);
		$this->dbforge->create_table('product_current_stock_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE product_current_stock_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		//  create current stock serial number table
		$fields = array(
						'current_stock_serial_number_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'current_stock_id'=> array(
									 'type' => 'BIGINT'
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'product_serial_number' => array(
									 'type' => 'TEXT',
							  ),
						'current_stock_status' => array(
							 'type' => 'ENUM("0","1","2","3","4")',
							'default' => '0',
							'null' => false,
						),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('current_stock_serial_number_id', TRUE);
		$this->dbforge->create_table('product_current_stock_serial_number_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE product_current_stock_serial_number_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		
		// create history table
		
		$fields = array(
						'history_id' => array(
									 'type' => 'BIGINT',
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
						'product_id' => array(
									 'type' => 'BIGINT'
							  ),
						'current_stock'=> array(
									 'type' => 'BIGINT'
							  ),
						'received_stock'=> array(
									 'type' => 'BIGINT'
							  ),
						'transfer_stock'=> array(
									 'type' => 'BIGINT'
							  ),
						'net_stock'=> array(
									 'type' => 'BIGINT'
							  ),
						'received_from' => array(
									 'type' => 'BIGINT'
							  ),
						'transfer_to'=> array(
									 'type' => 'BIGINT'
							  ),
						'extra_field_1'=> array(
									 'type' => 'VARCHAR',
									 'constraint' => '255'
							  ),
						'type_value'=> array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
						'remarks'=>array(
									'type' => 'TEXT'
						),
						'transaction_number'=>array(
									'type' => 'VARCHAR',
									 'constraint' => '255'
						),
						'authorized_by'=>array(
									'type' => 'BIGINT',
									 'default' => NULL,
						),
						'authorized_date'=>array(
									'type' => 'DATETIME',
									 'default' => NULL,
						),
						'creator_id' => array(
									 'type' => 'BIGINT'
							  ),
						'createdOn' => array(
									 'type' => 'DATETIME'
							  ),
						'updatedOn' => array(
									 'type' => 'TIMESTAMP'
							  ),
						);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('history_id', TRUE);
		$this->dbforge->create_table('inventory_office_history_'.$office_id, TRUE);
		
		$this->db->query("Alter TABLE inventory_office_history_".$office_id." CHANGE `updatedOn` `updatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
	}
	
   
}
