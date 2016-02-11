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


class module_product extends module_base{

	public $links;
	public $product_types;
    public $product_id;

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
    public function init(){
		$this->links = array();
		$this->product_types = array();
		$this->module_name = "product";
		$this->module_position = 31;
        $this->version = 2.155;
        // 2.155 - 2015-07-18 - product search
        // 2.154 - 2015-06-28 - started work on product API
        // 2.153 - 2015-02-12 - ui fix and product defaults (tax/bill/type)
        // 2.152 - 2014-01-23 - new quote feature
        // 2.151 - 2013-11-15 - working on new UI
        // 2.15 - 2013-10-02 - bulk product delete and product category import fix
        // 2.149 - 2013-09-08 - faq permission fix
        // 2.148 - 2013-08-07 - css improvement
        // 2.147 - 2013-06-16 - javascript fix
        // 2.146 - 2013-06-07 - further work on product categories
        // 2.145 - 2013-05-28 - further work on product categories
        // 2.144 - 2013-05-28 - started work on product categories
        // 2.143 - 2013-04-27 - css fix for large product list
        // 2.142 - 2013-04-16 - product fix in invoice
        // 2.141 - 2013-04-05 - product support in invoices
        // 2.14 - product import via CSV
        // 2.13 - permission fix
        // 2.12 - product permissions
        // 2.11 - initial release

        hook_add('api_callback_product','module_product::api_filter_product');

        if(module_security::is_logged_in() && self::can_i('view','Products')){

            module_config::register_css('product','product.css');
            module_config::register_js('product','product.js');

            if(isset($_REQUEST['_products_ajax'])){
                switch($_REQUEST['_products_ajax']){
                    case 'products_ajax_search':

//                        $sent = headers_sent($file, $line);
//                        echo 'here';
//                        print_r($sent);
//                        print_r($file);
//                        print_r($line);
                        if(self::$_product_count===false){
                            self::$_product_count = count(self::get_products());
                        }
                        $product_name = isset($_REQUEST['product_name']) ? $_REQUEST['product_name'] :'';
                        if(self::$_product_count>0){


                            $search = array();
                            if(strlen($product_name)>2){
                                $search['general'] = $product_name;
                            }
                            $products = self::get_products($search);
                            if(count($products)>0){
                                // sort products by categories.
                                $products_in_categories = array();
                                foreach($products as $product_id => $product){
                                    if($product['product_category_id'] && $product['product_category_name']){
                                        if(!isset($products_in_categories[$product['product_category_name']])){
                                            $products_in_categories[$product['product_category_name']] = array();

                                        }
                                        $products_in_categories[$product['product_category_name']][] = $product;
                                        unset($products[$product_id]);
                                    }else{

                                    }
                                }
                                $cat_id=1;
                                ?>
                                <ul>
                                    <?php foreach($products_in_categories as $category_name => $cat_products){ ?>
                                        <li>
                                            <a href="#" class="product_category_parent"><?php echo htmlspecialchars($category_name);?></a> (<?php _e('%s products',count($cat_products));?>)
                                            <ul style="display:none;" id="product_category_<?php echo $cat_id++;?>">
                                                <?php foreach($cat_products as $product){ ?>
                                                    <li>
                                                       <a href="#" onclick="return ucm.product.select_product(<?php echo $product['product_id'];?>);"> <?php echo htmlspecialchars($product['name']); ?></a>
                                                    </li>
                                                <?php } ?>
                                            </ul>

                                    <?php } ?>
                                    <?php foreach($products as $product){ ?>
                                    <li>
                                        <a href="#" onclick="return ucm.product.select_product(<?php echo $product['product_id'];?>);"><?php
                                            /*if($product['product_category_name']){
                                                echo htmlspecialchars($product['product_category_name']);
                                                echo ' &raquo; ';
                                            }*/
                                            echo htmlspecialchars($product['name']);?></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <?php
                            }
                        }else if(!strlen($product_name)){
                            _e('Pleae create Products first by going to Settings > Products');
                        }

                        exit;
                    case 'products_ajax_get':
                        $product_id = (int)$_REQUEST['product_id'];
                        if($product_id){
                            $product = self::get_product($product_id);
                        }else{
                            $product = array();
                        }
                        echo json_encode($product);
                        exit;
                }
            }
        }


	}

    public function ajax_search($search_key){
        // return results based on an ajax search.
        $ajax_results = array();
        $search_key = trim($search_key);
        if(strlen($search_key) > module_config::c('search_ajax_min_length',2)){
            //$sql = "SELECT * FROM `"._DB_PREFIX."customer` c WHERE ";
            //$sql .= " c.`customer_name` LIKE %$search_key%";
            //$results = qa($sql);
            $results = $this->get_products(array('general'=>$search_key));
            if(count($results)){
                foreach($results as $result){
                    $match_string = _l('Product: ');
                    $match_string .= _shl($result['name'],$search_key);
                    $ajax_results [] = '<a href="'.$this->link_open($result['product_id']) . '">' . $match_string . '</a>';
                    //$ajax_results [] = $this->link_open($result['customer_id'],true);
                }
            }
        }
        return $ajax_results;
    }


    public function pre_menu(){

		if($this->can_i('view','Products') && $this->can_i('edit','Products')){

            // how many products are there?
            $link_name = _l('Products');

            if(module_config::can_i('view','Settings')) {
                $this->links['products'] = array(
                    "name"                => $link_name,
                    "p"                   => "product_settings",
                    "args"                => array( 'product_id' => false ),
                    'holder_module'       => 'config', // which parent module this link will sit under.
                    'holder_module_page'  => 'config_admin',  // which page this link will be automatically added to.
                    'menu_include_parent' => 0,
                );
            }else{
                $this->links['products'] = array(
                    "name"                => $link_name,
                    "p"                   => "product_settings",
                    "args"                => array( 'product_id' => false ),
                );
            }
		}

    }

    /** static stuff */

    
     public static function link_generate($product_id=false,$options=array(),$link_options=array()){
        // we accept link options from a bubbled link call.
        // so we have to prepent our options to the start of the link_options array incase
        // anything bubbled up to this method.
        // build our options into the $options variable and array_unshift this onto the link_options at the end.
        $key = 'product_id'; // the key we look for in data arrays, on in _REQUEST variables. for sub link building.

        // we check if we're bubbling from a sub link, and find the item id from a sub link
        if(${$key} === false && $link_options){
            foreach($link_options as $link_option){
                if(isset($link_option['data']) && isset($link_option['data'][$key])){
                    ${$key} = $link_option['data'][$key];
                    break;
                }
            }
            if(!${$key} && isset($_REQUEST[$key])){
                ${$key} = $_REQUEST[$key];
            }
        }

        if(!isset($options['type']))$options['type']='product';
        if(!isset($options['page']))$options['page']='product_settings';
        if(!isset($options['arguments'])){
            $options['arguments'] = array();
        }
        $options['arguments']['product_id'] = $product_id;
        $options['module'] = 'product';

         if($options['page']=='product_admin'||$options['page']=='product_admin_category'){

            array_unshift($link_options,$options);
             if($options['page']=='product_admin_category'){
                $options['data'] = self::get_product_category($product_id);
                $options['data']['name'] = $options['data']['product_category_name'];
            }
            $options['page']='product_settings';
            // bubble back onto ourselves for the link.
            return self::link_generate($product_id,$options,$link_options);
         }
        // grab the data for this particular link, so that any parent bubbled link_generate() methods
        // can access data from a sub item (eg: an id)

        if(isset($options['full']) && $options['full']){
            // only hit database if we need to print a full link with the name in it.
            if(!isset($options['data']) || !$options['data']){
                if((int)$product_id>0){
                    $data = self::get_product($product_id);
                }else{
                    $data = array();
                    return _l('N/A');
                }
                $options['data'] = $data;
            }else{
                $data = $options['data'];
            }
            // what text should we display in this link?
            $options['text'] = $data['name'];
        }
        $options['text'] = isset($options['text']) ? htmlspecialchars($options['text']) : '';
        // generate the arguments for this link
        $options['arguments'] = array(
            'product_id' => $product_id,
        );
        // generate the path (module & page) for this link
        $options['module'] = 'product';

        // append this to our link options array, which is eventually passed to the
        // global link generate function which takes all these arguments and builds a link out of them.

         if(!self::can_i('view','Products')){
            if(!isset($options['full']) || !$options['full']){
                return '#';
            }else{
                return isset($options['text']) ? $options['text'] : _l('N/A');
            }
        }

        // optionally bubble this link up to a parent link_generate() method, so we can nest modules easily
        // change this variable to the one we are going to bubble up to:
        $bubble_to_module = false;
        $bubble_to_module = array(
            'module' => 'config',
            'argument' => 'product_id',
        );
        array_unshift($link_options,$options);
        if($bubble_to_module){
            global $plugins;
            return $plugins[$bubble_to_module['module']]->link_generate(false,array(),$link_options);
        }else{
            // return the link as-is, no more bubbling or anything.
            // pass this off to the global link_generate() function
            return link_generate($link_options);
        }
    }


	public static function link_open($product_id,$full=false,$data=array()){
		return self::link_generate($product_id,array('full'=>$full,'data'=>$data,'page'=>'product_admin'));
	}

	public static function link_open_category($product_category_id,$full=false,$data=array()){
		return self::link_generate($product_category_id,array('full'=>$full,'data'=>$data,'page'=>'product_admin_category','arguments'=>array('product_category_id'=>$product_category_id)));
	}



	public static function get_products($search=array()){

        $sql = "SELECT * FROM `"._DB_PREFIX."product` p ";
        $sql .= " LEFT JOIN `"._DB_PREFIX."product_category` pc USING (product_category_id) ";
        $sql .= " WHERE 1 ";
        if(isset($search['general'])&&strlen(trim($search['general']))){
            $sql .= " AND ( p.name LIKE '%".mysql_real_escape_string($search['general'])."%'";
            $sql .= " OR p.description LIKE '%".mysql_real_escape_string($search['general'])."%'";
            $sql .= " OR pc.product_category_name LIKE '%".mysql_real_escape_string($search['general'])."%'";
            $sql .= " )";
        }
        if(isset($search['name'])&&strlen(trim($search['name']))){
            $sql .= " AND p.name LIKE '%".mysql_real_escape_string($search['name'])."%'";
        }
        if(isset($search['description'])&&strlen(trim($search['description']))){
            $sql .= " AND p.description LIKE '%".mysql_real_escape_string($search['description'])."%'";
        }
        if(isset($search['product_category_name'])&&strlen(trim($search['product_category_name']))){
            $sql .= " AND pc.product_category_name LIKE '%".mysql_real_escape_string($search['product_category_name'])."%'";
        }
        if(isset($search['product_id']) && (int)$search['product_id']>0){
            $sql .= " AND p.product_id = ".(int)$search['product_id'];
        }
        if(isset($search['product_category_id']) && (int)$search['product_id']>0){
            $sql .= " AND p.product_category_id = ".(int)$search['product_category_id'];
        }
        $sql .= " ORDER BY pc.product_category_name ASC, p.name ASC";
        return qa($sql);

		//return get_multiple("product",$search,"product_id","fuzzy","name");
	}



	public static function get_product($product_id){
        $product = get_single('product','product_id',$product_id);
        //echo $product_id;print_r($product);exit;
        if(!$product){
            $product = array(
                'name'=>'',
                'product_category_id'=>'',
                'product_category_name'=>'',
                'amount'=>'',
                'quantity'=>'',
                'currency_id'=>'',
                'description'=>'',
            );
        }
        if($product['product_category_id']){
            $product_category = self::get_product_category($product['product_category_id']);
            $product['product_category_name'] = $product_category['product_category_name'];
        }
        return $product;
	}

    public static function get_product_categories($search=array()){
		return get_multiple("product_category",$search,"product_category_id","fuzzy","product_category_name");
    }
	public static function get_product_category($product_category_id){
        $product_category = get_single('product_category','product_category_id',$product_category_id);
        if(!$product_category){
            $product_category = array(
                'product_category_id'=>'',
                'product_category_name'=>'',
            );
        }
        return $product_category;
	}


    
	public function process(){
		if("save_product" == $_REQUEST['_process']){
            if(isset($_REQUEST['butt_del']) && $_REQUEST['butt_del'] && $_REQUEST['product_id']){
                $data = self::get_product($_REQUEST['product_id']);
                if(module_form::confirm_delete('product_id',_l("Really delete product: %s",$data['name']),self::link_open($_REQUEST['product_id']))){
                    $this->delete_product($_REQUEST['product_id']);
                    set_message("Product deleted successfully");
                    redirect_browser(self::link_open(false));
                }
            }
			$product_id = $this->save_product($_REQUEST['product_id'],$_POST);
			set_message("Product saved successfully");
			redirect_browser(self::link_open($product_id));
		}else if("save_product_category" == $_REQUEST['_process']){
            if(isset($_REQUEST['butt_del']) && $_REQUEST['butt_del'] && $_REQUEST['product_category_id']){
                $data = self::get_product_category($_REQUEST['product_category_id']);
                if(module_form::confirm_delete('product_category_id',_l("Really delete product category: %s",$data['product_category_name']),self::link_open_category($_REQUEST['product_category_id']))){
                    $this->delete_product_category($_REQUEST['product_category_id']);
                    set_message("Product category deleted successfully");
                    redirect_browser(self::link_open_category(false));
                }
            }
			$product_category_id = $this->save_product_category($_REQUEST['product_category_id'],$_POST);
			set_message("Product category saved successfully");
			redirect_browser(self::link_open_category($product_category_id));
		}
	}


	public function save_product($product_id,$data){
        if(isset($data['default_billable']) && !isset($data['billable'])){
            $data['billable'] = 0;
        }
        if(isset($data['default_taxable']) && !isset($data['taxable'])){
            $data['taxable'] = 0;
        }
		$product_id = update_insert("product_id",$product_id,"product",$data);
        module_extra::save_extras('product','product_id',$product_id);
		return $product_id;
	}
	public function save_product_category($product_category_id,$data){
		$product_category_id = update_insert("product_category_id",$product_category_id,"product_category",$data);
        //echo $product_category_id;print_r($data);exit;
		return $product_category_id;
	}


	public function delete_product($product_id){
		$product_id=(int)$product_id;
        $product = self::get_product($product_id);
        if($product && $product['product_id'] == $product_id){
            $sql = "DELETE FROM "._DB_PREFIX."product WHERE product_id = '".$product_id."' LIMIT 1";
            query($sql);
            module_extra::delete_extras('product','product_id',$product_id);
        }
	}
	public function delete_product_category($product_category_id){
		$product_category_id=(int)$product_category_id;
        delete_from_db('product_category','product_category_id',$product_category_id);
        $sql = "UPDATE `"._DB_PREFIX."product` SET product_category_id = 0 WHERE product_category_id = ".(int)$product_category_id;
        query($sql);
	}
    
    public static function bulk_handle_delete(){
        if(isset($_REQUEST['bulk_action']) && isset($_REQUEST['bulk_action']['delete']) && $_REQUEST['bulk_action']['delete'] == 'yes'){
            // confirm deletion of these tickets:
            $product_ids = isset($_REQUEST['bulk_operation']) && is_array($_REQUEST['bulk_operation']) ? $_REQUEST['bulk_operation'] : array();
            foreach($product_ids as $product_id => $k){
                if($k != 'yes'){
                    unset($product_ids[$product_id]);
                }else{
                    $product_ids[$product_id] = self::link_open($product_id,true);
                }
            }
            if(count($product_ids) > 0){
                if(module_form::confirm_delete('product_id',"Really delete products: ".implode(', ',$product_ids),self::link_open(false))){
                    foreach($product_ids as $product_id => $product_number){
                        self::delete_product($product_id);
                    }
                    set_message(_l("%s products deleted successfully",count($product_ids)));
                    redirect_browser(self::link_open(false));
                }
            }
        }
    }
    

    private static $_product_count = false;
    public static function print_quote_task_dropdown($quote_task_id=false,$quote_task_data=array()){
        if(self::can_i('view','Products')){
        ?>
        <span style="margin: 0 0 0 -23px; width: 20px; padding: 0; display: inline-block">
            <a href="#" onclick="return ucm.product.do_dropdown('<?php echo $quote_task_id;?>',this);" class="ui-icon ui-icon-arrowthick-1-s">Products</a>
            <input type="hidden" name="quote_task[<?php echo $quote_task_id;?>][product_id]" id="task_product_id_<?php echo $quote_task_id;?>" class="no_permissions" value="<?php echo isset($quote_task_data['product_id']) ? (int)$quote_task_data['product_id'] : '0';?>">
        </span>
        <?php
        }
    }
    public static function print_job_task_dropdown($task_id=false,$task_data=array()){
        if(self::can_i('view','Products')){
        ?>
        <span style="margin: 0 0 0 -23px; width: 20px; padding: 0; display: inline-block">
            <a href="#" onclick="return ucm.product.do_dropdown('<?php echo $task_id;?>',this);" class="ui-icon ui-icon-arrowthick-1-s">Products</a>
            <input type="hidden" name="job_task[<?php echo $task_id;?>][product_id]" id="task_product_id_<?php echo $task_id;?>" class="no_permissions" value="<?php echo isset($task_data['product_id']) ? (int)$task_data['product_id'] : '0';?>">
        </span>
        <?php
        }
    }
    public static function print_invoice_task_dropdown($task_id=false,$task_data=array()){
        if(self::can_i('view','Products')){
        ?>
        <span style="margin: 0 0 0 -23px; width: 20px; padding: 0; display: inline-block">
            <a href="#" onclick="return ucm.product.do_dropdown('<?php echo $task_id;?>',this);" class="ui-icon ui-icon-arrowthick-1-s">Products</a>
            <input type="hidden" name="invoice_invoice_item[<?php echo $task_id;?>][product_id]" id="invoice_product_id_<?php echo $task_id;?>" class="no_permissions" value="<?php echo isset($task_data['product_id']) ? (int)$task_data['product_id'] : '0';?>">
        </span>
        <?php
        }
    }
    
    public static function handle_import($data,$add_to_group){

        // woo! we're doing an import.

        // our first loop we go through and find matching products by their "product_name" (required field)
        // and then we assign that product_id to the import data.
        // our second loop through if there is a product_id we overwrite that existing product with the import data (ignoring blanks).
        // if there is no product id we create a new product record :) awesome.

        foreach($data as $rowid => $row){
            if(!isset($row['name']) || !trim($row['name'])){
                unset($data[$rowid]);
                continue;
            }
            if(!isset($row['product_id']) || !$row['product_id']){
                $data[$rowid]['product_id'] = 0;
            }
        }

        // now save the data.
        $count = 0;
        foreach($data as $rowid => $row){
            $row['product_id'] = update_insert('product_id',$row['product_id'],'product',$row);
            if($row['product_id']){
                // is there a category?
                if(isset($row['category_name']) && strlen(trim($row['category_name']))){
                    // find this category, if none exists then create it.
                    $product_category = get_single('product_category','product_category_name',trim($row['category_name']));
                    if(!$product_category){
                        $product_category = array(
                            'product_category_name'=>trim($row['category_name']),
                        );
                        $product_category['product_category_id'] = update_insert('product_category_id',false,'product_category',$product_category);
                    }
                    if(isset($product_category['product_category_id']) && $product_category['product_category_id']){
                        $row['product_id'] = update_insert('product_id',$row['product_id'],'product',array(
                            'product_category_id' => $product_category['product_category_id'],
                        ));
                    }
                }
                $count++;
            }
        }
        return $count;

    }


    public static function api_filter_product($hook, $response, $endpoint, $method)
    {
        $response['product'] = true;
        switch ($method) {
            case 'list':
                $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : array();
                $response['products'] = module_product::get_products($search);
                break;
        }
        return $response;
    }


    public function get_upgrade_sql(){
        $sql = '';
        $fields = get_fields('task');
        if(!isset($fields['product_id'])){
            $sql .= 'ALTER TABLE `'._DB_PREFIX.'task` ADD `product_id` INT(11) NOT NULL DEFAULT \'0\' AFTER `task_order`;';
        }
        $fields = get_fields('product');
        if(!isset($fields['default_task_type'])){
            $sql .= 'ALTER TABLE `'._DB_PREFIX.'product` ADD `default_task_type` INT(11) NOT NULL DEFAULT \'-1\' AFTER `currency_id`;';
        }
        if(!isset($fields['billable'])){
            $sql .= 'ALTER TABLE `'._DB_PREFIX.'product` ADD `billable` INT(11) NOT NULL DEFAULT \'1\' AFTER `default_task_type`;';
        }
        if(!isset($fields['taxable'])){
            $sql .= 'ALTER TABLE `'._DB_PREFIX.'product` ADD `taxable` INT(11) NOT NULL DEFAULT \'1\' AFTER `billable`;';
        }
        if(!$this->db_table_exists('product_category')){
            $sql .= 'CREATE TABLE `'._DB_PREFIX.'product_category` (
  `product_category_id` int(11) NOT NULL auto_increment,
  `product_category_name` varchar(255) NOT NULL DEFAULT \'\',
  `date_created` date NOT NULL,
  `date_updated` date NULL,
  PRIMARY KEY  (`product_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;';
        }
        return $sql;
    }
    public function get_install_sql(){
        ob_start();
        ?>

CREATE TABLE `<?php echo _DB_PREFIX; ?>product` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_category_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` TEXT NOT NULL DEFAULT '',
  `quantity` double(10,2) NOT NULL DEFAULT '0',
  `amount` double(10,2) NOT NULL DEFAULT '0',
  `currency_id` INT NOT NULL DEFAULT '1',
  `default_task_type` INT NOT NULL DEFAULT '-1',
  `billable` INT NOT NULL DEFAULT '1',
  `taxable` INT NOT NULL DEFAULT '1',
  `date_created` date NOT NULL,
  `date_updated` date NULL,
  PRIMARY KEY  (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE `<?php echo _DB_PREFIX; ?>product_category` (
  `product_category_id` int(11) NOT NULL auto_increment,
  `product_category_name` varchar(255) NOT NULL DEFAULT '',
  `date_created` date NOT NULL,
  `date_updated` date NULL,
  PRIMARY KEY  (`product_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


<?php
        return ob_get_clean();
    }


}
