<?php
/*
+----------------------------------------------------------------+
|																						
|	WordPress Plugin: SalesCheater								
|	Copyright (c) 2012 Manfred Fettinger									
|																						
|	File Written By:														
|	- Manfred Fettinger									
|																						
|	File Information:																
|	- Sales Cheater Settings Menu											
|	- wp-content/plugins/salescheater/salescheater-settings.php		
|																						
+----------------------------------------------------------------+
*/

### Check Whether User Can Manage Ratings
if(!current_user_can('role_salescheater')) {
	die('Access Denied');
}

### If Form Is Submitted
if($_POST['sc_submit']) {
	global $wpdb;
	
	/*check_admin_referer('salescheater');*/
	$sc_hour 		  = intval($_POST['sc_hour']);
	$sc_minute        = intval($_POST['sc_minute']);
	$sc_min 		  = intval($_POST['sc_min']);
	$sc_max 		  = intval($_POST['sc_max']);
	$sc_template_text = trim($_POST['sc_template_text']);
	
	
	if($sc_minute == 0 && $sc_hour == 0)
		$txt = '<font color="red">Either hours or minutes have to be greater than 0</font>';
	
	elseif(!($sc_minute >= 0 && $sc_minute < 60))
		$txt = '<font color="red">If minutes are selected, valid values are from 0 to 59</font>';

	elseif(!($sc_hour >= 0 && $sc_hour < 24))
		$txt = '<font color="red">If hours are selected, valid values are from 0 to 23</font>';

	elseif($sc_max <= $sc_min)
		$txt = '<font color="red">Maximum sales value must be less than the minimum sales value</font>';

	elseif($sc_max < 0 || $sc_min < 0)
		$txt = '<font color="red">Sales values must be greater than 0</font>';
	
	if(!empty($txt))
	{
		echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$txt.'</p></div>';
	}
	else
	{
		$sc_update_queries = array();
		$sc_update_text = array();
		
		$sc_update_queries[] = update_option('sc_hour', $sc_hour);
		$sc_update_queries[] = update_option('sc_minute', $sc_minute);
		$sc_update_queries[] = update_option('sc_min', $sc_min);
		$sc_update_queries[] = update_option('sc_max', $sc_max);
		$sc_update_queries[] = update_option('sc_template_text', $sc_template_text);

		$i = 0;
		$text = '';
		foreach($sc_update_queries as $sc_update_query) {
			if($sc_update_query) {
				$text .= '<font color="green">'.$sc_update_text[$i].' Updated</font><br />';
				$updated = 'X';
			}
			$i++;
		}
		if(empty($text)) {
			$text = '<font color="red">No SalesCheater Option Updated</font>';
		}
		if(!empty($text)) {
			echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p>'; 
		}
		
		if($updated == 'X'){
			$sql = "DELETE FROM $wpdb->salescheater";
			$res = $wpdb->query($sql);
			echo '<p><font color="green">'.$res.' entries were deleted from database table!</font></p></div>';
		}else{
			echo '</div>';
		}
	}
}

global $title;

### Needed Variables
$sc_hour 	= get_option('sc_hour');
$sc_minute  = get_option('sc_minute');
$sc_min 	= get_option('sc_min');
$sc_max 	= get_option('sc_max');
?>
	<h2><?php echo $title; ?></h2>
	<h3>Sells Menu</h3>

<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>"> 
	<?php wp_nonce_field('salescheater_settings'); ?>
	<table class="widefat">
		<tr>
			<th scope="row" valign="top">Add sells every:</th>
			<td>
				<input type="text" id="sc_hour" name="sc_hour" value="<?php echo $sc_hour; ?>" size="3"/> [HH]
				<input type="text" id="sc_minute" name="sc_minute" value="<?php echo $sc_minute; ?>" size="3"/> [MM]
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Minimum sells per time unit:</th>
			<td>
				<input type="text" id="sc_min" name="sc_min" value="<?php echo $sc_min; ?>" size="3"/>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Maximum sells per time unit:</th>
			<td>
				<input type="text" id="sc_max" name="sc_max" value="<?php echo $sc_max; ?>" size="3"/>
			</td>
		</tr>
	</table>

<?php	
if($_POST['evaluate']){
	$sc_hour 	= get_option('sc_hour'); 
	$sc_minute 	= get_option('sc_minute');
	$sc_minute  = $sc_minute + ($sc_hour * 60);	#Convert to minutes
	
	$ts 		= time();
	$datum 		= date("Ymd",$ts);
	$stunde 	= date("H",$ts);
	$minute 	= date("i",$ts);
	$minute		= $minute + ($stunde * 60);
	
	echo '<div id="message" class="updated" style="text-align: center;"><font color="green">';
	echo 'Sell will be generated at<br>';
	for($i = 0; $i < 1440; $i+=$sc_minute)				#A day has 1440 minutes
	{
		$stunde = intval($i/60);
		$minute = $i%60;
		if(strlen($stunde) == 1)
			$stunde = '0'.$stunde;
		if(strlen($minute) == 1)
			$minute = $minute.'0';
			
		echo $stunde.':'.$minute.'<br>';
	}
	echo '</font></div>';

}
?>
	
	<p class="submit" style="text-align: center;">
		<input type="submit" name="evaluate" value="Evaluate Data" class="secondary-primary" />
	</p>
	
	<br>
	<h3>Sells Text Template</h3>
	<table class="form-table">
		 <tr>
			<td width="30%">
				<strong>Text:</strong><br /><br />
				Allowed Variables:
				<p style="margin: 2px 0">- %SC_DATE%</p>
				<p style="margin: 2px 0">- %SC_SELLS%</p>
				<br />
			</td>
			<td>
				<textarea cols="80" rows="15" id="sc_template_text" name="sc_template_text"><?php echo htmlspecialchars(stripslashes(get_option('sc_template_text'))); ?></textarea>
			</td>
		</tr>
	</table>
	
	<p class="submit" style="text-align: center;">
		<input type="submit" name="sc_submit" class="button-primary" value="Save Changes" />
	</p>
</form>

