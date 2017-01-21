<?php

namespace Core;
require_once __DIR__ . '/../Helper/Utility.php';
use Helper\Utility;

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
		['route' => '/^\/$/', 'controller' => 'IndexController', 'action' => 'showHomeAction', 'params' => []]
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
	 * Array dei parametri che una rotta può passare all'action del
	 * controller associato.
	 * @var
	 */
	private $params;

	/**
	 * Core constructor.
	 * @param $requestURI
	 */
	public function __construct()
	{
		$this->requestURI = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
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
				$this->params = !empty($route['params']) ? $route['params'] : [];
				return;
			}
		}

		Utility::set404();
	}

	/**
	 * Richiama la action del controller corrispondente alla URI ricercata.
	 * Il metodo deve essere richiamato dopo findRoute.
	 */
	public function executeAction()
	{
		require_once(__DIR__ . '/../Controller/' . $this->controller . '.php');
		eval('$result = \Controller\\' . $this->controller . '::' . $this->action . '($this->params);');

		!empty($result) ? $this->resolveViewsAction($result) : Utility::set404();
	}

	private function resolveViewsAction(array $viewsList){
		foreach($viewsList as $view){
			require_once __DIR__ . '/../Views/' . $view;
		}
	}

	/**
	 * Restuisce il nome della Request URI
	 */
	public function getRequestURI()
	{
		return !empty($this->requestURI) ? $this->requestURI : false;
	}

	/**
	 * Restituisce il nome del controller
	 */
	public function getController()
	{
		return !empty($this->controller) ? $this->controller : false;
	}

	/**
	 * Restituisce il nome del controller
	 */
	public function getParams()
	{
		return !empty($this->params) ? $this->params : false;
	}

	/**
	 * Restituisce il nome della action
	 */

	public function getAction()
	{
		return !empty($this->action) ? $this->action : false;
	}

	/**
	 * @return array
	 */
	public function getAllRoutes(): array{
		return $this->routes;
	}
}