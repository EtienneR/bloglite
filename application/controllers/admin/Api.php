<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library(array('Functions', 'session'));
		$this->load->model(array('Model_articles', 'Model_users'));
		header('Content-Type: application/json');
	}

	// Récupération de tous les articles
	function fetchArticles()
	{
		if ($this->functions->getLoged()) {
			$articles = $this->Model_articles->findAll()->result();
			$json = json_encode($articles, true);
			echo $json;
		}
	}

	// Récupération d'un article
	function fetchOne($id)
	{
		if ($this->functions->getLoged()) {
			$article = $this->Model_articles->find($id);

			if ($article->num_rows() > 0) {
				echo json_encode($article->row());
			} else {
				header("HTTP/1.0 404 Not Found");
				echo json_encode("404 : Article #$id not found");
			}
		}
	}

}