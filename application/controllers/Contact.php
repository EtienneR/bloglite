<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Model_articles');
		$this->title_page = getConfig()->name_site;
	}

    public function index()
    {
		$this->load->library(array("form_validation", 'Template', 'Tags'));

		$data['title_page']	 = 'Contact';
		$data['name_site']	 = $this->title_page;
		$data['description'] = $this->title_page . ' - Contact';
		$data['tags_list']   = $this->tags->tagsList();

		// Appel du helper
		$this->load->helper("form");

		// Préparation des champs
		$data["subject"] = array("name"  => "subject",
		                         "id"    => "subject",
		                         "value" => set_value("subject"));

		$data["name"] = array("name"  => "name",
		                      "id"    => "name",
		                      "value" => set_value("name"));

		$data["email"] = array("name"  => "email",
		                       "id"    => "email",
		                       "value" => set_value("email"));

		$data["message"] = array("name"  => "message",
		                         "id"    => "message",
		                         "value" => set_value("message"));

		$data["submit"] = array("class" => "button",
		                        "value" => "Envoyer");

		// Règles de chaque champ
		$required   = "Ce champ est obligatoire";
		$min_length = "Ce champ doit comporter au moins 2 caractères";
		$this->form_validation->set_rules("subject", "Sujet", "trim|required|min_length[2]",
		    array(
		            "required"   => $required,
		            "min_length" => $min_length
		         )
		);
		$this->form_validation->set_rules("name", "Nom", "trim|required|min_length[2]",
		    array(
		            "required"   => $required,
		            "min_length" => $min_length
		         )
		);
		$this->form_validation->set_rules("email", "Email", "trim|required|valid_email",
		    array(
		            "required"    => $required,
		            "valid_email" => "Ce champ doit être au format email"
		         )
		);
		$this->form_validation->set_rules("message", "Message", "trim|required",
		    array(
		            "required"  => $required
		         )
		);

		// Délimiteurs pour les erreurs de chaque champ
		$this->form_validation->set_error_delimiters('<div class="callout warning" data-closable>', '<button class="close-button" aria-label="Dismiss alert" type="button" data-close><span aria-hidden="true">&times;</span></button></div>');

		$data["success"] = "";

		// Formulaire bien rempli
		if ($this->form_validation->run() !== FALSE) {

		    // Chargement de la librairie dédiée à l'envoie des emails
		    $this->load->library("email");

		    $this->email->from($this->input->post("email"), $this->input->post("name")); # email et nom de l'expéditeur
		    $this->email->to("contact@monsite.com");                                     # email destinataire (vous)
		    $this->email->subject($this->input->post("subject"));                        # sujet de l'email
		    $this->email->message($this->input->post("message"));                        # corps du message

		    $this->email->send();                                                        # envoie de l'email
		    echo($this->email->print_debugger());

		    $data["success"] = "Votre message a été envoyé";
		}

		// Affichage de la vue + données
		$this->template->load('front/view_layout', 'front/view_contact', $data);
    }

}