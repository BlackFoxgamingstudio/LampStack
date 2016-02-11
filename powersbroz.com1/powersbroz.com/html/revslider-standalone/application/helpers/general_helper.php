<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Nwdthemes Standalone Slider Revolution
 *
 * @package     StandaloneRevslider
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2015. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */


/**
 *	Check if unserialized
 *
 *	@param	var	Original
 *	@return	var
 */
if( ! function_exists('maybe_unserialize'))
{
	function maybe_unserialize($original)
	{
		if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
			return @unserialize( $original );
		return $original;
	}
}

/**
 * Check value to find if it was serialized.
 *
 * @param string $data   Value to check to see if was serialized.
 * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
 * @return bool False if not serialized and true if it was.
 */
if( ! function_exists('is_serialized'))
{
	function is_serialized( $data, $strict = true ) {
		// if it isn't a string, it isn't serialized.
		if ( ! is_string( $data ) ) {
			return false;
		}
		$data = trim( $data );
		if ( 'N;' == $data ) {
			return true;
		}
		if ( strlen( $data ) < 4 ) {
			return false;
		}
		if ( ':' !== $data[1] ) {
			return false;
		}
		if ( $strict ) {
			$lastc = substr( $data, -1 );
			if ( ';' !== $lastc && '}' !== $lastc ) {
				return false;
			}
		} else {
			$semicolon = strpos( $data, ';' );
			$brace     = strpos( $data, '}' );
			// Either ; or } must exist.
			if ( false === $semicolon && false === $brace )
				return false;
			// But neither must be in the first X characters.
			if ( false !== $semicolon && $semicolon < 3 )
				return false;
			if ( false !== $brace && $brace < 4 )
				return false;
		}
		$token = $data[0];
		switch ( $token ) {
			case 's' :
				if ( $strict ) {
					if ( '"' !== substr( $data, -2, 1 ) ) {
						return false;
					}
				} elseif ( false === strpos( $data, '"' ) ) {
					return false;
				}
				// or else fall through
			case 'a' :
			case 'O' :
				return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
			case 'b' :
			case 'i' :
			case 'd' :
				$end = $strict ? '$' : '';
				return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
		}
		return false;
	}
}

/**
 *	Output checkbox checked
 *
 *	@param	string	Value
 *	@param	string	State (on/off)
 */
if( ! function_exists('checked'))
{
	function checked($value = '', $state = 'on')
	{
		if ( $value == $state )
		{
			echo 'checked="checked"';
		}
	}
}

/**
 *	Output select option selected
 *
 *	@param	string	Value
 *	@param	string	State
 */
if( ! function_exists('selected'))
{
	function selected($value = '', $state = '')
	{
		if ( $value == $state )
		{
			echo 'selected="selected"';
		}
	}
}

/**
 *	Does nothing
 *
 *	@param	string	Nounce
 *	@param	string	Actions
 *	@return	boolean	True
 */
if( ! function_exists('wp_verify_nonce'))
{
	function wp_verify_nonce($nonce = '', $actions = '')
	{
		return TRUE;
	}
}


/**
 *	Does nothing
 *
 *	@param	string	Content
 *	@return	string	Content
 */
if( ! function_exists('do_shortcode'))
{
	function do_shortcode($content = '')
	{
		return $content;
	}
}


/**
 *	Does nothing
 *
 *	@return	string	Empty
 */
if( ! function_exists('wp_create_nonce'))
{
	function wp_create_nonce()
	{
		return '';
	}
}


/**
 *	Does nothing
 *
 *	@return	string	Empty
 */
if( ! function_exists('wp_register_script'))
{
	function wp_register_script()
	{
		return '';
	}
}


/**
 *	Does nothing
 *
 *	@return	string	Empty
 */
if( ! function_exists('wp_enqueue_script'))
{
	function wp_enqueue_script()
	{
		return '';
	}
}


/**
 *	Does nothing
 *
 *	@return	boolean	False
 */
if( ! function_exists('is_multisite'))
{
	function is_multisite()
	{
		return FALSE;
	}
}


/**
 *	Returns content url
 *
 *	@return	string
 */
if( ! function_exists('content_url'))
{
	function content_url()
	{
		return base_url() . 'media';
	}
}


/**
 *	Gets Get Param
 *
 *	@param	string	Handle
 *	@param	string	Default
 *	@return	string	Value
 */
if( ! function_exists('get_param'))
{
	function get_param($handle = '', $default = '')
	{
		$ci = &get_instance();
		$value = $ci->input->get($handle);
		return $value === FALSE ? $default : $value;
	}
}

/**
 *	Replicates WP style pagination
 *
 *	@param	array	Arguments
 *	@return	string	Pagination html
 */
if( ! function_exists('paginate_links'))
{
	function paginate_links( $args = array() )
	{
		$total        = 1;
		$current      = get_param('paged', 1);

		$defaults = array(
			'total' => $total,
			'current' => $current,
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __('&laquo; Previous'),
			'next_text' => __('Next &raquo;'),
			'end_size' => 1,
			'mid_size' => 2,
			'type' => 'plain',
			'add_args' => false, // array of query args to add
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => ''
		);

		$args = array_merge( $defaults, $args );

		// Who knows what else people pass in $args
		$total = (int) $args['total'];
		if ( $total < 2 ) {
			return;
		}
		$current  = (int) $args['current'];
		$end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
		if ( $end_size < 1 ) {
			$end_size = 1;
		}
		$mid_size = (int) $args['mid_size'];
		if ( $mid_size < 0 ) {
			$mid_size = 2;
		}
		$add_args = is_array( $args['add_args'] ) ? $args['add_args'] : false;
		$r = '';
		$page_links = array();
		$dots = false;

		if ( $args['prev_next'] && $current && 1 < $current ) :
			$link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
			$link = str_replace( '%#%', $current - 1, $link );
			if ( $add_args )
				$link = add_query_arg( $add_args, $link );
			$link .= $args['add_fragment'];

			/**
			 * Filter the paginated links for the given archive pages.
			 *
			 * @since 3.0.0
			 *
			 * @param string $link The paginated link URL.
			 */
			$page_links[] = '<a class="prev page-numbers" href="' . site_url($link) . '">' . $args['prev_text'] . '</a>';
		endif;
		for ( $n = 1; $n <= $total; $n++ ) :
			if ( $n == $current ) :
				$page_links[] = "<span class='page-numbers current'>" . $args['before_page_number'] . $n . $args['after_page_number'] . "</span>";
				$dots = true;
			else :
				if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
					$link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
					$link = str_replace( '%#%', $n, $link );
					if ( $add_args )
						$link = add_query_arg( $add_args, $link );
					$link .= $args['add_fragment'];

					/** This filter is documented in wp-includes/general-template.php */
					$page_links[] = "<a class='page-numbers' href='" . site_url($link) . "'>" . $args['before_page_number'] . $n . $args['after_page_number'] . "</a>";
					$dots = true;
				elseif ( $dots && ! $args['show_all'] ) :
					$page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';
					$dots = false;
				endif;
			endif;
		endfor;
		if ( $args['prev_next'] && $current && ( $current < $total || -1 == $total ) ) :
			$link = str_replace( '%_%', $args['format'], $args['base'] );
			$link = str_replace( '%#%', $current + 1, $link );
			if ( $add_args )
				$link = add_query_arg( $add_args, $link );
			$link .= $args['add_fragment'];

			/** This filter is documented in wp-includes/general-template.php */
			$page_links[] = '<a class="next page-numbers" href="' . site_url($link) . '">' . $args['next_text'] . '</a>';
		endif;
		switch ( $args['type'] ) {
			case 'array' :
				return $page_links;

			case 'list' :
				$r .= "<ul class='page-numbers'>\n\t<li>";
				$r .= join("</li>\n\t<li>", $page_links);
				$r .= "</li>\n</ul>\n";
				break;

			default :
				$r = join("\n", $page_links);
				break;
		}
		return $r;
	}
}


/**
 *	Add arguments to url
 *
 *	@param	array	Arguments
 *	@param	string	Url
 *	@return	string	Link
 */
if( ! function_exists('add_query_arg'))
{
	function add_query_arg($args = array(), $link) {
		if ( is_array($args) )
		{
			foreach ($args as $_key => $_val) {
				$link .= $_key . '/' . $_val . '/';
			}
		}
		return $link;
	}
}

/**
 *	Check if SSL in use
 *
 *	@return	boolean
 */
if( ! function_exists('is_ssl'))
{
	function is_ssl() {
		if ( isset($_SERVER['HTTPS']) ) {
			if ( 'on' == strtolower($_SERVER['HTTPS']) )
				return true;
			if ( '1' == $_SERVER['HTTPS'] )
				return true;
		} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
			return true;
		}
		return false;
	}
}


/**
 *	Get upload dir
 *
 *	@return	array
 */
if( ! function_exists('wp_upload_dir'))
{
	function wp_upload_dir() {
		$upload_dir = array(
			'path'		=> FCPATH . RS_IMAGE_PATH . '/',
			'url'		=> base_url() . RS_IMAGE_PATH . '/',
			'subdir'	=> '/',
			'basedir'	=> FCPATH . RS_IMAGE_PATH,
			'baseurl'	=> base_url() . RS_IMAGE_PATH,
			'error'		=> FALSE
		);
		return $upload_dir;
	}
}


/**
 *	Get time
 *
 *	@return	int
 */
if( ! function_exists('current_time'))
{
	function current_time() {
		return time();
	}
}


/**
 *	Snitize title
 *
 *	@param	string
 *	@return	string
 */
if( ! function_exists('sanitize_title'))
{
	function sanitize_title($title) {
		return sanitize_text_field($title);
	}
}


/**
 *	Snitize title
 *
 *	@param	string
 *	@return	string
 */
if( ! function_exists('sanitize_title_with_dashes'))
{
	function sanitize_title_with_dashes($title) {
		return url_title(sanitize_title($title));
	}
}


/**
 *	Snitize text field
 *
 *	@param	string
 *	@return	string
 */
if( ! function_exists('sanitize_text_field'))
{
	function sanitize_text_field($string) {
		$filtered = strip_tags($string);
		$found = false;
		while ( preg_match('/%[a-f0-9]{2}/i', $filtered, $match) ) {
			$filtered = str_replace($match[0], '', $filtered);
			$found = true;
		}
		if ( $found ) {
			$filtered = trim( preg_replace('/ +/', ' ', $filtered) );
		}
		return $filtered;
	}
}


/**
 *	Snitize title
 *
 *	@param	string
 *	@return	string
 */
if( ! function_exists('esc_attr'))
{
	function esc_attr($text) {
		return $text;
	}
}

/**
 *	Format sizes
 *
 *	@param	int	Bytes
 *	@param	int	Decimals
 *	@return	string
 */	

if( ! function_exists('size_format'))
{
	function size_format( $bytes, $decimals = 0 ) {
		$quant = array(
			// ========================= Origin ====
			'TB' => 1099511627776,  // pow( 1024, 4)
			'GB' => 1073741824,     // pow( 1024, 3)
			'MB' => 1048576,        // pow( 1024, 2)
			'kB' => 1024,           // pow( 1024, 1)
			'B ' => 1,              // pow( 1024, 0)
		);
		foreach ( $quant as $unit => $mag )
			if ( doubleval($bytes) >= $mag )
				return number_format( $bytes / $mag, $decimals ) . ' ' . $unit;
	
		return false;
	}
}


/**
 *	Add filter
 *
 *	@param	string	$handle
 *	@param	array	$filter
 */

if( ! function_exists('add_filter'))
{
	function add_filter($handle, $filter) {
		$ci = &get_instance();
		$filters = $ci->session->userdata('filters') ? $ci->session->userdata('filters') : array();
		$filters[$handle] = $filter;
		$ci->session->set_userdata('filters', $filters);
	}
}


/**
 *	Add filter
 *
 *	@param	string	$handle
 *	@param	var		$data
 *	@return	var
 */

if( ! function_exists('apply_filters'))
{
	function apply_filters($handle, $data) {
		$ci = &get_instance();
		$filters = $ci->session->userdata('filters') ? $ci->session->userdata('filters') : array();
		if (isset($filters[$handle]))
		{
			$data = call_user_func_array($filters[$handle], array($data));
		}
		return $data;
	}
}


/**
 *	Add action
 *
 *	@param	string	$handle
 *	@param	array	$action
 */

if( ! function_exists('add_action'))
{
	function add_action($handle, $action) {
		if (is_object($action[0]))
		{
			$action[0] = get_class($action[0]);
		}
		$ci = &get_instance();
		$actions = $ci->session->userdata('actions') ? $ci->session->userdata('actions') : array();
		$actions[$handle][] = $action;
		$ci->session->set_userdata('actions', $actions);
	}
}


/**
 *	Run actions
 *
 *	@param	string	$handle
 *	@param	bool	$output
 */

if( ! function_exists('call_actions'))
{
	function call_actions($handle) {
		$ci = &get_instance();
		$actions = $ci->session->userdata('actions') ? $ci->session->userdata('actions') : array();
		$output = false;
		if (isset($actions[$handle]) && is_array($actions[$handle]))
		{
			ob_start();
			foreach ($actions[$handle] as $action) {
				call_user_func($action);
			}
			unset($actions[$handle]);
			$output = ob_get_contents();
			ob_end_clean();
			$ci->session->set_userdata('actions', $actions);
		}
		return $output;
	}
}

if( ! function_exists('do_action'))
{
	/**
	 *	Do action
	 *
	 *	@param	string	Handle
	 *	@param	mixed	Arguments
	 *	@param	bool	$output
	 */

	function do_action($handle, $args) {
		return call_actions($handle);
	}
}


/**
 *	Add style
 *
 *	@param	string	$handle
 *	@param	string	$style
 */

if( ! function_exists('wp_enqueue_style'))
{
	function wp_enqueue_style($handle, $style = false) {
		if ($style)
		{
			$ci = &get_instance();
			$styles = $ci->session->userdata('styles') ? $ci->session->userdata('styles') : array();
			$styles[$handle] = $style;
			$ci->session->set_userdata('styles', $styles);
		}
	}
}

/**
 *	Add inline style
 *
 *	@param	string	$handle
 *	@param	string	$style
 */

if( ! function_exists('wp_add_inline_style'))
{
	function wp_add_inline_style($handle, $style = false) {
		if ($style)
		{
			$ci = &get_instance();
			$styles = $ci->session->userdata('inline_styles');
			$styles[] = $style;
			$ci->session->set_userdata('inline_styles', $styles);
		}
	}
}



/**
 *	Get styles
 *
 *	@return	string
 */

if( ! function_exists('wp_localize_script'))
{
	function wp_localize_script() {
		// TODO
	}
}


/**
 *	Get post meta (for compatibility)
 *
 *	@param	int		$id
 *	@param	string	$hanlde
 *	@param	bool	$flag
 *	@return	var
 */

if( ! function_exists('get_post_meta'))
{
	function get_post_meta($id, $handle, $flag = true) {
		return '';
	}
}


/**
 *	Get post types (for compatibility)
 *
 *	@param	int		$id
 *	@param	string	$hanlde
 *	@param	bool	$flag
 *	@return	var
 */

if( ! function_exists('get_post_types'))
{
	function get_post_types($arg1 = '', $arg2 = '') {
		return array();
	}
}

/**
 *	Check if in admin access now
 *
 *	@return	bool
 */

if( ! function_exists('is_admin'))
{
	function is_admin() {
		$ci =& get_instance();
		return isset($ci->router) && $ci->router->fetch_class() == 'admin' && $ci->session->userdata('user_id');
	}
}

if( ! function_exists('is_super_admin'))
{
	function is_super_admin() {
		return is_admin();
	}
}

if( ! function_exists('is_admin_bar_showing'))
{
	function is_admin_bar_showing() {
		return is_admin();
	}
}


/**
 *	Check if current user have access
 *
 *	@return	bool
 */

if( ! function_exists('current_user_can'))
{
	function current_user_can() {
		return is_admin();
	}
}

/**
 *	Get content from remote url
 *
 *	@param	string	$url
 *	@param	array	$args
 *	@return	array
 */

if( ! function_exists('wp_remote_post'))
{
	function wp_remote_post($url, $args) {

		if ( ! function_exists('curl_version'))
		{
			return  array(
				'response'	=> array('code' => 0, 'message' => __('cURL library not installed.')),
				'body'		=> false
			);
		}

		$defaults = array(
			'user-agent' => false,
			'headers' => array(),
			'cookies' => array(),
			'httpversion' => CURL_HTTP_VERSION_NONE,
			'timeout' => 30,
			'method' => 'POST',
			'body' => array()
		);
		$args = array_merge($defaults, $args);

		$ch = curl_init();

		$headers = array();
		foreach ($args['headers'] as $key => $value)
		{
			$headers[] = "$key: $value";
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		if ($args['method'] == 'POST')
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args['body']));
		}
		if ($args['user-agent'])
		{
			curl_setopt($ch, CURLOPT_USERAGENT, $args['user-agent']);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_COOKIE,  implode('; ', $args['cookies']));
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $args['timeout']);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, $args['httpversion'] == '1.0' ? CURL_HTTP_VERSION_1_0 : ($args['httpversion'] == '1.1' ? CURL_HTTP_VERSION_1_1 : CURL_HTTP_VERSION_NONE));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_ENCODING, '');

		$output = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_errno($ch);
		$message = curl_error($ch);

		curl_close ($ch);

		$result = array(
			'response'	=> array('code' => $code, 'message' => $message),
			'body'		=> $output
		);
		return $result;
	}
}


/**
 *	Get content from remote url
 *
 *	@param	string	$url
 *	@param	array	$args
 *	@return	array
 */

if( ! function_exists('wp_remote_get'))
{
	function wp_remote_get($url, $args = array()) {
		$args['method'] = 'GET';
		return wp_remote_post($url, $args);
	}
}


/**
 *	Open content from remote url
 *
 *	@param	string	$url
 *	@return	string
 */

if( ! function_exists('wp_remote_fopen'))
{
	function wp_remote_fopen($url) {
		$args = array(
			'method'             =>         'GET',
			'timeout'            =>         5,
			'redirection'        =>         5,
			'httpversion'        =>         '1.0',
			'blocking'           =>         true,
			'body'               =>         null
		);
		$response = wp_remote_post($url, $args);
		return $response['response']['code'] == 200 ? $response['body'] : '';
	}
}


/**
 *  Get response code from response
 *
 *	@param	array	$response
 *	@return	string
 */

if( ! function_exists('wp_remote_retrieve_response_code'))
{
	function wp_remote_retrieve_response_code($response) {
		return $response['response']['code'];
	}
}


/**
 *  Get body from response
 *
 *	@param	array	$response
 *	@return	string
 */

if( ! function_exists('wp_remote_retrieve_body'))
{
	function wp_remote_retrieve_body($response) {
		return $response['body'];
	}
}


/**
 *	Get blog info
 *
 *	@param	string	$option
 *	@return	array
 */

if( ! function_exists('get_bloginfo'))
{
	function get_bloginfo($option) {
		$info = array(
			'url' => site_url()
		);
		return isset($info[$option]) ? $info[$option] : false;
	}
}


/**
 *	Check for error in data
 *
 *	@param	var		$data
 *	@return	bool
 */

if( ! function_exists('is_wp_error'))
{
	function is_wp_error($data) {
		return false;
	}
}



/**
 *	Get transient
 *
 *	@param	string	Handle
 *	@return var
 */

if( ! function_exists('get_transient'))
{
	function get_transient($handle) {
		$ci = &get_instance();
		$ci->load->model('transient_model', 'Transient');
		return $ci->Transient->get_value($handle);
	}
}


/**
 *	Set transient
 *
 *	@param	string	Handle
 *	@param	var		Value
 *	@param	int		Expiration (seconds)
 */

if( ! function_exists('set_transient'))
{
	function set_transient($handle, $value, $expiration = 0) {
		$ci = &get_instance();
		$ci->load->model('transient_model', 'Transient');
		$expiration = (int) $expiration;
		$expires = $expiration ? date('Y-m-d H:i:s', strtotime("+$expiration seconds")) : NULL;
		$ci->Transient->set($handle, $value, $expires);
	}
}

/**
 *	Delete transient
 *
 *	@param	string	Handle
 */

if( ! function_exists('delete_transient'))
{
	function delete_transient($handle) {
		$ci = &get_instance();
		$ci->load->model('transient_model', 'Transient');
		$ci->Transient->delete($handle);
	}
}


/**
 *	Date localization
 *
 *	@param	string	$format
 *	@param	int		$date
 */

if( ! function_exists('date_i18n'))
{
	function date_i18n($format, $date) {
		$format = trim($format);
		$format = $format ? $format : 'd M, Y - H:i';
		return date($format, $date);
	}
}



/**
 *	Check if file is writable
 *
 *	@param	string	$path
 *	@return	bool
 */

if( ! function_exists('wp_is_writable'))
{
	function wp_is_writable($path) {
		return is_writable($path);
	}
}


/**
 *	Make writable directory
 *
 *	@param	string	$path
 *	@return	bool
 */

if( ! function_exists('wp_mkdir_p'))
{
	function wp_mkdir_p($dir) {
		if (file_exists($dir) && is_dir($dir))
		{
			return true;
		}
		else
		{
			return @mkdir($dir);
		}
	}
}


/**
 * Converts byte value to integer byte value
 *
 * @param	string	$size
 * @return	int
 */

if( ! function_exists('wp_convert_hr_to_bytes'))
{
	function wp_convert_hr_to_bytes($size) {
		$size  = strtolower( $size );
		$bytes = (int) $size;
		if ( strpos( $size, 'k' ) !== false ) $bytes = intval( $size ) * 1024;
		elseif ( strpos( $size, 'm' ) !== false ) $bytes = intval($size) * 1024 * 1024;
		elseif ( strpos( $size, 'g' ) !== false ) $bytes = intval( $size ) * 1024 * 1024 * 1024;
		return $bytes;
	}
}


/**
 *	Get current page info
 *
 *	@return	obj
 */

if( ! function_exists('get_current_screen'))
{
	function get_current_screen() {
		$screen = array('id' => 'revslider');
		return (object) $screen;
	}
}


/**
 *	Check if mobile browser
 *
 *	@return	boolean
 */

if( ! function_exists('wp_is_mobile'))
{
	function wp_is_mobile() {
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		$is_mobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
		return $is_mobile;
	}
}

