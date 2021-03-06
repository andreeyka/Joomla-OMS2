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
defined('_JEXEC') or die('Restricted access');

// Component Helper

jimport('joomla.application.component.helper');
jimport('joomla.html.grid');


class oms2Helper
{
	// Your custom code here
	static  $allowed_status=array(1,2,3,4,5);
	
	static function debug($param) {
		echo "<pre>";
		print_r($param);
		echo "</pre>";
		die();;
	}
	
	static function user_name ($id=null) {
		$user = JFactory::getUser();
	}
	
	static function getStatus($status){
		if (in_array($status, self::$allowed_status)) {
			return JText::_('OMS STATUS'.$status);
		}
	}
	
	static function getAllStatus( $current=FALSE){
		foreach (self::$allowed_status as $key) {
			$key<$current ? $disable='1' : $disable='0';  
			$allStatus[]=array('text'=>self::getStatus($key),'value'=>$key,'disable'=>$disable);
		}
		return $allStatus;
	}
	
	static function getUserSelect($name,$options=FALSE,$default=FALSE,$key='value', $value='text') {
		$query = 'SELECT id AS value, username AS text FROM #__users order by username asc';
		$b=JHTML::_('list.genericordering',$query);
		return  JHTML::_('select.genericlist',$b,$name,$options,$key,$value,$default);
	}

	static function getCurrencySelect($name,$default='USD',$options=FALSE,$key='value', $value='text') {
		$query = 'SELECT currency AS value, currency AS text FROM #__ordermanagementsystem_currency order by value asc';
		$dbo = JFactory::getDBO();
		$dbo->setQuery( $query );
		$b=$dbo->loadObjectList();
		return  JHTML::_('select.genericlist',$b,$name,$options,$key,$value,$default);
	}
	
	static function getStatusSelect($name,$default=FALSE,$current=FALSE,$options=FALSE){
		return JHTML::_('select.genericlist', self::getAllStatus($current), $name, $options, 'value', 'text', $default);
	}

	static function getCheckList($data,$name,$options=false){
		$checkList='';
		foreach (self::getAllStatus() as $key) {
			if (isset($data[$key['value']]) and $data[$key['value']]=='1') {
				$chechked =' checked="checked" ';
			} else {
				$chechked=' ';
			}
			$checkList .='<input  id="'.$name.'" name="'.$name.'['.$key['value'].']" type="checkbox" value="1" '.$chechked.' '.$options.'>'.$key['text'].'<br>';		
		}
		return $checkList;
	}
}







?>