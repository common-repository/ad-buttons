<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

if(isset($_GET['graphdate'])){
	$graphdate = intval($_GET['graphdate']);
} else {
	$graphdate = date('Ym');
}
$graphyear = substr($graphdate, 0, 4);
$graphmonth = substr($graphdate, 4, 2);
$checkdate = "$graphyear-$graphmonth-";
$stringmonth = date("F", mktime(0, 0, 0, ($graphmonth), 1));

function ad_buttons_monthdays($someMonth, $someYear){
	return date("t", strtotime($someYear . "-" . $someMonth . "-01"));
}

$view_counter = $wpdb->get_results("SELECT abs_dat, count(*) AS cnt 
									  FROM {$wpdb->prefix}ad_buttons_stats
									 WHERE abs_view <> 0 AND abs_dat LIKE '$checkdate%' 
									 GROUP by abs_dat");
									 
$click_counter = $wpdb->get_results("SELECT abs_dat, count(*) AS cnt 
									   FROM {$wpdb->prefix}ad_buttons_stats
									  WHERE abs_view = 0 AND abs_dat LIKE '$checkdate%' 
									  GROUP by abs_dat");

// Create 2 dimensional array 
$stat_values = array(array());

// Number of days in month
$days = ad_buttons_monthdays($graphmonth,$graphyear);

// Fill array with days of month
while($days > 0){
	$stat_values[$graphyear.'-'.$graphmonth.'-'.sprintf("%02s", $days)]['views']= 0;
	$stat_values[$graphyear.'-'.$graphmonth.'-'.sprintf("%02s", $days)]['clicks']= 0;
	$days = $days - 1;
}

// Sort the array
ksort($stat_values);

$days = ad_buttons_monthdays($graphmonth,$graphyear);

// initialize max variables
$max_view = 0;
$max_clicks = 0;

foreach($view_counter as $view){				
	$stat_values[$view->abs_dat]['views']= $view->cnt;
	if($view->cnt > $max_view){
		$max_view = $view->cnt;
	}
}				
foreach($click_counter as $click){				
	$stat_values[$click->abs_dat]['clicks']= $click->cnt;
	if($click->cnt > $max_clicks){
		$max_clicks = $click->cnt;
	}
}
// Get stats from history
$view_counter = $wpdb->get_results("SELECT abs_dat, sum(abs_view) AS cnt 
									  FROM ".$wpdb->prefix."ad_buttons_stats_hst
									 WHERE abs_dat LIKE '$checkdate%' 
									 GROUP by abs_dat");
									 
$click_counter = $wpdb->get_results("SELECT abs_dat, sum(abs_click) AS cnt 
									   FROM ".$wpdb->prefix."ad_buttons_stats_hst
									  WHERE abs_dat LIKE '$checkdate%' 
									  GROUP by abs_dat");

foreach($view_counter as $view){				
	$stat_values[$view->abs_dat]['views']= $stat_values[$view->abs_dat]['views'] + $view->cnt;
	if($view->cnt > $max_view){
		$max_view = $view->cnt;
	}
}

foreach($click_counter as $click){				
	$stat_values[$click->abs_dat]['clicks']= $stat_values[$click->abs_dat]['clicks'] + $click->cnt;
	if($click->cnt > $max_clicks){
		$max_clicks = $click->cnt;
	}
}

if($max_clicks == 0) $max_clicks = 1;
if($max_view < 8) $max_view = 8;
if(ceil($max_view/200)>floor(200/$max_view)){
	$view_scale = ceil($max_view/200);
	$view_scale_dir = 'div';
}else{
	$view_scale = floor(200/$max_view);
	$view_scale_dir = 'mul';
}

$statdays = array_keys($stat_values);

// set the HTTP header type to PNG
header("Content-type: image/png"); 

$days = ad_buttons_monthdays($graphmonth,$graphyear);

// set the width and height of the new image in pixels
$width = 740;
$height = 300;
$graph_width = 20 * $days;
$graph_height = 200;

// create a pointer to a new true color image
$im = imagecreatetruecolor($width, $height); 
 
// switch on image antialising if it is available
imageantialias($im, true);
// define colors
$white 		= imagecolorallocate($im, 255, 255, 255);  
$black 		= imagecolorallocate($im, 0, 0, 0);
$blue 		= imagecolorallocate($im, 0, 0, 255);
$lightblue 	= imagecolorallocate($im, 144, 186, 205);
$grey 		= imagecolorallocate($im, 210, 210, 210);
$lightgrey 	= imagecolorallocate($im, 245, 245, 245);
$grey_lines = imagecolorallocate($im, 225, 225, 225);
$darkgrey 	= imagecolorallocate($im, 70, 70, 70);

// sets background to white
imagefilltoborder($im, 0, 0, $white, $white);
 
// define the dimensions of our rectangle
$r_x = 60;
$r_y = 40;
 
imagerectangle($im, $r_x, $r_y, $r_x+$graph_width, $r_y+$graph_height, $black);

imagefilledrectangle($im, $r_x+1, $r_y+1, $r_x+$graph_width-1, $r_y+199, $lightgrey);

imageline($im, $r_x+1, $r_y+25, $r_x + $graph_width - 1, $r_y+25, $grey_lines);
imageline($im, $r_x+1, $r_y+50, $r_x + $graph_width - 1, $r_y+50, $grey_lines);
imageline($im, $r_x+1, $r_y+75, $r_x + $graph_width - 1, $r_y+75, $grey_lines);
imageline($im, $r_x+1, $r_y+100, $r_x + $graph_width - 1, $r_y+100, $grey_lines);
imageline($im, $r_x+1, $r_y+125, $r_x + $graph_width - 1, $r_y+125, $grey_lines);
imageline($im, $r_x+1, $r_y+150, $r_x + $graph_width - 1, $r_y+150, $grey_lines);
imageline($im, $r_x+1, $r_y+175, $r_x + $graph_width - 1, $r_y+175, $grey_lines);



// left side scale (views)

if ($view_scale_dir == 'mul'){
	$max_views = 200 / $view_scale;
} else {
	$max_views = 200 * $view_scale;
}
$length1 = strlen(round($max_views/4));
$length2 = strlen(round($max_views/4*2));
$length3 = strlen(round($max_views/4*3));
$length4 = strlen(round($max_views));
$length5 = 7;

$value1 = round($max_views/4);
$value2 = round($max_views/4*2);
$value3 = round($max_views/4*3);
$value4 = round($max_views);

if($length4 < $length5){
	$tmpcnt = 0;
	while(($length5-$length4) > $tmpcnt){
		$value4 = ' '.$value4;
		$tmpcnt = $tmpcnt + 1;
	}
}

if($length3 < $length5){
	$tmpcnt = 0;
	while(($length5-$length3) > $tmpcnt){
		$value3 = ' '.$value3;
		$tmpcnt = $tmpcnt + 1;
	}
}

if($length2 < $length5){
	$tmpcnt = 0;
	while(($length5-$length2) > $tmpcnt){
		$value2 = ' '.$value2;
		$tmpcnt = $tmpcnt + 1;
	}
}

if($length1 < $length5){
	$tmpcnt = 0;
	while(($length5-$length1) > $tmpcnt){
		$value1 = ' '.$value1;
		$tmpcnt = $tmpcnt + 1;
	}
}

imagestring($im, 2, 10, 234, '      0', $black);
imagestring($im, 2, 10, 184, $value1, $black);
imagestring($im, 2, 10, 134, $value2, $black);
imagestring($im, 2, 10, 84, $value3, $black);
imagestring($im, 2, 10, 34, $value4, $black);

// right side scale (clicks)

$max_clicks = ceil($max_clicks/8) * 8;

imagestring($im, 2, 690, 234, '0', $black);
imagestring($im, 2, 690, 184, round($max_clicks/4), $black);
imagestring($im, 2, 690, 134, round($max_clicks/4*2), $black);
imagestring($im, 2, 690, 84, round($max_clicks/4*3), $black);
imagestring($im, 2, 690, 34, round($max_clicks), $black);

// define the dimensions of our filled rectangle
$r_width = 18;
$r_height = 100;
$r_x = 61;
$r_y = 110;
$count = 0;
$day = 1;
$statdays = array_keys($stat_values);

while($count<$days){
	$count = $count + 1;
	$daynum = substr($statdays[$count], 8,2);
	if($daynum){
		$r_x = 41 + $daynum * 20;
		if($view_scale_dir == 'mul'){
			$r_height = $stat_values[$statdays[$count]]['views'] * $view_scale;
		}else{
			$r_height = round($stat_values[$statdays[$count]]['views'] / $view_scale);
		}
		$r_y = 239 - $r_height;
		imagefilledrectangle($im, $r_x, $r_y, $r_x+$r_width, $r_y+$r_height, $lightblue);
	}
}

$count = 0;
$r_x = 70;

while($count<$days){
	$count = $count + 1;
	$daynum = substr($statdays[$count], 8,2);
	if($daynum){
		$r_x = 50 + $daynum * 20;
		$click_scale = 200 / $max_clicks;
		$r_y = 240 - ($click_scale * $stat_values[$statdays[$count]]['clicks']);
		if ($count<$days){
			$r_y2 = 240 - ($click_scale * $stat_values[$statdays[$count + 1]]['clicks']);
			// make a new line and add it to the image
			imageline($im, $r_x, $r_y, $r_x + 20, $r_y2, $darkgrey);
		}
		// draw a dot at the line start
		imagefilledellipse($im, $r_x, $r_y, 5, 5, $darkgrey);
	}	
}

$count = 0;
$r_x = 70;

while($count<$days){
	$count = $count + 1;
	// write the day numbers
	if($count<10){
		$day_pos = 2;
	} else {
		$day_pos = 5;
	}
	imagestring($im, 2, $r_x - $day_pos, 250, $count, $black);
	$r_x = $r_x + 20;
}

// add some text to the image
// header
$header_text = "Ad Buttons stats for $stringmonth $graphyear";
imagestring($im,5,70,10,$header_text, $black);

// send the new PNG image to the browser
imagepng($im); 
 
// destroy the reference pointer to the image in memory to free up resources
imagedestroy($im); 
?>
