<?php
defined('_JEXEC') or die('Restricted access');

class TablePayments extends JTable
{
	public $id=null;
	public $time = null;
	public $payment_date=null;
	public $user_id=null;
	public $value=null;
	public $notes=null;
	public $status=0;

	function __construct(&$db)
	{
		parent::__construct('#__ordermanagementsystem_payments', 'id', $db);
	}
}

?>