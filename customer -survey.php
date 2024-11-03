<?php 
/*
	Plugin Name: Customer Survey
	Plugin URI: https://github.com/wagnerlima85/customer-survey/
	Description: A WordPress plugin focused on receiving and displaying customer feedback within the admin dashboard.
	Author: Wagner Lima
	Author URI: https://br.linkedin.com/in/wagnerlima
	Version: 0.2
	Text Domain: customer-survey
	License: GPL v2 or later
*/

$return_message = '';
$survey_input_field = array(
	'message' => '<textarea name="cs_message" id="cs_message" class="form-control" rows="3" placeholder="Enter your feedback"></textarea>'
);

function customer_survey_activate() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS wp_customer_survey (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		message text NOT NULL,
		created_by varchar(255) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once(ABSPATH. 'wp-admin/includes/upgrade.php' );
	dbDelta($sql);
}

register_activation_hook(__FILE__, 'customer_survey_activate');

function add_customer_survey_menu() {
	add_menu_page('Customer Survey', 'Customer Survey', 'manage_options', 'customer-survey', 'display_survey_form', 'dashicons-feedback', 5);
	add_submenu_page('customer-survey', 'View Feedback', 'View Feedback', 'manage_options', 'view-feedback', 'display_survey_feedback');
}

function display_survey_form() {
	global $survey_input_field, $return_message;

	$message = isset($_POST['cs_message']) ? stripslashes(trim($_POST['cs_message'])) : '';

	if (isset($_POST['submit'])) {
		if (empty($message) || strlen($message) < 10) {
			$return_message = '<div class="alert alert-danger" role="alert">The feedback field must contain at least 10 characters.</div>';
		} else {
			insert_customer_survey($message);
			$return_message = '<div class="alert alert-success" role="alert">Feedback submitted successfully.</div>';
		}
	}

	$url_styles = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">';

	$survey_form = '
	<div class="container">
		<div class="py-5">
			<h4>Welcome to Customer Survey</h4>
			<p class="lead">Please submit your feedback to help us improve our service.</p>
		</div>
		<div class="row">
			<div class="col-md-12">
				<form action="" method="POST">
					<div class="form-group">
						<label for="cs_message">Feedback</label>
						'. $survey_input_field['message'] .'
						'. $return_message .'
					</div>
					<button name="submit" type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
	' . $url_styles;

	echo $survey_form;
}

add_action('admin_menu', 'add_customer_survey_menu');

function insert_customer_survey($msg) {
	global $wpdb;

	$curr_user = wp_get_current_user();
	$email = $curr_user->user_email;
	$cur_date = date("Y-m-d H:i:s");

	$wpdb->query("INSERT INTO wp_customer_survey 
			(message, created_by, created_at) 
			VALUES 
			('$msg', '$email', '$cur_date')");
}

// New function to display feedback entries in an admin page
function display_survey_feedback() {
	global $wpdb;

	// Retrieve all feedback entries from the database
	$results = $wpdb->get_results("SELECT * FROM wp_customer_survey ORDER BY created_at DESC");

	// Display feedback entries in a table format
	echo '<div class="wrap">';
	echo '<h2>Submitted Feedback</h2>';
	echo '<table class="wp-list-table widefat fixed striped">';
	echo '<thead>
			<tr>
				<th scope="col" class="manage-column column-id">ID</th>
				<th scope="col" class="manage-column column-message">Message</th>
				<th scope="col" class="manage-column column-user">Submitted By</th>
				<th scope="col" class="manage-column column-date">Date</th>
			</tr>
		  </thead>';
	echo '<tbody>';

	// Display each feedback entry
	if ($results) {
		foreach ($results as $row) {
			echo '<tr>';
			echo '<td>' . esc_html($row->id) . '</td>';
			echo '<td>' . esc_html($row->message) . '</td>';
			echo '<td>' . esc_html($row->created_by) . '</td>';
			echo '<td>' . esc_html($row->created_at) . '</td>';
			echo '</tr>';
		}
	} else {
		echo '<tr><td colspan="4">No feedback has been submitted yet.</td></tr>';
	}

	echo '</tbody>';
	echo '</table>';
	echo '</div>';
}
