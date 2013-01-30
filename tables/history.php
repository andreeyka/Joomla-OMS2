<?php
defined('_JEXEC') or die('Restricted access');

class TableHistory extends JTable
{
	public $id = null;
	public $order = null;
	public $status = 1;
	public $date = null;

	function __construct(&$db)
	{
		parent::__construct('#__ordermanagementsystem_status_history', 'id', $db);
	}
}

