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

jimport( 'joomla.application.component.view' );

class oms2ViewPayments extends Jview
{
	function display($tpl = null){
		$this->assign('OmsUser', $this->get('OmsUserPayments'));
		parent::display($tpl);
	}

}
?>