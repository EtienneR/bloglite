<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('date', 'text'));
		$this->load->library(array('markdown', 'Template', 'Tags'));
		$this->load->model('Model_articles');
		$this->title_page = getConfig()->name_site;
		$this->output->enable_profiler(TRUE);
	}

	// Home
	public function index($currentPage = 0)
	{
		// Chargement de la librairie de pagination
		$this->load->library('pagination');

		if ($currentPage == 1) {
			redirect('/', 'location', 301);
		}

		$search	= htmlspecialchars($this->input->get('q'));		// <script>alert('coucou')<script>

		// Recherche
		if (isset($search) && $search) {
			$data['search'] = $search;
			$articles = $this->Model_articles->search($search);

			$this->load->model('Model_search');
			// Insertion dans la table du mot recherché
			$this->Model_search->insert($search, $articles->num_rows());
		} else {
			$articles = $this->Model_articles->findAll('','', TRUE);
		}

		$data['tags_list'] = $this->tags->tagsList();

		if ($articles->num_rows() > 0) {
			
			// Pour une recherche
			if (isset($search) && $search) {
				$config['enable_query_strings'] = TRUE;
				$config['page_query_string']    = TRUE;
				$config['query_string_segment'] = 'page';
				$config['base_url']				= '?q=' . $search;
				// Mise en page de la pagination
				$config = pagination_custom($articles->num_rows(), '?q=' . $search, '', TRUE, TRUE);
			} else {
				$config = pagination_custom($articles->num_rows(), base_url('page'), base_url(), FALSE);
			}

			// Initialisation de la pagination
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();

			$pageMax = ceil($articles->num_rows() / $config['per_page']);

			// Chargement des données
			if (isset($search) && $search) {

				if (isset($_GET['page'])) {
					$currentPage = $_GET['page'];

					if ($_GET['page'] == 1) {
						redirect('/', 'location', 301);
					}

				}

				$this->_checkPaginationLimit($currentPage, $pageMax, base_url('?q='.$search));

				$data['articles'] = $this->Model_articles->search($search, $currentPage, $config['per_page'])->result();
			} else {
				$this->_checkPaginationLimit($currentPage, $pageMax, base_url());

				$data['articles'] = $this->Model_articles->findAll($currentPage, $config['per_page'], TRUE, TRUE)->result();
			}
		}

		// Titre de la page
		if (isset($search) && $search) {

			if ($currentPage == 0) {
				$data['title_page'] = 'Recherche ' . $search . ' - Page ' . ($currentPage+1);
			} else {
				$data['title_page'] = 'Recherche ' . $search . ' - Page ' . $currentPage;
			}

		} else if ($currentPage == 0) {
			$data['title_page'] = $this->title_page;
		} else {
			$data['title_page'] = $this->title_page . ' - Page ' . $currentPage;
		}

		$data['name_site'] = $this->title_page;

		$data['description'] = getConfig()->description;

		// Affichage de la vue + données
		$this->template->load('front/view_layout', 'front/view_listing', $data);
	}

	// Limitations de la pagination
	private function _checkPaginationLimit($page, $limit, $url)
	{
		if (isset($page) && $page > $limit) {
			redirect($url);
		}
	}

	// Archives
	public function archives()
	{
		$data['title_page']	 = 'Archives';
		$data['name_site']	 = $this->title_page;
		$data['description'] = $this->title_page . ' - Archives';
		$data['tags_list']   = $this->tags->tagsList();

		$data['articles'] = $this->Model_articles->findAll('','', TRUE, TRUE);

		// Affichage de la vue + données
		$this->template->load('front/view_layout', 'front/view_archives', $data);
	}

	// Page article
	public function article($slug)
	{
		// Chargement des données
		$article = $this->Model_articles->findAllSlug($slug);

		// Vérification de l'existence de l'article
		if ($article->num_rows() == 1) {

			// En brouillon
			if ($article->row()->state == 0 || $article->row()->pdate > date(DATE_ISO8601, time())) {
				$this->load->library(array('functions', 'session'));

				if ($this->functions->getLoged()) {

					// Brouillon
					if ($article->row()->state == 0) {
						$data['message206'] = 'Cet article n\'est pas publié sur votre site, la page retourne ainsi une erreur HTTP 206.';
					  // Publié mais dans le futur...
					} else if ($article->row()->pdate > date(DATE_ISO8601, time())) {
						$data['message206'] = 'Cet article n\'est pas encore publié sur votre site, la page retourne ainsi une erreur HTTP 206.';
					}

					header('HTTP/1.1 206 Partial Content');
				}

			}

			$data['name_site']  = $this->title_page;
			$data['tags_list']  = $this->tags->tagsList();

			// Chargement des lignes concernées
			$data['title']		 = $article->row()->title;
			$data['content']	 = $article->row()->content;
			$data['description'] = strip_tags(character_limiter($this->markdown->parse($data['content']), 256));
			$data['tags']		 = $article->row()->tags;
			$data['pdate']		 = $article->row()->pdate;
			$data['type']		 = $article->row()->type;
			$data['demo']		 = $article->row()->demo;
			$data['download']	 = $article->row()->download;
			$data['title_page']  = $data['title'] . ' - ' . $this->title_page;

			// Chargement des autres articles
			$data['articles'] = $this->Model_articles->others($slug)->result();

			// Affichage de la vue + données
			$this->template->load('front/view_layout', 'front/view_article', $data);
		} else {
			redirect(show_404());
		}
	}

	// Tag
	public function tag($tag, $currentPage = 0)
	{
		$articles = $this->Model_articles->findTagActive($tag, '', '');

		if ($articles->num_rows() > 0) {
			$data['name_site']   = $this->title_page;
			$data['title_page']  = 'Tag : ' . $tag;
			$data['description'] = character_limiter(strip_tags($this->markdown->parse($articles->row()->content), 256));
			$data['tags_list']   = $this->tags->tagsList();

			// Chargement de la librairie de pagination
			$this->load->library('pagination');

			// Mise en page de la pagination
			$config = pagination_custom($articles->num_rows(), base_url('tag/' . $tag . '/page' ), base_url('tag/' . $tag), false);

			// Initialisation de la pagination
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();

			// Chargement des articles concernés
			$data['articles'] = $this->Model_articles->findTagActive($tag, $currentPage, $config['per_page'])->result();

			// Affichage de la vue + données
			$this->template->load('front/view_layout', 'front/view_listing', $data);
		} else {
			redirect(show_404());
		}
	}

	// Flux RSS
	public function feed()
	{
		$this->load->helper('xml');

		$data['site_name']		  = $this->title_page;
		$data['site_link']		  = base_url();
		$data['site_description'] = 'Les flux RSS de mes articles';
		$data['encoding']		  = 'utf-8';
		$data['feed_url']		  = base_url() . '/feed';
		$data['page_language']	  = 'fr-fr';
		$data['articles']		  = $this->Model_articles->findAll('', '', '', TRUE);

		$this->load->view('front/view_feed', $data);
	}
}