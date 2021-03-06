<?php
/**
 * @version		1.0.0 oms2 $
 * @package		oms2
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license		GNU/GPL
 * @author		Andrey Shlyapin
 * @author mail	andrey@skzd.ru
 * @website		http://www.pokupaem-v-amerike.com/
 *
 *
 * @MVC architecture generated by MVC generator tool at http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access Model');

jimport( 'joomla.application.component.model' );

class oms2ModelOms2 extends Jmodel
{
	private $user;
	private $user_id;
	public $update_status=false;
	
	function __construct(){
		$this->user = JFactory::getUser();
		$this->user_id=$this->user->id;	
		
		
		$session = JFactory::getSession();
		$this->orderFilter=$session->get('orderFilter');
		
		if ($this->user->omsadmin and $this->orderFilter['oms-user']>'0') {
			$this->user_id = $this->orderFilter['oms-user'];
		}
		if(!isset($this->orderFilter['oms-user'])) { $this->orderFilter['oms-user']='0';}
		if(!isset($this->orderFilter['order-filter-status']))$this->orderFilter['order-filter-status']=array(1=>1,3=>1,4=>1);
		if(!isset($this->orderFilter['order-filter-date']))$this->orderFilter['order-filter-date']='';
		parent::__construct();
	}
	
	function getOrderFilter(){
		return $this->orderFilter;
	}
	
	function getOrder()
	{
		$order = $this->getTable('Orders');
		$order->load(JRequest::getCMD('id'));
		$order->total=round($order->price*$order->amount*(1+$order->tax/100)*(1+$order->interest/100)*$order->currency_rate,2);
		if ($order->user_id==$this->user_id or $this->user->omsadmin) {
			return $order;
		} else {
			return FALSE;
		}
		
	}

	function  getOrders(){
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->nameQuote('#__ordermanagementsystem'));
		$query->where($this->getSqlWhere());
		$query->order('id desc');
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function getOrdersCosts(){
		$query = $this->_db->getQuery(true);
		$query->select('sum(price*amount*(1+tax/100)*(1+interest/100)*currency_rate) as ordersSum');
		$query->from($this->_db->nameQuote('#__ordermanagementsystem'));
		$query->where("`user_id` = '".$this->user_id."'");
		$query->where("`status` != 2");
		$this->_db->setQuery($query);
		$orders=$this->_db->loadObjectList();
		return $orders[0]->ordersSum;
	}
	
	function getOrderImage(){
		$fieldName = 'order-image';
		$fileError = $_FILES[$fieldName]['error'];
		if ($fileError > 0)
		{
			switch ($fileError)
			{
				case 1:
					return JText::_( 'FILE TO LARGE THAN PHP INI ALLOWS' );
						
	
				case 2:
					return JText::_( 'FILE TO LARGE THAN HTML FORM ALLOWS' );
	
				case 3:
					return JText::_( 'ERROR PARTIAL UPLOAD' );
	
				case 4:
					return JText::_( 'NO FILE TO UPLOAD' );
			}
		}
	
		$fileSize = $_FILES[$fieldName]['size'];
		if($fileSize > 2000000)
		{
			return JText::_( 'FILE BIGGER THAN 2MB' );
		}
	
		$fileName = $_FILES[$fieldName]['name'];
		$uploadedFileNameParts = explode('.',$fileName);
		$uploadedFileExtension = array_pop($uploadedFileNameParts);
	
		$validFileExts = explode(',', 'jpeg,jpg,png,gif');
		$extOk = false;
		foreach($validFileExts as $key => $value)
		{
			if( preg_match("/$value/i", $uploadedFileExtension ) )
			{
				$extOk = true;
				$extMatch=$value;
			}
		}
	
		if ($extOk == false)
		{
			return JText::_( 'INVALID EXTENSION' );
		}
		$fileTemp = $_FILES[$fieldName]['tmp_name'];
		$imageinfo = getimagesize($fileTemp);
		$okMIMETypes = 'image/jpeg,image/pjpeg,image/png,image/x-png,image/gif';
		$validFileTypes = explode(",", $okMIMETypes);
		if( !is_int($imageinfo[0]) || !is_int($imageinfo[1]) ||  !in_array($imageinfo['mime'], $validFileTypes) )
		{
			return JText::_( 'INVALID FILETYPE' );
		}
		$fileName = preg_replace("/[^A-Za-z0-9\.]/i", "-", $fileName);
		$fileNameUniq = $this->user_id.'-'.time().'-'.$fileName;
		$uploadPath = JPATH_COMPONENT.DS.'assets'.DS.'images'.DS.'orders'.DS.$fileNameUniq;
		if(!JFile::upload($fileTemp, $uploadPath))
		{
			return JText::_( 'ERROR MOVING FILE' );
		}
		else
		{
			if(is_file(JPATH_COMPONENT.DS.'assets'.DS.'images'.DS.'orders'.DS.$this->row->image)){
				unlink(JPATH_COMPONENT.DS.'assets'.DS.'images'.DS.'orders'.DS.$this->row->image);
			}
			$this->row->image=$fileNameUniq;
			return JText::_( '. IMAGE SAVED' );;
		}
	}
	
	function addOrder() {
		$data = JRequest::get('post');
		$data['order-site']=preg_replace('/http:\/\/anonymouse.org\/cgi-bin\/anon-www.cgi/', '', $data['order-url']);
		preg_match('/:\/\/([a-z0-9\.]+)\//i',$data['order-site'],$matches);
		$this->row = $this->getTable('Orders');
		$this->row->item=$data['order-item'];
		$this->row->item_url=$data['order-url'];
		$this->row->article=$data['order-article'];
		$this->row->size=$data['order-size'];
		$this->row->color=$data['order-color'];
		$this->row->amount=$data['order-amount'];
		$this->row->price=str_replace(',','.',$data['order-price']);
		$this->row->currency=$data['order-currency'];
		$this->row->notes=$data['order-notes'];
		$this->row->user_id=$this->user_id;
		$this->row->site=$matches[1];
		$this->row->currency_rate=str_replace(',','.',$this->getCurrencyRate($this->row->currency));
		$messageImage=$this->getOrderImage();
		$messageSave=$this->save();
		$this->row->id=$this->_db->insertid();
		$messageHistory=$this->updateHistory();
		return $messageSave.'. '.$messageImage.'. '.$messageHistory;
	}
	
	function saveOrder() {
		$data = JRequest::get('post');
		$data['order-site']=preg_replace('/http:\/\/anonymouse.org\/cgi-bin\/anon-www.cgi/', '', $data['order-url']);
		preg_match('/:\/\/([a-z0-9\._-]+)\//i',$data['order-site'],$matches);
		$this->row = $this->getTable('Orders');
		$this->row->load($data['id']);
	
		if ($this->row->status != $data['order-status']){
			$updateHistory=true;
		}
	
		$this->row->status=$data['order-status'];
		$this->row->item=$data['order-item'];
		$this->row->item_url=$data['order-url'];
		$this->row->article=$data['order-article'];
		$this->row->size=$data['order-size'];
		$this->row->color=$data['order-color'];
		$this->row->amount=$data['order-amount'];
		$this->row->price=str_replace(',','.',$data['order-price']);
		$this->row->currency=$data['order-currency'];
		$this->row->notes=$data['order-notes'];
		$this->row->site=$matches[1];
		$this->row->currency_rate=str_replace(',','.',$data['order-currency-rate']);
		$this->row->tax=str_replace(',','.',$data['order-tax']);
		$this->row->interest=str_replace(',','.',$data['order-interest']);
		$messageImage=$this->getOrderImage();
		if ($updateHistory) {
			$messageHistory=$this->updateHistory();
			if($this->row->status==4){
				$this->row->delivery=date('Y-m-d');
				oms2Helper::debug($this->row);
				$this->addDelivery();
			}
		}
		$messageSave=$this->save();
		return $messageSave.'. '.$messageHistory;
	}
	

	function setOrderStatus() {
		$this->row = $this->getTable('Orders');
		$this->row->load(JRequest::getCmd('id'));
		if($this->row->status!=JRequest::getCmd('order-status')) $updateHistory=true;
		$this->row->status=JRequest::getCmd('order-status');
		if ($updateHistory) {
			$messageHistory=$this->updateHistory();
			if($this->row->status==4){
				$this->row->delivery=date('Y-m-d');
				$this->addDelivery();
			}
		}
		$messageSave=$this->save();
		return $messageSave.'. '.$messageSave;
	}
	
	function deleteOrder() {
		$id = JRequest::getCMD('id');
		$table = $this->getTable('Orders');
		if (!$table->delete($id)) {
			$this->setError($table->getErrorMsg());
			return JText::_('DELETE FAILED');
		} else {
			$query = $this->_db->getQuery(true);
			$query->delete('#__ordermanagementsystem_status_history')->where('`order` = '.$id);
			$this->_db->setQuery($query);
			if ($this->_db->query()){
				$message=". ".JText::_('History DELETE OK');
			}
			return JText::_('DELETE OK').$message;
		}
	}
	
	function getOmsUser(){
		$user=JFactory::getUser($this->user_id);
		$o = new stdclass;
		$o->user=$this->user;
		$o->user_id=$this->user_id;
		$o->username=$user->username;
		$o->name=$user->name;
		$o->ordersSum=round($this->getOrdersCosts(),2);
		$o->paymentsByStatus=$this->getPaymentsByStatus();
		return $o;
	}
	
	function getOmsUserOrders(){
		$o=$this->getOmsUser();
		$o->orders=$this->getOrders();
		return $o;
	}
	
	function getOmsUserDeliverys(){
		$o=$this->getOmsUser();
		$o->deliverys=$this->getDeliverys();
		return $o;
	}
	
	function getOmsUserPayments(){
		$o=$this->getOmsUser();
		$o->payments=$this->getPayments();
		return $o;
	}
	
	function getDeliverys() {
				
		$query = $this->_db->getQuery(true);
		$a=$this->_db->nameQuote('#__ordermanagementsystem_delivery');
		$query->select("*");
		$query->from($a);
		if ($this->orderFilter['oms-user']>'0')$query->where("$a.user_id='$this->user_id'");
		$query->order("$a.date desc, $a.user_id");
		$this->_db->setQuery($query);
		$this->_db->query($query);
		return $this->_db->loadObjectList();
	}
	
	function addDelivery(){
		$query = $this->_db->getQuery(true);
		$query->select('date');
		$query->from('#__ordermanagementsystem_delivery');
		$query->where('`user_id` = '.$this->row->user_id);
		$query->where('`date` = \''.$this->row->delivery.'\'');
		$dbo = JFactory::getDBO();
		$dbo->setQuery( $query );
		$res=$dbo->query( $query );
		if ($dbo->getAffectedRows($res)==0){
			$query  = $dbo->getQuery(true);
			$query->insert('#__ordermanagementsystem_delivery');
			$query->set('`date`=\''.$this->row->delivery.'\'');
			$query->set('`user_id`=\''.$this->row->user_id.'\'');
			$dbo->setQuery( $query );
			$dbo->query( $query );
		}
	}
	
	function addPay() {
		$data = JRequest::get('post');
		$this->row = $this->getTable('Payments');
		$this->row->payment_date=$data['payment_date'];
		$this->row->value=$data['payment-value'];
		$this->row->notes=$data['payment-notes'];
		$this->row->user_id=$this->user_id;
		return $this->save();
	}
	
	function getPayments(){
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->nameQuote('#__ordermanagementsystem_payments'));
		$query->where("`user_id` = '".$this->user_id."'");
		$query->order('id');
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function paymentConfirm()
	{
		$id=JRequest::getCmd('id');
		$this->row = $this->getTable('payments');
		$this->row->load($id);
		$this->row->status=1;
		return $this->save();
	}
	
	function getPaymentsByStatus(){
		$query = $this->_db->getQuery(true);
		$query->select('status, sum(value) as paymentsSum');
		$query->from($this->_db->nameQuote('#__ordermanagementsystem_payments'));
		$query->where("`user_id` = '".$this->user_id."'");
		$query->order('id');
		$this->_db->setQuery($query);
		$paiments=$this->_db->loadObjectList();
		$paymentsByStatus[0]=0;
		$paymentsByStatus[1]=0;
		foreach ($this->_db->loadObjectList() as $key){
			$paymentsByStatus[$key->status]=$key->paymentsSum;
		}
		return $paymentsByStatus;
	}
	
	function getSqlWhere(){
		if(JRequest::getCmd('delivery')!=''){
			$where[]='`delivery`=\''.JRequest::getCmd('delivery').'\'';
			if(JRequest::getCmd('user_id')!=''  and $this->user->omsadmin){
				$where[]="`user_id`='".JRequest::getCmd('user_id')."'";
			} else {
				$where[]="`user_id`='".$this->user_id."'";
			}
		}else{
			if($this->orderFilter['oms-user']!='0' or !$this->admin) $where[]="`user_id`='".$this->user_id."'";
			foreach ($this->orderFilter['order-filter-status'] as $key=>$value){
				$status[]="`status`='".$key."'";
			}
			if(is_array($status)) $where[]='('.implode(' OR ', $status).')';
		}
		
		if($this->orderFilter['order-filter-date']!='') $where[]="`time` like '%".$this->orderFilter['order-filter-date']."%'";
		return $where;
	}	
	
	function getInsertId(){
		return $this->_db->insertid();
	}
	
	function getCurrencyRate($currency='USD'){
		$rate = $this->getTable('Currency');
		$rate->load($currency);
		return $rate->rate;
	}
	
	function save(){
		if ($this->row->check())
		{
			if (!$this->row->store())
			{
				$this->setError($this->row->getError());
				return JText::_('SAVE FAILED');
			}
		}
		else
		{
			$this->setError($this->row->getError());
			return JText::_('SAVE FAILED');
		}
		return JText::_('SAVE OK');
	}
	
	function updateHistory() {
		$this->rowHistory = $this->getTable('History');
		$this->rowHistory->status=$this->row->status;
		$this->rowHistory->order=$this->row->id;
		if ($this->rowHistory->check())
		{
			if (!$this->rowHistory->store())
			{
				$this->setError($this->rowHistory->getError());
				return JText::_('HISTORY SAVE FAILED');
			}
		}
		else
		{
			$this->setError($this->rowHistory->getError());
			return JText::_('HISTORY SAVE FAILED');
		}
		return JText::_('HISTORY SAVE OK');
	}
	
	function  getHistory(){
		$query 	= 'SELECT *'
				. ' FROM ' . $this->_db->nameQuote('#__ordermanagementsystem_status_history')
				. ' WHERE `order` = '.JRequest::getCMD('id')
				. ' ORDER BY date';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function setUserId(){
		if ($this->admin and $this->orderFilter['order-filter-user']!='0') {
			$this->user_id = $this->orderFilter['order-filter-user'];
		}
	}
	
	
}
?>