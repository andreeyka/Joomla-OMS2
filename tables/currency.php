<?php
defined('_JEXEC') or die('Restricted access');

class TableCurrency extends JTable
{
	public $currency = null;
	public $rate = null;

	function __construct(&$db)
	{
		parent::__construct('#__ordermanagementsystem_currency', 'currency', $db);
	}
}

