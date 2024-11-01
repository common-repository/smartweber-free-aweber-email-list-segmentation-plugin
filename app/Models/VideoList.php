<?php

class VideoList extends ActionPHPRapid_Model
{
	public function __construct()
	 {
	 	global $wpdb;

	 	$table_name = $wpdb->prefix . "tubeloot_video_lists";
		$this->table($table_name, 'id');
	 }
}