<?php
/*
Plugin Name: Sales Cheater
Plugin URI: http://sites.google.com/site/manfred.fettinger/
Description: Tells your visitor how many pieces of a product were sold - even if no one was sold.
Version: 1.0
Author: Manfred Fettinger
Author URI: http://sites.google.com/site/manfred.fettinger/
*/

/*  Copyright 2012  Manfred Fettinger  (email : manfred.fettinger@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

### SalesCheater Logs Table Name
global $wpdb;
$wpdb->salescheater = $wpdb->prefix.'salescheater';


### Function: Print Out Sales-Text
add_filter("the_content","wpErsetzen");
function wpErsetzen($content) {
	global $wpdb;
	$suche = '[salescheater]';

	$sc_autodelentries 	= get_option('sc_autodelentries');
	$sc_min     = get_option('sc_min');
	$sc_max     = get_option('sc_max');
	$sc_hour 	= get_option('sc_hour'); 
	$sc_minute 	= get_option('sc_minute');
	$sc_minute  = $sc_minute + ($sc_hour * 60);	#Convert to minutes
	
	$ts 		= time();
	$datum 		= date("Ymd",$ts);
	$stunde 	= date("H",$ts);
	$minute 	= date("i",$ts);
	$minute		= $minute + ($stunde * 60);

	### Do autodeletion if wanted
	
	if(strtoupper($sc_autodelentries) <> 'OFF')
	{
		$sql="Select count(*) from $wpdb->salescheater";
		$anzahl = $wpdb->get_var($sql);	

		if($anzahl >= $sc_autodelentries){
		echo 'löschen<br>';
			$sql = "DELETE FROM $wpdb->salescheater";
			$res = $wpdb->query($sql);
		}
	}
	

	$sql="Select count(*) from $wpdb->salescheater where date = '$datum'";
	$anzahl = $wpdb->get_var($sql);
	if($anzahl == 0)   #Keine Einträge für heute vorhanden -> erstelle welche
	{
		for($i = 0; $i < 1440; $i+=$sc_minute)				#A day has 1440 minutes
		{
			$sold = rand($sc_min, $sc_max);
			$sql = "Insert into $wpdb->salescheater (date, minute, sells) values ('$datum', '$i', '$sold' )";
			$wpdb->query($sql);
		}
	}


	#Read actual sells
	$sql = "select sum(sells) from $wpdb->salescheater where minute < '$minute' and date = '$datum'"; 
	$sold = $wpdb->get_var($sql);
	if ($sold > 0){
		$ersetze = stripslashes(get_option('sc_template_text'));
		$ersetze = str_replace("%SC_SELLS%", $sold, $ersetze);
		$ersetze = str_replace("%SC_DATE%", date("j. F Y"), $ersetze);
	
		$content = str_replace($suche,$ersetze,$content);
	}
	return $content;
}


### Function: Ratings Administration Menu
add_action('admin_menu', 'salescheater_menu');
function salescheater_menu() {
	if (function_exists('add_menu_page')) 
		add_menu_page('Sales Cheater Settings', 'SalesCheater', 'role_salescheater', 'sales-cheater/salescheater-settings.php');
		
	if (function_exists('add_submenu_page')) {
		add_submenu_page('sales-cheater/salescheater-settings.php', 'Sales Cheater Settings',  'Settings',     'role_salescheater', 'sales-cheater/salescheater-settings.php');
		add_submenu_page('sales-cheater/salescheater-settings.php', 'Maintain Sales Cheater',  'Maintainance', 'role_salescheater', 'sales-cheater/salescheater-maintainance.php');
		add_submenu_page('sales-cheater/salescheater-settings.php', 'Uninstall Sales Cheater', 'Uninstall',    'role_salescheater', 'sales-cheater/salescheater-uninstall.php');
	}
	
}



### Function: Create Rating Logs Table
add_action('activate_sales-cheater/salescheater.php', 'init');
function init() {
	global $wpdb;
	
	$createtable = "CREATE TABLE IF NOT EXISTS $wpdb->salescheater (".
				  "date date NOT NULL,".
				  "minute int(11) NOT NULL,".
				  "sells int(11) NOT NULL,".
				  "PRIMARY KEY (date,minute)".
				  ") DEFAULT CHARSET=latin1;";

	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($createtable);

	update_option('sc_hour', '1', 'Hour for new sells');
	update_option('sc_minute', '0', 'Minute unit for new sells');
	update_option('sc_min', '0', 'Minimum sells');
	update_option('sc_max', '2', 'Maximum sells');
	update_option('sc_autodelentries', 'off', 'Delete automatically old entries');
	update_option('sc_template_text', '<center><strong><font size="4">Sold items today (%SC_DATE%):'.
								   '<font size="5" color="#ff0000">&nbsp;%SC_SELLS% </font></span></strong></center>');
	
	// Set 'manage_ratings' Capabilities To Administrator	
	$role = get_role('administrator');
	if(!$role->has_cap('role_salescheater')) {
		$role->add_cap('role_salescheater');
	}
}
?>