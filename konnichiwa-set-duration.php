<?php
/*
Plugin Name: Konnichiwa! Set Duration to 2100
Description: Gives the admin an easy way to set a plan's duration to the year 2100 to mimic an unending duration.
Author: admiralchip
Version: 0.1
License: GPLv2 or later
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

add_action("admin_menu", "ad_konn_change_plan");

function ad_konn_change_plan() {
	add_options_page('Konnichiwa! 2100', 'Konnichiwa! 2100', 'manage_options', 'adkonn-change-plan', 'ad_konn_change');
}

function ad_konn_change(){
	echo '<h2>' . _e('Konnichiwa! Set Duration to 2100').'</h2>';
	echo '<p>' . _e('Choose a plan and set its duration to the year 2100.') . '</p>';
	
	if( isset( $_POST['change_duration'] ) ) {
		$plan_id = trim( $_POST['plan_id'] );
		global $wpdb;
		$plan_table = $wpdb->prefix . "konnichiwa_plans";
		$current_year = date('Y');
		$year_diff = 2100 - $current_year;
		$wpdb->update( $plan_table, 
			array(
				'duration' => $year_diff,
				'duration_unit' => 'year'
			),
			array( 'id' => $plan_id ),
			array(
				'%s',
				'%s'
			),
			array( '%d' )			
		);
		echo '<p><strong>' . _e('Plan Updated!') . '</strong></p>';
	}
	
	global $wpdb;
	$plan_table = $wpdb->prefix . "konnichiwa_plans";
	$plan_query = $wpdb->get_results( "SELECT id, name FROM " . $plan_table );
	
	if( $plan_query ) {
		?>
		<form method="POST" action="">
			<select name="plan_id">
		<?php
		foreach( $plan_query as $plans ) {
			$plan_id = $plans->id;
			$plan = $plans->name;
			?>
			<option value="<?php echo $plan_id; ?>"><?php echo $plan; ?></option>
			<?php
		}
		?>
			</select>
			<input type="submit" name="change_duration" value="Change" />
		</form>
		<?php
	} else {
		echo '<p><strong><em>' . _e('No plans were created.') . '</em></strong></p>';
	}
}

?>
