<?php


/**
 * Stores all the routes
 */
class ActionPHPRapid_Route
{
	private $routes = array();

	private function setRoute($request_type, $route, $destination, $group){

		$this->routes[$group][$request_type][$route] = $destination;

	}

	public function post($route, $destination, $group='public')
	{
		$this->setRoute('POST', $route, $destination, $group );
	}

	public function get($route, $destination, $group='public')
	{
		$this->setRoute('GET', $route, $destination, $group );;
	}


	public function put($route, $destination, $group='public')
	{
		$this->setRoute('PUT', $route, $destination, $group );;
	}


	public function delete($route, $destination, $group='public')
	{
		$this->setRoute('DELETE', $route, $destination, $group );
	}

	public function routes($group='public')
	{	
		if(isset($this->routes[$group])){
			return $this->routes[$group];
		}
		//@todo empty array is probably not the best solution here
		return array();
	}
}