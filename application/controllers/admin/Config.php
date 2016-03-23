<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->library(array('form_validation', 'Functions', 'session', 'Template'));
		$this->layout = layoutAdmin();
		$this->output->enable_profiler(TRUE);
	}

	function index()
	{
		if ($this->functions->getLoged()) {
			$data['connected'] = $this->functions->getUser();

			if ($data['connected']['level'] !== 0) {
				$this->session->set_flashdata('warning', 'Vous n\'avez pas les droits pour modifier la config du site.');
				redirect(base_url('admin/articles'));
			}

			$data['title_page'] = "Configuration du blog";
			// Chargement du fichier JSON de configuration
			$config = getConfig();

			// Règles de chaque champs
			$this->form_validation->set_rules('name_site', 'Nom du site', 'required|trim');
			$this->form_validation->set_rules('description', 'Description du site', 'required|trim|max_length[256]');
			$this->form_validation->set_rules('email', 'Email de contact', 'required|trim|valid_email');
			$this->form_validation->set_rules('pagination', 'Pagination', 'required|trim|is_natural');
			$this->form_validation->set_rules('slugs_reserved', 'Slugs réservés', 'trim');

			// Délimiteurs pour les erreurs de chaque champ
			$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');

			// Préparation des champs
			$data['name_site'] = array(
						'class' => 'form-control',
						'name'  => 'name_site',
						'id'    => 'name_site',
						'value' => isset($config->name_site)?$config->name_site:set_value('name_site'));

			$data['description'] = array(
						'class' => 'form-control',
						'name'  => 'description',
						'id'    => 'description',
						'value' => isset($config->description)?$config->description:set_value('description'));

			$data['email'] = array(
						'class' => 'form-control',
						'name'  => 'email',
						'id'    => 'email',
						'type'  => 'email',
						'value' => isset($config->email)?$config->email:set_value('email'));

			$data['pagination'] = array(
						'class' => 'form-control',
						'name'  => 'pagination',
						'id'    => 'pagination',
						'type'  => 'number',
						'value' => isset($config->pagination)?$config->pagination:set_value('pagination'));

			$data['slugs_reserved'] = array(
						'class' => 'form-control',
						'name'  => 'slugs_reserved',
						'id'    => 'slugs_reserved',
						'value' => isset($config->slugs_reserved)?$config->slugs_reserved:set_value('slugs_reserved'));

			$data['submit'] = array(
						'class' => 'btn btn-primary',
						'name'  => '',
						'value' => 'Enregistrer');

			// Récupération des données
			$name_site		= $this->input->post('name_site');
			$description	= $this->input->post('description');
			$email			= $this->input->post('email'); 
			$pagination		= intval($this->input->post('pagination'));
			$slugs_reserved = $this->input->post('slugs_reserved');

			// Préparation pour le JSON
			$keys		 = array('name_site', 'description', 'email', 'pagination', 'slugs_reserved'); 
			$values		 = array($name_site, $description, $email, $pagination, $slugs_reserved); 
			$array		 = array_combine($keys, $values);
			$json_output = json_encode($array);

			if ($this->form_validation->run() !== FALSE) {
				if ($name_site == $config->name_site && $description == $config->description && $email == $config->email && $pagination == $config->pagination && $slugs_reserved == $config->slugs_reserved) {
					$this->session->set_flashdata('warning', 'Aucune modification à prendre en compte.');
				} else {
					file_put_contents(getFileConfig(), $json_output);
					$this->session->set_flashdata('success', 'Les modifications ont bien été prise en compte.');
				}

				redirect(current_url());
			}

			$this->template->load($this->layout, 'admin/config/view_config_edit', $data);
		}
	}

	// Lire les information du fichier "readme.md"
	function about()
	{
		if ($this->functions->getLoged()) {
			$data['connected'] = $this->functions->getUser();
			$data['title_page'] = "A propos du blog";
			$this->load->library('markdown');

			$data['content'] = $this->markdown->parse(@file_get_contents('readme.md'));
			if (strlen($data['content']) == 1){
				$data['content'] = "Oups, le fichier <b>readme.md</b> n'existe pas.";
			}

			$this->template->load($this->layout, 'admin/config/view_about', $data);
		}
	}

}