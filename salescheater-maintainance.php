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
|	- Sales Cheater Maintainance Menu											
|	- wp-content/plugins/salescheater/salescheater-maintainance.php		
|																						
+----------------------------------------------------------------+
*/

### Check Whether User Can Manage Ratings
if(!current_user_can('role_salescheater')) {
	die('Access Denied');
}

### If Form Is Submitted
if($_POST['sc_dbdelete']) {
	global $wpdb;
	$sql = "DELETE FROM $wpdb->salescheater";
	$res = $wpdb->query($sql);
	echo '<div id="message" class="updated fade"><p>'; 
	echo '<p><font color="green">'.$res.' entries were deleted from database table!</font></p></div>';
}elseif($_POST['sc_saveauto']){
	$anz = intval($_POST['sc_autodelentries']);
	$off = trim($_POST['sc_autodelentries']);
	if(strtoupper($off) == 'OFF'){
		update_option('sc_autodelentries', 'off');
		echo '<div id="message" class="updated fade"><p>'; 
		echo '<p><font color="green">Automatic maintainance was turned off!</font></p></div>';
	}else{
		update_option('sc_autodelentries', $anz);
		echo '<div id="message" class="updated fade"><p>'; 
		echo '<p><font color="green">Automatic maintainance will start if '.$anz.' entries are in database table!</font></p></div>';
	}
}

global $title;
echo '<h2>'.$title.'</h2>';

### Needed Variables
$sc_autodelentries 	= get_option('sc_autodelentries');


### Read actual number of lines in database
$sql="Select count(*) from $wpdb->salescheater";
$anzahl = $wpdb->get_var($sql);


echo '<table class="widefat">';
echo 	'<tr>';
echo 		'<th><b>Maintain manually</b></th>';
echo 		'<th></th>';
echo 	'</tr>';
echo 	'<tr>';
echo 		'<td>';

if($anzahl > 0){
	echo 'Currently there are <b>'.$anzahl.'</b> entries in the Sales Cheater database table.<br>';
	echo 'Click Button to delete all entries!';
	
}else{
	echo 'Currently there is nothing to maintain because there are no entries in the Sales Cheater database table';
}
echo '</td>';
echo '<td>';
if($anzahl > 0){
?>
			<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>"> 
				<?php wp_nonce_field('salescheater_maintainance'); ?>
				<p class="submit" style="text-align: center;">
					<input type="submit" name="sc_dbdelete" class="button-primary" value="Delete entries in database table" />
				</p>
<?php
} //End if $anzahl
?>
			</form>
		</td>
	</tr>
</table>
<br>
<table class="widefat">
	<tr>
		<th><b>Maintain automatically</b></th>
		<th></th>
	</tr>
	<tr>
		<td>
			Please indicate the number of entries for which you want to delete the database table<br><br>
			If you want to turn off, please insert <b>off</b> in the text field!
		</td>
		<td>
			<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
				Enter number or 'off':&nbsp<input type="text" id="sc_autodelentries" name="sc_autodelentries" value="<?php echo $sc_autodelentries; ?>" size="3"/>
				<input type="submit" name="sc_saveauto" class="button-primary" value="Save" />
			</form>
		</td>
	</tr>
</table>



