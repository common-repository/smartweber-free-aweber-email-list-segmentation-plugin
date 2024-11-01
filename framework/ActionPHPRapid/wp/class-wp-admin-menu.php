<?php

class ActionPHPRapid__WP_Admin_Menu
{
	public $page_title;
	public $title;
	public $capability = 'manage_options';
	public $slug;
	public $function;
	public $icon_url;
	public $position;
	public $menu_file;


	public function menu()
	{	
		add_action('admin_menu', array( $this, 'admin_menu') );


	}

	public function admin_menu()
	{

		if(!$this->slug){
			$this->slug = strtolower(str_replace(' ', '_', $this->title));
		}

		if(!$this->page_title){
			$this->page_title = $this->title;
		}

		if(!$this->function){

			$this->function = array( $this, 'menu_file');
		}

		add_menu_page($this->page_title, $this->title, $this->capability, $this->slug, $this->function,	$this->icon_url, $this->position );

		return $this->slug;
	}

	public function menu_file()
	{
		if($this->menu_file){
			include $this->menu_file;
		}
	}



}