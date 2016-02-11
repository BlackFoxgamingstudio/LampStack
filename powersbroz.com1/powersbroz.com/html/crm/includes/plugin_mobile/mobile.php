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

class module_mobile extends module_base{
	
	var $links;

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	function init(){
		$this->links = array();
		$this->module_name = "mobile";
		$this->module_position = 8882;

        $this->version = 2.241;
        // 2.2 - started with custom mobile themed files. in conjunction with recent theme plugin update.
        // 2.22 - added ticket layout
        // 2.221 - ticket layout fix.
        // 2.222 - some mobile fixes
        // 2.223 - horizontal scrolling
        // 2.224 - better horizontal scrolling
        // 2.225 - fixing for invoice layout
        // 2.226 - large page memory limit fix
        // 2.227 - some mobile layout fixes
        // 2.228 - new version of jQuery + jQuery Mobile
        // 2.229 - mobile bug fix
        // 2.23 - mobile layout improvements
        // 2.24 - mobile_enabled option, use this to disable built in mobile layout
        // 2.241 - 2013-07-29 - fix for missing "add" buttons

        if(get_display_mode()=='mobile'){
            module_config::register_css('mobile','mobile.css');
        }

	}

    public static function render_start($page_title,$page){
        $display_mode='mobile';
        ob_start();
    }
    public static function render_stop($page_title,$page){

        $display_mode='mobile';
        $mobile_content = ob_get_clean();
        $new_content = '';

        @ini_set('pcre.backtrack_limit','500K');

        // remove our new <div class="content_box_wheader"> ... </div> stuff from the output
        if(module_config::c('mobile_div_hack',0)){
            $mobile_content2 = preg_replace('#<div class="content_box_wheader">(.*)</div>#imsU','\1',$mobile_content);
            if($mobile_content2){
                $mobile_content=$mobile_content2;
                unset($mobile_content2);
            }
        }

        // strip out all the header blocks and turn them into collabsable components.
        $first = module_config::c('mobile_show_first',0);
        foreach(array(
                    '#<h([234])[^>]*>([^<]+)</h\1>[^<]*(<table.*</table>)#imsU',
                    '#<h([234])[^>]*>([^<]+)</h\1>[^<]*(<div.*</div>)#imsU',
                    '#<h([234])[^>]*>(.+)</h\1>[^<]*(<mobile.*</mobile>)#imsU',
                    '#<h([234])[^>]*>(.+)</h\1>[^<]*(<table.*</table>)#imsU',
                    '#<h([234])[^>]*>(.+)</h\1>[^<]*(<div.*</div>)#imsU',
                ) as $search){
            if(preg_match_all($search,$mobile_content,$matches)){
                //$new_content .= '<div data-role="collapsible-set">';
                //print_r($matches);
                foreach($matches[0] as $key=>$val){
                    //echo "||||".htmlspecialchars($matches[2][$key])."||||<br>";
                    if(preg_match('#<h#',$matches[2][$key]))continue;
                    if(preg_match('#<a.*</a>#imsU',$matches[2][$key],$button_matches)){
                        $matches[2][$key] = str_replace($button_matches[0],'',$matches[2][$key]);
                        $matches[3][$key] = $button_matches[0] . $matches[3][$key];
                    }
                    $new_content .= '<div data-role="collapsible"'.($first ? ' data-collapsed="false"' : '').'>';
                    $new_content .= '<h3>'.$matches[2][$key].'</h3>';
                    $new_content .= $matches[3][$key];
                    $new_content .= '</div>';
                    $mobile_content = str_replace($val,'',$mobile_content);
                    $first = false;
                }

                //$new_content .= '</div>';

            }
        }
        /*if(preg_match_all('#<h([234])>(.+)</h\1>[^<]*<div class="content_box_wheader">[^<]*(<table.*</table>)[^<]*</div>#imsU',$mobile_content,$matches)){

            //$new_content .= '<div data-role="collapsible-set">';

            $first = true;
            foreach($matches[0] as $key=>$val){
                //echo "||||".htmlspecialchars($matches[2][$key])."||||<br>";
                if(preg_match('#<h#',$matches[2][$key]))continue;
                if(preg_match('#<a.*</a>#imsU',$matches[2][$key],$button_matches)){
                    $matches[2][$key] = str_replace($button_matches[0],'',$matches[2][$key]);
                    $matches[3][$key] = $button_matches[0] . $matches[3][$key];
                }
                $new_content .= '<div data-role="collapsible"'.($first ? ' data-collapsed="false"' : '').'>';
                $new_content .= '<h3>'.$matches[2][$key].'</h3>';
                $new_content .= $matches[3][$key];
                $new_content .= '</div>';
                $mobile_content = str_replace($val,'',$mobile_content);
                $first = false;
            }

            //$new_content .= '</div>';
        }*/


        // todo: format the search bar:
        // for now we just remove it
        $mobile_content = preg_replace('#<table class="search_bar".*</table>#imsU','',$mobile_content);

        // format the table listings.
        $mobile_content2 = preg_replace('#<table[^>]*tableclass_rows[^>]*>.*</table>#imsU','<div class="iscroll">\0</div>',$mobile_content);
        if($mobile_content2){
            $mobile_content=$mobile_content2;
            unset($mobile_content2);
        }

        /*if(preg_match_all('#<table.*tableclass_rows.*</table>#imsU',$mobile_content,$table_listings)){
            $x=1;
            foreach($table_listings[0] as $table_listing){
                $table_listing = str_replace('$','\$',$table_listing);
                //$mobile_content = preg_replace('#'.preg_quote($table_listing,'#').'#','<div style="overflow:auto;">'.$table_listing.'</div> ',$mobile_content);
                $mobile_content = str_replace($table_listing,'<div id="iscroll'.$x.'">'.$table_listing.'</div> <script type="text/javascript"> new iScroll(\'iscroll'.$x.'\'); </script>',$mobile_content); $x++;
            }
        }*/

        // clean up tables.
        // todo - figure out why <td></td><td></td><td></td> are not replaced. but two td's are.
        $mobile_content = preg_replace('#<tr>\s*(?:<td[^>]*>\s*</td>\s*)*</tr>#imsu','',$mobile_content);
        $mobile_content = preg_replace('#<tbody[^>]*>\s*</tbody>#imsu','',$mobile_content);
        $mobile_content = preg_replace('#<table[^>]*>\s*</table>#imsu','',$mobile_content);

        if(preg_match('#<form[^>]*>#imsU',$mobile_content,$matches)){
            $mobile_content = str_replace($matches[0],$matches[0].$new_content,$mobile_content);
        }else if(preg_match('#<!-- end page -->#',$mobile_content)){
            $mobile_content = str_replace('<!-- end page -->',$new_content,$mobile_content);
        }else{
            $mobile_content .= $new_content;
        }


        ob_start();
        include('pages/mobile_header.php');
        $mobile_header = ob_get_clean();
        // now we look for a main <h2> that has a button within it - we move this button up into the
        // header area of the page - and put the text content of the h2 into the page header.
        // #mobile_page_header > h1
        if(preg_match('#<h2>(.*)</h2>#imsU',$mobile_content,$matches)){
            if(!preg_match('#<h2>#i',$matches[1])){
                if(preg_match('#<a.*href="([^"]+)".*>(.*)</a>#imsU',$matches[1],$link_matches)){
                    $link_text = trim(strip_tags($link_matches[2]));
                    $link_href = $link_matches[1];
                    $mobile_header = str_replace('<!-- mobile page link -->','<a href="'.htmlspecialchars($link_href).'" data-icon="plus">'.htmlspecialchars($link_text).'</a> ',$mobile_header);
                    $title_text = trim(strip_tags(str_replace($link_matches[0],'',$matches[1])));
                    $mobile_header = preg_replace('#<h1 id="mobile_page_title">[^<]*</h1>#imsU','<h1>'.$title_text.'</h1>',$mobile_header);
                    $mobile_content = str_replace($matches[0],'',$mobile_content);
                }
            }
        }

        echo $mobile_header;
        echo $mobile_content;

        include('pages/mobile_footer.php');
    }

    public static function is_mobile_browser(){

        if(!module_config::c('mobile_enabled',1))return false;

        if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
        if(!isset($_SERVER['HTTP_ACCEPT']))return false;
        $mobile_browser = '0';

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');

        if (in_array($mobile_ua,$mobile_agents)) {
            $mobile_browser++;
        }

        if (isset($_SERVER['ALL_HTTP']) && strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
            $mobile_browser = 0;
        }



        return $mobile_browser;
    }
}