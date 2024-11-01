<?php

class SmartWeber
{
	public function run(){
				
				define( 'RAPID_APP_PATH', dirname(__FILE__) . '/' );//We should be inside our app
				// directory

				define( 'RAPID_PATH', dirname(__FILE__) . '/../framework/ActionPHPRapid/');

				define ( 'RAPID_APP_URL', plugins_url( '/', __FILE__ ) );

				include RAPID_APP_PATH . 'bootstrap/init.php';

				$this->init();
				
			}

			protected function init(){
				$this->install();
				$this->admin_menu();
				$this->scripts();
				$this->load_ajax();
				$this->redirect();


			}

			public function redirect()
			{
				if(isset($_GET['_c_'])){
					
					if(strlen( $_GET["_c_"] ) == 6 && ctype_alnum($_GET["_c_"])){
						
						$code = $_GET["_c_"];

					} else {
						return;
					}
					
					require_once RAPID_APP_PATH . "Models/Link.php";

					$link = new Link;
					$link->find($code, "code");

					if(isset($_GET['_e_']) && trim($_GET['_e_']) != ""){
						//@todo We may want to validate the email - it doesn't appear to be necessary though
						$email = trim( $_GET['_e_'] );


						//Let's move the email to the appropriate list
						include_once RAPID_APP_PATH . "includes/class-aweber.php";
						include_once RAPID_APP_PATH . "includes/class-subscriber.php";

						//Let's initialize Aweber
						$aweber = new PHPClan_Aweber;
						$aweber->consumerKey =get_option('smartweber_aweber_consumerKey');
						$aweber->consumerSecret =get_option('smartweber_aweber_consumerSecret');
						$aweber->accessKey = get_option('smartweber_aweber_accessKey');
						$aweber->accessSecret = get_option('smartweber_aweber_accessSecret');
						$aweber->aweber();
						    

						$_subscriber = new PHPClan_Subscriber($aweber);

						$_subscriber->email = $email;

						$_subscriber->list = $link->aweber_list;

						switch ($link->redirect_type) {
							case 'move':
								$_subscriber->move();
								break;
							
							default:
							$_subscriber->copy();
							
							break;
						}

					} else {
						$email = null;
					}

					$url = $link->url;


					if($url){
						header("Location: " . $url); die();
					}

				}
			}
			public function install()
			{
				$this->tables();
			}

			public function scripts()
			{
				if(isset($_GET['page']) && $_GET['page'] == "smartweber_manager") {
					
					add_action( 'admin_enqueue_scripts', array($this, 'admin_style' ) );

				}

				add_action('wp_enqueue_scripts', array( $this, 'front_end_scripts' ));

			}

			public function front_end_scripts()
			{
			}

			public function load_ajax()
			{
				add_action( 'wp_ajax_smartweber', array($this, 'ajax') );

			}

			public function ajax()
			{
				include RAPID_APP_PATH . '/config/routes.php';

				$routes = $route->routes();

				$route_match = new ActionPHPRapid_RouteMatch($routes);

				$method = $_SERVER['REQUEST_METHOD'];

				$_route = $_GET['route'];

				$matched_route = $route_match->match( $_route, $method );
				
				$dispatch = new ActionPHPRapid_RouteDispatch( $matched_route );

				die();

			}

			public function admin_menu()
			{	
				$admin_menu = new ActionPHPRapid__WP_Admin_Menu;
				$admin_menu->title = "SmartWeber";
				$admin_menu->position = 0.253234;
				$admin_menu->slug = 'smartweber_manager';
				$admin_menu->menu_file = RAPID_APP_PATH . 'wp-menu/index.php';
				$admin_menu->menu();

			}

			public function admin_style() {

						wp_enqueue_media();
				        wp_register_style( 'smartweber_foundation_css', "https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.2/css/foundation.min.css", false, '1.0.0' );
				        wp_register_style( 'smartweber_main_style_css', plugin_dir_url( __FILE__ )  . 'wp-menu/css/style.css', false, '1.0.0' );
				        wp_register_style( 'smartweber_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', false, '1.0.0' );
				        wp_enqueue_style( 'smartweber_foundation_css' );
				        wp_enqueue_style( 'smartweber_main_style_css' );
				        wp_enqueue_style( 'smartweber_pro_style_css' );
				        wp_enqueue_style( 'smartweber_font_awesome' );
				        wp_enqueue_script( 'jquery' );
				        wp_enqueue_script( 'jquery-ui-sortable' );
				        wp_enqueue_script( 'underscore' );
				        wp_enqueue_script( 'backbone' );
				        wp_enqueue_script( 'smartweber_marionette', "https://cdnjs.cloudflare.com/ajax/libs/backbone.marionette/2.4.2/backbone.marionette.min.js", array("backbone"));
                                       
                        wp_enqueue_script('jeditable-js', RAPID_APP_URL . 'wp-menu/js/jeditable.js');                                         
                                        }

			public function tables()
			{
				global $wpdb;

				$table_name = $wpdb->prefix . "smartweber_links";

				$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (

				id mediumint(9) NOT NULL AUTO_INCREMENT,

				name varchar(256) ,

				url varchar(1024) ,

				code varchar(6),

				aweber_list varchar(56),

				redirect_type varchar(10) DEFAULT 'copy',

				Status varchar(10)  DEFAULT 'fresh',

				UNIQUE KEY id (id)

				);";
	      
		      	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	    		dbDelta($sql);

	    	}
}