<?php

class ActionPHPRapid_RouteDispatch
{	
	private $controller_path;

	public function __construct(ActionPHPRapid_RouteMatchMatched $route)
	{	
		$this->dispatch($route);
	}

	protected function dispatch($route)
	{	
		if( $this->isValidController( $route->controller ) ){

			include $this->controller_path;

		} else {

			die('Controller Error'); //@todo throw Controller Error exception

		}

		if( !method_exists(new $route->controller, $route->method)){
			die('Controller method error');//@todo this whole bit has to be refactored
		}
		
		call_user_func_array(array( new $route->controller, $route->method ), $route->params);
	}

	public function isValidController($controller)
	{
		//The controller name must end with 'Controller'
		if( !$this->helperEndsWith( $controller, 'Controller')){
			return false;
		}
		
		//It must exist in our defined controller path
		
		$this->controller_path = RAPID_APP_PATH . "Controllers/";

		$controller_file = $controller . ".php";

		$this->controller_path .= $controller_file;

		if( !file_exists($this->controller_path )){

			return false;
		}

		//@todo the controller must be an instance of ActionPHPRapid_Controller

		return true;
	}

	//@todo we need a separate place for helper methods
	public function helperEndsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
        $length = strlen($needle);

    	return (substr($haystack, -$length) === $needle);
	}
}