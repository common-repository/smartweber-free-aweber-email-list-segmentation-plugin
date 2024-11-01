<?php

class Link extends ActionPHPRapid_Model
{
	public function __construct()
	 {
	 	global $wpdb;

	 	$table_name = $wpdb->prefix . "smartweber_links";
		$this->table($table_name, 'id');
	 }
}