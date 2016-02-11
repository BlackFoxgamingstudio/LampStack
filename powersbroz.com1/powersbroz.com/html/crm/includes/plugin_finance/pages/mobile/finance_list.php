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

if(isset($_REQUEST['link_go']) && $_REQUEST['link_go'] == 'go'){
    module_finance::handle_link_transactions();
}

$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : array();
$recent_transactions = module_finance::get_finances($search);

$total_debit = $total_credit = 0;
foreach($recent_transactions as $recent_transaction){
    $total_credit += $recent_transaction['credit'];
    $total_debit += $recent_transaction['debit'];
}


if(!module_finance::can_i('view','Finance')){
    redirect_browser(_BASE_HREF);
}



if(class_exists('module_table_sort',false)){
    module_table_sort::enable_pagination_hook(
    // pass in the sortable options.
    /*="sort_date"><?php echo _l('Date'); ?></th>
                    <th id="sort_name"><?php echo _l('Name'); ?></th>
                    <th><?php echo _l('Description'); ?></th>
                    <th id="sort_credit"><?php echo _l('Credit'); ?></th>
                    <th id="sort_debit"><?php echo _l('Debit'); ?></th>
                    <th id="sort_account"><?p*/
        array(
            'table_id' => 'finance_list',
            'sortable'=>array(
                // these are the "ID" values of the <th> in our table.
                // we use jquery to add the up/down arrows after page loads.
                'sort_date' => array(
                    'field' => 'transaction_date',
                    'current' => 2, // 1 asc, 2 desc
                ),
                'sort_name' => array(
                    'field' => 'name',
                ),
                'sort_credit' => array(
                    'field' => 'credit',
                ),
                'sort_debit' => array(
                    'field' => 'debit',
                ),
            ),
        )
    );
}


// hack to add a "export" option to the pagination results.
if(class_exists('module_import_export',false) && module_finance::can_i('view','Export Finance')){
    module_import_export::enable_pagination_hook(
    // what fields do we pass to the import_export module from this customers?
        array(
            'name' => 'Finance Export',
            'parent_form' => 'finance_form',
            'fields'=>array(
                'Date' => 'transaction_date',
                'Name' => 'name',
                'URL' => 'url',
                'Description' => 'description',
                'Credit' => 'credit',
                'Debit' => 'debit',
                'Account' => 'account_name',
                'Categories' => 'categories',
            ),
        )
    );
}

$recent_transactions_pagination = process_pagination($recent_transactions);

$upcoming_finances = array();

?>




            <h2>
            <?php if(module_finance::can_i('create','Finance')){ ?>
                <span class="button">
                    <?php echo create_link("Add New","add",module_finance::link_open('new')); ?>
                </span>
            <?php } ?>
                <?php echo _l('Financial Transactions'); ?>
            </h2>


            <form action="" method="post" id="finance_form">

            <table class="search_bar" width="100%">
                <tr>
                    <th width="70"><?php _e('Filter By:'); ?></th>
                    <td width="40">
                        <?php _e('Name/Description:');?>
                    </td>
                    <td>
                        <input type="text" name="search[generic]" value="<?php echo isset($search['generic'])?htmlspecialchars($search['generic']):''; ?>" size="20">
                    </td>
                    <td width="20">
                        <?php echo _l('Date:');?>
                    </td>
                    <td>
                        <input type="text" name="search[date_from]" value="<?php echo isset($search['date_from'])?htmlspecialchars($search['date_from']):''; ?>" class="date_field">
                        <?php _e('to');?>
                        <input type="text" name="search[date_to]" value="<?php echo isset($search['date_to'])?htmlspecialchars($search['date_to']):''; ?>" class="date_field">
                    </td>
                    <td width="30">
                        <?php _e('Account:');?>
                    </td>
                    <td>
                        <?php echo print_select_box(module_finance::get_accounts(),'search[finance_account_id]',isset($search['finance_account_id'])?$search['finance_account_id']:'','',true,'name'); ?>
                    </td>
                    <td width="30">
                        <?php _e('Category:');?>
                    </td>
                    <td>
                        <?php echo print_select_box(module_finance::get_categories(),'search[finance_category_id]',isset($search['finance_category_id'])?$search['finance_category_id']:'','',true,'name'); ?>
                    </td>
                    <td class="search_action">
                        <?php echo create_link("Reset","reset",module_finance::link_open(false)); ?>
                        <?php echo create_link("Search","submit"); ?>
                    </td>
                </tr>
            </table>
            </form>

            <script type="text/javascript">
                function link_it(t){
                    // select all others of this same credit/debit price
                    $('.link_box').show();
                    $('.link_box').each(function(){
                        if(t && $(this).val() != t){
                            $(this).hide();
                        }
                    });
                }
                $(function(){
                    $('.link_box').each(function(){
                        $(this).change(function(){
                            link_it( $(this)[0].checked ? $(this).val() : false );
                        });
                        $(this).mouseup(function(){
                            link_it( $(this)[0].checked ? $(this).val() : false );
                        });
                    });
                });
            </script>

            <?php echo $recent_transactions_pagination['summary'];?>


            <table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows">
                <thead>
                <tr class="title">
                    <th id="sort_date"><?php echo _l('Date'); ?></th>
                    <th id="sort_name"><?php echo _l('Name'); ?></th>
                    <th><?php echo _l('Description'); ?></th>
                    <th id="sort_credit"><?php echo _l('Credit'); ?></th>
                    <th id="sort_debit"><?php echo _l('Debit'); ?></th>
                    <th id="sort_account"><?php echo _l('Account'); ?></th>
                    <th width="90"><?php echo _l('Categories'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $c=0;
                $displayed_finance_ids = array(); // keep track of which parent / child finance ids have been displayed.
                $displayed_invoice_payment_ids = array(); // keep track of which parent / child invoice_payment ids have been displayed.

                foreach($recent_transactions_pagination['rows'] as $finance){

                    $c++;

                    $link_rowspan = 1;
                    $description_rowspan = 1;
                    $shared_description = $finance['description'];
                    if(isset($finance['finance_id']) && $finance['finance_id']){
                        $finance_record = module_finance::get_finance($finance['finance_id']);
                        if(count($finance_record['linked_invoice_payments'])){
                            $link_rowspan += count($finance_record['linked_invoice_payments']);
                        }
                        if(count($finance_record['linked_finances'])){
                            $link_rowspan += count($finance_record['linked_finances']);
                        }
                        // a little hack to find if we use a shared description.
                        if($link_rowspan>1 && count($finance_record['linked_invoice_payments'])){
                            foreach($finance_record['linked_invoice_payments'] as $this_finance_record){
                                if(strlen(trim($shared_description)) && strlen(trim(strip_tags($this_finance_record['description']))) > 0 && trim(strip_tags($shared_description)) != trim(strip_tags($this_finance_record['description']))){
                                    $description_rowspan = 1;
                                    $shared_description = $finance['description'];
                                    break;
                                }else{
                                    $description_rowspan++;
                                    $shared_description = $this_finance_record['description'];
                                }
                            }
                            if($description_rowspan>1){
                                foreach($finance_record['linked_finances'] as $this_finance_record){
                                    if(strlen(trim($shared_description)) && strlen(trim(strip_tags($this_finance_record['description']))) > 0 && trim(strip_tags($shared_description)) != trim(strip_tags($this_finance_record['description']))){
                                        $description_rowspan = 1;
                                        $shared_description = $finance['description'];
                                        break;
                                    }else{
                                        $description_rowspan++;
                                    }
                                }
                            }
                        }
                    }
                    for($x=0;$x<$link_rowspan;$x++){
                        if($x>0){
                            if(count($finance_record['linked_finances'])){
                                $finance = array_shift($finance_record['linked_finances']);
                            }else if(count($finance_record['linked_invoice_payments'])){
                                $finance = array_shift($finance_record['linked_invoice_payments']);
                            }
                        }
                        ?>
                        <tr class="<?php echo ($c%2)?"odd":"even"; ?>">
                            <?php if($link_rowspan > 1){
                                ?>
                                <td>
                                    <?php
                                    if($x==0){
                                        // loop over all finance records and print the dates
                                        // only print dates if they differ from the others.
                                        $dates = array();
                                        $dates[print_date($finance['transaction_date'])]=true;
                                        foreach($finance_record['linked_finances'] as $f){
                                            $dates[print_date($f['transaction_date'])]=true;
                                        }
                                        foreach($finance_record['linked_invoice_payments'] as $f){
                                            $dates[print_date($f['transaction_date'])]=true;
                                        }
                                        echo implode(', ',array_keys($dates));
                                    }
                                    ?>
                                </td>
                                <?php
                            }else{
                                // just display the normal date:
                                ?>
                                <td>
                                    <?php echo print_date($finance['transaction_date']); ?>
                                </td>
                            <?php } ?>
                            <td>
                                <?php if($x>0 && isset($finance['invoice_id'])){
                                    // skip this link as it will promt to create a new entry
                                }else{ ?>
                                    <a href="<?php echo $finance['url'];?>"><?php echo !trim($finance['name']) ? 'N/A' :    htmlspecialchars($finance['name']);?></a>
                                <?php } ?>
                            </td>
                            <?php if($description_rowspan>1){ ?>
                                <?php if($x==0){ ?>
                                    <td rowspan="<?php echo $description_rowspan;?>">
                                        <?php echo $shared_description; ?>
                                    </td>
                                <?php } ?>
                            <?php }else{ ?>
                                <td>
                                    <?php echo $finance['description']; ?>
                                </td>
                            <?php } ?>
                            <?php if($x==0){ ?>
                            <td rowspan="<?php echo $link_rowspan;?>">
                                <span class="success_text"><?php echo $finance['credit'] > 0 ? '+'.dollar($finance['credit'],true,$finance['currency_id']) : '';?></span>
                            </td>
                            <td rowspan="<?php echo $link_rowspan;?>">
                                <span class="error_text"><?php echo $finance['debit'] > 0 ? '-'.dollar($finance['debit'],true,$finance['currency_id']) : '';?></span>
                            </td>
                            <?php } ?>
                            <td>
                                <?php echo htmlspecialchars($finance['account_name']);?>
                            </td>
                            <td>
                                <?php echo $finance['categories'];?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
              </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" align="right">
                        <?php /*_e('Total for all search results:'); */?>
                    </td>
                    <td>
                        <!--<strong><?php /*echo dollar($total_credit);*/?></strong>-->
                    </td>
                    <td>
                        <!--<strong><?php /*echo dollar($total_debit);*/?></strong>-->
                    </td>
                    <td colspan="2">

                    </td>
                </tr>
                </tfoot>
            </table>
                <?php echo $recent_transactions_pagination['links'];?>


<p>
    <?php echo _l('Totals for all %s search results: %s Credit, %s Debit',count($recent_transactions),dollar($total_credit),dollar($total_debit)); ?>
</p>