<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articles extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('date'));
		$this->load->library(array('form_validation', 'Functions', 'session', 'Template'));
		$this->load->model(array('Model_articles', 'Model_users'));
		$this->reserved_slugs = array('admin', 'contact', 'feed', 'page');
		$this->layout = layoutAdmin();
		$this->url    = 'admin/articles/';
		$this->output->enable_profiler(TRUE);
	}

	// Tous les articles
	function index()
	{
		if ($this->functions->getLoged()) {
			$this->load->helper('text');
			$this->load->library(array('markdown'));

			$data['connected'] = $this->functions->getUser();

			if (isset($_GET['tag'])) {
				// Articles avec le même tag en paramètre
				$data['title_page'] = 'Articles avec le tag "' . $_GET['tag'];
				$data['articles']   = $this->Model_articles->findTag($_GET['tag']);
			} else if (isset($_GET['status'])) {
				if ($_GET['status'] == 'published') {
					// Articles publiés
					$data['title_page'] = 'Articles publiés';
					$data['articles'] = $this->Model_articles->findAllByState(1);
				} else if ($_GET['status'] == 'draft') {
					$data['title_page'] = 'Articles brouillons';
					$data['articles'] = $this->Model_articles->findAllByState(0);
				}
			} else {
				// Tous les articles
				$data['title_page'] = 'Articles';
				$data['articles']   = $this->Model_articles->findAll();
			}

			// Nombre d'articles de l'utilisateur connecté
			$data['number_articles'] = $this->Model_articles->countFindByUser($data['connected']['id']);

			$this->template->load($this->layout, 'admin/articles/view_articles', $data);
		}
	}

	// Ajout / modification d'un article
	function edit($id = 0)
	{
		if ($this->functions->getLoged()) {
			$data['connected'] = $this->functions->getUser();

			// Récupération de la liste des tags
			$tag_list = $this->Model_articles->getAllTags();

			// Récupération des tags dans un tableau
			foreach ($tag_list->result_array() as $row) {
				$allTags[] = $row['tags'];
			}

			// Récupération des tags séparés dans une chaine de caractères
			$tagsInString = implode(',', $allTags);
			// Récupération des tags séparés dans tableau
			$data['tagInArray']  = explode(',', $tagsInString);

			// Règles de chaque champs
			$this->form_validation->set_rules('content', 'Content', 'required|trim');

			// Délimiteurs pour les erreurs de chaque champ
			$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');

			// Stockage des valeurs
			$title	  = $this->input->post('title');
			$content  = $this->input->post('content');
			$pdate	  = $this->input->post('pdate');
			$state	  = $this->input->post('state');
			$tags	  = $this->input->post('tags');
			$demo	  = $this->input->post('demo');
			$download = $this->input->post('download');

			if (!empty($id)) {
				
				if (is_numeric($id)) {
					$article = $this->Model_articles->find($id);
				} else {
					$this->session->set_flashdata('warning', 'Impossible d\'éditer cet article car l\'id d\'un article est toujours un entier.');
					redirect(base_url('admin/dashboard'));
				}

				if ($article->num_rows() > 0) {
					$data['title_page'] = 'Modifier l\'article ' . $article->row()->title;
					$data['id']			= $article->row()->articleId;
					$data['title']		= $article->row()->title;
					$data['content']	= $article->row()->content;
					$data['slug']		= $article->row()->slug;
					$data['state']		= $article->row()->state;
					$data['cdate']		= $article->row()->cdate;
					$data['pdate']		= $article->row()->pdate;
					$data['tags']		= $article->row()->tags;
					$data['demo']		= $article->row()->demo;
					$data['download']	= $article->row()->download;

					$data['slug'] = array(
									'class' => 'form-control',
									'name'  => 'slug',
									'id'    => 'slug',
									'value' => isset($data['slug'])?$data['slug']:set_value('slug'));

					if ($this->input->post('title') == $data['title']) {
						$unique_title = '';
					} else {
						$unique_title = '|is_unique[articles.title]';
					}

					$this->form_validation->set_rules('title', 'Title', 'required|trim|min_length[2]' . $unique_title);
					$this->form_validation->set_rules('slug', 'slug', 'required|trim|min_length[2]|is_unique[articles.title]|callback__reservedSlug');
					$this->form_validation->set_rules('demo', 'demo', 'trim');
					$this->form_validation->set_rules('download', 'download', 'trim');

					// Formulaire OK
					if ($this->form_validation->run() !== FALSE) {
						$tags = str_replace(' ', '', $tags);

						if ($pdate) {
							$pdate = date(DATE_ISO8601, intval($pdate));
						} else {
							$pdate = $data['pdate'];
						}

						if ($title == $data['title'] && $content == $data['content'] && $pdate == $data['pdate'] && $state == $data['state'] && $tags == $data['tags'] && $demo == $data['demo'] && $download == $data['download']) {
							$this->session->set_flashdata('warning', 'Aucune modification à prendre en compte.');
						} else {
							// Modification en BDD
							$this->Model_articles->update($title, $content, $pdate, $state, $tags, $demo, $download, $id);
							$this->session->set_flashdata('success', 'Article "' . $title . '" modifié.');
						}

						redirect(base_url($this->url));
					}

				} else {
					$this->session->set_flashdata('warning', 'L\'article #' . $id . ' n\'existe pas ou n\'a jamais existé.');
					redirect(base_url($this->url));
				}

			} else {
				$data['title_page'] = 'Ajouter un article';

				$this->form_validation->set_rules('title', 'Title', 'required|trim|min_length[2]|is_unique[articles.title]');

				// Récupération des slugs réservés
				$slugs_reserved = explode(',', getConfig()->slugs_reserved);
				function trimArray(&$value) 
				{ 
					$value = trim($value); 
				}
				// Suppression des espaces (trim)
				array_walk($slugs_reserved, 'trimArray');

				// Formulaire OK
				if ($this->form_validation->run() !== FALSE) {
					$this->load->helper(array('date', 'text'));
					$slug = url_title(convert_accented_characters($title), '-', TRUE);

					// Vérification de l'existence d'un slug
					$check_slug = $this->Model_articles->checkSlug($slug);

					if (in_array(strtolower($slug), $slugs_reserved)) {
						$this->session->set_flashdata('warning', 'Le slug "' . $slug . '" est réservé. Merci de changer le titre de cet article.');
					} elseif ($check_slug->num_rows() == 1) {
						$this->session->set_flashdata('warning', 'Le slug "' . $slug . '" est déjà utilisé. Merci de changer le titre de cet article.');
					} else {

						// Gestion date de publication et date de création
						if (empty($pdate)) {
							$pdate = $cdate = date(DATE_ISO8601, time());
						} else {
							$pdate = intval($pdate);
							$pdate = date(DATE_ISO8601, $pdate);
							$cdate = date(DATE_ISO8601, time());
						}

						$this->Model_articles->insert($title, $content, $state, $slug, $cdate, $pdate, $tags, $demo, $download, $data['connected']['id']);
						$this->session->set_flashdata('success', 'Article "' . $title . '" créé.');
						redirect(base_url($this->url));
					}
				}
			}

			// Préparation des champs
			$data['title'] = array(
							'class' => 'form-control',
							'name'  => 'title',
							'id'    => 'title',
							'value' => isset($data['title'])?$data['title']:set_value('title'));

			$data['content'] = array(
							'class' => 'form-control',
							'name'  => 'content',
							'id'    => 'content',
							'value' => isset($data['content'])?$data['content']:set_value('content'));

			$data['tags'] = array(
							'class' => 'form-control',
							'name'  => 'tags',
							'id'    => 'tags',
							'value' => isset($data['tags'])?$data['tags']:set_value('tags'));

			$data['demo'] = array(
							'class' => 'form-control',
							'name'  => 'demo',
							'id'    => 'demo',
							'value' => isset($data['demo'])?$data['demo']:set_value('demo'));

			$data['download'] = array(
							'class' => 'form-control',
							'name'  => 'download',
							'id'    => 'download',
							'value' => isset($data['download'])?$data['download']:set_value('download'));

			$data['pdate'] = array(
							'class' => 'flatpickr form-control',
							'name'  => 'pdate2',
							'id'    => 'pdate2',
							'value' => isset($data['pdate'])?$data['pdate']:set_value('pdate'));

			$data['submit'] = array(
							'class' => 'btn btn-primary',
							'name'  => '',
							'value' => !empty($id)?'Modifier':'Ajouter');

			$this->template->load($this->layout, 'admin/articles/view_articles_edit', $data);

		}
	}

	// Callback des slugs réservés
	function _reservedSlug($slug)
	{
		if (in_array(strtolower($slug), $this->reserved_slugs)) {
			$this->form_validation->set_message('_reservedSlug', 'Le slug "' . $slug . '" est déja réservé.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// Suppression d'un article
	public function delete($id)
	{
		if ($this->functions->getLoged()) {
			$article = $this->Model_articles->find($id);

			// Vérification de l'article à supprimer
			if ($article->num_rows() == 1) {
				$this->session->set_flashdata('success', 'Article "' . $article->row()->title . '" supprimé');
				// Suppression de l'article
				$this->Model_articles->delete($id);
			} else {
				$this->session->set_flashdata('warning', 'Impossible de supprimer l\'article #' . $id . ' car il n\'existe pas ou n\'a jamais existé');
			}

			redirect(base_url($this->url));
		}
	}

	// Les articles de tel utilisateur
	public function user($id)
	{
		if ($this->functions->getLoged()) {
			$articles = $this->Model_articles->findByUser($id);

			if ($articles->num_rows() > 0) {
				$this->load->helper('text');
				$this->load->library('markdown');

				$data['connected'] = $this->functions->getUser();

				if ($this->uri->segment(4) == $data['connected']['id']) {
					$data['title_page'] = 'Tous mes articles';
				} else {
					$data['title_page'] = 'Tous les articles de ' . $articles->row()->name;
				}

				$data['number_articles'] = $articles->num_rows();

				$data['articles'] = $articles;

				$this->template->load($this->layout, 'admin/articles/view_articles', $data);

			} else {
				// Gestion des erreurs
				$user = $this->Model_users->find($id);

				if ($user->num_rows() == 1) {
					$this->session->set_flashdata('warning', 'L\'utilisateur "' . $user->row()->name . '" n\'a rédigé aucun article.');
				} else {
					$this->session->set_flashdata('warning', 'Cet utilisateur n\'existe pas ou n\'existe plus.');
				}

				redirect(base_url($this->url));
			}
		}
	}

}