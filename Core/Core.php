<?php
require_once __DIR__ . '/../Helper/Utility.php';

class Core
{

	/**
	 * Nome della request URI.
	 * @var
	 */
	private $requestURI;

	/**
	 * Lista di rotte da poter matchare.
	 * @var array
	 */
	private $routes = [
		['route' => '/^\/$/', 'controller' => 'IndexController', 'action' => 'showHomeAction']
	];

	/**
	 * Nome del controller matchato.
	 * @var
	 */
	private $controller;

	/**
	 * Nome della action del controller matchato.
	 * @var
	 */
	private $action;

	/**
	 * Core constructor.
	 * @param $requestURI
	 */
	public function __construct(string $requestURI)
	{
		$this->requestURI = $requestURI;
	}

	/**
	 * Cerca se la URI ricercata è tra le rotte previste.
	 * In caso negativo, restituisce 404.
	 */
	public function findRoute()
	{
		foreach ($this->routes as $route) {
			if (preg_match($route['route'], $this->requestURI)) {
				$this->controller = $route['controller'];
				$this->action = $route['action'];
				return;
			}
		}

		Utility::set404();
	}

	/**
	 * Richiama la action del controller corrispondente alla URI ricercata.
	 * Il metodo deve essere richiamato dopo findRoute.
	 */
	public function start()
	{
		require_once(__DIR__ . '/../Controller/' . $this->controller . '.php');
		eval($this->controller . '::' . $this->action . '();');
	}

	/**
	 * Restuisce il nome della Request URI
	 */
	public function getRequestURI()
	{
		!empty($this->requestURI) ? $this->requestURI : false;
	}

	/**
	 * Restituisce il nome del controller
	 */
	public function getController()
	{
		!empty($this->controller) ? $this->controller : false;
	}

	/**
	 * Restituisce il nome della action
	 */
	public function getAction()
	{
		!empty($this->action) ? $this->action : false;
	}
}