<?php
namespace Controller;

/**
 * Class IndexController
 * @package Controller
 */
class IndexController
{
	/**
	 * @param array $params
	 * @return array
	 */
	static public function showHomeAction(array $params): array
	{
		/**
		 * Lista di pagine da includere secondo una gestione FIFO
		 * Sono tutti implicitamente prelevati dalla cartella settata come Views
		 */
		return [
			'common/html/open-page.html',
			'homepage/html/head.php',
			'homepage/html/body.php',
			'common/html/footer.php',
			'common/html/close-page.html',
		];
	}
}