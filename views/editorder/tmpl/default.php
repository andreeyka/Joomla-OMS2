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

// Your custom code here
$order=$this->Order;
#oms2Helper::debug($order);

?>
<form action="index.php?option=com_oms2&view=saveorder&id=<?php echo $order->id;?>" method="post" name="adminForm" id="adminForm">
<div style="overflow:hidden;" id="order-container">
	
    
    <div style="overflow:hidden;">
    	<div style="float: left; overflow:hidden;">
    		<h3>Наименование</h3>
    		<input id="order-item" size="50" name="order-item" value="<?php echo $order->item;?>">
    	</div>
    	<div style="float: left; overflow:hidden;">
    		<h3>Ссылка</h3>
    		<input id="order-url" size="50" name="order-url" value="<?php echo $order->item_url;?>">
    	</div>
    </div>
    <div style="overflow:hidden;">
    	<div style="float:left;">
    		<h3>Артикул</h3> 
    		<input id="order-article" size="10" name="order-article" value="<?php echo $order->article;?>">
    	</div>
    	<div style="float:left;">
    		<h3>Размер</h3>
    		<input id="order-size" size="4" name="order-size" value="<?php echo $order->size;?>">
    	</div>
    	<div style="float:left;">
    		<h3>Цвет</h3>
    		<input id="order-color" size="10" name="order-color" value="<?php echo $order->color;?>">
    	</div>
    	<div style="float:left;">
    		<h3>Кол-во</h3>
    		<input id="order-amount" name="order-amount" size="3" value="<?php echo $order->amount;?>">
    	</div>
    	<div style="float:left;">
    		<h3>Цена</h3>
    		<input id="order-price" name="order-price" size="5" value="<?php echo $order->price;?>">
    	</div>
    	<div style="float:left;">
    		<h3>Валюта</h3>
    		<input id="order-currecy" name="order-currency" size="5" value="<?php echo $order->currency;?>">
    	</div>
    	<div style="float:left;">
    		<h3>Курс</h3>
    		<input id="order-currecy-rate" name="order-currency-rate" size="5" value="<?php echo $order->currency_rate;?>">
    	</div>
    	<div style="float:left;">
    		<h3>Налог</h3>
    		<input id="order-tax" name="order-tax" size="5" value="<?php echo $order->tax;?>">
    	</div>
    	<div style="float:left;">
    		<h3>Коммисия	</h3>
    		<input id="order-interest" name="order-interest" size="5" value="<?php echo $order->interest;?>">
    	</div>
    </div>
    <div style=""><h3>Комментарий</h3>
    	<input id="item-form" size="50" name="order-notes" value="<?php echo $order->notes;?>">
    </div>
    <input id="form-submit" type="submit">
</div>
	<input type="hidden" name="option" value="com_oms2" />
	<input type="hidden" name="id" value="<?php echo $order->id;?>" />
	<input type="hidden" name="view" value="saveorder" />
	<?php echo JHTML::_('form.token'); ?>

</form>