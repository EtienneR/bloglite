<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('date'));
		$this->load->library(array('form_validation', 'Functions', 'session', 'Template'));
		$this->load->model(array('Model_articles', 'Model_pages', 'Model_users'));
		$this->reserved_slugs = array('admin', 'contact', 'feed', 'page');
		$this->layout = layoutAdmin();
		$this->url    = 'admin/pages/';
		$this->output->enable_profiler(TRUE);
	}

	// Toutes les pages
	function index()
	{
		if ($this->functions->getLoged()) {
			$this->load->helper('text');
			$this->load->library(array('markdown'));

			$data['connected']  = $this->functions->getUser();
			$data['title_page'] = 'Pages';
			$data['pages'] 		= $this->Model_pages->findAll();

			$this->template->load($this->layout, 'admin/pages/view_pages', $data);
		}
	}

	// Ajout / modification d'une page
	function edit($id = 0)
	{
		if ($this->functions->getLoged()) {
			$data['connected'] = $this->functions->getUser();

			// Règles de chaque champs
			$this->form_validation->set_rules('content', 'Content', 'required|trim');

			// Délimiteurs pour les erreurs de chaque champ
			$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');

			// Stockage des valeurs
			$title	 = $this->input->post('title');
			$content = $this->input->post('content');
			$state	 = $this->input->post('state');
			$tags	 = $this->input->post('tags');

			if (!empty($id)) {
				
				if (is_numeric($id)) {
					$article = $this->Model_pages->find($id);
				} else {
					$this->session->set_flashdata('warning', 'Impossible d\'éditer cette page car l\'id d\'une paeg est toujours un entier.');
					redirect(base_url('admin/dashboard'));
				}

				if ($article->num_rows() > 0) {
					$data['title_page'] = 'Modifier la page ' . $article->row()->title;
					$data['id']			= $article->row()->articleId;
					$data['title']		= $article->row()->title;
					$data['content']	= $article->row()->content;
					$data['slug']		= $article->row()->slug;
					$data['state']		= $article->row()->state;
					$data['cdate']		= $article->row()->cdate;
					$data['tags']		= $article->row()->tags;

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
					$this->form_validation->set_rules('tags', 'tags', 'trim');

					// Formulaire OK
					if ($this->form_validation->run() !== FALSE) {
						if ($title == $data['title'] && $content == $data['content'] && $state == $data['state'] && $tags == $data['tags']) {
							$this->session->set_flashdata('warning', 'Aucune modification à prendre en compte.');
						} else {
							// Modification en BDD
							$this->Model_pages->update($title, $content, $state, $tags, $id);
							$this->session->set_flashdata('success', 'Article "' . $title . '" modifié.');
						}
						redirect(base_url($this->url));
					}

				} else {
					$this->session->set_flashdata('warning', 'L\'article #' . $id . ' n\'existe pas ou n\'a jamais existé.');
					redirect(base_url($this->url));
				}

			} else {
				$data['title_page'] = 'Ajouter une page';

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
						// Création de la date du post
						$cdate = date(DATE_ISO8601, time());

						$this->Model_pages->insert($title, $content, $state, $slug, $cdate, $data['connected']['id']);
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


			$data['submit'] = array(
							'class' => 'btn btn-primary',
							'name'  => '',
							'value' => !empty($id)?'Modifier':'Ajouter');

			$this->template->load($this->layout, 'admin/pages/view_pages_edit', $data);

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

	// Suppression d'une page
	public function delete($id)
	{
		if ($this->functions->getLoged()) {
			$article = $this->Model_pages->find($id);

			// Vérification de la page à supprimer
			if ($article->num_rows() == 1) {
				$this->session->set_flashdata('success', 'Page "' . $article->row()->title . '" supprimée');
				// Suppression de la page
				$this->Model_articles->delete($id);
			} else {
				$this->session->set_flashdata('warning', 'Impossible de supprimer la page #' . $id . ' car il n\'existe pas ou n\'a jamais existé');
			}

			redirect(base_url($this->url));
		}
	}


}