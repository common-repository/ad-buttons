<?php
/*
Plugin Name: Ad Buttons 
Plugin URI: http://adbuttons.net/
Description: Plugin to add ad buttons to your blog
Author: Nico
Version: 3.1
Author URI: http://www.blogio.net/blog/
Questions, suggestions, problems? Let me know at https://wordpress.org/support/plugin/ad-buttons
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function ad_buttons_install() {
	
	//set the options
	$newoptions['ab_dspcnt'] = '1';  // number of ads to display
	$newoptions['ab_target'] = 'bnk';// target attribute for links
	$newoptions['ab_powered'] = '0'; // display 'powered by' link, default 0 due to WordPress plugin guidelines
	add_option('widget_adbuttons_cfg', $newoptions); // add the options to the options database table only if they don't exist
	
	// create table
    global $wpdb;
    $table = "{$wpdb->prefix}ad_buttons";
    $structure = "CREATE TABLE $table (
								id INT(9) NOT NULL AUTO_INCREMENT,
								ad_picture VARCHAR(100) NOT NULL,
								ad_link VARCHAR(500) NOT NULL,
								ad_text VARCHAR(80) NOT NULL,
								ad_views INT(9) DEFAULT 0,
								ad_clicks INT(9) DEFAULT 0,
								ad_active TINYINT(1) NOT NULL DEFAULT 0,
								ad_pos INT(9) DEFAULT 0,
				  UNIQUE KEY id (id)
				);";
	
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($structure);
		
    $wpdb->query("INSERT INTO $table 
							 (id, ad_picture, ad_link, ad_text, ad_views, ad_clicks, ad_active)
				  VALUES	 (1, '".plugins_url( 'ab125.jpg', __FILE__ )."', 'http://wordpress.org/plugins/ad-buttons/', 'ads powered by Ad Buttons', 0, 0, 0),
							 (2, '".plugins_url( 'wordpress_logo.png', __FILE__ )."', 'http://wordpress.org/', 'WordPress.org', 0, 0, 1)");

    $table = "{$wpdb->prefix}ad_buttons_stats";
    $structure = "CREATE TABLE $table (
								abs_dat date NOT NULL,
								abs_ip int(10) NOT NULL,
								abs_view tinyint(4) NOT NULL,
								abs_click tinyint(4) NOT NULL,
					 KEY abs_dat (abs_dat)
				);";
	
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($structure);

	$table = "{$wpdb->prefix}ad_buttons_stats_hst";
	$structure = "CREATE TABLE $table (
								abs_dat date NOT NULL,
								abs_view int(11) NOT NULL,
								abs_click int(11) NOT NULL
				);";
	
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($structure);
	
	$ad_buttons_version = "3.0";
	update_option("ad_buttons_version", $ad_buttons_version);

	$ad_buttons_db_version = "3.0";
	update_option("ad_buttons_db_version", $ad_buttons_db_version);
}

register_activation_hook(__FILE__,'ad_buttons_install');

function ad_buttons_is_bot() {
	//check if user is a bot of some sort
    $bots = array('google','yahoo','msn','jeeves','lycos','whatuseek','BSDSeek','BullsEye','Yandex',
	'Seznam','XoviBot','NerdyBot','MJ12bot','bingbot','spider', 'crawler','eniro.com','ApptusBot','scraper','validator');
    //takes the list above and returns /(google)(yahoo)(msn)...)/
    $regex = '/('.implode($bots, ')(').')/';
    //uses the generated regex above to see if those keywords are contained in the user agent variable    
    return preg_match($regex, $_SERVER['HTTP_USER_AGENT']);
}

function ad_buttons_show_ad($ad_id) {
	return true; // decide if the ad should be shown, depending on geo-targeting options
}

function ad_buttons_get_config() {

	$widget_adbuttons_cfg = array(

	'ab_title'				=> '',
	'ab_dspcnt'				=> '',
	'ab_target' 			=> '',
	'ab_adsense' 			=> '',
	'ab_adsense_fixed'		=> '',
	'ab_adsense_pos'		=> '',
	'ab_adsense_pubid'		=> '',
	'ab_adsense_channel'	=> '',
	'ab_adsense_corners'	=> '',
	'ab_adsense_col_border'	=> '',
	'ab_adsense_col_title'	=> '',
	'ab_adsense_col_bg'		=> '',
	'ab_adsense_col_txt'	=> '',
	'ab_adsense_col_url'	=> '',
	'ab_nocss'				=> '',
	'ab_width'				=> '',
	'ab_padding'			=> '',
	'ab_nofollow'			=> '',
	'ab_powered'			=> '',
	'ab_yah'				=> '',
	'ab_yourad'				=> '',
	'ab_yaht'				=> '',
	'ab_yahurl'				=> '',
	'ab_fix'				=> '',
	'ab_count'				=> ''
	);
	
	$widget_adbuttons_cfg = get_option('widget_adbuttons_cfg');

	return $widget_adbuttons_cfg;
}

function ad_buttons() {
    global $wpdb;
	$widget_adbuttons_cfg = ad_buttons_get_config();
	
	$wp_root = get_option('home');

	if($widget_adbuttons_cfg['ab_nofollow']){
		$ab_nofollow = ' rel="nofollow" ';
	}else{
		$ab_nofollow = '';
	}

	if($widget_adbuttons_cfg['ab_powered']){
		if($widget_adbuttons_cfg['ab_nocss']){
			$ab_powered = '<a class="ab_power" href="http://wordpress.org/plugins/ad-buttons/">powered by Ad Buttons</a>';
		} else {
			$ab_powered = '<div id="ab_power"><a class="ab_power" href="http://wordpress.org/plugins/ad-buttons/">powered by Ad Buttons</a></div>';	
		}
	}else{
		$ab_powered = '';
	}

	if($widget_adbuttons_cfg['ab_adsense']){
		if($widget_adbuttons_cfg['ab_nocss']){
			$ab_adsensecss = '';
			$ab_adsenseenddiv = '';
		}else{
			$ab_adsensecss = '<div id="ab_adsense">';
			$ab_adsenseenddiv = '</div>';
		}	
		$ab_adsense_ad = $ab_adsensecss.'
						<script><!--
						google_ad_client = "'.esc_html($widget_adbuttons_cfg['ab_adsense_pubid']).'";
						google_ad_width = 125;
						google_ad_height = 125;
						google_ad_format = "125x125_as";
						google_ad_type = "text_image";
						google_ad_channel = "'.esc_html($widget_adbuttons_cfg['ab_adsense_channel']).'";
						google_color_border = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_border'])).'";
						google_color_bg = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_bg'])).'";
						google_color_link = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_title'])).'";
						google_color_text = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_txt'])).'";
						google_color_url = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_url'])).'";
						google_ui_features = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_corners'])).'";
						//-->
						</script>
						<script src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
						</script>'.$ab_adsenseenddiv;
	}
		
	if($widget_adbuttons_cfg['ab_target'] == 'bnk'){
		$target = ' target="_blank" ';
	}elseif($widget_adbuttons_cfg['ab_target'] == 'top'){
		$target = ' target="_top" ';
	}elseif($widget_adbuttons_cfg['ab_target'] == 'non'){
		$target = ' ';
	}

	if($widget_adbuttons_cfg['ab_adsense']){
		$ab_count = 1;
	}else {
		$ab_count = 0;
	}
	
	echo'
	<style type="text/css">
	#ab_adblock
	{
	width: '.esc_html($widget_adbuttons_cfg['ab_width']).'px;
	padding:'.esc_html($widget_adbuttons_cfg['ab_padding']).'px;
	overflow:hidden;
	}
	#ab_adblock a
	{
	float: left;
	padding:'.esc_html($widget_adbuttons_cfg['ab_padding']).'px;
	}
	#ab_adsense
	{
	float: left;
	padding:'.esc_html($widget_adbuttons_cfg['ab_padding']).'px;
	}
	#ab_clear
	{
	clear: both;
	}
	#ab_power, a.ab_power:link, a.ab_power:visited, a.ab_power:hover 
	{
	width: 150px;
	color: #333;
	text-decoration:none;
	font-size: 10px;
	}

	</style>'; 
	
	if(!$widget_adbuttons_cfg['ab_nocss']){
		echo '<div id="ab_adblock">';
	}

	if($widget_adbuttons_cfg['ab_fix']){
		$results = $wpdb->get_results("SELECT * FROM "."{$wpdb->prefix}ad_buttons WHERE 
		ad_active = 1 ORDER BY ad_pos");
	}else{
		$results = $wpdb->get_results("SELECT * FROM "."{$wpdb->prefix}ad_buttons WHERE 
		ad_active = 1 ORDER BY RAND()");
	}

	foreach($results as $result){
		if ($ab_count < $widget_adbuttons_cfg['ab_dspcnt']) {
			if($widget_adbuttons_cfg['ab_adsense']){
				if($widget_adbuttons_cfg['ab_adsense_pos']==$ab_count){
					echo $ab_adsense_ad;
				}
			}
			if(ad_buttons_show_ad($result->id)) {
				echo $widget_adbuttons_cfg['ab_nofollow'];
				echo '<a href="'.esc_html($wp_root).'/index.php?recommends='. esc_html($result->id) .'" '. $target .' title="'. esc_html($result->ad_text).'" '. esc_html($ab_nofollow).'><img src="'. esc_html($result->ad_picture) .'" alt="'. esc_html($result->ad_text) .'"  vspace="1" hspace="1" border="0"></a>';
				$ab_count = $ab_count + 1;
				// update view counter on the ad button
				if(!ad_buttons_is_bot()) {
					if($widget_adbuttons_cfg['ab_count'] OR !is_user_logged_in()){
						$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}ad_buttons 
							SET ad_views = ad_views + 1 WHERE id = %d",$result->id));
						$ab_ip = (int)ip2long($_SERVER['REMOTE_ADDR']);
						$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}ad_buttons_stats(abs_dat, abs_ip, abs_view)
						VALUES(CURDATE(), %d, %d)",$ab_ip,$result->id));
					}
				}
			}
		}
	}

	if($widget_adbuttons_cfg['ab_adsense']){
		if($widget_adbuttons_cfg['ab_adsense_pos']==$ab_count){
			echo $ab_adsense_ad;
			}
	}

	if($widget_adbuttons_cfg['ab_yah']){
		if($widget_adbuttons_cfg['ab_yaht'] == 'url'){
			echo'<a href="'.$widget_adbuttons_cfg['ab_yahurl'].'" title="Advertise here"><img src="'.plugins_url( 'your_ad_here.jpg', __FILE__ ).'" alt="Advertise here"></a>';
		} else {
			echo'<a href="'.$wp_root.'/?page_id='.$widget_adbuttons_cfg['ab_yourad'].'" title="Advertise here"><img src="'.plugins_url( 'your_ad_here.jpg', __FILE__ ).'" alt="Advertise here"></a>';
		}
	}

	if($widget_adbuttons_cfg['ab_nocss']){
		echo $ab_powered;
	}else{
		echo ''.$ab_powered.'</div>';
	}
}

function ad_buttons_settings(){
    global $wpdb;
    include 'adbuttonsadmin.php';
}
 
function ad_buttons_stats(){
    global $wpdb;
    include 'adbuttonsstats.php';
}

function ad_buttons_test_gae(){
    global $wpdb;
    include 'adbuttonstestgae.php';
}

function ad_buttons_top(){
    global $wpdb;
    include 'adbuttonstop.php';
}
 
function ad_buttons_act(){
    global $wpdb;
    include 'adbuttonsact.php';
}

function ad_buttons_stats_actions(){
	add_menu_page('Ad Buttons', 'Ad Buttons', 'edit_pages', __FILE__, 'ad_buttons_act', get_option('siteurl').'/'.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/ad_buttons_icon.png');
	// Add a submenu to the custom top-level menu:
	add_submenu_page(__FILE__, 'Ad Buttons Settings', 'Settings', 'edit_pages', 'ad-buttons-settings', 'ad_buttons_settings');
	add_submenu_page(__FILE__, 'Ad Buttons Stats', 'Stats', 'edit_pages', 'ad-buttons-stats', 'ad_buttons_stats');
}

add_action('admin_menu', 'ad_buttons_stats_actions');


// process ad clicks
function ad_buttons_getclick(){
	global $wpdb;

	$widget_adbuttons_cfg = ad_buttons_get_config();

	if(isset($_GET['recommends'])){
		if(is_numeric($_GET['recommends'])){
			$ad_id = (int)$_GET['recommends'];
			if(is_numeric($ad_id)){
				$results = $wpdb->get_results($wpdb->prepare("SELECT ad_link FROM {$wpdb->prefix}ad_buttons WHERE id = %d LIMIT 1",$ad_id));
				foreach($results as $result){
					$send_to_url = $result->ad_link;
					if(!ad_buttons_is_bot()) {
						if($widget_adbuttons_cfg['ab_count'] OR !is_user_logged_in()){
							$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}ad_buttons 
								SET ad_clicks = ad_clicks + 1 WHERE id = %d",$ad_id));
							$ab_ip = ip2long($_SERVER['REMOTE_ADDR']);
							$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}ad_buttons_stats(abs_dat, abs_ip, abs_click)
							VALUES(CURDATE(), %d, %d)",$ab_ip,$ad_id));	
						}
					}
					//redirect to the URL of the clicked ad
					header("Location: ".esc_url( $send_to_url ));
					exit(0);
				}
			}
		}
	}
}
	
// widget
function widget_init_ad_buttons_widget() {
	// Check for required functions
	if (!function_exists('register_sidebar_widget'))
		return;

	function ad_buttons_widget($args){
	    extract($args);
		$options = get_option('widget_adbuttons_cfg');
		$title = empty($options['ab_title']) ? __('Sponsored Links') : $options['ab_title'];
		echo $before_widget;
		echo $before_title . esc_html($title) . $after_title ;
		if( !stristr( $_SERVER['PHP_SELF'], 'widgets.php' ) ){
			ad_buttons();
		}
		echo $after_widget;
	}
		
	function ad_buttons_widget_control() {
		$options = $newoptions = get_option('widget_adbuttons_cfg');
		if($_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can( 'manage_options' )) {
			// add a nonce check here
			//adbuttonsupdtitle
			
			if ( !empty($_POST['ad_buttons_widget_submit']) && wp_verify_nonce( $_POST['adbuttonsupdtitle'], 'adbuttons_updatewidgettitle' )) {
				$newoptions['ab_title'] = sanitize_text_field( $_POST['ad_buttons_widget_title'] );
			}
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option('widget_adbuttons_cfg', $options);
			}
		}
		$title = esc_attr($options['ab_title']);
		// add a nonce creation here
		?>
			<p><label for="ad_buttons_widget_title"><?php _e('Title:'); ?> <input class="widefat" id="ad_buttons_widget_title" 
			name="ad_buttons_widget_title" type="text" value="<?php echo esc_html( $title ); ?>" /></label></p>
			<?php wp_nonce_field( 'adbuttons_updatewidgettitle', 'adbuttonsupdtitle' ); ?>
			<input type="hidden" id="ad_buttons_widget_submit" name="ad_buttons_widget_submit" value="1" /><br/>
			That's all you can set here. All other options and ad controls can be found in the <strong>Ad Buttons</strong> 
			menu located on the far left side of this page.
		<?php
	}
	wp_register_sidebar_widget(
		'ad_buttons_widget_1',    // unique widget id
		'Ad Buttons',        	// widget name
		'ad_buttons_widget',    	// callback function
		array(              	// options
			'description' => 'Displays ad buttons'
		)
	);
	wp_register_widget_control(
		'ad_buttons_widget_1',    // unique widget id
		'Ad Buttons',        	// widget name
		'ad_buttons_widget_control',    	// callback function
		array(              	// options
			'description' => 'Displays ad buttons'
		)
	);
}

// Delay plugin execution until sidebar is loaded
add_action('widgets_init', 'widget_init_ad_buttons_widget');

add_action('init', 'ad_buttons_getclick'); 

add_filter('query_vars','ad_buttons_add_trigger');
function ad_buttons_add_trigger($vars) {
    $vars[] = 'ad_buttons_graph';
    return $vars;
}
 
add_action('template_redirect', 'ad_buttons_gen_graph');
function ad_buttons_gen_graph() {
    if(intval(get_query_var('ad_buttons_graph')) == 1) {
		// png image generation code
		include('adbuttonsstatsimg.php');
		exit;
    }
}
?>
