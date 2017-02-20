<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('date'));
		$this->load->library(array('form_validation', 'Functions', 'session', 'Template'));
		$this->load->model(array('Model_search'));
		$this->layout = layoutAdmin();
		$this->url    = 'admin/articles/';
		$this->output->enable_profiler(TRUE);
	}

	function index()
	{
		if ($this->functions->getLoged()) {
			$data['connected'] = $this->functions->getUser();
			$data['title_page'] = 'Recherches';
			$data['search']     = $this->Model_search->findAll();

			$this->template->load($this->layout, 'admin/search/view_search', $data);
		}
	}

	function occurrences()
	{
		if ($this->functions->getLoged()) {
			$data['connected']  = $this->functions->getUser();
			$data['title_page'] = 'Recherches (occurences)';

			$data['search']     = $this->Model_search->distinctWords();

			$this->template->load($this->layout, 'admin/search/view_search', $data);
		}
	}
}