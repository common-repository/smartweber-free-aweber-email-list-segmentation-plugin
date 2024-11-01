<?php

class ActionPHPRapid_RouteMatch
{	
	//@todo make sure you catch the error if there is no route array
	public function __construct(Array $routes)
	{
		$this->routes($routes);
	}

	public function match($route, $request_type="GET")
	{
		$this->route = $route;
		$this->request_type = $request_type;

		if(!isset($this->routes[$this->request_type])){
			$this->error();
		}


		//We will test to see if there is an exact match for the route
		if( $this->exactMatch()){

			return $this->matched();
		}

		//So we don't have an exact match - we probably have some parameters in our route.
		//Let's sort that out!

		if( $this->routeWithParams() ){

			return $this->matched();
		}

		$this->error();
		//print_r(get_defined_vars());	
	}

	public function error()
	{
		//@todo throw exception if route not found
		die('Route not found!');
	}

	public function routes(Array $routes)
	{
		$this->routes = $routes;
	}

	public function exactMatch()
	{	
		//Here is the fastest way to match the route - if it's an exact match
		if(array_key_exists( $this->route, $this->routes[$this->request_type])){

			$this->prepareRoute();

			return true;
		}

		return false;
	}

	protected function routeWithParams()
	{
		//Let's divide the route into it's components
		
		$trimmed_route = trim($this->route, '/');
		$route_components = explode( '/', $trimmed_route );

		//Let's count the number of components
		$number_of_components = count( $route_components );

		//Let's define an array for our possible matches
		$possible_matches = array();
		//Now will will match with all routes with the same number of components -this
		// will give us our possible matches.
		
		foreach ($this->routes[$this->request_type] as $route => $details) {
			//print_r($route); echo "\n";
			$possible_route = trim($route, '/');
			$possible_components = explode('/', $possible_route);

			//Since we have removed trailing slashes, let's double check if that matches the route
			if( $possible_components  == $route_components ) {

				//Assign the route so it matches the definition in the array
				$this->route = $route;

				$this->prepareRoute();

				return true;
			}


			if( count($possible_components) == $number_of_components ){
				
				//Since there hasn't been an exact match so far, we can rule out any route 
				// that doesn't contain parameter placeholders
				
					if( $this->containsParams( $possible_components)){
						$possible_matches[$route] = $possible_components;

					}
				}
			}

			//Let's see if there no possible matches - we might have to get our of here!
			
			if( empty( $possible_matches ) ){
				return false;
			}

			

			//Now, if there is only one matched route, let's get cracking with it
			if( count( $possible_matches ) == 1 ){

				$the_route = $this->route = key( $possible_matches );

				$possible_match = $possible_matches[$the_route];

				//Now we want to see if the route components actually match
				
				if($possible_match[0] == $route_components[0]){


					//So now we gotta match the parameters
					$this->matchParams( $route_components, $possible_matches[$the_route]);

					$this->prepareRoute();

					return true;

				}

				return false; //The route doesn't match
/*				print_r($possible_matches[$the_route]);
print_r(get_defined_vars());die();*/


			}


			
			//We have several possible matches
			
			//OK, so first let's find out if our first component matches any of the 
			// possible routes
			
			$__component = $route_components[0];

			foreach ($possible_matches as $key => $possible_match) {

				if($route_components[0] == $possible_match[0]){
                    
                    $the_route = $this->route = $key;

					//So now we gotta match the parameters
					$this->matchParams( $route_components, $possible_matches[$the_route]);

					$this->prepareRoute();
					return true;
				}
				
			}

			//@todo Match routes with multiple parameters
			

	}

	protected function matchParams(Array $route_components, Array $matched_components)
	{
		$n = count( $route_components );

		for($i = 0; $i< $n; $i++){

			if( $this->isParam( $matched_components[$i])){

				$this->params[] = $route_components[$i];
			}
			//print_r( $matched_components[$i] . " => " . $route_components[$i] . " \n" );
		}

	}
	protected function containsParams( $components ){

		foreach ($components as $component) {
			
			if( $this->isParam( $component)){
				return true;
			};
		}

		//We have looped and found nothing
		return false;

	}

	public function prepareRoute()
	{
		$route_definition = $this->routes[$this->request_type][$this->route];

		$route_details = explode('@', $route_definition);

		$this->method = $route_details[0];

		$this->controller = $route_details[1];

		//print_r( get_defined_vars());
	}

	public function matched()
	{
		$matched_route = new ActionPHPRapid_RouteMatchMatched;

		$matched_route->method = $this->method;
		$matched_route->controller = $this->controller;

		//Now we will assign the parameters
		$params = array();

		if( ! empty( $this->params )){
			$params = $this->params;
		}

		$matched_route->params = $params;

		return $matched_route;
	}

	protected function isParam($string)
	{	
		//Parameters are represented by a string in curly brackets
		if(preg_match('/(\\{)((?:[a-z][a-z0-9_]*))(\\})/', $string)){

			return true;

		}

		return false;
	}
}