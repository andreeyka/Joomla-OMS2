	<div id="order-topmenu-container"  style="overflow:hidden;">
		<div style="float:left;">Ваш баланс: <?php echo $this->OmsUser->paymentsByStatus[1]-$this->OmsUser->ordersSum;?> руб.</div>
		<div style="float:left;"><a href="index.php?option=com_oms2">Заказы</a></div>
		<div style="float:left;">
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
		
		<div style="float:left;"><a href="index.php?option=com_oms2&task=neworder">Внести заказ</a></div>
		<div style="float:left;"><a href="index.php?option=com_oms2&task=newpay">Внести платеж</a></div>
	</div>
