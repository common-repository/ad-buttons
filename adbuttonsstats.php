<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

if(isset($_GET['month'])){
	$graphdate = intval($_GET['month']);
} else {
	$graphdate = date('Ym');
}

if(isset($_GET['cln'])){
	$cleanup   = intval($_GET['cln']);
} else {
	$cleanup   = 0;
}

$graphyear = substr($graphdate, 0, 4);
$graphmonth = substr($graphdate, 4, 2);	

$prevmonth = $graphmonth - 1;
$prevyear  = $graphyear;
if ($prevmonth < 1){
	$prevmonth = 12;
	$prevyear--;
}

$nextmonth = $graphmonth + 1;
$nextyear  = $graphyear;
if ($nextmonth > 12){
	$nextmonth = 1;
	$nextyear++;
}

$prevdate = $prevyear.str_pad($prevmonth, 2, 0, STR_PAD_LEFT);
$nextdate = $nextyear.str_pad($nextmonth, 2, 0, STR_PAD_LEFT);
	
$replacetag = "&month=$graphdate";
$nplink = str_replace($replacetag, "", $_SERVER['REQUEST_URI']);

$replacetag = "&cln=1";
$nplink = str_replace($replacetag, "", $nplink);
?>
<div class="wrap">
	<h2>Ad Buttons Stats </h2>
	<a href="<?php echo esc_url( $nplink.'&month='.$prevdate ); ?>">previous month</a> 
	<a href="<?php echo esc_url( $nplink.'&month='.$nextdate ); ?>">next month</a> <br/>
	<img src="<?php echo site_url(); ?>/?ad_buttons_graph=1&graphdate=<?php echo $graphdate;?>">
	<br/>
	<p>Bars represent ad views. The scale is shown on the left side. (Each ad is counted individually, so if you are 
	showing an Ad Buttons ad block with 4 ads in your sidebar, you should see numbers four times as high as your page 
	view count)<br/>
	Lines show the number of ad clicks for each day. The scale is shown on the right side of the graph.
	</p>
<p>
<?php
if ($cleanup === 1 && wp_verify_nonce( $_REQUEST['adbuttonscleanup'], 'cleanupstats' ) && current_user_can( 'manage_options' )) {

	echo "cleaning up stats database...</br>";
	// CLEANUP PROCEDURE
	$wpdb->query("INSERT INTO {$wpdb->prefix}ad_buttons_stats_hst(abs_view, abs_click,  abs_dat)
				  SELECT sum(abs_view) , sum(abs_click), abs_dat FROM {$wpdb->prefix}ad_buttons_stats 
				   WHERE EXTRACT(YEAR_MONTH FROM abs_dat) < EXTRACT(YEAR_MONTH FROM CURDATE())
				   GROUP BY abs_dat 
				   ORDER BY abs_dat"
				);
				
	$wpdb->query("DELETE FROM {$wpdb->prefix}ad_buttons_stats 
				   WHERE EXTRACT(YEAR_MONTH FROM abs_dat) < EXTRACT(YEAR_MONTH FROM CURDATE())"
				);
				
	echo "done...</br>";
}
	
$old_total = $wpdb->get_results("
				SELECT count(*) as cnt
				  FROM {$wpdb->prefix}ad_buttons_stats
				 WHERE EXTRACT(YEAR_MONTH FROM abs_dat) < EXTRACT(YEAR_MONTH FROM CURDATE()) 
			");

foreach($old_total as $old){				
	$old_records = $old->cnt;
}

if ($old_records > 0) {	
	echo 'Total old records: <b>'.$old_records.'</b> cleaning up old records will free up space in the database. The daily totals will still be available for viewing here.</br>';
	echo '<a class="button button-primary" href="'.esc_url( add_query_arg( array( 'cln' => '1', 'adbuttonscleanup' => wp_create_nonce('cleanupstats') ) ) ).'">clean up now</a>';
}	


?>	
</p>
		
</div>
