<?php

class Report_model extends CI_Model {

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
	
   
	public function _get_all_report($office_operation_type=null,$office_id=null){
		
		
		/* $table_inventory_stock_receipt='inventory_'.$office_operation_type.'_stock_receipt_'.$office_id;
		$table_inventory_stock_receipt_product='inventory_'.$office_operation_type.'_stock_receipt_product_'.$office_id; */
		$inventory_office_history="inventory_office_history_".$office_id;
		
		if($office_operation_type=='showroom'){
			
			//print_r($office_id);die;
			$this->db->select("$inventory_office_history.*,$inventory_office_history.createdOn,product_master.product_name");
            $this->db->from("$inventory_office_history");
			$this->db->join('product_master',"product_master.product_id=$inventory_office_history.product_id","LEFT");
            $this->db->order_by("history_id","Desc");
            $query = $this->db->get();
			$data=$query->result_array();
			/* echo "<pre>";
			print_r($data);die; */
			return $data;
			 
		
		}else{
			$this->db->select("$inventory_office_history.*,$inventory_office_history.createdOn, product_master.product_name");
            $this->db->from("$inventory_office_history");
			$this->db->join('product_master',"product_master.product_id=$inventory_office_history.product_id","LEFT");
            $query = $this->db->get();
			$data=$query->result_array();
			$data=$query->result_array();
			/* echo "<pre>";
			print_r($data);die; */
			return $data;
			
		}		
	}
	
	public function _get_all_report_between_date($office_operation_type=null,$office_id=null,$from=null,$to=null,$product_id=null,$transaction_name=null,$teansfer_recive_office_location=null,$vendor_id=null){
		
		//print_r($office_id);die;
		/* $table_inventory_stock_receipt='inventory_'.$office_operation_type.'_stock_receipt_'.$office_id;
		$table_inventory_stock_receipt_product='inventory_'.$office_operation_type.'_stock_receipt_product_'.$office_id; */
		$inventory_office_history="inventory_office_history_".$office_id;
		
		if($office_operation_type=='showroom'){
			
			//print_r($office_id);die;
			$this->db->select("$inventory_office_history.*,$inventory_office_history.createdOn,product_master.product_name");
            $this->db->from("$inventory_office_history");
			$this->db->join('product_master',"product_master.product_id=$inventory_office_history.product_id","LEFT");
			if(!empty($from))
			{
			$this->db->where("$inventory_office_history.createdOn >=", $from);
		   }if(!empty($to))
			{
            $this->db->where("$inventory_office_history.createdOn <=", $to);
		    }
			if($product_id)
			{
				$this->db->where_in("$inventory_office_history.product_id",$product_id);
			}
			if($transaction_name)
			{
				if($transaction_name=='Initial')
				{
					//$this->db->where("$inventory_office_history.received_stock ",'');
					//$this->db->where("$inventory_office_history.transfer_stock ",'');
					$this->db->where("$inventory_office_history.type_value",'initial');
				}
				elseif($transaction_name=='Product Recived')
				{
					$this->db->where("$inventory_office_history.received_stock !=",'');
					$this->db->where("$inventory_office_history.transfer_stock",'');
					$this->db->where("$inventory_office_history.type_value ",'vendor');
				}
				elseif($transaction_name=='Stock Recived')
				{
					$this->db->where("$inventory_office_history.received_stock !=",'');
					$this->db->where("$inventory_office_history.transfer_stock",'');
					$this->db->where("$inventory_office_history.type_value !=",'vendor');
					$this->db->where("$inventory_office_history.type_value !=",'customer');
					$this->db->where("$inventory_office_history.type_value !=",'Invoice Edited');
					$this->db->where("$inventory_office_history.type_value !=",'Invoice Deleted');
					$this->db->where("$inventory_office_history.type_value !=",'Buy Back');
				}
				elseif($transaction_name=='Stock Transfer')
				{
					$this->db->where("$inventory_office_history.received_stock ",'');
					$this->db->where("$inventory_office_history.transfer_stock !=",'');
					$this->db->where("$inventory_office_history.type_value !=",'customer');
					$this->db->where("$inventory_office_history.type_value !=",'Invoice Edited');
					$this->db->where("$inventory_office_history.type_value !=",'Invoice Deleted');
					$this->db->where("$inventory_office_history.type_value !=",'Buy Back');
				}
				elseif($transaction_name=='Invoice')
				{
					$this->db->where("$inventory_office_history.type_value",'customer');
					
				}
				elseif($transaction_name=='Buy Back')
				{
					$this->db->where("$inventory_office_history.type_value",'Buy Back');
					
				}
				
			}
			if($teansfer_recive_office_id)
			{
				
				    $this->db->where("$inventory_office_history.received_stock ",$teansfer_recive_office_id);
					$this->db->where("$inventory_office_history.transfer_stock ",$teansfer_recive_office_id);
				
			}
			if($vendor_id)
			{
				    $this->db->where("$inventory_office_history.received_from ",$vendor_id);
			}
            $query = $this->db->get();
			$data=$query->result_array();
			/* echo "<pre>";
			print_r($data);die; */
			return $data;
			 
		
		}else{
			$this->db->select("$inventory_office_history.*,$inventory_office_history.createdOn,product_master.product_name");
            $this->db->from("$inventory_office_history");
			$this->db->join('product_master',"product_master.product_id=$inventory_office_history.product_id","LEFT");
			if(($this->input->post('access_right_from')!="") && ($this->input->post('access_right_to')!=""))
			{
				$this->db->where("$inventory_office_history.createdOn >=",trim($from));
				$this->db->where("$inventory_office_history.createdOn <=",trim($to));
			}
			if($product_id)
			{
				$this->db->where_in("$inventory_office_history.product_id",$product_id);
			}
			if($transaction_name)
			{
				if($transaction_name=='Initial')
				{
					$this->db->where("$inventory_office_history.received_stock ",'');
					$this->db->where("$inventory_office_history.transfer_stock ",'');
				}
				elseif($transaction_name=='Product Recived')
				{
					$this->db->where("$inventory_office_history.received_stock !=",'');
					$this->db->where("$inventory_office_history.transfer_stock",'');
					$this->db->where("$inventory_office_history.type_value ",'vendor');
				}
				elseif($transaction_name=='Stock Recived')
				{
					$this->db->where("$inventory_office_history.received_stock !=",'');
					$this->db->where("$inventory_office_history.transfer_stock",'');
					$this->db->where("$inventory_office_history.type_value !=",'vendor');
				}
				elseif($transaction_name=='Stock Transfer')
				{
					$this->db->where("$inventory_office_history.received_stock ",'');
					$this->db->where("$inventory_office_history.transfer_stock !=",'');
				}
				elseif($transaction_name=='Invoice')
				{
					$this->db->where("$inventory_office_history.type_value ",'customer');
				}
			}
			if($teansfer_recive_office_location)
			{
				//print_r($teansfer_recive_office_location);die;
				    $this->db->where_in("$inventory_office_history.received_from ",$teansfer_recive_office_location);
					$this->db->or_where_in("$inventory_office_history.transfer_to",$teansfer_recive_office_location);
					
					//$where = "($inventory_office_history.received_from=$teansfer_recive_office_location or $inventory_office_history.transfer_to = $teansfer_recive_office_location)";
                    //$this->db->where_in($where);
					
				
			}
			if($vendor_id)
			{
				    $this->db->where("$inventory_office_history.received_from ",$vendor_id);
			}
            $query = $this->db->get();
			$data=$query->result_array();
			$data=$query->result_array();
			/* echo "<pre>";
			print_r($data);die; */
			return $data;
			
		}		
	}
	public function _get_transaction_report($office_operation_type=null,$office_id=null)
	{

		$str_ids=='';
		$from = date('Y-m-d',strtotime('now'));
		$to = date('Y-m-d',strtotime('now'));
		//$transaction_data=$this->db->get_where('invoice_showroom_'.$office_id)->result();
		$where = "where created_date >='".date('Y-m-d')." 00:00:00' and created_date <='".date('Y-m-d')." 23:59:59' ";
		if($this->input->post('submit'))
		{
			
			$from=$this->input->post('access_right_from');
			$to=$this->input->post('access_right_to');
			
			$from2 = explode('/',$from);
			$to2 = explode('/',$to);
			$fromDate = $from2[2].'-'.$from2[1].'-'.$from2[0];
			$toDate = $to2[2].'-'.$to2[1].'-'.$to2[0];
			
			$str_where=array();
			$str_ids='';
			$str_ids_pur='';
		
			if(!empty($fromDate)){
			$from="$fromDate 00:00:00";
			
			 $str_where[]=" created_date >='".$from."'";
		    }if(!empty($toDate)){
			$to="$toDate 23:59:59";
			$str_where[]=" created_date <='".$to."'";
		     }
			$product_id="";
			$transaction_name="";
			if($this->input->post('type_transaction'))
			{
			 $type_transaction=$this->input->post('type_transaction');
			 $data['type_transaction']=$type_transaction;
			 if($type_transaction=='sales')
			 {
				  $str_where[]=" (invoice_type!='purchase' and invoice_type!='')";
			 }
			 elseif($type_transaction=='purchase')
			 {
				  $str_where[]=" (invoice_type='purchase' or invoice_type='')";
			 }
			
			}
			if($this->input->post('payment_mode'))
			{
			 $payment_mode=$this->input->post('payment_mode');
			 $data['payment_mode']=$payment_mode;
			
			 $payment_mode_table='invoice_showroom_payment_mode_'.$this->session->userdata('office_id');
											$this->db->select('invoice_id')->from($payment_mode_table);
											 $this->db->group_by('invoice_id');
											$this->db->where_in('payment_type', $payment_mode);
											$arr_records_payments=$this->db->get()->result();
											foreach($arr_records_payments as $data_payment)
											{
												$arr_invoice[]=$data_payment->invoice_id;
											}
											if(count($arr_invoice)>0)
											{
												$str_ids=" where invoice.invoice_id in ('".implode("','",$arr_invoice)."')";
											}
											else
											{
												$str_ids=" where invoice.invoice_id in ('0')";
											}
											if(in_array('cash',$payment_mode))
											{
												
											}
											else
											{
												$str_ids_pur=" where purchase.purchase_id='0'";
											}
											
											
			}
				if($str_ids=='')
				{
					$str_ids=" where invoice.is_deleted=0 ";
				}
				else
				{
					$str_ids=$str_ids." and invoice.is_deleted=0";
				}
				
				if(count($str_where)>0)
				{
					$where=" where ".implode(" and ",$str_where);
				}
		  
		}
		if($str_ids=='')
			{
				$str_ids=" where invoice.is_deleted=0 ";
			}
		$transaction_data = $this->db->query("SELECT * FROM 
		(SELECT invoice.invoice_number as invoice_number, invoice.invoice_id as invoice_id, invoice.invoice_date as transaction_date, invoice.invoice_type as invoice_type, invoice.total_amount as total_amount, invoice.amount_received as amount_received, invoice.surcharge_on_vat as surcharge_on_vat, invoice.amount_refunded as amount_refunded, invoice.adjustment as adjustment, invoice.createdOn as created_date FROM invoice_showroom_$office_id as invoice  $str_ids
		UNION ALL 
		SELECT purchase.purchase_number	as invoice_number, purchase.purchase_id as invoice_id, purchase.purchase_date as transaction_date, purchase.purchase_type as invoice_type, 0-CAST(purchase.total_amount AS DECIMAL) as total_amount, 0-CAST(purchase.amount_paid AS DECIMAL) as amount_received, 0.00 as surcharge_on_vat, 0.00 as amount_refunded, purchase.adjustment_amount  as adjustment, purchase.createdOn as created_date FROM purchase_showroom_$office_id as purchase $str_ids_pur ) as union_table $where order by created_date desc")->result();
		

$data['get_all_record']=$transaction_data;
$data['query']=$this->db->last_query();
$from2 = date('d/m/Y',strtotime($from));
$to2 = date('d/m/Y',strtotime($to));
$data['fromDate'] = $from2;
$data['toDate'] = $to2;
		return $data;
		
		
	}
	
	
	public function _get_transaction_report_data($office_operation_type=null,$office_id=null)
	{
		//$transaction_data=$this->db->get_where('invoice_showroom_'.$office_id)->result();
		$str_ids='';
		$where = "where created_date >='".date('Y-m-d')." 00:00:00' and created_date <='".date('Y-m-d')." 23:59:59' ";
		if($this->input->post('submit'))
		{
			
			$from=$this->input->post('access_right_from');
			$to=$this->input->post('access_right_to');
		
			$from2 = explode('/',$from);
			$to2 = explode('/',$to);
			$fromDate = $from2[2].'-'.$from2[1].'-'.$from2[0];
			$toDate = $to2[2].'-'.$to2[1].'-'.$to2[0];
			$str_where=array();
			$str_ids_pur='';
		
			if(!empty($fromDate)){
			$from="$fromDate 00:00:00";
			
			 $str_where[]=" created_date >='".$from."'";
		    }if(!empty($toDate)){
			$to="$toDate 23:59:59";
			$str_where[]=" created_date <='".$to."'";
		     }
			$product_id="";
			$transaction_name="";
			if($this->input->post('type_transaction'))
			{
			 $type_transaction=$this->input->post('type_transaction');
			 $data['type_transaction']=$type_transaction;
			 if($type_transaction=='sales')
			 {
				  $str_where[]=" (invoice_type!='purchase' and invoice_type!='')";
			 }
			 elseif($type_transaction=='purchase')
			 {
				  $str_where[]=" (invoice_type='purchase' or invoice_type='')";
			 }
			
			}
			if($this->input->post('payment_mode'))
			{
			 $payment_mode=$this->input->post('payment_mode');
			 $data['payment_mode']=$payment_mode;
			
			 $payment_mode_table='invoice_showroom_payment_mode_'.$office_id;
											$this->db->select('invoice_id')->from($payment_mode_table);
											 $this->db->group_by('invoice_id');
											$this->db->where_in('payment_type', $payment_mode);
											$arr_records_payments=$this->db->get()->result();
											foreach($arr_records_payments as $data_payment)
											{
												$arr_invoice[]=$data_payment->invoice_id;
											}
											if(count($arr_invoice)>0)
											{
												$str_ids=" where invoice.invoice_id in ('".implode("','",$arr_invoice)."')";
											}
											else
											{
												$str_ids=" where invoice.invoice_id in ('0')";
											}
											if(in_array('cash',$payment_mode))
											{
												
											}
											else
											{
												$str_ids_pur=" where purchase.purchase_id='0'";
											}
											
			}
			if($str_ids=='')
			{
				$str_ids=" where invoice.is_deleted=0 ";
			}
			else
			{
				$str_ids=$str_ids." and invoice.is_deleted=0";
			}
			
				if(count($str_where)>0)
				{
					$where=" where ".implode(" and ",$str_where);
				}
				
		  
		}
		
		if($str_ids=='')
			{
				$str_ids=" where invoice.is_deleted=0 ";
			}
		$transaction_data = $this->db->query("SELECT * FROM 
		(SELECT invoice.invoice_number as invoice_number, invoice.invoice_id as invoice_id, date_format(str_to_date(invoice.invoice_date, '%d/%m/%Y %H:%i:%s'), '%d/%m/%Y') as transaction_date, invoice.invoice_date as invoice_date, invoice.invoice_type as invoice_type, invoice.total_amount as total_amount, invoice.amount_received as amount_received, invoice.surcharge_on_vat as surcharge_on_vat, invoice.amount_refunded as amount_refunded, invoice.adjustment as adjustment, invoice.createdOn as created_date FROM invoice_showroom_$office_id as invoice  $str_ids
		UNION ALL 
		SELECT purchase.purchase_number	as invoice_number, purchase.purchase_id as invoice_id, date_format(str_to_date(purchase.purchase_date, '%d/%m/%Y %H:%i:%s'), '%d/%m/%Y') as transaction_date, purchase.purchase_date as invoice_date, purchase.purchase_type as invoice_type, 0-CAST(purchase.total_amount AS DECIMAL) as total_amount, 0-CAST(purchase.amount_paid AS DECIMAL) as amount_received, 0.00 as surcharge_on_vat, 0.00 as amount_refunded, purchase.adjustment_amount  as adjustment, purchase.createdOn as created_date FROM purchase_showroom_$office_id as purchase $str_ids_pur ) as union_table $where order by created_date desc")->result();
		

$data['get_all_record']=$transaction_data;
$data['query']=$this->db->last_query();
		return $data;
		
		
	}

	
	public function _get_schedule_report($fromDate,$toDate,$office_id,$type)
	{
		$date_where = array('createdOn >='=>$fromDate,'createdOn <='=>$toDate);
		$his_date_where = array('his.createdOn >='=>$fromDate,'his.createdOn <='=>$toDate);
		$in_date_where = array('inv.createdOn >='=>$fromDate,'inv.createdOn <='=>$toDate);
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		if($type == "all")
		{
			if($office_id == ''){
				//$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master')->where('office_operation_type','showroom')->get()->result();
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master')->where('office_id >','1')->get()->result();
			}
			else{
				$officeDatas[] = (object)array('office_id'=>$office_id,'office_operation_type'=>getOfficeOperationType($office_id));
			}
		}
		else if($type == "mmtc")
		{
			if($office_id == ''){
				//$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->join('regional_store_master as reg','om.regional_store_id=reg.regional_store_id')->where(array('om.office_operation_type'=>'showroom','reg.regional_store_type'=>'mmtc'))->get()->result(); 
				
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->join('regional_store_master as reg','om.regional_store_id=reg.regional_store_id')->where(array('om.office_id >'=>'1','reg.regional_store_type'=>'mmtc'))->get()->result();
			}
			else{
				$officeDatas[] = (object)array('office_id'=>$office_id,'office_operation_type'=>getOfficeOperationType($office_id));
			}
		}
		else if($type == "others")
		{
			if($office_id == ''){
				// $officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->join('regional_store_master as reg','om.regional_store_id=reg.regional_store_id')->where(array('om.office_operation_type'=>'showroom','reg.regional_store_type'=>'others'))->get()->result();
				$user_id=$this->session->userdata('user_id');
			$role_permission_id=$this->session->userdata('role_permission_id');
			$office_id=$this->session->userdata('office_id');
			$user_role_data=$this->db->get_where('user_role_permission_master',array('user_id'=>$user_id,'role_permission_id'=>$role_permission_id,'office_id'=>$office_id))->row();
			$str_ext=array();
			if(!empty($user_role_data) && $user_role_data->regional_store_id>0)
			{
				$str_ext=array('reg.regional_store_id'=>$user_role_data->regional_store_id);
			}
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->join('regional_store_master as reg','om.regional_store_id=reg.regional_store_id')->where(array('om.office_id >'=>'1','reg.regional_store_type'=>'others'))->where($str_ext)->get()->result();
				
			}
			else{
				$officeDatas[] = (object)array('office_id'=>$office_id,'office_operation_type'=>getOfficeOperationType($office_id));
			}
		}
		
		// echo '<pre>';
		// //echo dirname($_SERVER['SCRIPT_NAME']);
		// print_r($date_where);// die;
		// echo '</pre>';
		$openingData = array();
		
		if(!empty($officeDatas))
		{
			foreach($productData as $key=>$product){
				$openingStockValue = 0;
				$closingStockValue = 0;
				$stockInValue = 0;
				$salesValue = 0;
				$buybackValue = 0;
				$stockOutValue = 0;
				$inTransitStockValue = 0;
				$serialCount = 0;
				
				foreach($officeDatas as $office)
				{
					$deleted_letter=0;
					$deleted_created_preious=0;
					$created_prev_deleted_current=0;
					$table_name = "inventory_office_history_".$office->office_id;
					if($office->office_operation_type == "showroom"){
						$invoice_table_name = "invoice_showroom_".$office->office_id;
						$invoice_serial_table_name = "invoice_showroom_product_serial_number_".$office->office_id;
						$invoice_product_table_name = "invoice_showroom_product_".$office->office_id;
					}
					$transfer_table = "inventory_".$office->office_operation_type."_stock_transfer_".$office->office_id;
					$transfer_product_table = "inventory_".$office->office_operation_type."_stock_transfer_product_".$office->office_id;
					$transfer_product_sr_table = "inventory_".$office->office_operation_type."_stock_transfer_product_serial_number_".$office->office_id;
						
					
					$openDatas = $this->db->select('*')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->get()->result();
					
					// opening Stock Value 
					$openStockData = $this->db->select('current_stock as currentStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn >='=>$fromDate))->order_by('history_id','ASC')->limit('1')->get()->row();
					if(empty($openStockData)){
						$openStockData = $this->db->select('net_stock as currentStock')->from($table_name)->where(array('product_id'=>$product->product_id))->order_by('history_id','DESC')->limit('1')->get()->row();
					}
					$openingStockValue = $openingStockValue + $openStockData->currentStock ;
					
					// closing Stock Value
					$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn <=' => $toDate))->order_by('history_id','DESC')->limit('1')->get()->row();
					$netStock = $closeStockData->netStock;
					// if(empty($closeStockData)){
						// $closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id))->order_by('history_id','DESC')->limit('1')->get()->row();
						// $netStock = 0;
					// }
					$closingStockValue = $closingStockValue + $netStock ;
					
					$same_day_delete=0;
					if($office->office_operation_type == "showroom")
					{
					/*$saleData_same_day_delete = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id,'is_deleted'=>'1'))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
					if($saleData_same_day_delete->sales_data!=NULL)
					{
						$same_day_delete=$saleData_same_day_delete->sales_data;
					}*/
					$alldeleted_serials_later=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' and ins.is_deleted='1' and his.createdOn >'".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					
					if($alldeleted_serials_later->add_in_sale!='')
					{
						$deleted_letter=$alldeleted_serials_later->add_in_sale;
					}
					
				$alldeleted_serials_previous=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn <'".$fromDate."' and ins.is_deleted='1' and his.createdOn >'".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					
					if($alldeleted_serials_previous->add_in_sale!='')
					{
						$deleted_created_preious=$alldeleted_serials_previous->add_in_sale;
					}
					
					
					$alldeleted_serials_previous_del_cur=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn <'".$fromDate."' and ins.is_deleted='1' and his.createdOn >='".$fromDate."' and  his.createdOn <='".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					
					if($alldeleted_serials_previous_del_cur->add_in_sale!='')
					{
						$created_prev_deleted_current=$alldeleted_serials_previous_del_cur->add_in_sale;
					}
					
					}
					
					// stock receipt Stock Value
					//if($office_id!='' || ($type == "others" && $office_id==''))
					//{
						
						$stockInData = $this->db->select('sum(received_stock) as stock_in_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					$stockInValue = ($stockInData->stock_in_data != '') ?  $stockInValue + $stockInData->stock_in_data : $stockInValue + 0;
				
					//}
					/*else
					{
						$stockInData = $this->db->select('sum(received_stock) as stock_in_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('vendor'))->get()->row();
					$stockInValue = ($stockInData->stock_in_data != '') ? $stockInValue + $stockInData->stock_in_data : $stockInValue + 0;
					}*/
					
					
					
					
					
					if($office->office_operation_type == "showroom"){
					
//$saleData = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
					$saleData=$this->db->query("select count(invoice_product_serial_number_id) as sales_data from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id where product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' and ins.is_deleted='0' group by product_id" )->row();
					
					$saleDataValue = $saleData->sales_data;

					
					// deleted sales Stock Value
					/*$deletedSaleData = $this->db->select('sum(his.received_stock) as deleted_sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($his_date_where)->where_in('his.type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
					$deletedSaleDataValue = $deletedSaleData->deleted_sales_data;*/
					$deletedSaleDataValue = 0;
					}
					else if($office->office_operation_type == "store"){
					// sales Stock Value
					 $saleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
					$saleDataValue = $saleData->sales_data;
					
					
					// deleted sales Stock Value
					$deletedSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedSaleDataValue = $deletedSaleData->deleted_sales_data;
					}
					
					
					$salesValue = $salesValue + $saleDataValue - $deletedSaleDataValue;
					
					// buyback Stock Value
					$buyBackData = $this->db->select('sum(received_stock) as buyback_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('Buy Back'))->get()->row();
					$buybackValue = $buybackValue + $buyBackData->buyback_data;				
					//if($office_id!='' || ($type == "others" && $office_id==''))
					//{
					// stock transfer Stock Value
					$stockOutData = $this->db->select('sum(transfer_stock) as stock_out_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('store','showroom'))->get()->row();
					$stockOutValue = ($stockOutData->stock_out_data != '') ? $stockOutValue + $stockOutData->stock_out_data : $stockOutValue + 0;
					//}
					//else
					//{
					//	$stockOutValue = 0;
					//}
					/*$stockTransferDatas = $this->db->distinct()->select('transaction_number')->from($table_name)->where(array('product_id'=>$product->product_id,'transfer_stock !='=>'0','transfer_stock !='=>''))->where_in('type_value',array('store','showroom'))->where(array('createdOn <='=>$toDate))->get()->result();
					
					if(!empty($stockTransferDatas)){
						foreach($stockTransferDatas as $stockTransferData)
						{
							$transfer_number = $stockTransferData->transaction_number;
							
							$transferData = $this->db->select('*')->from($transfer_table)->where(array('stock_transfer_number'=>$transfer_number,'access_level_status'=>'1'))->get()->row();
							
							if(!empty($transferData))
							{
								$transferProductData = $this->db->select('*')->from($transfer_product_table)->where(array('stock_transfer_id'=>$transferData->stock_transfer_id,'product_id'=>$product->product_id))->get()->result();
								
								
								foreach($transferProductData as $transferProduct)
								{
									$serialData = $this->db->select('*')->from($transfer_product_sr_table)->where(array('stock_transfer_product_id'=>$transferProduct->stock_transfer_product_id,'stock_transfer_product_serial_number_status'=>2))->get()->num_rows();
									
									$serialCount = $serialCount + $serialData;
								}
							}
							
						}
					}
					$inTransitStockValue = $serialCount;
					
					*/
					$tableNameSTOCKRECEIPT='inventory_'.$office->office_operation_type.'_stock_transfer_'.$office->office_id;		
					$this->db->select('trans_table.stock_transfer_number,trans_table.stock_transfer_date,trans_table.stock_transfer_narration,trans_table.stock_transfer_status,trans_table.stock_transfer_id, trans_table.stock_transfer_to_office_id,trans_table.authorized_date,ofc_mstr.office_name,ofc_mstr.office_address,ofc_mstr.city_id,trans_table.access_level_status,ofc_mstr.office_operation_type,
		ofc_mstr.district_id,ofc_mstr.state_id,trans_table.added_by')->from($tableNameSTOCKRECEIPT.' as trans_table');
		$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=trans_table.stock_transfer_to_office_id');
		$this->db->where(array('trans_table.authorized_date >=' => $fromDate,'trans_table.authorized_date <=' => $toDate));
		$stock_transfer_st_sh=$this->db->get()->result();
					$transfer_product_table='inventory_'.$office->office_operation_type.'_stock_transfer_product_'.$office->office_id;	
				
					foreach($stock_transfer_st_sh as $Stock_receipt_details){
										
								$recipet_table='inventory_'.$Stock_receipt_details->office_operation_type.'_stock_receipt_'.$Stock_receipt_details->stock_transfer_to_office_id;
							
								$receipt_data=$this->db->query("select * from ".$recipet_table." where stock_transfer_number='".$Stock_receipt_details->stock_transfer_number."' and authorized_date > '".$toDate."' order by stock_receipt_id desc limit 1")->row();
								if(!empty($receipt_data))
								{
										$productList=$this->db->get_where($transfer_product_table,array('stock_transfer_id'=>$Stock_receipt_details->stock_transfer_id,'product_id'=>$product->product_id))->result();
								foreach($productList as $product_transfer)
											{
												
												$inTransitStockValue=$inTransitStockValue+$product_transfer->stock_transfer_product_quantity;
											}
								}
								else
								{
									$receipt_data=$this->db->query("select * from ".$recipet_table." where stock_transfer_number='".$Stock_receipt_details->stock_transfer_number."' order by stock_receipt_id desc limit 1")->row();
									
										if(empty($receipt_data) || (!empty($receipt_data) && $receipt_data->authorized_date==''))
										{
											$productList=$this->db->get_where($transfer_product_table,array('stock_transfer_id'=>$Stock_receipt_details->stock_transfer_id,'product_id'=>$product->product_id))->result();
											foreach($productList as $product_transfer)
											{
												
												$inTransitStockValue=$inTransitStockValue+$product_transfer->stock_transfer_product_quantity;
											}
										}	
								}

								
					}
					
					
					
					$openingStockValue=$openingStockValue+$deleted_created_preious+$created_prev_deleted_current;
					$closingStockValue=$closingStockValue+$deleted_letter+$deleted_created_preious;
					$openingData[$product->product_id] = array('product_name'=>$product->product_name,'opening_stock'=>$openingStockValue,'closing_stock'=>$closingStockValue,'stock_in'=>$stockInValue,'sales_stock'=>$salesValue,'buyback_stock'=>$buybackValue,'stock_out'=>$stockOutValue,'in_transit_stock'=>$inTransitStockValue,'sum_show_open'=>$sum_show_open,'sum_show_close'=>$sum_show_close,'sum_show_stock_in'=>$sum_show_stock_in,'sum_show_stock_out'=>$sum_show_stock_out,'sum_show_buyback_stock'=>$sum_show_buyback_stock,'sum_show_sales'=>$sum_show_sales,'sum_show_in_transit'=>$sum_show_in_transit,'sum_store_open'=>$sum_store_open,'sum_store_close'=>$sum_store_close,'sum_store_stock_in'=>$sum_store_stock_in,'sum_store_stock_out'=>$sum_store_stock_out,'sum_store_buyback_stock'=>$sum_store_buyback_stock,'sum_store_sales'=>$sum_store_sales,'sum_store_in_transit'=>$sum_store_in_transit);
					
					// if(!empty($openDatas)){
						// foreach($openDatas as $openData){
							// $openingData[] = array('table_name'=>$table_name,'history_id'=>$openData->history_id,'product_id'=>$openData->product_id,'current_stock'=>$openData->current_stock,'received_stock'=>$openData->received_stock,'transfer_stock' =>$openData->transfer_stock,'net_stock'=>$openData->net_stock,'received_from'=>$openData->received_from,'transfer_to'=>$transfer_to,'type_value'=>$openData->type_value,'transaction_number'=>$openData->transaction_number,'creator_id'=>$openData->creator_id,'createdOn'=>$openData->createdOn);
						// }
					// }
				}
			}
		}
		
		// ksort($openingData);
		
		// echo '<pre>';
		// print_r($openingData);
		// echo "<br/>openStock  ".array_sum($openingData['opening_stock']).'<br/>';
		// echo '</pre>';
	//	die;
		return $openingData;
	}
	
	public function _get_sales_report($fromDate,$toDate,$office_ids,$regional_ids)
	{
		$date_where = array('createdOn >='=>$fromDate,'createdOn <='=>$toDate);
		$his_date_where = array('his.createdOn >='=>$fromDate,'his.createdOn <='=>$toDate);
		$in_date_where = array('inv.createdOn >='=>$fromDate,'inv.createdOn <='=>$toDate);
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		
		if(empty($office_ids)){
			if(empty($regional_ids))
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where(array('om.office_operation_type'=>'showroom'))->get()->result();
			}
			else
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where(array('om.office_operation_type'=>'showroom'))->where_in('regional_store_id',$regional_ids)->get()->result();
			}
		}
		else{
			$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where(array('om.office_operation_type'=>'showroom'))->where_in('office_id',$office_ids)->get()->result();
		}
		
		$productSaleData = array();
		$totalCost = 0;
		foreach($officeDatas as $ofkey=>$office)
		{
			$table_name = "inventory_office_history_".$office->office_id;
			$invoice_table_name = "invoice_showroom_".$office->office_id;
			$invoice_serial_table_name = "invoice_showroom_product_serial_number_".$office->office_id;
			$invoice_product_table_name = "invoice_showroom_product_".$office->office_id;
			$salesValue = 0;
			$TotalSalesValue = 0;
			$grossWeight = 0;
			$openingStockValue = 0;
			$closingStockValue = 0;
			
			
			$invoiceData = $this->db->select("sum(amount_received) as totalAmount,sum(amount_refunded) as refundAmount,sum(adjustment) as adjustAmount")->from($invoice_table_name.' as inv')->where(array('transaction'=>'completed','is_deleted !='=>'1'))->where($date_where)->get()->row();
			
			$totalCost = $totalCost + $invoiceData->totalAmount - $invoiceData->refundAmount; // - $invoiceData->adjustAmount;
			$productSaleData[$office->office_id]['turnover']=$invoiceData->totalAmount - $invoiceData->refundAmount;
			
			foreach($productData as $pkey=>$product)
			{
				$salesValue = 0;
				$totalWeight = 0;
				$openingStockValue = 0;
				$closingStockValue = 0;
				$deleted_letter=0;
				$deleted_created_preious=0;
				$created_prev_deleted_current=0;
				
				// opening Stock Value 
				$openStockData = $this->db->select('current_stock as currentStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn >='=>$fromDate))->order_by('history_id','ASC')->limit('1')->get()->row();
				if(empty($openStockData)){
					$openStockData = $this->db->select('net_stock as currentStock')->from($table_name)->where(array('product_id'=>$product->product_id))->order_by('history_id','DESC')->limit('1')->get()->row();
				}
				$openingStockValue = $openingStockValue + $openStockData->currentStock ;

				// closing Stock Value
				$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn <=' => $toDate))->order_by('history_id','DESC')->limit('1')->get()->row();
				$netStock = $closeStockData->netStock;
				// if(empty($closeStockData)){
					// $closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id))->order_by('history_id','DESC')->limit('1')->get()->row();
					// $netStock = 0;
				// }
				$closingStockValue = $closingStockValue + $netStock ;
				
				
				$same_day_delete=0;
					
					$saleData_same_day_delete = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id,'is_deleted'=>'1'))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
					if($saleData_same_day_delete->sales_data!=NULL)
					{
						$same_day_delete=$saleData_same_day_delete->sales_data;
					}
					
					$alldeleted_serials_later=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' and ins.is_deleted='1' and his.createdOn >'".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					
					if($alldeleted_serials_later->add_in_sale!='')
					{
						$deleted_letter=$alldeleted_serials_later->add_in_sale;
					}
					
				$alldeleted_serials_previous=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn <'".$fromDate."' and ins.is_deleted='1' and his.createdOn >'".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					if($alldeleted_serials_previous->add_in_sale!='')
					{
						$deleted_created_preious=$alldeleted_serials_previous->add_in_sale;
					}
					
					$alldeleted_serials_previous_del_cur=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn <'".$fromDate."' and ins.is_deleted='1' and his.createdOn >='".$fromDate."' and  his.createdOn <='".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					if($alldeleted_serials_previous_del_cur->add_in_sale!='')
					{
						$created_prev_deleted_current=$alldeleted_serials_previous_del_cur->add_in_sale;
					}
				
				// sales Stock Value
				//$saleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
				//$saleDataValue = $saleData->sales_data;
				/*$saleData = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
				$saleDataValue = $saleData->sales_data;*/
				$saleData=$this->db->query("select count(invoice_product_serial_number_id) as sales_data from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id where product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' and ins.is_deleted='0' group by product_id" )->row();
					
					$saleDataValue = $saleData->sales_data;
				
				// deleted sales Stock Value
				//$deletedSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
				//$deletedSaleDataValue = $deletedSaleData->deleted_sales_data;
				/*$deletedSaleData = $this->db->select('sum(his.received_stock) as deleted_sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($his_date_where)->where_in('his.type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
				
				$deletedSaleDataValue = $deletedSaleData->deleted_sales_data;
				//- $deletedSaleDataValue*/
				$salesValue = $salesValue + $saleDataValue ;
				$totalWeight = $salesValue * $product->product_weight;
				$grossWeight = $grossWeight + $totalWeight;
				
				$TotalSalesValue = $TotalSalesValue + $salesValue;
				
				$openingStockValue=$openingStockValue+$deleted_created_preious+$created_prev_deleted_current;		
				$closingStockValue=$closingStockValue+$deleted_letter+$deleted_created_preious;
				
				$productSaleData[$office->office_id][$product->product_id] = array('opening_stock'=>$openingStockValue,'sales_stock'=>$salesValue,'totalWeight'=>$totalWeight,'grossWeight'=>$grossWeight,'closing_stock'=>$closingStockValue,'officeId'=>$office->office_id,'TotalSalesValue'=>$TotalSalesValue);
			}
		}
		
		function sort5gram($a, $b) {
			  $a = $a[3]['TotalSalesValue'];
			  $b = $b[3]['TotalSalesValue'];
			  if ($a == $b)
				return 0;
			  return ($a > $b) ? -1 : 1;
			}

		usort($productSaleData, "sort5gram");
		$productSaleData['totalCost'] = $totalCost;
		
		return $productSaleData;
	}
	
	public function _get_payment_mode_report($fromDate,$toDate,$office_ids,$regional_ids)
	{
		$date_where = array('i.createdOn >='=>$fromDate,'i.createdOn <='=>$toDate,'i.is_deleted !='=>'1','i.transaction'=>'completed');
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		
		if(empty($office_ids)){
			$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where(array('om.office_operation_type'=>'showroom'))->where_in('regional_store_id',$regional_ids)->get()->result();
		}
		else{
			$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where(array('om.office_operation_type'=>'showroom'))->where_in('office_id',$office_ids)->get()->result();
		}
		$paymentSaleData = array();
		
		foreach($officeDatas as $ofkey=>$office)
		{
			$in_table_name = "invoice_showroom_".$office->office_id;
			$in_mode_table_name = "invoice_showroom_payment_mode_".$office->office_id;
		
			$totalValue = 0;
			$cashValue = 0;
			$cardValue = 0;
			$chequeValue = 0;
			$otherValue = 0;
			//	SELECT sum(payment_amount), i15.invoice_id,p15.payment_type from invoice_showroom_15 as i15 join invoice_showroom_payment_mode_15 as p15 on i15.invoice_id=p15.invoice_id group by payment_type, i15.invoice_id
				
				$paymentData = $this->db->select(' sum(payment_amount) as payment, i.invoice_id,p.payment_type as payment_type')->from($in_table_name.' as i')->join($in_mode_table_name.' as p','i.invoice_id = p.invoice_id')->where($date_where)->group_by('payment_type, i.invoice_id')->get()->result();
				// echo $this->db->last_query();
				// print_r($paymentData);
				$invoice_id=array();
				foreach($paymentData as $mypayment){
					if($mypayment->payment_type == 'cash'){
						$cashValue = $cashValue + $mypayment->payment;
					}
					else if($mypayment->payment_type == 'debit card' || $mypayment->payment_type == 'credit card'){
						$cardValue = $cardValue + $mypayment->payment;
					}
					else if($mypayment->payment_type == 'cheque'){
						$chequeValue = $chequeValue + $mypayment->payment;
					}
					else{
						$otherValue = $otherValue + $mypayment->payment;
					}
					$invoice_id[]=$mypayment->invoice_id;
				}
				$refunded=0;
				if(count($invoice_id)>0)
				{
					$invoice_Data = $this->db->select('sum(amount_refunded) as total_refunded')->from($in_table_name)->where_in('invoice_id',$invoice_id)->where(array('transaction'=>'completed','is_deleted !='=>'1'))->get()->row();
					
					if(!empty($invoice_Data))
					{
						$refunded=$invoice_Data->total_refunded;
					}
					
				}
				$totalValue = $otherValue + $cashValue + $cardValue + $chequeValue - $refunded;
				
				$paymentSaleData[$office->office_id] = array('cashValue'=>$cashValue,'cardValue'=>$cardValue,'chequeValue'=>$chequeValue,'otherValue'=>$otherValue,'totalValue'=>$totalValue,'officeId'=>$office->office_id);
		}
		function sortTotalSale($a, $b) {
			  $a = $a['totalValue'];
			  $b = $b['totalValue'];
			  if ($a == $b)
				return 0;
			  return ($a > $b) ? -1 : 1;
			}

		usort($paymentSaleData, "sortTotalSale");
		
		return $paymentSaleData;
	}
	
	
	public function _get_daily_statement_report($reportDate,$regional_ids)
	{
		$date_where_one = " createdOn LIKE '".$reportDate."%'";
		// $date_where_all = array('createdOn >='=>$reportDate." 00:00:00",'createdOn <='=>$reportDate." 23:59:59");
		$date_where_all = array('createdOn <='=>$reportDate." 23:59:59");
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		
		$officeDatas = $this->db->select('office_id,office_operation_type,regional_store_id')->from('office_master as om')->where(array('office_id >'=>'1'))->where_in('regional_store_id',$regional_ids)->get()->result();

		$openingData = array();
		$productSaleData = array();
		foreach($regional_ids as $region)
		{
			foreach($productData as $key=>$product){
				
				$closingStockValue = 0;
				$salesValueOne = 0;
				$salesValueAll = 0;
				$mintValueOne = 0;
				$mintValueAll = 0;
				
				foreach($officeDatas as $office)
				{
					if($office->regional_store_id == $region){
						$table_name = "inventory_office_history_".$office->office_id;
						
						$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->order_by('history_id','DESC')->limit('1')->get()->row();
						
						$closingStockValue = $closingStockValue + $closeStockData->netStock;
						
						// sales Stock Value one Date
						$oneSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
						$oneSaleDataValue = $oneSaleData->sales_data;
						
						// deleted sales Stock Value
						$deletedOneSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedOneSaleDataValue = $deletedOneSaleData->deleted_sales_data;
						
						$salesValueOne = $salesValueOne + $oneSaleDataValue - $deletedOneSaleDataValue;
						
						// sales Stock Value upto Date
						$allSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
						$allSaleDataValue = $allSaleData->sales_data;
						
						// deleted sales Stock Value
						$deletedAllSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedAllSaleDataValue = $deletedAllSaleData->deleted_sales_data;
						
						$salesValueAll = $salesValueAll + $allSaleDataValue - $deletedAllSaleDataValue;
						
						// vendor Stock Value one Date
						$oneMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
						$mintValueOne = $mintValueOne + $oneMintData->mint_data;
						
						//echo $this->db->last_query();
						
						// vendor Stock Value upto Date
						$allMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
						$mintValueAll = $mintValueAll + $allMintData->mint_data;
						
					}
				}
				$openingData[$region][$product->product_id] = array('closing_stock'=>$closingStockValue,'sales_stock_one'=>$salesValueOne,'sales_stock_all'=>$salesValueAll,'mint_stock_one'=>$mintValueOne,'mint_stock_all'=>$mintValueAll);
			}
		}

		return $openingData;

	}

	
	public function _get_sold_inventory($fromDate,$toDate,$office_id,$product_ids)
	{
		
		$in_table_name = "invoice_showroom_".$office_id;
		$in_p_table_name = "invoice_showroom_product_".$office_id;
		$in_sr_table_name = "invoice_showroom_product_serial_number_".$office_id;
		
		$date_where = array('invp.createdOn >='=>$fromDate,'invp.createdOn <='=>$toDate);
		
		$invProductData = $this->db->select('*')->from($in_p_table_name.' as invp')->join($in_table_name.' as inv','inv.invoice_id=invp.invoice_id')->where($date_where)->where(array('inv.is_deleted'=>'0','inv.transaction'=>'completed'))->where_in('product_id',$product_ids)->get()->result();
		// echo '<pre>';
		// print_r($invProductData);
		// echo '</pre>';
		// die;
		$invoiceIds = array();
		$totalWeight = 0;
		$totalQty = 0;
		foreach($invProductData as $invProduct)
		{
			$invoiceIds[] = $invProduct->invoice_id;
			$totalWeight = $totalWeight + ($invProduct->weight * $invProduct->qunatity);
			$totalQty = $totalQty + $invProduct->qunatity;
		}
		
		$invoiceIds = array_unique($invoiceIds);
		$invoiceData = array();
		
		foreach($invoiceIds as $invoiceId)
		{
			$invoice_data = $this->db->select('*')->from($in_table_name)->where(array('invoice_id'=>$invoiceId,'transaction'=>'completed','is_deleted'=>'0'))->get()->row();
			$invoiceData[] = array('invoice_number'=>$invoice_data->invoice_number,'invoice_date'=>$invoice_data->invoice_date,'invoice_id'=>$invoiceId);
		}
		
		$data['totalWeight'] = $totalWeight;
		$data['totalQty'] = $totalQty;
		$data['invoiceData'] = $invoiceData;

		return $data;
	}
	
	public function _get_current_inventory($office_id,$product_ids)
	{
		
		$cur_table_name = "product_current_stock_".$office_id;
		$cur_p_table_name = "product_current_stock_serial_number_".$office_id;
		
		$currentStockDatas = $this->db->select('*')->from($cur_table_name)->where_in('product_id',$product_ids)->get()->result();
		$currentStockIds = array();
		$result = array();
		$totalWeight = 0;
		$totalQty = 0;
		
		foreach($currentStockDatas as $currentStock)
		{
			$productData = $this->db->get_where('product_master',array('product_id'=>$currentStock->product_id))->row();
			$currentStockIds[] = $currentStock->current_stock_id;
			$totalWeight = $totalWeight + ($productData->product_weight * $currentStock->product_current_stock);
			$totalQty = $totalQty + $currentStock->product_current_stock;
		}
		
		$data['totalWeight'] = $totalWeight;
		$data['totalQty'] = $totalQty;
	//	$data['currentStockIds'] = $currentStockIds;
		
		foreach($product_ids as $product_id)
		{
			$productData = $this->db->get_where('product_master',array('product_id'=>$product_id))->row();
			$currentStockData = $this->db->select('*')->from($cur_table_name)->where('product_id',$product_id)->get()->row();
			$serialArray = $this->db->select('product_serial_number')->from($cur_p_table_name)->where(array('product_id'=>$product_id,'current_stock_status'=>'0'))->get()->result();
			
			$result[$product_id] = array('productName'=>$productData->product_name,'productTotalWeight'=>($productData->product_weight * $currentStockData->product_current_stock),'productTotalQuantity'=>$currentStockData->product_current_stock,'serialArray'=>$serialArray,'product_id'=>$product_id);
		}
		
		$data['result'] = $result;

		return $data;
	}
	
	public function searchCurrentSerialNumber($office_id,$serialNumber,$product_ids)
	{
		$result = array();
		$cur_table_name = "product_current_stock_".$office_id;
		$cur_p_table_name = "product_current_stock_serial_number_".$office_id;
		$serialData = $this->db->select('product_id')->from($cur_p_table_name)->like(array('product_serial_number'=>$serialNumber))->where(array('current_stock_status'=>'0'))->get()->result();
		foreach($serialData as $sd )
		{
			$product_id = $sd->product_id;
			$productData = $this->db->get_where('product_master',array('product_id'=>$product_id))->row();
			$currentStockData = $this->db->select('*')->from($cur_table_name)->where('product_id',$product_id)->get()->row();
			$serialArray = $this->db->select('product_serial_number')->from($cur_p_table_name)->like(array('product_serial_number'=>$serialNumber))->where(array('product_id'=>$product_id,'current_stock_status'=>'0'))->get()->result();
			
			$result[$product_id] = array('productName'=>$productData->product_name,'productTotalWeight'=>($productData->product_weight * $currentStockData->product_current_stock),'productTotalQuantity'=>$currentStockData->product_current_stock,'serialArray'=>$serialArray,'product_id'=>$product_id,'product_weight'=>$productData->product_weight);
		}

		$data['get_all_record'] = $result;

		$view = $this->load->view('includes/_current_inventory_report_table',$data);

		return $view;
	}
		
	public function _get_sold_and_inventory_report($reportDate,$fromDate,$toDate,$office_ids,$regional_ids)
	{
		$date_where = array('createdOn >='=>$fromDate,'createdOn <='=>$toDate);
		$his_date_where = array('his.createdOn >='=>$fromDate,'his.createdOn <='=>$toDate);
		$in_date_where = array('inv.createdOn >='=>$fromDate,'inv.createdOn <='=>$toDate);
		if(empty($office_ids)){
			if(empty($regional_ids))
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where("office_id !=",1)->get()->result();
			}
			else
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where("office_id !=",1)->where_in('regional_store_id',$regional_ids)->get()->result();
			}
		}
		else{
			$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where_in('office_id',$office_ids)->get()->result();
		}
		
		$ofcDatas = $officeDatas;
		$totalCost = 0;
		$date_where_one = " createdOn LIKE '".$reportDate."%'";
	//	echo $date_where_one;
		$in_date_where_one = " inv.createdOn LIKE '".$reportDate."%'";
		$report_Date = date( 'Y-m-d', strtotime( $reportDate2 . ' -1 day' ) );
		$date_where_close = array('createdOn >='=>$fromDate);
		// $date_where_all = array('createdOn >='=>$reportDate." 00:00:00",'createdOn <='=>$reportDate." 23:59:59");
		$date_where_all = $date_where;
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		
		$openingData = array();
		$productSaleData = array();
		
		// print_r($ofcDatas); // die;
		foreach($ofcDatas as $ofc)
		{
			$table_name = "inventory_office_history_".$ofc->office_id;
			if($ofc->office_operation_type == "showroom"){
				$invoice_table_name = "invoice_showroom_".$ofc->office_id;
			}
			//echo '<pre>'; print_r($ofc);
			foreach($productData as $key=>$product){
				$closingStockValue = 0;
				$closingStockValue_sum_cal = 0;
				$salesValueOne = 0;
				$salesValueAll = 0;
				$mintValueOne = 0;
				$mintValueAll = 0;
				
					// echo $table_name; 
					/*$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->order_by('history_id','DESC')->limit('1')->get();
					if($closeStockData->num_rows() > '0'){
						$closingStockValue = $closingStockValue + $closeStockData->row()->netStock;
					}
					else{
						$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id,'net_stock >= '=> '0','createdOn <= '=>$reportDate." 23:59:59"))->order_by('history_id','DESC')->limit('1')->get()->row();
						
						$closingStockValue = $closingStockValue + $closeStockData->netStock;
					}*/
					$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn <=' => $toDate))->order_by('history_id','DESC')->limit('1')->get()->row();
					$closingStockValue = $closingStockValue + $closeStockData->netStock;
				//	$closingStockValue = $closingStockValue + $closeStockData->row()->netStock;
				/*	$closeStockData = $this->db->select('current_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn >='=>$fromDate))->order_by('history_id','ASC')->limit('1')->get();
				//	echo $this->db->last_query();
					if($closeStockData->num_rows() > '0'){
						$closingStockValue = $closingStockValue + $closeStockData->row()->netStock;
					}
					else{
						//$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id,'net_stock >= '=> '0','createdOn <= '=>$reportDate." 23:59:59"))->order_by('history_id','DESC')->limit('1')->get()->row();
						$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id))->order_by('history_id','DESC')->limit('1')->get()->row();
						$closingStockValue = $closingStockValue + $closeStockData->netStock;
					}
					*/
					// sales Stock Value one Date
				/*	$oneSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
					$oneSaleDataValue = $oneSaleData->sales_data;
				//	echo $this->db->last_query().'<br/>';
					// deleted sales Stock Value
					$deletedOneSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
					$deletedOneSaleDataValue = $deletedOneSaleData->deleted_sales_data;
					
					$salesValueOne = $salesValueOne + $oneSaleDataValue - $deletedOneSaleDataValue;
					
					// sales Stock Value upto Date
					$allSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
					$allSaleDataValue = $allSaleData->sales_data;
					// echo $this->db->last_query().'<br/>';
					// deleted sales Stock Value
					$deletedAllSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
					$deletedAllSaleDataValue = $deletedAllSaleData->deleted_sales_data;
					*/
					$same_day_delete=0;
					if($ofc->office_operation_type == "showroom"){
					$invoice_serial_table_name = "invoice_showroom_product_serial_number_".$ofc->office_id;
						$invoice_product_table_name = "invoice_showroom_product_".$ofc->office_id;
						// sales Stock Value one Date
						$oneSaleData = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where_one)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
						$oneSaleDataValue = $oneSaleData->sales_data;
						
					//	echo $this->db->last_query().'<br/>';
						// deleted sales Stock Value
						$deletedOneSaleData = $this->db->select('sum(his.received_stock) as deleted_sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where_one)->where_in('his.type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedOneSaleDataValue = $deletedOneSaleData->deleted_sales_data;
						
						$salesValueOne = $salesValueOne + $oneSaleDataValue - $deletedOneSaleDataValue;
						
						// sales Stock Value upto Date
						/*$allSaleData = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
						$allSaleDataValue = $allSaleData->sales_data;
						// echo $this->db->last_query().'<br/>';
						// deleted sales Stock Value
						$deletedAllSaleData = $this->db->select('sum(his.received_stock) as deleted_sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where)->where_in('his.type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedAllSaleDataValue = $deletedAllSaleData->deleted_sales_data;*/
						
						
						
						$saleData_same_day_delete = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id,'is_deleted'=>'1'))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
						if($saleData_same_day_delete->sales_data!=NULL)
						{
							$same_day_delete=$saleData_same_day_delete->sales_data;
						}
							
						//echo $same_day_delete."<br>";
						
						/*$allSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
						$allSaleDataValue = $allSaleData->sales_data;
						
						// deleted sales Stock Value
						$deletedAllSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedAllSaleDataValue = $deletedAllSaleData->deleted_sales_data;*/
						
						
						$allSaleData=$this->db->query("select count(invoice_product_serial_number_id) as sales_data from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id where product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' group by product_id" )->row();
					
						$allSaleDataValue = $allSaleData->sales_data-$same_day_delete;
						//$closingStockValue = $closingStockValue + $same_day_delete;
						
					}
					else if($ofc->office_operation_type == "store"){
						// sales Stock Value one Date
						$oneSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
						$oneSaleDataValue = $oneSaleData->sales_data;
					//	echo $this->db->last_query().'<br/>';
						// deleted sales Stock Value
						$deletedOneSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedOneSaleDataValue = $deletedOneSaleData->deleted_sales_data;
						
						$salesValueOne = $salesValueOne + $oneSaleDataValue - $deletedOneSaleDataValue;
						
						// sales Stock Value upto Date
						$allSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
						$allSaleDataValue = $allSaleData->sales_data;
						// echo $this->db->last_query().'<br/>';
						// deleted sales Stock Value
						$deletedAllSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedAllSaleDataValue = $deletedAllSaleData->deleted_sales_data;
						
					}
					
					
					$salesValueAll = $salesValueAll + $allSaleDataValue - $deletedAllSaleDataValue;
					
					// vendor Stock Value one Date
					$oneMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					$mintValueOne = $mintValueOne + $oneMintData->mint_data;
					
				//	echo $this->db->last_query().'<br/>';
					//echo $this->db->last_query();
					
					
					
					// vendor Stock Value upto Date
					$allMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					
					// vendor Stock Value upto Date
					$trans_allMintData = $this->db->select('sum(transfer_stock) as trans_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					/* 
					if(getOfficeOperationType($ofc->office_id) == 'showroom')
					{
						$mintValueAll = $mintValueAll + $allMintData->mint_data - $trans_allMintData->trans_data;
					}
					if(getOfficeOperationType($ofc->office_id) == 'store')
					{
						$mintValueAll = $mintValueAll + $allMintData->mint_data;
					} */
					//print_r($allMintData);
					if(($allMintData->mint_data<=0 || $allMintData->mint_data==''))
						{
							//$allMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where(array('createdOn <'=>$fromDate))->where_in('type_value',array('vendor','store','showroom'))->group_by('product_id')->having('sum(received_stock) >0', NULL, FALSE)->order_by('createdOn','desc')->get()->row();
						//	$allMintData = $this->db->select('current_stock as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn >='=>$fromDate))->order_by('history_id','ASC')->limit('1')->get()->row();
							if(empty($allMintData)){
							//	$allMintData = $this->db->select('net_stock as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->order_by('history_id','DESC')->limit('1')->get()->row();
							}
							//$mintValueAll = $mintValueAll + $allMintData->mint_data;
							//$openingStockValue = $openingStockValue + $allMintData->mint_data ;
							//echo $this->db->last_query();
							//$trans_allMintData = $this->db->select('sum(transfer_stock) as trans_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where("createdOn LIKE '".$fromDate."%'")->where_in('type_value',array('vendor','store','showroom'))->get()->row();
							//$mintValueAll = $allMintData->mint_data - $trans_allMintData->trans_data;;
						//	$mintValueAll = $mintValueAll + $closingStockValue + $salesValueAll;
						}
						else if(($allMintData->mint_data - $trans_allMintData->trans_data)<0)
						{
						//	$mintValueAll = $mintValueAll + $closingStockValue + $salesValueAll;
						}
					//else
					//{
						//echo 'else'+$same_day_delete;
						$mintValueAll = $mintValueAll + $allMintData->mint_data - $trans_allMintData->trans_data;
					//}
					
					if($mintValueAll < 0){
						$mintValueAll = 0;
					}
					
					if($closingStockValue == '0' && $salesValueOne == '0' && $salesValueAll == '0' && $mintValueOne == '0' && $mintValueAll =='0')
					{
						continue;
						
					}
					else{
						
						
						$openingData[$ofc->office_id][$product->product_id] = array('closing_stock'=>$closingStockValue,'sales_stock_one'=>$salesValueOne,'sales_stock_all'=>$salesValueAll,'mint_stock_one'=>$mintValueOne,'mint_stock_all'=>$mintValueAll,'closing_stock_sum_cal'=>$closingStockValue_sum_cal);
					}
					
				//	echo $this->db->last_query().'<br/>';
				
				// $openingData[$ofc->office_id][$product->product_id] = array('closing_stock'=>$closingStockValue,'sales_stock_one'=>$salesValueOne,'sales_stock_all'=>$salesValueAll,'mint_stock_one'=>$mintValueOne,'mint_stock_all'=>$mintValueAll);
			}
			//die;
			// echo '<pre>';
			// print_r($openingData);
		
		}
		// echo '<pre>';
		// print_r($openingData); echo "</pre>"; //die;
		return $openingData;
	}
	
	
	public function _get_transaction_tax_report_data($office_operation_type=null,$office_id=null)
	{
		//$transaction_data=$this->db->get_where('invoice_showroom_'.$office_id)->result();
		$str_ids='';
		$where = "where created_date >='".date('Y-m-d')." 00:00:00' and created_date <='".date('Y-m-d')." 23:59:59' ";
		if($this->input->post('submit'))
		{
			
			$from=$this->input->post('access_right_from');
			$to=$this->input->post('access_right_to');
		
			$from2 = explode('/',$from);
			$to2 = explode('/',$to);
			$fromDate = $from2[2].'-'.$from2[1].'-'.$from2[0];
			$toDate = $to2[2].'-'.$to2[1].'-'.$to2[0];
			$str_where=array();
			$str_ids_pur='';
		
			if(!empty($fromDate)){
			$from="$fromDate 00:00:00";
			
			 $str_where[]=" created_date >='".$from."'";
		    }if(!empty($toDate)){
			$to="$toDate 23:59:59";
			$str_where[]=" created_date <='".$to."'";
		     }
			$product_id="";
			$transaction_name="";
			if($this->input->post('type_transaction'))
			{
			 $type_transaction=$this->input->post('type_transaction');
			 $data['type_transaction']=$type_transaction;
			 if($type_transaction=='sales')
			 {
				  $str_where[]=" (invoice_type!='purchase' and invoice_type!='')";
			 }
			 elseif($type_transaction=='purchase')
			 {
				  $str_where[]=" (invoice_type='purchase' or invoice_type='')";
			 }
			
			}
			if($this->input->post('payment_mode'))
			{
			 $payment_mode=$this->input->post('payment_mode');
			 $data['payment_mode']=$payment_mode;
			
			 $payment_mode_table='invoice_showroom_payment_mode_'.$office_id;
											$this->db->select('invoice_id')->from($payment_mode_table);
											 $this->db->group_by('invoice_id');
											$this->db->where_in('payment_type', $payment_mode);
											$arr_records_payments=$this->db->get()->result();
											foreach($arr_records_payments as $data_payment)
											{
												$arr_invoice[]=$data_payment->invoice_id;
											}
											if(count($arr_invoice)>0)
											{
												$str_ids=" where invoice.invoice_id in ('".implode("','",$arr_invoice)."')";
											}
											else
											{
												$str_ids=" where invoice.invoice_id in ('0')";
											}
											if(in_array('cash',$payment_mode))
											{
												
											}
											else
											{
												$str_ids_pur=" where purchase.purchase_id='0'";
											}
											
			}
			if($str_ids=='')
			{
				$str_ids=" where invoice.is_deleted=0 ";
			}
			else
			{
				$str_ids=$str_ids." and invoice.is_deleted=0";
			}
			
				if(count($str_where)>0)
				{
					$where=" where ".implode(" and ",$str_where);
				}
				
		  
		}
		
		if($str_ids=='')
			{
				$str_ids=" where invoice.is_deleted=0 ";
			}
		$transaction_data = $this->db->query("SELECT * FROM 
		(SELECT invoice.invoice_number as invoice_number, invoice.invoice_id as invoice_id, date_format(str_to_date(invoice.invoice_date, '%d/%m/%Y %H:%i:%s'), '%d/%m/%Y') as transaction_date, invoice.invoice_date as invoice_date, invoice.invoice_type as invoice_type, invoice.total_amount as total_amount, invoice.amount_received as amount_received, invoice.surcharge_on_vat as surcharge_on_vat, invoice.amount_refunded as amount_refunded, invoice.adjustment as adjustment, invoice.createdOn as created_date, invoice.customer_id FROM invoice_showroom_$office_id as invoice  $str_ids
		UNION ALL 
		SELECT purchase.purchase_number	as invoice_number, purchase.purchase_id as invoice_id, date_format(str_to_date(purchase.purchase_date, '%d/%m/%Y %H:%i:%s'), '%d/%m/%Y') as transaction_date, purchase.purchase_date as invoice_date, purchase.purchase_type as invoice_type, 0-CAST(purchase.total_amount AS DECIMAL) as total_amount, 0-CAST(purchase.amount_paid AS DECIMAL) as amount_received, 0.00 as surcharge_on_vat, 0.00 as amount_refunded, purchase.adjustment_amount  as adjustment, purchase.createdOn as created_date, purchase.customer_id FROM purchase_showroom_$office_id as purchase $str_ids_pur ) as union_table $where order by created_date desc")->result();
		

$data['get_all_record']=$transaction_data;
$data['query']=$this->db->last_query();
		return $data;
		
		
	}

	public function _get_sold_and_inventory_report_new($reportDate,$fromDate,$toDate,$office_ids,$regional_ids)
	{
		$date_where = array('createdOn >='=>$fromDate,'createdOn <='=>$toDate);
		$his_date_where = array('his.createdOn >='=>$fromDate,'his.createdOn <='=>$toDate);
		$in_date_where = array('inv.createdOn >='=>$fromDate,'inv.createdOn <='=>$toDate);
		if(empty($office_ids)){
			if(empty($regional_ids))
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->get()->result();
			}
			else
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where("office_id !=",1)->where_in('regional_store_id',$regional_ids)->get()->result();
			}
		}
		else{
			$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where_in('office_id',$office_ids)->get()->result();
		}
	
		$ofcDatas = $officeDatas;
		$totalCost = 0;
		$date_where_one = " createdOn LIKE '".$reportDate."%'";
	//	echo $date_where_one;
		$in_date_where_one = " inv.createdOn LIKE '".$reportDate."%'";
		$report_Date = date( 'Y-m-d', strtotime( $reportDate2 . ' -1 day' ) );
		$date_where_close = array('createdOn >='=>$fromDate);
		// $date_where_all = array('createdOn >='=>$reportDate." 00:00:00",'createdOn <='=>$reportDate." 23:59:59");
		$date_where_all = $date_where;
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		
		$openingData = array();
		$productSaleData = array();
		
		// print_r($ofcDatas); // die;
		foreach($ofcDatas as $ofc)
		{
			$table_name = "inventory_office_history_".$ofc->office_id;
			if($ofc->office_operation_type == "showroom"){
				$invoice_table_name = "invoice_showroom_".$ofc->office_id;
			}
			//echo '<pre>'; print_r($ofc);
			foreach($productData as $key=>$product){
				$openingStockValue = 0;
				$closingStockValue = 0;
				$closingStockValue_sum_cal = 0;
				$salesValueOne = 0;
				$salesValueAll = 0;
				$mintValueOne = 0;
				$mintValueAll = 0;
				
					
					$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn <=' => $toDate))->order_by('history_id','DESC')->limit('1')->get()->row();
					$closingStockValue = $closingStockValue + $closeStockData->netStock;
					
					
					$openDatas = $this->db->select('*')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->get()->result();
					
					// opening Stock Value 
					$openStockData = $this->db->select('current_stock as currentStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn >='=>$fromDate))->order_by('history_id','ASC')->limit('1')->get()->row();
					if(empty($openStockData)){
						$openStockData = $this->db->select('net_stock as currentStock')->from($table_name)->where(array('product_id'=>$product->product_id))->order_by('history_id','DESC')->limit('1')->get()->row();
					}
					$openingStockValue = $openingStockValue + $openStockData->currentStock ;
				
					
					$same_day_delete=0;
					$add_in_sale=0;
					if($ofc->office_operation_type == "showroom")
					{
							$invoice_serial_table_name = "invoice_showroom_product_serial_number_".$ofc->office_id;
						$invoice_product_table_name = "invoice_showroom_product_".$ofc->office_id;
					$saleData_same_day_delete = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id,'is_deleted'=>'1'))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
					if($saleData_same_day_delete->sales_data!=NULL)
					{
						$same_day_delete=$saleData_same_day_delete->sales_data;
					}
				$saleData_sold_deleted_leter = $this->db->select('GROUP_CONCAT(his.transaction_number) as all_transaction')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id,'is_deleted'=>'1'))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
				
					if($saleData_sold_deleted_leter->all_transaction!=NULL)
					{
					$stockdelete_letter = $this->db->select('sum(received_stock) as add_in_sale')->from($table_name)->where(array('product_id'=>$product->product_id))->where('createdOn >',$toDate)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->where_in('transaction_number',explode(",",$saleData_sold_deleted_leter->all_transaction))->get()->row();
				//	print_r($stockdelete_letter);
						if($stockdelete_letter->add_in_sale!='')
						{
							$add_in_sale=$stockdelete_letter->add_in_sale;
							$same_day_delete=$same_day_delete-$add_in_sale;
						}
					}
					}
					
					$stockInData = $this->db->select('sum(received_stock) as stock_in_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('vendor','store','showroom','Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
					$stockInValue = ($stockInData->stock_in_data != '') ?  $stockInData->stock_in_data -$same_day_delete : 0;
					
					if($ofc->office_operation_type == "showroom"){
				
						
						$oneSaleData = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where_one)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
						$oneSaleDataValue = $oneSaleData->sales_data;
						
					
						$deletedOneSaleData = $this->db->select('sum(his.received_stock) as deleted_sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where_one)->where_in('his.type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedOneSaleDataValue = $deletedOneSaleData->deleted_sales_data;
						
						$salesValueOne = $salesValueOne + $oneSaleDataValue - $deletedOneSaleDataValue;
						
						
						$allSaleData=$this->db->query("select count(invoice_product_serial_number_id) as sales_data from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id where product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' group by product_id" )->row(); 
					
						$allSaleDataValue = $allSaleData->sales_data-$same_day_delete;
						$deletedAllSaleDataValue = 0;
						
					}
					else if($ofc->office_operation_type == "store"){
						// sales Stock Value one Date
						$oneSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
						$oneSaleDataValue = $oneSaleData->sales_data;
					//	echo $this->db->last_query().'<br/>';
						// deleted sales Stock Value
						$deletedOneSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedOneSaleDataValue = $deletedOneSaleData->deleted_sales_data;
						
						$salesValueOne = $salesValueOne + $oneSaleDataValue - $deletedOneSaleDataValue;
						
						// sales Stock Value upto Date
						$allSaleData = $this->db->select('sum(transfer_stock) as sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('customer','BackDateInvoice'))->get()->row();
						$allSaleDataValue = $allSaleData->sales_data;
						// echo $this->db->last_query().'<br/>';
						// deleted sales Stock Value
						$deletedAllSaleData = $this->db->select('sum(received_stock) as deleted_sales_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedAllSaleDataValue = $deletedAllSaleData->deleted_sales_data;
						
					}
					
					
					$salesValueAll = $salesValueAll + $allSaleDataValue - $deletedAllSaleDataValue;
					
					
					
					$buyBackData = $this->db->select('sum(received_stock) as buyback_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('Buy Back'))->get()->row();
					$buybackValue = $buybackValue + $buyBackData->buyback_data;			
					$stockOutData = $this->db->select('sum(transfer_stock) as stock_out_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('store','showroom'))->get()->row();
					$stockOutValue = ($stockOutData->stock_out_data != '') ? $stockOutValue + $stockOutData->stock_out_data : $stockOutValue + 0;
					
					// vendor Stock Value one Date
					$oneMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					$mintValueOne = $mintValueOne + $oneMintData->mint_data;
					
				//	echo $this->db->last_query().'<br/>';
					//echo $this->db->last_query();
					// opening Stock Value 
					
					
					// vendor Stock Value upto Date
					$allMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					
					// vendor Stock Value upto Date
					$trans_allMintData = $this->db->select('sum(transfer_stock) as trans_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					
				
						//echo $openStockData->currentStock." ".$stockInValue." ".$buyBackData->buyback_data." ".$stockOutData->stock_out_data.'<br/>';
						
						$mintValueAll =  $openStockData->currentStock  + $stockInValue   + $buyBackData->buyback_data - (($stockOutData->stock_out_data != '') ?  $stockOutData->stock_out_data : 0);
					
					
					if($mintValueAll < 0){
						$mintValueAll = 0;
					}
					
					if($closingStockValue == '0' && $salesValueOne == '0' && $salesValueAll == '0' && $mintValueOne == '0' && $mintValueAll =='0')
					{
						continue;
						
					}
					else{
						
						
						$openingData[$ofc->office_id][$product->product_id] = array('closing_stock'=>$closingStockValue,'sales_stock_one'=>$salesValueOne,'sales_stock_all'=>$salesValueAll,'mint_stock_one'=>$mintValueOne,'mint_stock_all'=>$mintValueAll,'closing_stock_sum_cal'=>$closingStockValue_sum_cal);
					}
					
				
			}
			
		
		}
	
		return $openingData;
	}
	
	public function _get_sales_all_report($reportDate,$fromDate,$toDate,$office_ids,$regional_ids)
	{
		$date_where = array('inser.createdOn >='=>$fromDate,'inser.createdOn <='=>$toDate);
		$his_date_where = array('his.createdOn >='=>$fromDate,'his.createdOn <='=>$toDate);
		$in_date_where = array('inv.createdOn >='=>$fromDate,'inv.createdOn <='=>$toDate);
		if(empty($office_ids)){
			if(empty($regional_ids))
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where("office_operation_type",'showroom')->get()->result();
			}
			else
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where("office_operation_type",'showroom')->where_in('regional_store_id',$regional_ids)->get()->result();
			}
		}
		else{
			$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where_in('office_id',$office_ids)->get()->result();
		}
		
		$ofcDatas = $officeDatas;
		$totalCost = 0;
		$date_where_one = " createdOn LIKE '".$reportDate."%'";
	//	echo $date_where_one;
		$in_date_where_one = " inv.createdOn LIKE '".$reportDate."%'";
		$report_Date = date( 'Y-m-d', strtotime( $reportDate2 . ' -1 day' ) );
		$date_where_close = array('createdOn >='=>$fromDate);
		// $date_where_all = array('createdOn >='=>$reportDate." 00:00:00",'createdOn <='=>$reportDate." 23:59:59");
		$date_where_all = $date_where;
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		
		$sales = array();
		$productSaleData = array();
		
		// print_r($ofcDatas); // die;
		foreach($ofcDatas as $ofc)
		{
			$table_name = "inventory_office_history_".$ofc->office_id;
			
				$invoice_table_name = "invoice_showroom_".$ofc->office_id;
				$invoice_serial_table_name = "invoice_showroom_product_serial_number_".$ofc->office_id;
				$invoice_product_table_name = "invoice_showroom_product_".$ofc->office_id;
			
			//echo '<pre>'; print_r($ofc);
			foreach($productData as $key=>$product){
				$closingStockValue = 0;
				$closingStockValue_sum_cal = 0;
				$salesValueOne = 0;
				$salesValueAll = 0;
				$mintValueOne = 0;
				$mintValueAll = 0;
				
					$result_data=$this->db->query("select count(invoice_product_serial_number_id) as cnt from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id where ins.is_deleted=0 and product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' group by product_id" )->row();
					
						$sales[$ofc->office_id][$product->product_id] = array('total_sales'=>$result_data->cnt);
					
				
			}
		
		}
		
		// echo '<pre>';
		// print_r($openingData); echo "</pre>"; //die;
		return $sales;
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
	
		$this->db->select('trans_table.stock_transfer_number,trans_table.stock_transfer_date,trans_table.stock_transfer_narration,trans_table.stock_transfer_status,trans_table.stock_transfer_id, trans_table.stock_transfer_to_office_id,trans_table.authorized_date,ofc_mstr.office_name,ofc_mstr.office_address,ofc_mstr.city_id,trans_table.access_level_status,ofc_mstr.office_operation_type,
		ofc_mstr.district_id,ofc_mstr.state_id,trans_table.added_by')->from($tableNameSTOCKRECEIPT.' as trans_table');
		$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=trans_table.stock_transfer_to_office_id');
		$this->db->where(array('trans_table.authorized_date >=' => $fromDate,'trans_table.authorized_date <=' => $toDate));
		if(count($stock_trans_id)>0)
		{
		$this->db->where_not_in('stock_transfer_id',$stock_trans_id);
		}
		$data = $this->db->get()->result();
		
		return $data;
		
	}
	public function _get_detail_sold_and_inventory_report($reportDate,$fromDate,$toDate,$office_ids,$regional_ids)
	{
		$date_where = array('createdOn >='=>$fromDate,'createdOn <='=>$toDate);
		$his_date_where = array('his.createdOn >='=>$fromDate,'his.createdOn <='=>$toDate);
		$in_date_where = array('inv.createdOn >='=>$fromDate,'inv.createdOn <='=>$toDate);
		if(empty($office_ids)){
			if(empty($regional_ids))
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->get()->result();
			}
			else
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where("office_id !=",1)->where_in('regional_store_id',$regional_ids)->get()->result();
			}
		}
		else{
			$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where_in('office_id',$office_ids)->get()->result();
		}
	
		$ofcDatas = $officeDatas;
		$totalCost = 0;
		$date_where_one = " createdOn LIKE '".$reportDate."%'";
	//	echo $date_where_one;
		$in_date_where_one = " inv.createdOn LIKE '".$reportDate."%'";
		$report_Date = date( 'Y-m-d', strtotime( $reportDate2 . ' -1 day' ) );
		$date_where_close = array('createdOn >='=>$fromDate);
		// $date_where_all = array('createdOn >='=>$reportDate." 00:00:00",'createdOn <='=>$reportDate." 23:59:59");
		$date_where_all = $date_where;
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		
		$openingData = array();
		$productSaleData = array();
		
		// print_r($ofcDatas); // die;
		foreach($ofcDatas as $ofc)
		{
			$table_name = "inventory_office_history_".$ofc->office_id;
			if($ofc->office_operation_type == "showroom"){
				$invoice_table_name = "invoice_showroom_".$ofc->office_id;
			}
			//echo '<pre>'; print_r($ofc);
			foreach($productData as $key=>$product){
				$openingStockValue = 0;
				$closingStockValue = 0;
				$closingStockValue_sum_cal = 0;
				$salesValueOne = 0;
				$salesValueAll = 0;
				$mintValueOne = 0;
				$mintValueAll = 0;
				$alldeleted_serialsDataValue = 0;
				 $allSaleDataValue=0;
				$same_day_delete=0;
					$add_in_sale=0;	
					$deleted_letter=0;
					$deleted_created_preious=0;
					$created_prev_deleted_current=0;
					$closeStockData = $this->db->select('net_stock as netStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn <=' => $toDate))->order_by('history_id','DESC')->limit('1')->get()->row();
					$closingStockValue = $closingStockValue + $closeStockData->netStock;
					
					
					$openDatas = $this->db->select('*')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->get()->result();
					
					// opening Stock Value 
					$openStockData = $this->db->select('current_stock as currentStock')->from($table_name)->where(array('product_id'=>$product->product_id,'createdOn >='=>$fromDate))->order_by('history_id','ASC')->limit('1')->get()->row();
					if(empty($openStockData)){
						$openStockData = $this->db->select('net_stock as currentStock')->from($table_name)->where(array('product_id'=>$product->product_id))->order_by('history_id','DESC')->limit('1')->get()->row();
					}
					$openingStockValue = $openingStockValue + $openStockData->currentStock ;
				
					
					if($ofc->office_operation_type == "showroom")
					{
							$invoice_serial_table_name = "invoice_showroom_product_serial_number_".$ofc->office_id;
						$invoice_product_table_name = "invoice_showroom_product_".$ofc->office_id;
					/*$saleData_same_day_delete = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id,'is_deleted'=>'1'))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
					if($saleData_same_day_delete->sales_data!=NULL)
					{
						$same_day_delete=$saleData_same_day_delete->sales_data;
					}
						$saleData_sold_deleted_leter = $this->db->select('GROUP_CONCAT(his.transaction_number) as all_transaction')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id,'is_deleted'=>'1'))->where($in_date_where)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
				
					if($saleData_sold_deleted_leter->all_transaction!=NULL)
					{
					$stockdelete_letter = $this->db->select('sum(received_stock) as add_in_sale')->from($table_name)->where(array('product_id'=>$product->product_id))->where('createdOn >',$toDate)->where_in('type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->where_in('transaction_number',explode(",",$saleData_sold_deleted_leter->all_transaction))->get()->row();
				//	print_r($stockdelete_letter);
						if($stockdelete_letter->add_in_sale!='')
						{
							$add_in_sale=$stockdelete_letter->add_in_sale;
							$same_day_delete=$same_day_delete-$add_in_sale;
						}
					}*/
					/*$alldeleted_serials=$this->db->query("select count(invoice_product_serial_number_id) as sales_data from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id where product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' and ins.is_deleted='1' group by product_id" )->row(); 
				*/
					
						$alldeleted_serials_later=$this->db->query("select sum(received_stock) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' and ins.is_deleted='1' and his.createdOn >='".$fromDate."' and his.createdOn <='".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row();
					$alldeleted_serialsDataValue = $alldeleted_serials->sales_data;
					
					$alldeleted_serials_later=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' and ins.is_deleted='1' and his.createdOn >'".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					//echo $this->db->last_query();
					//echo '<br>';
					if($alldeleted_serials_later->add_in_sale!='')
					{
						$deleted_letter=$alldeleted_serials_later->add_in_sale;
					}
					
				$alldeleted_serials_previous=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn <'".$fromDate."' and ins.is_deleted='1' and his.createdOn >'".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					
					if($alldeleted_serials_previous->add_in_sale!='')
					{
						$deleted_created_preious=$alldeleted_serials_previous->add_in_sale;
					}
					
					
					$alldeleted_serials_previous_del_cur=$this->db->query("select count(invoice_product_serial_number_id) as add_in_sale from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id left join ".$table_name." as his on ins.invoice_number=his.transaction_number where invpro.product_id='".$product->product_id."' and inser.createdOn <'".$fromDate."' and ins.is_deleted='1' and his.createdOn >='".$fromDate."' and  his.createdOn <='".$toDate."' and his.type_value in ('Back Date Invoice Deleted','Invoice Deleted') group by invpro.product_id" )->row(); 
					
					
					if($alldeleted_serials_previous_del_cur->add_in_sale!='')
					{
						$created_prev_deleted_current=$alldeleted_serials_previous_del_cur->add_in_sale;
					}
					
					
					}
					
					$stockInData = $this->db->select('sum(received_stock) as stock_in_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					
					$stockInValue = ($stockInData->stock_in_data != '') ?  $stockInData->stock_in_data : 0;
					
					if($ofc->office_operation_type == "showroom"){
				
						
						$oneSaleData = $this->db->select('sum(his.transfer_stock) as sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where_one)->where_in('his.type_value',array('customer','BackDateInvoice'))->get()->row();
						$oneSaleDataValue = $oneSaleData->sales_data;
						
					
						$deletedOneSaleData = $this->db->select('sum(his.received_stock) as deleted_sales_data')->from($table_name.' as his')->join($invoice_table_name.' as inv','transaction_number=invoice_number')->where(array('his.product_id'=>$product->product_id))->where($in_date_where_one)->where_in('his.type_value',array('Back Date Invoice Deleted','Invoice Deleted'))->get()->row();
						$deletedOneSaleDataValue = $deletedOneSaleData->deleted_sales_data;
						
						$salesValueOne = $salesValueOne + $oneSaleDataValue - $deletedOneSaleDataValue;
						
						
						$allSaleData=$this->db->query("select count(invoice_product_serial_number_id) as sales_data from ".$invoice_serial_table_name." as inser left join ".$invoice_product_table_name." as invpro on inser.invoice_product_id=invpro.invoice_product_id left join ".$invoice_table_name." as ins on inser.invoice_id=ins.invoice_id where product_id='".$product->product_id."' and inser.createdOn >='".$fromDate."' and inser.createdOn <='".$toDate."' and ins.is_deleted='0' group by product_id" )->row(); 
				//echo $this->db->last_query();
						$allSaleDataValue = $allSaleData->sales_data;
						$deletedAllSaleDataValue = 0;
						
					}
					else if($ofc->office_operation_type == "store"){
						// sales Stock Value one Date
						
						
					
						
					}
					
					
					$salesValueAll = $salesValueAll + $allSaleDataValue;
					
					
					
					$buyBackData = $this->db->select('sum(received_stock) as buyback_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('Buy Back'))->get()->row();
					$buybackValue = $buybackValue + $buyBackData->buyback_data;			
					$stockOutData = $this->db->select('sum(transfer_stock) as stock_out_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('store','showroom'))->get()->row();
					$stockOutValue = ($stockOutData->stock_out_data != '') ? $stockOutValue + $stockOutData->stock_out_data : $stockOutValue + 0;
					
					// vendor Stock Value one Date
					$oneMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_one)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					$mintValueOne = $mintValueOne + $oneMintData->mint_data;
					
				//	echo $this->db->last_query().'<br/>';
					//echo $this->db->last_query();
					// opening Stock Value 
					
					
					// vendor Stock Value upto Date
					$allMintData = $this->db->select('sum(received_stock) as mint_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					
					// vendor Stock Value upto Date
					$trans_allMintData = $this->db->select('sum(transfer_stock) as trans_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where_all)->where_in('type_value',array('vendor','store','showroom'))->get()->row();
					
				
						//echo $openStockData->currentStock." ".$stockInValue." ".$buyBackData->buyback_data." ".$stockOutData->stock_out_data.'<br/>';
					//echo $openStockData->currentStock ."-". $deleted_created_preious ."-". $created_prev_deleted_current ."-". $stockInValue ."-". $buyBackData->buyback_data.'<br>';+ $alldeleted_serialsDataValue 
					
					//echo 'opening--'.$openStockData->currentStock." ".$deleted_created_preious." ".$created_prev_deleted_current.'<br>';
					//echo 'Closing--'.$closingStockValue." ".$deleted_letter." ".$deleted_created_preious.'<br>';
						$openStockData_value=$openStockData->currentStock+$deleted_created_preious+$created_prev_deleted_current;
						
						
						//echo $openStockData->currentStock." ".$stockInValue." ".$stockOutData->stock_out_data."<br>";
					//	echo $closingStockValue." ".$deleted_letter." ".$deleted_created_preious."<br>";
					//echo  $openStockData_value ." ". $stockInValue ." ". $buyBackData->buyback_data ." ". (($stockOutData->stock_out_data != '') ?  $stockOutData->stock_out_data : 0).'<br>';
					$closingStockData_Value=$closingStockValue+$deleted_letter+$deleted_created_preious;
					$stockout_data=($stockOutData->stock_out_data != '') ?  $stockOutData->stock_out_data : 0;
					$mintValueAll =  $openStockData_value + $stockInValue + $buyBackData->buyback_data - ($stockout_data);	
					//echo $stockInValue." ".$mintValueAll.'<br>';
					
					if($mintValueAll < 0){
						$mintValueAll = 0;
					}
				$tableNameSTOCKRECEIPT='inventory_'.$ofc->office_operation_type.'_stock_transfer_'.$ofc->office_id;		
					$this->db->select('trans_table.stock_transfer_number,trans_table.stock_transfer_date,trans_table.stock_transfer_narration,trans_table.stock_transfer_status,trans_table.stock_transfer_id, trans_table.stock_transfer_to_office_id,trans_table.authorized_date,ofc_mstr.office_name,ofc_mstr.office_address,ofc_mstr.city_id,trans_table.access_level_status,ofc_mstr.office_operation_type,
		ofc_mstr.district_id,ofc_mstr.state_id,trans_table.added_by')->from($tableNameSTOCKRECEIPT.' as trans_table');
		$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=trans_table.stock_transfer_to_office_id');
		$this->db->where(array('trans_table.authorized_date >=' => $fromDate,'trans_table.authorized_date <=' => $toDate));
		$stock_transfer_st_sh=$this->db->get()->result();
	
				$transfer_product_table='inventory_'.$ofc->office_operation_type.'_stock_transfer_product_'.$ofc->office_id;	
				$in_transit_product=0;				
					foreach($stock_transfer_st_sh as $Stock_receipt_details){
										
								$recipet_table='inventory_'.$Stock_receipt_details->office_operation_type.'_stock_receipt_'.$Stock_receipt_details->stock_transfer_to_office_id;
							
								$receipt_data=$this->db->query("select * from ".$recipet_table." where stock_transfer_number='".$Stock_receipt_details->stock_transfer_number."' and authorized_date > '".$toDate."' order by stock_receipt_id desc limit 1")->row();
								if(!empty($receipt_data))
								{
										$productList=$this->db->get_where($transfer_product_table,array('stock_transfer_id'=>$Stock_receipt_details->stock_transfer_id,'product_id'=>$product->product_id))->result();
								foreach($productList as $product_transfer)
											{
												
												$in_transit_product=$in_transit_product+$product_transfer->stock_transfer_product_quantity;
												
					
											}
								}
								else
								{
									$receipt_data=$this->db->query("select * from ".$recipet_table." where stock_transfer_number='".$Stock_receipt_details->stock_transfer_number."' order by stock_receipt_id desc limit 1")->row();
									
										if(empty($receipt_data) || (!empty($receipt_data) && $receipt_data->authorized_date==''))
										{
											$productList=$this->db->get_where($transfer_product_table,array('stock_transfer_id'=>$Stock_receipt_details->stock_transfer_id,'product_id'=>$product->product_id))->result();
											foreach($productList as $product_transfer)
											{
												
												$in_transit_product=$in_transit_product+$product_transfer->stock_transfer_product_quantity;
											}
										}	
								}

								
					}
					
					
					
		
		$todate_x = date( 'Y-m-d', strtotime( $fromDate . ' -1 day' ) );			
		/*$this->db->select('trans_table.stock_transfer_number,trans_table.stock_transfer_date,trans_table.stock_transfer_narration,trans_table.stock_transfer_status,trans_table.stock_transfer_id, trans_table.stock_transfer_to_office_id,trans_table.authorized_date,ofc_mstr.office_name,ofc_mstr.office_address,ofc_mstr.city_id,trans_table.access_level_status,ofc_mstr.office_operation_type,
		ofc_mstr.district_id,ofc_mstr.state_id,trans_table.added_by')->from($tableNameSTOCKRECEIPT.' as trans_table');
		$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=trans_table.stock_transfer_to_office_id');
		$this->db->where(array('trans_table.authorized_date >=' => '2015-10-01','trans_table.authorized_date <=' => $todate_x));
		$stock_transfer_st_sh=$this->db->get()->result();
	
		$transfer_product_table='inventory_'.$ofc->office_operation_type.'_stock_transfer_product_'.$ofc->office_id;	
		$in_transit_product_x=0;				
		foreach($stock_transfer_st_sh as $Stock_receipt_details)
		{
										
			$recipet_table='inventory_'.$Stock_receipt_details->office_operation_type.'_stock_receipt_'.$Stock_receipt_details->stock_transfer_to_office_id;
		
			$receipt_data=$this->db->query("select * from ".$recipet_table." where stock_transfer_number='".$Stock_receipt_details->stock_transfer_number."' and authorized_date > '".$todate_x."' order by stock_receipt_id desc limit 1")->row();
			
			if(!empty($receipt_data))
			{
				
					$productList=$this->db->get_where($transfer_product_table,array('stock_transfer_id'=>$Stock_receipt_details->stock_transfer_id,'product_id'=>$product->product_id))->result();
			foreach($productList as $product_transfer)
						{
							
							$in_transit_product_x=$in_transit_product_x+$product_transfer->stock_transfer_product_quantity;
							
						}
			}
			else
			{
				$receipt_data=$this->db->query("select * from ".$recipet_table." where stock_transfer_number='".$Stock_receipt_details->stock_transfer_number."' order by stock_receipt_id desc limit 1")->row();
				
					if(empty($receipt_data) || (!empty($receipt_data) && $receipt_data->authorized_date==''))
					{
						
						$productList=$this->db->get_where($transfer_product_table,array('stock_transfer_id'=>$Stock_receipt_details->stock_transfer_id,'product_id'=>$product->product_id))->result();
						foreach($productList as $product_transfer)
						{
							
							$in_transit_product_x=$in_transit_product_x+$product_transfer->stock_transfer_product_quantity;
						}
					}	
			}

								
		}
		$this->db->select('trans_table.stock_transfer_number,trans_table.stock_transfer_date,trans_table.stock_transfer_narration,trans_table.stock_transfer_status,trans_table.stock_transfer_id, trans_table.stock_transfer_to_office_id,trans_table.authorized_date,ofc_mstr.office_name,ofc_mstr.office_address,ofc_mstr.city_id,trans_table.access_level_status,ofc_mstr.office_operation_type,
		ofc_mstr.district_id,ofc_mstr.state_id,trans_table.added_by')->from($tableNameSTOCKRECEIPT.' as trans_table');
		$this->db->join('office_master as ofc_mstr','ofc_mstr.office_id=trans_table.stock_transfer_to_office_id');
		$this->db->where(array('trans_table.authorized_date >=' => '2015-10-01','trans_table.authorized_date <=' => $toDate));
		$stock_transfer_st_sh=$this->db->get()->result();
	//echo $this->db->last_query();
				$transfer_product_table='inventory_'.$ofc->office_operation_type.'_stock_transfer_product_'.$ofc->office_id;	
				$in_transit_product_y=0;				
					foreach($stock_transfer_st_sh as $Stock_receipt_details){
										
								$recipet_table='inventory_'.$Stock_receipt_details->office_operation_type.'_stock_receipt_'.$Stock_receipt_details->stock_transfer_to_office_id;
							
								$receipt_data=$this->db->query("select * from ".$recipet_table." where stock_transfer_number='".$Stock_receipt_details->stock_transfer_number."' and authorized_date > '".$toDate."' order by stock_receipt_id desc limit 1")->row();
								if(!empty($receipt_data))
								{
										$productList=$this->db->get_where($transfer_product_table,array('stock_transfer_id'=>$Stock_receipt_details->stock_transfer_id,'product_id'=>$product->product_id))->result();
								foreach($productList as $product_transfer)
											{
												
												$in_transit_product_y=$in_transit_product_y+$product_transfer->stock_transfer_product_quantity;
											}
								}
								else
								{
									$receipt_data=$this->db->query("select * from ".$recipet_table." where stock_transfer_number='".$Stock_receipt_details->stock_transfer_number."' order by stock_receipt_id desc limit 1")->row();
									
										if(empty($receipt_data) || (!empty($receipt_data) && $receipt_data->authorized_date==''))
										{
											$productList=$this->db->get_where($transfer_product_table,array('stock_transfer_id'=>$Stock_receipt_details->stock_transfer_id,'product_id'=>$product->product_id))->result();
											foreach($productList as $product_transfer)
											{
												
												$in_transit_product_y=$in_transit_product_y+$product_transfer->stock_transfer_product_quantity;
											}
										}	
								}

								
					}
					
					
					
					*/
					
					
					$stockInData_vendor = $this->db->select('sum(received_stock) as stock_in_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('vendor'))->get()->row();
					
					$stockInData_vendorValue = ($stockInData_vendor->stock_in_data != '') ?  $stockInData_vendor->stock_in_data : 0;
					
					
				if($closingStockValue == '0' && $salesValueOne == '0' && $salesValueAll == '0' && $mintValueOne == '0' && $mintValueAll =='0' && $in_transit_product==0 && $openStockData_value=='0' && $stockInValue=='0' && $stockout_data=='0' )
					{
						
						/* $stock_out=($stockOutData->stock_out_data != '') ?  $stockOutData->stock_out_data : 0;
						$openingData[$ofc->office_id][$product->product_id] = array('opening_stock'=>$openStockData_value,'stock_in'=>$stockInValue,'buyback'=>$buyBackData->buyback_data,'in_transit'=>0,'deleted_sales'=>$deleted_letter,'closing_stock'=>$closingStockData_Value,'sales_stock_one'=>$salesValueOne,'stock_out'=>$stock_out,'sales_stock_all'=>$salesValueAll,'mint_stock_one'=>$mintValueOne,'mint_stock_all'=>$mintValueAll,'in_transit_product'=>$in_transit_product,'closing_stock_sum_cal'=>$closingStockValue_sum_cal,'deleted_letter'=>$deleted_letter,'stockInData_vendor'=>$stockInData_vendorValue,'status_disp'=>'vendor'); */
						
						continue;
						
					}
					else{
						
						//$mintValueAll=$openStockData_value+$stockInValue+$buyBackData->buyback_data;
						$stock_out=($stockOutData->stock_out_data != '') ?  $stockOutData->stock_out_data : 0;
						$openingData[$ofc->office_id][$product->product_id] = array('opening_stock'=>$openStockData_value,'stock_in'=>$stockInValue,'buyback'=>$buyBackData->buyback_data,'in_transit'=>0,'deleted_sales'=>$deleted_letter,'closing_stock'=>$closingStockData_Value,'sales_stock_one'=>$salesValueOne,'stock_out'=>$stock_out,'sales_stock_all'=>$salesValueAll,'mint_stock_one'=>$mintValueOne,'mint_stock_all'=>$mintValueAll,'in_transit_product'=>$in_transit_product,'closing_stock_sum_cal'=>$closingStockValue_sum_cal,'deleted_letter'=>$deleted_letter,'stockInData_vendor'=>$stockInData_vendorValue,'status_disp'=>'');
					}
				
			}
			
		
		}
	$openingData['ofcDatas']=$ofcDatas;
	$openingData['todate_x']=$todate_x;
	$openingData['toDate']=$toDate;
	
	
		return $openingData;
	}
		public function _get_detail_sold_and_inventory_vendor_report($reportDate,$fromDate,$toDate,$office_ids,$regional_ids)
	{
		$date_where = array('createdOn >='=>$fromDate,'createdOn <='=>$toDate);
		$his_date_where = array('his.createdOn >='=>$fromDate,'his.createdOn <='=>$toDate);
		$in_date_where = array('inv.createdOn >='=>$fromDate,'inv.createdOn <='=>$toDate);
		if(empty($office_ids)){
			if(empty($regional_ids))
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->get()->result();
			}
			else
			{
				$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where("office_id !=",1)->where_in('regional_store_id',$regional_ids)->get()->result();
			}
		}
		else{
			$officeDatas = $this->db->select('office_id,office_operation_type')->from('office_master as om')->where_in('office_id',$office_ids)->get()->result();
		}
	
		$ofcDatas = $officeDatas;
		$totalCost = 0;
		$date_where_one = " createdOn LIKE '".$reportDate."%'";
	//	echo $date_where_one;
		$in_date_where_one = " inv.createdOn LIKE '".$reportDate."%'";
		$report_Date = date( 'Y-m-d', strtotime( $reportDate2 . ' -1 day' ) );
		$date_where_close = array('createdOn >='=>$fromDate);
		// $date_where_all = array('createdOn >='=>$reportDate." 00:00:00",'createdOn <='=>$reportDate." 23:59:59");
		$date_where_all = $date_where;
		$productData = $this->db->select('*')->from('product_master')->get()->result();
		
		$vendorsData = array();
		$productSaleData = array();
		
		// print_r($ofcDatas); // die;
		foreach($ofcDatas as $ofc)
		{
			$table_name = "inventory_office_history_".$ofc->office_id;
			
			//echo '<pre>'; print_r($ofc);
			foreach($productData as $key=>$product){
				$stockInData_vendor = $this->db->select('sum(received_stock) as stock_in_data')->from($table_name)->where(array('product_id'=>$product->product_id))->where($date_where)->where_in('type_value',array('vendor'))->get()->row();
					
					$stockInData_vendorValue = ($stockInData_vendor->stock_in_data != '') ?  $stockInData_vendor->stock_in_data : 0;
					$vendorsData[$ofc->office_id][$product->product_id] = array('stockInData_vendor'=>$stockInData_vendorValue);
			}
		}
		return $vendorsData;
	}
}
