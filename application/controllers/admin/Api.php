<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library(array('Functions', 'session', 'Template'));
		$this->load->model(array('Model_articles', 'Model_users'));
		
	}

	// Page d'accueil
	function index()
	{
		if ($this->functions->getLoged()) {
			$data['connected'] = $this->functions->getUser();
			$data['title_page'] = 'A propos du blog';

			$this->load->library('markdown');
			$data['content'] = $this->markdown->parse(@file_get_contents('api.md'));
			if (strlen($data['content']) == 1){
				$data['content'] = "Oups, le fichier <b>readme.md</b> n'existe pas.";
			}

			$this->template->load(layoutAdmin(), 'admin/view_api', $data);
		}
	}

	// Récupération de tous les articles
	function fetchArticles()
	{
		if ($this->functions->getLoged()) {
			header('Content-Type: application/json');
			$articles = $this->Model_articles->findAll()->result();
			$json = json_encode($articles, true);
			echo $json;
		}
	}

	// Récupération d'un article
	function fetchOne($id)
	{
		if ($this->functions->getLoged()) {
			header('Content-Type: application/json');
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