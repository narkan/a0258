<?php

/**
 * Manages the interactions with the database
 * User: NARKAN
 * Date: 24/03/2017
 * Time: 16:58
 */
class DBhandler {

	private $cpd_db_version = '1.0';

	/**
	 * DBhandler constructor.
	 * Registers the activation hook.
	 */
	function __construct() {
		register_activation_hook( __FILE__ , array( $this, 'cpd_install' ) );
	}

	/**
	 * Creates the 'organisation' DB table on activation of plugin
	 */
	private function cpd_install() {
		global $wpdb;


		$table_name = CP_DATABASE_PREFIX . 'organisation';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		text text NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) {$charset_collate};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'cpd_db_version', $this->cpd_db_version );
	}

	function cpd_install_data() {
		global $wpdb;

		$welcome_name = 'Mr. WordPress';
		$welcome_text = 'Congratulations, you just completed the installation!';

		$table_name = CP_DATABASE_PREFIX . 'liveshoutbox';

		$wpdb->insert(
			$table_name,
			array(
				'time' => current_time( 'mysql' ),
				'name' => $welcome_name,
				'text' => $welcome_text,
			)
		);
	}

}