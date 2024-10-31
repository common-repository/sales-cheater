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
|	- Remove all SalesCheater data from Wordpress			
|	- wp-content/plugins/salescheater/salescheater-uninstall.php		
|																						
+----------------------------------------------------------------+
*/


### Check Whether User Can Manage Ratings
if(!current_user_can('role_salescheater')) {
	#die('Access Denied');
}



### Ratings Variables
$mode = trim($_GET['mode']);
$sc_tables   = array($wpdb->salescheater);
$sc_settings = array('sc_hour', 'sc_minute', 'sc_min', 'sc_max', 'sc_autodelentries', 'sc_template_text');



### Form Processing 
if(!empty($_POST['do'])) {
	// Decide What To Do
	switch($_POST['do']) {
		//  Uninstall SalesCheater
		case ('UNINSTALL SalesCheater') :
			check_admin_referer('salescheater_uninstall');
			if(trim($_POST['uninstall_yes']) == 'yes') {
				echo '<div id="message" class="updated fade">';
				echo '<p>';
				foreach($sc_tables as $table) {
					$wpdb->query("DROP TABLE $table");
					echo '<font style="color: green;">';
					printf('Table \'%s\' has been deleted.', "<strong><em>{$table}</em></strong>");
					echo '</font><br />';
				}
				echo '</p>';
				echo '<p>';
				
				foreach($sc_settings as $setting) {
					$delete_setting = delete_option($setting);
					if($delete_setting) {
						echo '<font color="green">';
						printf('Setting Key \'%s\' has been deleted.', "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					} else {
						echo '<font color="red">';
						printf('Error deleting Setting Key \'%s\' or Setting Key \'%s\' does not exist.', "<strong><em>{$setting}</em></strong>", "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					}
				}
				echo '</p>';
				echo '</div>'; 
				$mode = 'end-UNINSTALL';
			}
			break;
	}
}


### Determines Which Mode It Is
switch($mode) {
		//  Deactivating SalesCheater
		case 'end-UNINSTALL':
			$deactivate_url = 'plugins.php?action=deactivate&amp;plugin=sales-cheater/salescheater.php';
			if(function_exists('wp_nonce_url')) { 
				$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_sales-cheater/salescheater.php');
			}
			echo '<div class="wrap">';
			echo '<h2>Uninstall SalesCheater</h2>';
			echo '<p><strong>'.sprintf('<a href="%s">Click Here</a> to finish the uninstallation and SalesCheater will be deactivated automatically.', $deactivate_url).'</strong></p>';
			echo '</div>';
			break;
	// Main Page
	default:
			global $title;	
			echo '<h2>'.$title.'</h2>';
?>

<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
<?php wp_nonce_field('salescheater_uninstall'); ?>
<div class="wrap">
	<p>
		Deactivating SalesCheater plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.
	</p>
	<p style="color: red">
		<strong>WARNING:</strong><br />
		Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.
	</p>
	<p style="color: red">
		<strong>The following WordPress Options and Tables will be DELETED:</strong><br />
	</p>
	<table class="widefat">
		<thead>
			<tr>
				<th>WordPress Options</th>
				<th>WordPress Tables</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td valign="top">
					<ol>
					<?php
						foreach($sc_settings as $settings) {
							echo '<li>'.$settings.'</li>'."\n";
						}
					?>
					</ol>
				</td>
				<td valign="top" class="alternate">
					<ol>
					<?php
						foreach($sc_tables as $tables) {
							echo '<li>'.$tables.'</li>'."\n";
						}
					?>
					</ol>
				</td>
			</tr>
		</tbody>
	</table>
	<p>&nbsp;</p>
	<p style="text-align: center;">
		<input type="checkbox" name="uninstall_yes" value="yes" />&nbsp;Yes<br /><br />
		<input type="submit" name="do" value="UNINSTALL SalesCheater" class="button-primary" onclick="return confirm('You Are About To Uninstall SalesCheater From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.')" />
	</p>
</div>
</form>

<?php
} // End switch($mode)
?>

