<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $wpdb;

$ol_flash 	= '';
$htp 		= "http://";
$htps		= "https://";
$ab_img 	= $htp;
$ab_link	= $htp;
$ab_img_err	= '';
$ab_link_err= '';
$ab_formfunc= 'add';
$ad_button_action = '';
$ad_button = 0;

$ab_txt		= '';
$ab_views = '';
$ab_clicks = '';
$ab_pos  = '';


if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
	if(!empty($_GET['action']) && !empty($_GET['adbut'])){
		$ad_button_action = sanitize_text_field( $_GET['action'] );
		$ad_button = intval( $_GET['adbut'] );
		//check if the nonce is set
		if ( ! isset( $_GET['_abnonce'] ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
		   // process form data
			if($ad_button_action == 'deactivate' && wp_verify_nonce( $_GET['_abnonce'], 'deactivate' )) {
				$ol_flash = "Ad Button $ad_button has been deactivated.";
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}ad_buttons 
								 SET ad_active = 0 
							   WHERE id = %d",$ad_button));
			} elseif($ad_button_action == 'activate' && wp_verify_nonce( $_GET['_abnonce'], 'activate' )) {
				$ol_flash = "Ad Button $ad_button has been activated.";
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}ad_buttons 
								 SET ad_active = 1 
							   WHERE id = %d",$ad_button));
			} elseif($ad_button_action == 'delete' && wp_verify_nonce( $_GET['_abnonce'], 'delete' )) {
				$ol_flash = "Ad Button $ad_button has been deleted.";
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}ad_buttons 
								 SET ad_active = 2 
							   WHERE id = %d",$ad_button));	
			} elseif($ad_button_action == 'edit' && wp_verify_nonce( $_GET['_abnonce'], 'edit' )) {
				$ab_formfunc= 'edit';
				$this_ad = $wpdb->get_row($wpdb->prepare("SELECT * 
											 FROM {$wpdb->prefix}ad_buttons 
											WHERE id = %d",$ad_button));
				$ab_img 	= $this_ad->ad_picture;
				$ab_link	= $this_ad->ad_link;
				$ab_txt		= $this_ad->ad_text;
				$ab_views 	= $this_ad->ad_views;
				$ab_clicks 	= $this_ad->ad_clicks;
				$ab_pos  	= $this_ad->ad_pos;
				$ab_adbut	= $this_ad->id;
			} else {
				print 'Sorry, your nonce did not verify.';
				exit;
			}
		}
	}
}

$widget_adbuttons_cfg = get_option('widget_adbuttons_cfg');

// check if the form has been submitted and validate input
if( $_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can( 'manage_options' )){
	if ( ! isset( $_POST['_abupd'] ) || ! wp_verify_nonce( $_POST['_abupd'], 'update-ad' )) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
	   // process form data
		if(!empty($_POST['ab_img']) || !empty($_POST['ab_link']) || !empty($_POST['ab_txt'])) {		
			if (!empty($_POST['ab_img'])) { 
				$ab_img = $htp.str_replace($htps, "", str_replace($htp, "", esc_url($_POST['ab_img'])));
			}

			if (!empty($_POST['ab_link'])) {
				if(substr($_POST['ab_link'], 0, 7) == $htp){
					$ab_link = esc_url($_POST['ab_link']);
				}elseif(substr($_POST['ab_link'], 0, 8) == $htps){
					$ab_link = esc_url($_POST['ab_link']);
				}else{
					$ab_link = esc_url($htp.$_POST['ab_link']);
				}
			}
			
			if (!empty($_POST['ab_adbut'])) { 
				$ad_button = intval( $_POST['ab_adbut'] );
			}

			if (!empty($_POST['ab_txt'])) { 
				$ab_txt = sanitize_text_field( $_POST['ab_txt'] );
			}
			
			if (!empty($_POST['ab_formfunc'])) { 
				$ab_formfunc = sanitize_text_field( $_POST['ab_formfunc'] );
			}

			if (!empty($_POST['ab_views'])) { 
				$ab_views = intval( $_POST['ab_views'] );
			}

			if (!empty($_POST['ab_clicks'])) { 
				$ab_clicks = intval( $_POST['ab_clicks'] );
			}

			if (!empty($_POST['ab_pos'])) { 
				$ab_pos = intval( $_POST['ab_pos'] );
			}

			if($ab_img == $htp || $ab_img == ''){
				$ab_img_err = 'Please fill in the link to your image file';
			}
			if($ab_link == $htp || $ab_link == ''){
				$ab_link_err = 'Please fill in the target link for your ad';
			}
			
			if($ab_img_err == '' && $ab_link_err == ''){
				// everything looks good, lets write to the database
				if($ab_formfunc=='add'){
					$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}ad_buttons 
										 (ad_picture, ad_link, ad_text, ad_active, ad_views, 
										  ad_clicks, ad_pos)
								  VALUES ( %s, %s, %s, 0, %d, %d, %d )", $ab_img, $ab_link, $ab_txt, $ab_views, $ab_clicks, $ab_pos));
					$ol_flash = 'Your Ad Button has been created!';
					$ab_img 	= $htp;
					$ab_link	= $htp;
					$ab_txt	= '';
					$ab_img_err	= '';
					$ab_link_err= '';
				}elseif($ab_formfunc=='edit'){
					$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}ad_buttons 
									 SET ad_picture = %s, ad_link = %s, 
										 ad_text = %s, ad_views = %d, 
										 ad_clicks = %d, ad_pos = %d 
								   WHERE id = %d", $ab_img, $ab_link, $ab_txt, $ab_views, $ab_clicks, $ab_pos, $ad_button));
					$ol_flash = "Ad Button $ad_button has been updated.";
				}	
			}
		}

	}
?>
<?php if ($ol_flash != '') echo '<div id="message"class="updated fade"><p>' . esc_html($ol_flash) . '</p></div>'; ?>
<div class="wrap">

<h2>Ad Buttons ad management</h2>

<?php if ($ab_formfunc=='edit'){ 
		echo "<h3>Edit Ad Button</h3>";
}else{
	echo "<h3>Create new Ad Button</h3>";}
}
?>

<p><form method="post" name="ab_form">
<?php wp_nonce_field('update-ad', '_abupd'); 
$widget_adbuttons_cfg = get_option('widget_adbuttons_cfg');
?>

<input type="hidden" name="ab_adbut" value="<?php echo esc_html($ad_button); ?>">
<table class="form-table">

<tr valign="top">
<th scope="row">Ad Button Image </th>
<td><input name="ab_img" type="text" value="<?php echo esc_html($ab_img); ?>" size="40" /> <?php if($ab_img_err)echo esc_html($ab_img_err); ?></td>
<td rowspan="3"><?php if ($ad_button_action == 'edit'){echo'<a href="'.esc_html($ab_link).'" target="_blank" title="'.esc_html($ab_txt).'"><img src="'.esc_html($ab_img).'" alt="'.esc_html($ab_txt).'"  align="left" vspace="10" hspace="10" border="0"></a>';}?></td>
</tr>

<tr valign="top">
<th scope="row">Ad Button Link </th>
<td><input name="ab_link" type="text" value="<?php echo esc_html($ab_link); ?>" size="40" /> <?php if($ab_link_err)echo esc_html($ab_link_err); ?></td>
</tr>
<tr valign="top">
<th scope="row">Ad Button Text </th>
<td><input name="ab_txt" type="text" value="<?php echo esc_html($ab_txt); ?>" size="40" /></td>
</tr>
<tr valign="top">
<th scope="row">Ad position</th>
<td><input name="ab_pos" type="text" value="<?php echo esc_html($ab_pos); ?>" size="40" /></td>
<td>change the order of the ads, a higher number means the ad will move down in the list </td>
</tr>
<tr valign="top">
<th scope="row">Counters</th>
<td><input name="ab_views" type="text" value="<?php echo esc_html($ab_views); ?>" size="9" /> views<br>
	<input name="ab_clicks" type="text" value="<?php echo esc_html($ab_clicks); ?>" size="9" /> clicks</td>
<td>This only resets the views and clicks seen on this screen. Detailed view and click information is stored elsewhere. 
Viewing detailed statistics is being worked on and will be incorporated into a future release.</td>
</tr>
</table>
<p class="submit">
<input type="hidden" name="ab_formfunc" value="<?php echo esc_html($ab_formfunc); ?>">
<input type="submit" name="Submit" value="<?php if ($ab_formfunc=='edit'){ 
		echo "Update Ad Button";
}else{
	echo "Create Ad Button";}
?>" />
</p>

</form>

<h3 id="currently-active">Active Ad Buttons</h3>
<table class="widefat" id="active-plugins-table">
	<thead>
	<tr>
		<th scope="col" class="manage-column column-comment column-primary">Ad ID</th>
		<th scope="col" class="manage-column column-comment column-primary">Ad Button</th>
		<th scope="col" class="manage-column column-comment column-primary">Ad Text</th>
		<th scope="col" class="manage-column column-comment column-primary">Ad Views</th>
		<th scope="col" class="manage-column column-comment column-primary">Ad Clicks</th>
		<th scope="col" class="manage-column column-comment column-primary">CTR</th>
		<th scope="col" class="action-links">Action</th>
	</tr>
	</thead>
	<tbody class="plugins">
<?php
$results = $wpdb->get_results("SELECT * FROM "."{$wpdb->prefix}ad_buttons WHERE ad_active = 1");
foreach($results as $result){
	if($result->ad_views){
		$ad_ctr = round((($result->ad_clicks / $result->ad_views) * 100 ), 2);
	}else{
		$ad_ctr = 0;
	}

	echo  '
		<tr class="active">
			<td class="vers">'.esc_html($result->id).'</td>
			<td class="name"><a href="'.esc_url($result->ad_link).'" target="_blank" title="'.esc_html($result->ad_text).'"><img src="'.esc_url($result->ad_picture).'" alt="'.esc_html($result->ad_text).'"  align="left" vspace="10" hspace="10" border="0"></a></td>
			<td class="vers">'.esc_html($result->ad_text).'</td>
			<td class="vers">'.esc_html($result->ad_views).'</td>
			<td class="vers">'.esc_html($result->ad_clicks).'</td>
			<td class="vers">'.esc_html($ad_ctr).'%</td>
			<td class="togl action-links"><a href="'.esc_url(add_query_arg('_abnonce', wp_create_nonce('deactivate'), '?page=ad-buttons/adbuttons.php&action=deactivate&adbut='.$result->id )).'" title="Deactivate this Ad Button" class="delete">Deactivate</a><br/>
			<a href="'.esc_url(add_query_arg('_abnonce', wp_create_nonce('edit'), '?page=ad-buttons/adbuttons.php&action=edit&adbut='.$result->id )).'" title="Edit this Ad Button" class="delete">Edit</a></td> 
		</tr>
	';
}
?>
</tbody>
</table>

<h3 id="inactive-plugins">Inactive Ad Buttons</h3>
<table class="widefat" id="inactive-plugins-table">
	<thead>
	<tr>
		<th scope="col" class="manage-column column-comment column-primary">Ad ID</th>
		<th scope="col" class="manage-column column-comment column-primary">Ad Button</th>
		<th scope="col" class="manage-column column-comment column-primary">Ad Text</th>
		<th scope="col" class="manage-column column-comment column-primary">Ad Views</th>
		<th scope="col" class="manage-column column-comment column-primary">Ad Clicks</th>
		<th scope="col" class="manage-column column-comment column-primary">CTR</th>
		<th scope="col" class="action-links">Action</th>
	</tr>
	</thead>
	<tbody class="plugins">
<?php
$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ad_buttons WHERE ad_active = 0");
foreach($results as $result){
	if($result->ad_views){
		$ad_ctr = round((($result->ad_clicks / $result->ad_views) * 100 ), 2);
	}else{
		$ad_ctr = 0;
	}

	echo  '
		<tr class="inactive">
			<td class="vers">'.esc_html($result->id).'</td>
			<td class="name"><a href="'.esc_url($result->ad_link).'" target="_blank" title="'.esc_html($result->ad_text).'"><img src="'.esc_url($result->ad_picture).'" alt="'.esc_html($result->ad_text).'"  align="left" vspace="10" hspace="10" border="0"></a></td>
			<td class="vers">'.esc_html($result->ad_text).'</td>
			<td class="vers">'.esc_html($result->ad_views).'</td>
			<td class="vers">'.esc_html($result->ad_clicks).'</td>
			<td class="vers">'.esc_html($ad_ctr).'%</td>
			<td class="togl action-links"><a href="'.esc_url(add_query_arg('_abnonce', wp_create_nonce('activate'), '?page=ad-buttons/adbuttons.php&action=activate&adbut='.$result->id )).'" title="Activate this Ad Button" class="delete">Activate</a><br/>
			<a href="'.esc_url(add_query_arg('_abnonce', wp_create_nonce('edit'), '?page=ad-buttons/adbuttons.php&action=edit&adbut='.$result->id )).'" title="Edit this Ad Button" class="delete">Edit</a><br/><br> 
			<a href="'.esc_url(add_query_arg('_abnonce', wp_create_nonce('delete'), '?page=ad-buttons/adbuttons.php&action=delete&adbut='.$result->id )).'" title="Delete this Ad Button" class="delete">Delete</a></td> 
		</tr>
	';

}
?>
</tbody>
</table>
