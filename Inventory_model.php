<?php

class Inventory_model extends CI_Model {

    public function __construct() {
        parent::__construct();
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
	
    public function _get_all_record_of_inventory_initial_stock_product_by_join($table_name,$office_id=null,$office_operation_type=null,$user_id=null,$trigger=null,$initial_stock_id=null,$product_id=null,$initial_stock_serial_number=null,$table_name_initial_product_serial=null){
		$checks=$this->base_model->table_exists($table_name);
		if($checks==true)
		{
		if(isset($trigger) && $trigger=='show'){
		if($office_operation_type=='showroom'){
			$sql="SELECT inventory_initial_stock.initial_stock_quantity,inventory_initial_stock.initial_stock_id,inventory_initial_stock.initial_stock_serial_number,inventory_initial_stock.initial_stock_starting_store_date,product_master.* FROM product_master left join office_product_master ON product_master.product_id=office_product_master.product_id left join ".$table_name." AS inventory_initial_stock ON product_master.product_id=inventory_initial_stock.product_id WHERE office_product_master.`office_id`=?";//Preventing SQL injection in Codeigniter using Query Binding Method
			//WHERE product_master.`creator_id`=?
			//array($user_id)
		$res=$this->db->query($sql,array($office_id));
		
		}else{
			$sql="SELECT inventory_initial_stock.initial_stock_quantity,inventory_initial_stock.initial_stock_id,inventory_initial_stock.initial_stock_serial_number,inventory_initial_stock.initial_stock_status,inventory_initial_stock.initial_stock_starting_store_date,product_master.* FROM product_master left join ".$table_name." AS inventory_initial_stock ON product_master.product_id=inventory_initial_stock.product_id";//Preventing SQL injection in Codeigniter using Query Binding Method
		$res=$this->db->query($sql);	
		}
		}else{
		$sql="SELECT inventory_initial_stock.initial_stock_quantity,inventory_initial_stock.initial_stock_id,inventory_initial_stock.initial_stock_serial_number,inventory_initial_stock.initial_stock_starting_store_date,inventory_initial_stock_product_serial_number.* FROM ".$table_name." AS inventory_initial_stock join ".$table_name_initial_product_serial." AS inventory_initial_stock_product_serial_number ON inventory_initial_stock.initial_stock_id=inventory_initial_stock_product_serial_number.initial_stock_id WHERE inventory_initial_stock_product_serial_number.`initial_stock_id`=? and inventory_initial_stock_product_serial_number.`product_id`=? order by inventory_initial_stock_product_serial_number.initial_product_serial_id";	
		$res=$this->db->query($sql, array($initial_stock_id,$product_id));
		}
		return $res->result();
		}
	}
	
	public function _get_all_record_of_inventory_stock_transfer_by($office_operation_type=null,$office_id=null){
		//$tableNameSTOCKRECEIPT='inventory_'.$office_operation_type.'_stock_receipt_'.$office_id;
		$tableNameSTOCKTRANSFERproduct='inventory_'.$office_operation_type.'_stock_transfer_product_'.$office_id;
		$checks=$this->base_model->table_exists($tableNameSTOCKTRANSFERproduct);
		if($checks==true)
		{
		   $sql="SELECT stock_transfer_product_weight, SUM(I_S_T_P.stock_transfer_product_weight) AS NET_WEIGHT,SUM(I_S_T_P.stock_transfer_product_quantity) AS NET_QUANTITY,product_master.product_name,product_master.product_id FROM ".$tableNameSTOCKTRANSFERproduct." AS I_S_T_P join product_master ON I_S_T_P.product_id=product_master.product_id group by I_S_T_P.product_id";//Preventing SQL injection in Codeigniter using Query Binding Method
			$res=$this->db->query($sql);
			return $res->result();
		}
		
	}
	
	/*public function _get_all_record_of_inventory_stock_transfer_by($user_id=null,$trigger=null){
		if(isset($trigger) && $trigger=='show'){
		$sql="SELECT SUM(inventory_stock_transfer_product.stock_transfer_product_weight) AS net_weight,SUM(inventory_stock_transfer_product.stock_transfer_product_quantity) AS net_quantity,product_master.product_name FROM inventory_stock_transfer_product join product_master ON   inventory_stock_transfer_product.product_id=product_master.product_id WHERE inventory_stock_transfer_product.`creator_id`=? group by inventory_stock_transfer_product.product_id";//Preventing SQL injection in Codeigniter using Query Binding Method
		$res=$this->db->query($sql, array($user_id));
		}else{
		$sql="SELECT inventory_initial_stock.initial_stock_quantity,inventory_initial_stock.initial_stock_id,inventory_initial_stock.initial_stock_serial_number,inventory_initial_stock.initial_stock_starting_store_date,inventory_initial_stock_product_serial_number.* FROM inventory_initial_stock join inventory_initial_stock_product_serial_number ON inventory_initial_stock.initial_stock_id=inventory_initial_stock_product_serial_number.initial_stock_id WHERE inventory_initial_stock.`creator_id`=? and inventory_initial_stock_product_serial_number.`initial_stock_id`=? and inventory_initial_stock_product_serial_number.`product_id`=?";	
		$res=$this->db->query($sql, array($user_id,$initial_stock_id,$product_id));
		}
		return $res->result();
	}*/
	
	
	public function _get_all_record_of_transfer_to_by_join($user_id=null,$trigger=null){
		$office_id=$this->session->userdata('office_id');
		$office_master=$this->db->get_where('office_master',array('office_id'=>$office_id))->row();
		$regional_store_id=$office_master->regional_store_id;
		
		$user_master=$this->db->get_where('users_master',array('user_id'=>$user_id))->row();
		
		if(isset($trigger) && $trigger=='show'){
		$sql="SELECT office_master.*,regional_store_master.regional_store_type FROM regional_store_master join office_master ON regional_store_master.regional_store_id=office_master.regional_store_id and office_master.office_id!=$office_id  order by office_master.office_id";//Preventing SQL injection in Codeigniter using Query Binding Method
		$res=$this->db->query($sql);
		}
		if(isset($trigger) && ($trigger=='store' || $trigger=='show')){
		if($user_master->role=='0')
		{
		$sql="SELECT office_master.*,regional_store_master.regional_store_type FROM regional_store_master join office_master ON regional_store_master.regional_store_id=office_master.regional_store_id and office_master.office_id!=$office_id  order by office_master.office_id";
		
		}
		else
		{
		//$sql="SELECT office_master.*,regional_store_master.regional_store_type FROM regional_store_master join office_master ON regional_store_master.regional_store_id=office_master.regional_store_id and office_master.office_id!=$office_id and ((office_master.regional_store_id='".$regional_store_id."' and office_operation_type='showroom') or office_master.regional_store_id!='".$regional_store_id."' and office_operation_type='store')  order by office_master.office_id";
		
		$sql="SELECT office_master.*,regional_store_master.regional_store_type FROM regional_store_master join office_master ON regional_store_master.regional_store_id=office_master.regional_store_id and office_master.office_id!=$office_id and ((office_master.regional_store_id='".$regional_store_id."' and office_operation_type='showroom') or  (office_operation_type='store'))  order by office_master.office_id";
		
		}
		//echo $sql;
		//Preventing SQL injection in Codeigniter using Query Binding Method
		$res=$this->db->query($sql);
		}
		if(isset($trigger) && $trigger=='showroom'){
		if($user_master->role=='0')
		{
		$sql="SELECT office_master.*,regional_store_master.regional_store_type FROM regional_store_master join office_master ON regional_store_master.regional_store_id=office_master.regional_store_id and office_master.office_id!=$office_id  where office_operation_type=? order by office_master.office_id";
		
		}
		else
		{
		$sql="SELECT office_master.*,regional_store_master.regional_store_type FROM regional_store_master join office_master ON regional_store_master.regional_store_id=office_master.regional_store_id and office_master.office_id!='".$office_id."'  where office_operation_type=? and office_master.regional_store_id='".$regional_store_id."' order by office_master.office_id";
		}
		//Preventing SQL injection in Codeigniter using Query Binding Method	
		$res=$this->db->query($sql,array('store'));
		}
		return $res->result();
	}
	public function _get_all_record_of_inventory_product_stock_receipt_by($table_name){
	$checks=$this->base_model->table_exists($table_name);
		if($checks==true)
		{
		   $sql="SELECT stock_product_weight, SUM(I_P_S_R.stock_product_weight) AS NET_WEIGHT,SUM(I_P_S_R.stock_product_qty_received) AS NET_QUANTITY,product_master.product_name,product_master.product_id FROM ".$table_name." AS I_P_S_R join product_master ON I_P_S_R.product_id=product_master.product_id group by I_P_S_R.product_id";//Preventing SQL injection in Codeigniter using Query Binding Method
		 
			$res=$this->db->query($sql);
			return $res->result();
		}
		
		
	}
	/*public function _get_all_record_of_inventory_product_stock_receipt_by($table_name,$office_id=null,$office_operation_type=null,$user_id=null,$trigger=null){
		$checks=$this->base_model->table_exists($table_name);
		if($checks==true)
		{
		if(isset($trigger) && $trigger=='show'){
		$sql="SELECT SUM(inventory_stock_product_receipt.stock_product_weight) AS net_weight,SUM( inventory_stock_product_receipt.stock_product_qty_received) AS net_quantity,product_master.product_name FROM ".$table_name." AS inventory_stock_product_receipt join product_master ON   inventory_stock_product_receipt.product_id=product_master.product_id group by inventory_stock_product_receipt.product_id";//Preventing SQL injection in Codeigniter using Query Binding Method
		$res=$this->db->query($sql);
		}else{
		$sql="SELECT inventory_initial_stock.initial_stock_quantity,inventory_initial_stock.initial_stock_id,inventory_initial_stock.initial_stock_serial_number,inventory_initial_stock.initial_stock_starting_store_date,inventory_initial_stock_product_serial_number.* FROM inventory_initial_stock join inventory_initial_stock_product_serial_number ON inventory_initial_stock.initial_stock_id=inventory_initial_stock_product_serial_number.initial_stock_id WHERE inventory_initial_stock.`creator_id`=? and inventory_initial_stock_product_serial_number.`initial_stock_id`=? and inventory_initial_stock_product_serial_number.`product_id`=?";	
		$res=$this->db->query($sql, array($user_id,$initial_stock_id,$product_id));
		}
		return $res->result();
		}
	}*/
	
	
	public function _get_all_record_of_inventory_stock_transfer_receipt_by($stock_receipt_id,$office_operation_type,$office_id)
	{
		
		/*  $sql="SELECT inventory_stock_transfer_product.stock_transfer_product_serial_number,SUM(inventory_stock_transfer_product.stock_transfer_product_weight) AS NET_WEIGHT,SUM(inventory_stock_transfer_product.stock_transfer_product_quantity) AS NET_QUANTITY,product_master.product_name,product_master.product_id FROM inventory_stock_transfer_product join product_master ON   inventory_stock_transfer_product.product_id=product_master.product_id WHERE inventory_stock_transfer_product.`stock_transfer_id`=? group by inventory_stock_transfer_product.product_id";//Preventing SQL injection in Codeigniter using Query Binding Method
        $res=$this->db->query($sql, array($stock_transfer_id));
        return $res->result(); */
		
		//$this->db->select('(rec_tab.weight * rec_tab.stock_transferred) as NET_WEIGHT,rec_tab.stock_transferred as NET_QUANTITY,rec_s_n_tab.serial_number as stock_transfer_product_serial_number, p_m_tab.product_name, rec_tab.product_id')->from('inventory_'.$office_operation_type.'_stock_receipt_product_'.$office_id.' as rec_tab');
		// $this->db->join('inventory_'.$office_operation_type.'_stock_receipt_product_serial_number_'.$office_id.' as rec_s_n_tab','rec_tab.stock_receipt_id=rec_s_n_tab.stock_receipt_id','left');
		$this->db->select('(rec_tab.weight * rec_tab.stock_transferred) as NET_WEIGHT,rec_tab.stock_transferred as NET_QUANTITY, p_m_tab.product_name, rec_tab.product_id,rec_tab.stock_receipt_product_id,rec_tab.stock_received,rec_tab.stock_pending')->from('inventory_'.$office_operation_type.'_stock_receipt_product_'.$office_id.' as rec_tab');
		
		$this->db->join('product_master as p_m_tab','rec_tab.product_id = p_m_tab.product_id','left');
		
		$this->db->where('rec_tab.stock_receipt_id',$stock_receipt_id);
		$data = $this->db->get()->result();
		//print_r($data); die;
		return $data;
		
		
		
		
	}
	
	public function getAllProductsByOfficeId($office_id)
	{
		$office_cur_id=$this->session->userdata('office_id');
		$this->db->where('office_id',$office_id);
		$this->db->where('product_current_stock > ','0');
		
		$this->db->select('product_master.*')->from('office_product_master');
		$this->db->join('product_master','product_master.product_id= office_product_master.product_id','left');
		$this->db->join('product_current_stock_'.$office_cur_id.' as current_st','product_master.product_id=current_st.product_id','left');
		
		$query = $this->db->get();
		
		return $query->result();
	}
	
	public function _get_all_record_of_inventory_stock_receipt_by($office_operation_type=null,$office_id=null){
		//$tableNameSTOCKRECEIPT='inventory_'.$office_operation_type.'_stock_receipt_'.$office_id;
		$tableNameSTOCKRECEIPTproduct='inventory_'.$office_operation_type.'_stock_receipt_product_'.$office_id;
		$checks=$this->base_model->table_exists($tableNameSTOCKRECEIPTproduct);
		if($checks==true)
		{
		   $sql="SELECT product_master.product_weight,I_S_P_R.serial_number,SUM(I_S_P_R.weight) AS NET_WEIGHT,SUM(I_S_P_R.stock_received) AS NET_QUANTITY,SUM(I_S_P_R.stock_transferred) AS TRANSFER_STOCK,SUM(I_S_P_R.stock_pending) AS PENDING_STOCK,product_master.product_name,product_master.product_id FROM ".$tableNameSTOCKRECEIPTproduct." AS I_S_P_R join product_master ON I_S_P_R.product_id=product_master.product_id group by I_S_P_R.product_id";//Preventing SQL injection in Codeigniter using Query Binding Method
			$res=$this->db->query($sql);
			return $res->result();
		}
		
	}
	
	public function _get_all_record_stock_receipt_details_to_by_join($tableNameSTOCKRECEIPT,$office_operation_type,$office_id,$fromDate,$toDate){
		/* $checks=$this->base_model->table_exists($tableNameSTOCKRECEIPT);
		if($checks==true)
		{
		$sql="SELECT inventory_stock_transfer.stock_transfer_date,inventory_stock_transfer.stock_transfer_number,inventory_stock_transfer.stock_transfer_from,inventory_stock_transfer.stock_transferStatus,I_S_R.stock_receipt_date FROM inventory_stock_transfer left join ".$tableNameSTOCKRECEIPT." AS I_S_R ON I_S_R.stock_transfer_id=inventory_stock_transfer.stock_transfer_id where inventory_stock_transfer.stock_transfer_to_office_id=?";//Preventing SQL injection in Codeigniter using Query Binding Method
		
		$sql = "select inv_trans.*,om.office_name from ".$tableNameSTOCKRECEIPT. " as inv_trans left join office_master as om on inv_trans.stock_transfer_to_office_id=om.office_id";
		$res=$this->db->query($sql,array($office_id));
		return $res->result();
		} */
		$tableNameSTOCKRECEIPTPro='inventory_'.$office_operation_type.'_stock_receipt_product_'.$office_id;	
		 $this->db->select('tp.product_id,t.product_type_short_code,tp.stock_receipt_id')->from($tableNameSTOCKRECEIPTPro.' as tp');
			 $this->db->join('product_master as p','tp.product_id=p.product_id','letf');
			 $this->db->join('product_type_master as t','p.product_type_id=t.product_type_id','letf');
			 $this->db->where('t.product_type_short_code =','SANCHI');
			 $arr_pro_type=$this->db->get()->result();
		
		 $stock_receipt_id=array();
		 foreach($arr_pro_type as $pro_data)
		 {
			$stock_receipt_id[]=$pro_data->stock_receipt_id;
		 }
		 $stock_receipt_id=array_unique($stock_receipt_id);
		$this->db->select('rec_table.stock_transfer_number,rec_table.stock_receipt_number,rec_table.stock_transfer_date,rec_table.stock_transfer_status, rec_table.stock_receipt_id,ofc_mstr.office_name,ofc_mstr.office_address,ofc_mstr.city_id,rec_table.access_level_status,
		ofc_mstr.district_id,ofc_mstr.state_id,rec_table.added_by')->from($tableNameSTOCKRECEIPT.' as rec_table');
		$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=rec_table.stock_receipt_from');
		$this->db->where(array('rec_table.createdOn >=' => $fromDate,'rec_table.createdOn <=' => $toDate));
		if(count($stock_receipt_id)>0)
		{
			$this->db->where_not_in('stock_receipt_id',$stock_receipt_id);
		}
		$data = $this->db->get()->result();
		return $data;
		
	}
	public function _get_all_record_stock_transfer_details_to_by_join($tableNameSTOCKRECEIPT,$office_operation_type,$office_id,$fromDate,$toDate){
		 $tableNameSTOCKTRANSPro='inventory_'.$office_operation_type.'_stock_transfer_product_'.$office_id;	
			
		
		 
		
			 $this->db->select('tp.product_id,t.product_type_short_code,tp.stock_transfer_id')->from($tableNameSTOCKTRANSPro.' as tp');
			 $this->db->join('product_master as p','tp.product_id=p.product_id','letf');
			 $this->db->join('product_type_master as t','p.product_type_id=t.product_type_id','letf');
			 $this->db->where('t.product_type_short_code =','SANCHI');
			 $arr_pro_type=$this->db->get()->result();
		
		 $stock_trans_id=array();
		 foreach($arr_pro_type as $pro_data)
		 {
			$stock_trans_id[]=$pro_data->stock_transfer_id;
		 }
		 $stock_trans_id=array_unique($stock_trans_id);
	
		$this->db->select('trans_table.stock_transfer_number,trans_table.stock_transfer_date,trans_table.stock_transfer_narration,trans_table.stock_transfer_status,trans_table.stock_transfer_id, ofc_mstr.office_name,ofc_mstr.office_address,ofc_mstr.city_id,trans_table.access_level_status,
		ofc_mstr.district_id,ofc_mstr.state_id,trans_table.added_by')->from($tableNameSTOCKRECEIPT.' as trans_table');
		$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=trans_table.stock_transfer_to_office_id');
		$this->db->where(array('trans_table.createdOn >=' => $fromDate,'trans_table.createdOn <=' => $toDate));
		if(count($stock_trans_id)>0)
		{
		$this->db->where_not_in('stock_transfer_id',$stock_trans_id);
		}
		$data = $this->db->get()->result();
		
		return $data;
		
	}
	public function _get_all_record_of_inventory_stock_transfer_by_stock_transfer_id($stock_transfer_id=null){
		
		$office_id=$this->session->userdata('office_id');
		$office_operation_type=$this->session->userdata('office_operation_type');
		$table_transfer='inventory_'.$office_operation_type.'_stock_transfer_'.$office_id;
		
		$table_transfer_product='inventory_'.$office_operation_type.'_stock_transfer_product_'.$office_id;
		$table_transfer_product_serials='inventory_'.$office_operation_type.'_stock_transfer_product_serial_number_'.$office_id;
		$pass_data=array();
		$sql="SELECT *  from $table_transfer where stock_transfer_id=?";//Preventing SQL injection in Codeigniter using Query Binding Method
		$pass_data['stock_transfer_detail']=$this->db->query($sql, array($stock_transfer_id))->row();
		
		$sql="SELECT tp.*,pm.product_name  from $table_transfer_product as tp left join product_master as pm on tp.product_id=pm.product_id where stock_transfer_id=?";//Preventing SQL injection in Codeigniter using Query Binding Method
		$pass_data['stock_transfer_product_detail']=$this->db->query($sql, array($stock_transfer_id))->result();
		
		
		foreach($pass_data['stock_transfer_product_detail'] as $products)
		{
		$sql="SELECT *  from $table_transfer_product_serials where stock_transfer_id=? and stock_transfer_product_id=?";//Preventing SQL injection in Codeigniter using Query Binding Method
		$pass_data['stock_transfer_product_serials_detail'][$products->stock_transfer_product_id]=$this->db->query($sql, array($stock_transfer_id,$products->stock_transfer_product_id))->result();
		
		}
		
		
		
		return $pass_data;
	}
	public function _get_all_record_of_inventory_stock_receipt_by_stock_transfer_id($stock_receipt_id=null){
		
		$office_id=$this->session->userdata('office_id');
		$office_operation_type=$this->session->userdata('office_operation_type');
		
		$table_receipt='inventory_'.$office_operation_type.'_stock_receipt_'.$office_id;
		
		$table_receipt_product='inventory_'.$office_operation_type.'_stock_receipt_product_'.$office_id;
		$table_receipt_product_serials='inventory_'.$office_operation_type.'_stock_receipt_product_serial_number_'.$office_id;
		$pass_data=array();
		$sql="SELECT *  from $table_receipt where stock_receipt_id=?";//Preventing SQL injection in Codeigniter using Query Binding Method
		
		$pass_data['stock_receipt_detail']=$this->db->query($sql, array($stock_receipt_id))->row();
		
		$sql="SELECT tr.*,pm.product_name,pm.product_weight from $table_receipt_product as tr left join product_master as pm on tr.product_id=pm.product_id where stock_receipt_id=?";//Preventing SQL injection in Codeigniter using Query Binding Method
		$pass_data['stock_receipt_product_detail']=$this->db->query($sql, array($stock_receipt_id))->result();
		
		
		foreach($pass_data['stock_receipt_product_detail'] as $products)
		{
		$sql="SELECT *  from $table_receipt_product_serials where stock_receipt_id=? and stock_receipt_product_id=? and stock_receipt_product_serial_number_status!=3";//Preventing SQL injection in Codeigniter using Query Binding Method
		$pass_data['stock_receipt_product_serials_detail'][$products->stock_receipt_product_id]=$this->db->query($sql, array($stock_receipt_id,$products->stock_receipt_product_id))->result();
		
		}
		
		
		
		return $pass_data;
	}
	
	public function _get_all_record_product_stock_transfer_details_to_by_join($tableNamePRODUCTSTOCKRECEIPT,$office_operation_type,$office_id,$fromDate,$toDate)
	{
		
		/*$this->db->select('product_stock_table.*, ofc_mstr.office_name,ofc_mstr.office_address,ofc_mstr.city_id,
		ofc_mstr.district_id,ofc_mstr.state_id')->from($tableNamePRODUCTSTOCKRECEIPT.' as product_stock_table');
		$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=product_stock_table.stock_transfer_to_office_id');*/
		$this->db->select('product_stock_table.*,vendor_master.vendor_name')->from($tableNamePRODUCTSTOCKRECEIPT.' as product_stock_table');
		$this->db->join('vendor_master','product_stock_table.vendor_id=vendor_master.vendor_id','letf');
		//$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=product_stock_table.stock_transfer_to_office_id');
		$this->db->where(array('product_stock_table.createdOn >=' => $fromDate,'product_stock_table.createdOn <=' => $toDate));
		$data = $this->db->get()->result();
		
		return $data;
		
	}
public function _get_all_record_of_inventory_product_receipt_by_stock_transfer_id($product_stock_receipt_id=null){
		
		$office_id=$this->session->userdata('office_id');
		$office_operation_type=$this->session->userdata('office_operation_type');
		
		
		$table_product_receipt='inventory_'.$office_operation_type.'_product_stock_receipt_'.$office_id;
		
		$table_product_receipt_product='inventory_'.$office_operation_type.'_product_stock_receipt_product_'.$office_id;
		$table_product_receipt_product_serials='inventory_'.$office_operation_type.'_product_stock_receipt_p_s_n_'.$office_id;
		$pass_data=array();
		$sql="SELECT *  from $table_product_receipt where product_stock_receipt_id=?";//Preventing SQL injection in Codeigniter using Query Binding Method
		
		$pass_data['product_receipt_detail']=$this->db->query($sql, array($product_stock_receipt_id))->row();
		
		$sql="SELECT tr.*,pm.product_name  from $table_product_receipt_product as tr left join product_master as pm on tr.product_id=pm.product_id where product_stock_receipt_id=?";
		//Preventing SQL injection in Codeigniter using Query Binding Method
		$pass_data['product_receipt_product_detail']=$this->db->query($sql, array($product_stock_receipt_id))->result();
		
		
		foreach($pass_data['product_receipt_product_detail'] as $products)
		{
		$sql="SELECT *  from $table_product_receipt_product_serials where stock_product_id=?";
		//Preventing SQL injection in Codeigniter using Query Binding Method
		$pass_data['stock_receipt_product_serials_detail'][$products->stock_product_id]=$this->db->query($sql, array($products->stock_product_id))->result();
		
		}
		
		
		return $pass_data;
	}
	public function office_location_list()
	{
	$sql="SELECT office_master.*,regional_store_master.regional_store_type FROM regional_store_master join office_master ON regional_store_master.regional_store_id=office_master.regional_store_id  order by office_master.office_id";
	$res=$this->db->query($sql);
	return $res->result();
	}
	
	public function getAllVendorsWithStore($office_id)
	{
		$data = $this->db->select('vndr.*')->from('vendor_master as vndr')->join('vendor_store_master as vsm','vndr.vendor_id=vsm.vendor_id')->where(array('vsm.office_id'=>$office_id))->get()->result();
		return $data;
	}
	
	public function getAllProductsByOfficeIdWithoutCurrent($office_id)
	{
		$office_cur_id=$this->session->userdata('office_id');
		$this->db->where('office_id',$office_id);
		
		$this->db->select('product_master.*')->from('office_product_master');
		$this->db->join('product_master','product_master.product_id= office_product_master.product_id','left');
		$this->db->join('product_current_stock_'.$office_cur_id.' as current_st','product_master.product_id=current_st.product_id','left');
		
		$query = $this->db->get();
		
		return $query->result();
	}

	public function showroom_office_location_list($regional_ids)
	{
		$this->db->select('office_master.*,regional_store_master.regional_store_type')->from('office_master');
		$this->db->join('regional_store_master','regional_store_master.regional_store_id=office_master.regional_store_id');
		$this->db->where(array('office_operation_type'=>'showroom'));
		if(!empty($regional_ids))
		{
		$this->db->where_in('regional_store_master.regional_store_id',$regional_ids);
		}
		$res=$this->db->get();
		return $res->result();
	}
	
	public function schedule_office_location_list($regional_ids)
	{
		$this->db->select('office_master.*,regional_store_master.regional_store_type')->from('office_master');
		$this->db->join('regional_store_master','regional_store_master.regional_store_id=office_master.regional_store_id');
		$this->db->where(array('office_id >'=>'1'));
		$this->db->where_in('regional_store_master.regional_store_id',$regional_ids);
		$res=$this->db->get();
		return $res->result();
	}
	
}
