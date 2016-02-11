<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */ 
if(module_quote::can_i('view','Quotes')){
	$rows = array();
	// we hide quote tax if there is none
	$hide_tax = true;
	foreach($quote['taxes'] as $quote_tax){
	    if(isset($quote_tax['percent']) && $quote_tax['percent']>0){
	        $hide_tax=false;
	        break;
	    }
	}
	if($quote['total_sub_amount_unbillable']){
	    $rows[]=array(
	        'label'=> _l('Sub Total:'),
	        'value'=> '<span class="currency">'.dollar($quote['total_sub_amount'] + $quote['total_sub_amount_unbillable'] + $quote['discount_amount'],true,$quote['currency_id']).'</span>'
	    );
	    $rows[]=array(
	        'label'=> _l('Unbillable:'),
	        'value'=> '<span class="currency">'.dollar($quote['total_sub_amount_unbillable'],true,$quote['currency_id']).'</span>'
	    );
	}
	if($quote['discount_type']==_DISCOUNT_TYPE_BEFORE_TAX){
	    $rows[]=array(
	        'label'=>_l('Sub Total:'),
	        'value'=>'<span class="currency">'.dollar($quote['total_sub_amount']+$quote['discount_amount'],true,$quote['currency_id']).'</span>'
	    );
	    if($quote['discount_amount']>0){
	        $rows[]=array(
	            'label'=> htmlspecialchars(_l($quote['discount_description'])),
	            'value'=> '<span class="currency">'.dollar($quote['discount_amount'],true,$quote['currency_id']).'</span>'
	        );
	        $rows[]=array(
	            'label'=>_l('Sub Total:'),
	            'value'=>'<span class="currency">'.dollar($quote['total_sub_amount'],true,$quote['currency_id']).'</span>'
	        );
	    }
	    if(!$hide_tax){
		    if($quote['total_sub_amount'] != $quote['total_sub_amount_taxable']) {
			    $rows[] = array(
				    'label' => _l( 'Taxable Amount:' ),
				    'value' => '<span class="currency">' . dollar( $quote['total_sub_amount_taxable'], true, $quote['currency_id'] ) . '</span>'
			    );
		    }
	        foreach($quote['taxes'] as $quote_tax){
	            $rows[]=array(
	                'label'=>_l('Tax:'),
	                'value'=>'<span class="currency">'.dollar($quote_tax['amount'],true,$quote['currency_id']).'</span>',
	                'extra'=>$quote_tax['name'] . ' = '.number_out($quote_tax['percent'], module_config::c('tax_trim_decimal', 1), module_config::c('tax_decimal_places',module_config::c('currency_decimal_places',2))).'%',
	            );
	        }
	    }

	}else if($quote['discount_type']==_DISCOUNT_TYPE_AFTER_TAX){
	    $rows[]=array(
	        'label'=>_l('Sub Total:'),
	        'value'=>'<span class="currency">'.dollar($quote['total_sub_amount'],true,$quote['currency_id']).'</span>'
	    );
	    if(!$hide_tax){
		    if($quote['total_sub_amount'] != $quote['total_sub_amount_taxable']) {
			    $rows[] = array(
				    'label' => _l( 'Taxable Amount:' ),
				    'value' => '<span class="currency">' . dollar( $quote['total_sub_amount_taxable'], true, $quote['currency_id'] ) . '</span>'
			    );
		    }
	        foreach($quote['taxes'] as $quote_tax){
	            $rows[]=array(
	                'label'=>_l('Tax:'),
	                'value'=>'<span class="currency">'.dollar($quote_tax['amount'],true,$quote['currency_id']).'</span>',
	                'extra'=>$quote_tax['name'] . ' = '.number_out($quote_tax['percent'], module_config::c('tax_trim_decimal', 1), module_config::c('tax_decimal_places',module_config::c('currency_decimal_places',2))).'%',
	            );
	        }
	        $rows[]=array(
	            'label'=>_l('Sub Total:'),
	            'value'=>'<span class="currency">'.dollar($quote['total_sub_amount']+$quote['total_tax'],true,$quote['currency_id']).'</span>',
	        );
	    }
	    if($quote['discount_amount']>0){ //if(($discounts_allowed || $quote['discount_amount']>0) &&  (!($quote_locked && module_security::is_page_editable()) || $quote['discount_amount']>0)){
	        $rows[]=array(
	            'label'=> htmlspecialchars(_l($quote['discount_description'])),
	            'value'=> '<span class="currency">'.dollar($quote['discount_amount'],true,$quote['currency_id']).'</span>'
	        );
	    }
	}

	// any fees?
	if(isset($quote['fees']) && count($quote['fees'])){
	    foreach($quote['fees'] as $fee){
	        $rows[] = array(
	            'label' => $fee['description'],
	            'value'=> '<span class="currency">'.dollar($fee['total'],true,$quote['currency_id']).'</span>'
	        );
	    }
	}

	$rows[]=array(
	    'label'=>_l('Total:'),
	    'value'=>'<span class="currency" style="text-decoration: underline; font-weight: bold;">'.dollar($quote['total_amount'],true,$quote['currency_id']).'</span>',
	);

	?>
	<table class="tableclass tableclass_full">
	<tbody>
	<?php foreach($rows as $row){ ?>
	<tr>
	    <?php if($show_task_numbers){ ?>
	    <td style="width:60%">&nbsp;</td>
	    <?php } ?>
	    <td>

	    </td>
	    <td>
	        <?php echo $row['label'];?>
	    </td>
	    <td>
	        <?php echo $row['value'];?>
	    </td>
	    <td colspan="2">
	        <?php echo isset($row['extra']) ? $row['extra'] : '&nbsp;';?>
	    </td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
<?php }