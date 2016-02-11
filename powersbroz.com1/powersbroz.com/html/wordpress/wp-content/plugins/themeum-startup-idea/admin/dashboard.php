<?php

function themeum_lms_dashboard_widget_init() {

	wp_add_dashboard_widget(
		'themeum_lms_dashboard_widget',
		'<span class="dashicons dashicons-screenoptions"></span>' . ' ' . __('Themeum Startup Status', 'themeum-startup-idea'),
		'themeum_startup_dashboard_widget_function'
		);	
}
add_action( 'wp_dashboard_setup', 'themeum_lms_dashboard_widget_init' );

function themeum_startup_dashboard_widget_function() {
	?>
	<ul class="themeum-lms-data clearfix">
		<li class="total-project">
			<a href="edit.php?post_type=lmsorder">
				<span class="dashicons dashicons-chart-bar"></span> <?php echo themeum_startup_get_total_project().' '.__('Project','themeum-startup-idea') ; ?> 
			</a>
		</li>
		<li class="total-sales">
			<a href="edit.php?post_type=lmsorder">
				<span class="dashicons dashicons-cart"></span> <?php echo themeum_startup_get_total_sales().' '.__('Sales','themeum-startup-idea') ; ?> 
			</a>
		</li>
		<li class="total-orders">
			<a href="edit.php?post_type=lmsorder">
				<span class="dashicons dashicons-portfolio"></span> <?php echo themeum_startup_get_pending_project().' '. __('Pending Project', 'themeum-startup-idea'); ?>
			</a>
		</li>
		<li class="pending-orders">
			<a href="edit.php?post_type=lmsorder">
				<span class="dashicons dashicons-upload"></span> <?php echo themeum_startup_get_project_this_month(). ' '. __('Publish in this month', 'themeum-startup-idea'); ?>
			</a>
		</li>
	</ul>
	<?php

	$data = $labels = '';
	$current_year = date('Y');
    $current_month = date('n');
	$data[] = '"' . themeum_startup_get_project_data( $current_month, $current_year ) . '"';
	$labels[] = '"' . date("F", mktime(0, 0, 0, $current_month, 10)) .' - ' . $current_year . '"';

	for ($i=1; $i<=20; $i++) { 
		if($current_month == 1){
			$current_month = 12;
			$current_year--;
		}else{
			$current_month--;
		}
		$data[] = '"' . themeum_startup_get_project_data( $current_month, $current_year ) . '"';
		$labels[] = '"' . date("F", mktime(0, 0, 0, $current_month, 10)) .' - ' . $current_year . '"';
	}

	$data = rtrim(implode(",",array_reverse($data)));
	$labels = rtrim(implode(",",array_reverse($labels)));

	?>

	<div class="themeum-lms-chart">
		<div>
			<canvas id="themeum-lms-canvas" height="400" width="600"></canvas>
		</div>
	</div>

	<script>
	var barChartData = {
		labels : [<?php echo $labels; ?>],
		datasets : [
			{
				fillColor : "#48b0f7",
				strokeColor : "#48b0f7",
				highlightFill: "#2690d8",
				highlightStroke: "#2690d8",
				data : [<?php echo $data; ?>]
			}
		]

	}
	window.onload = function(){
		var ctx = document.getElementById("themeum-lms-canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}

	</script>




<?php
}




function themeum_startup_get_project_data( $month, $year ) {
    $args = array(
        'year'     => $year,
        'monthnum' => $month,
        'post_status'     => 'publish',
        'post_type'       => 'project',
        'posts_per_page'   => -1,
    );
    $number_posts = get_posts( $args );
    return count($number_posts);
}

// Number of Published Project 
function themeum_startup_get_total_project() {
	 return wp_count_posts( 'project' )->publish;
}

// Number of pending project
function themeum_startup_get_pending_project() {
	return wp_count_posts( 'project' )->draft; 
}

// Number of published project current month
function themeum_startup_get_project_this_month() {
    $current_year = date('Y');
    $current_month = date('n');
    $args = array(
        'year'     => $current_year,
        'monthnum' => $current_month,
        'post_status'     => 'publish',
        'post_type'       => 'project'
    );
    $number_posts = get_posts( $args );
    return count($number_posts);
}



function themeum_startup_get_total_sales() {
	global $wpdb;
	//$result = $wpdb->get_row("SELECT SUM(pt.meta_value) as total_price FROM " . $wpdb->prefix . "posts AS p RIGHT JOIN " . $wpdb->prefix . "postmeta AS pt ON p.ID = pt.post_id RIGHT JOIN " . $wpdb->prefix . "postmeta as pt2 ON p.ID = pt2.post_id WHERE p.post_type = 'lmsorder' AND pt.meta_key = 'themeum_order_price' AND pt2.meta_value = 'complete'");
	$result = $wpdb->get_row( $wpdb->prepare("SELECT SUM(meta_value) as total_price FROM " . $wpdb->prefix . "postmeta WHERE post_id in (SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key='%s' AND meta_value='%s') AND meta_key='%s'",'themeum_status_all','complete','themeum_investment_amount'));
	if($result->total_price) {
		return $result->total_price;
	} else {
		return "0";
	}
}

//Add admin assets
function themeum_lms_load_admin_assets() {
	wp_enqueue_style( 'themeum-startup-css', plugins_url('themeum-startup-idea') . '/admin/assets/css/dashboard.css', false );
	wp_enqueue_script( 'themeum-startup-dashboard', plugins_url('themeum-startup-idea') . '/admin/assets/js/Chart.min.js', false );
}
add_action( 'admin_enqueue_scripts', 'themeum_lms_load_admin_assets' );