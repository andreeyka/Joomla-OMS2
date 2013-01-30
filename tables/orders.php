<?php
defined('_JEXEC') or die('Restricted access');

class TableOrders extends JTable
{
	public  $id = null;
	public $time = null;
	public $user_id = null;
	public $item = null;
	public $item_url = null;
	public $site = null;
	public $article = null;
	public $size = null;
	public $color = null;
	public $amount = null;
	public $price = null;
	public $currency = null;
	public $currency_rate = null;
	public $interest = 10;
	public $tax = 0;
	public $weight = null;
	public $notes = null;
	public $status = 1;
	public $image = null;
	public $delivery = null;



	function __construct(&$db)
	{
		parent::__construct('#__ordermanagementsystem', 'id', $db);
	}
}

