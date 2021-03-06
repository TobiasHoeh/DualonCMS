<!--  Produkt Administrations View -->
	<?php
	//LOAD js
	 $this->Html->script('/web_shop/js/admin', false); 
	
	//LOAD style-sheet
	echo $this->Html->css('/web_shop/css/webshop');
	
	//LOAD menu
	echo $this->element('admin_menu', array('contentID' => $contentID));
	?>
	
	<div id="webshop_product_administration">
		<h1><?php echo __d("web_shop", 'Open Orders'); ?></h1>	
		<table>
			<thead>
				<tr>
					<th><?php echo __d("web_shop", 'Order'); ?></th>
					<th><?php echo __d("web_shop", 'Date'); ?></th>
					<th><?php echo __d("web_shop", 'Status'); ?></th>
					<th><?php echo __d("web_shop", 'Action'); ?></th>
				</tr>
			</thead>
			<?php
				if(empty($orders)){
					echo '<tr><td colspan="4">'.__d("web_shop", 'No open orders.').'</td></tr>';
				} else {
						
				//GET orders
				 foreach ($orders as $order): 
					
					if($order['WebshopOrder']['status'] == 0)
						$status = __d("web_shop", 'open');
					else if ($order['WebshopOrder']['status'] == 1)
						$status = __d("web_shop", 'edit');
			
				    echo '<tr>';
				    
				    //GET detailed order info
					echo '<td>';
						echo '<p><strong>'.__d("web_shop", 'Order').':</strong> '.$order['WebshopOrder']['id'].'</p>';
						echo '<p style="margin-bottom:10px"><strong>'.__d("web_shop", 'Customer').':</strong> '.$order['User']['username'].' (ID: '.$order['User']['id'].')</p>';

						echo '<table>';
						echo '<tr>';
						echo '<th>'.__d("web_shop", 'Article').'</th>';
						echo '<th>'.__d("web_shop", 'Quantity').'</th>';
						echo '<th>'.__d("web_shop", 'Unit Price').'</th>';
						echo '<th>'.__d("web_shop", 'Price').'</th>';
						echo '</tr>';
						
						//Attributes
						$pricePerProd = 0;
						$totalPrice = 0;
						
						foreach ((!isset($order['WebshopPosition'])) ? array() : $order['WebshopPosition'] as $position) {
							$pricePerProd = $position['Product']['WebshopProduct']['price'] * $position['count'];
							$totalPrice = $totalPrice + $pricePerProd;
							
							echo '<tr>';
							echo '<td>'.$position['Product']['WebshopProduct']['name'].' (ID: '.$position['Product']['WebshopProduct']['id'].')</td>';
							echo '<td>'.$position['count'].'</td>';
							echo '<td>'.number_format($position['Product']['WebshopProduct']['price'], 2, ',', '.').' '.$position['Product']['WebshopProduct']['currency'].'</td>';
							echo '<td>'.number_format($pricePerProd, 2, ',', '.').' '.$position['Product']['WebshopProduct']['currency'].'</td>';
							echo '</tr>';
						}
						
						echo '<tr>';
						echo '<td style="text-align: right; padding-top:15px" colspan="4"><strong>'.__d("web_shop", 'Subtotal').': '.number_format($totalPrice, 2, ',', '.').' '.$position['Product']['WebshopProduct']['currency'].'</strong></td>';
						echo '</tr>';
						echo '</table>';
					echo '</td>';
					
					
					echo '<td>'.$order['WebshopOrder']['created'].'</td>';
					echo '<td><p style="font-style: italic;">'.$status.'</p></td>';
					echo '<td>'.$this->Html->link(
					    			   $this->Html->image("check.png"), 
					    					array('action' => 'closeOrder', $contentID, $order['WebshopOrder']['id']),
					    					array('escape' => False)
					    			    ).'</td>';
				   echo '</tr>';
				   
				endforeach; 
			}
			?>
		</table>
	</div>