<?php

class YouTubeVideo extends ActionPHPRapid_Model
{
	public function __construct()
	 {
	 	global $wpdb;

	 	$table_name = $wpdb->prefix . "tubeloot_videos";
		$this->table($table_name, 'id');
	 }
}