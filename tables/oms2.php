<?php
defined('_JEXEC') or die('Restricted access');

class TableOms extends JTable
{
	public $id = null;
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
	public $interest = null;
	public $tax = null;
	public $weight = null;
	public $notes = null;
	public $status = null;



	function __construct(&$db)
	{
		parent::__construct('#__ordermanagementsystem', 'id', $db);
	}
}

