	<div id="order-topmenu-container"  style="overflow:hidden;">
		<div id="oms-user-balance" class="balance">
		<h3>Пользователь: <?php echo $this->OmsUser->name;?> (<?php echo $this->OmsUser->username;?>).  
		Ваш баланс: <?php echo $this->OmsUser->paymentsByStatus[1]-$this->OmsUser->ordersSum;?> руб.</h3>
		</div>
		<div class="clear"></div>
		<div id="oms-menu-row">
		<div id="oms-menu-orders" class="cell"><a href="index.php?option=com_oms2">Заказы</a></div>
		<div id="oms-menu-payment"  class="cell">
			<a href="index.php?option=com_oms2&task=payments">
			<?php 
			if($this->OmsUser->paymentsByStatus[0] != 0) {
				echo 'Не потвержденых платежей: '.$this->OmsUser->paymentsByStatus[0].' руб.';
 			} else {
				echo 'Платежи';
			}
			?>
			</a>
		</div>
		<div  id="oms-menu-newpayment"  class="cell"><a href="index.php?option=com_oms2&task=deliverys">Доставки</a></div>
		<div  id="oms-menu-neworder"  class="cell"><a href="index.php?option=com_oms2&task=neworder">Внести заказ</a></div>
		<div  id="oms-menu-newpayment"  class="cell"><a href="index.php?option=com_oms2&task=newpay">Внести платеж</a></div>
		<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>	
