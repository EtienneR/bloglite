<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Model_users');
		$this->load->library(array('form_validation', 'Functions', 'session', 'Template'));
		$this->load->helper('form');
		$this->output->enable_profiler(TRUE);
		$this->layout = layoutAdmin();
		$this->url    = 'admin/users/';

		// Préparation des champs
		$this->name = array(
		                'class' => 'form-control',
		                'name'  => 'name',
		                'id'    => 'name',
		                'value' => set_value('name'));

		$this->password = array(
		                'class' => 'form-control',
		                'name'  => 'password',
		                'id'    => 'password',
		                'type'  => 'password');

		$this->option_level = array('1' => 'Modérateur',
									'0' => 'Administrateur');

		// Longeur minimum pour le mdp
		$this->password_length = 4;
	}

	// Crypter / hacher le MDP
	function _crypt($password)
	{
		$options = [
			'cost' => 11, 										// Cout algorithmique
			'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),	// Salt automatique
		];
		
		return password_hash($password, PASSWORD_BCRYPT, $options);
	}

	// Vérifier le MDP
	function _decrypt($password, $passwordCrypted)
	{
		// Récupération du MDP saisie par l'utilisateur
		if (password_verify($password, $passwordCrypted)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function index()
	{
		if ($this->functions->getLoged()) {
			$data['connected']  = $this->functions->getUser();

			$data['title_page']	= 'Utilisateurs';
			$data['users']		= $this->Model_users->findAll();

			$this->template->load($this->layout, 'admin/users/view_users', $data);
		}
	}

	// Connexion
	public function signIn()
	{
		// Déja connecté
		if ($this->session->userdata('logged_in')) {
			redirect('admin/articles');
		} 

		// Aucun utilisateur dans la BDD
		if ($this->Model_users->findAll()->num_rows() == 0) {
			redirect('users/signup');
			//echo 'que dalle';
			//$this->signUp();
			//echo 'Inscription';
		} else {
			$data['title_page'] = 'Connexion';

			// Préparation des champs
			$data['name']	  = $this->name;
			$data['password'] = $this->password;

			// Règles de chaque champs
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			// Délimiteurs pour les erreurs de chaque champ
			$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');

			// Stockage des valeurs
			$name     = $this->input->post('name');
			$password = $this->input->post('password');

			if ($this->form_validation->run() !== FALSE) {
				$result = $this->Model_users->check_user($name);

				if ($result->num_rows() == 1) {

					if ($this->_decrypt($password, $result->row()->password) == TRUE) {

						if ($result->num_rows() == 1) {
							// Création de la session de connexion
							$session = array(
								'id'		=> $result->row()->userId,
								'username'	=> $name,
								'level'		=> $result->row()->level,
								'logged_in' => TRUE
							);
							$this->session->set_userdata('logged_in', $session);

							if ($this->session->userdata('url')) {
								redirect($this->session->userdata('url'));
							} else {
								$this->session->set_flashdata('success', 'Bienvenue '. $name . ' sur votre dashboard.');

								redirect(base_url('admin/articles'));
							}

						} else {
							$this->session->set_flashdata('warning', 'Votre compte n\'est pas activé.');
						}

					} else {
						$this->session->set_flashdata('warning', 'Mot de passe invalide.');
					}

				} else {
					$this->session->set_flashdata('warning', 'Cet utilisateur n\'existe pas.');
				}
			}

			$this->template->load($this->layout, 'admin/users/view_signin', $data);
		}
	}

	// Inscription
	public function signUp()
	{
		$check_user = $this->Model_users->findAll()->num_rows();

		if ( $check_user === 0 OR ($this->functions->getLoged() && $this->functions->getUser()['level'] === 0) ) {

			$data['title_page'] = 'Inscription';

			if ($check_user != 0) {
				$data['connected']  = $this->functions->getUser();
			} else {
				$data['connected'] = false;
			}

			// Préparation des champs
			$data['name'] 	  = $this->name;
			$data['password'] = $this->password;

			$data['password_confirm'] = array(
							'class' => 'form-control',
							'name'  => 'password_confirm',
							'id'    => 'password_confirm',
							'type'  => 'password',
							'value' => set_value('password_confirm'));

			$data['email'] = array(
							'class' => 'form-control',
							'name'  => 'email',
							'id'    => 'email',
							'type'  => 'email',
							'value' => set_value('email'));

			if ($check_user != 0) {
				$data['options_level'] = $this->option_level;
			} else {
				$data['options_level'] = '';
			}
			$data['level']		   = '';

			$data['password_length'] = $this->password_length;

			// Règles de chaque champs
			$this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]|is_unique[users.name]');
			$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[' . $data['password_length'] . ']');
			$this->form_validation->set_rules('password_confirm', 'Password confirm', 'required|trim|matches[password]');
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');

			// Délimiteurs pour les erreurs de chaque champ
			$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');

			// Stockage des valeurs
			$name     = $this->input->post('name');
			$password = $this->input->post('password');
			$email	  = $this->input->post('email');
			if ($check_user != 0) {
				$level = $this->input->post('level');
			}

			// Formulaire OK
			if ($this->form_validation->run() !== FALSE) {
				// Encryption du MDP
				$password = $this->_crypt($password);
				// Stockage en BDD
				if ($check_user != 0) {
					$this->Model_users->insert($name, $password, $email, $level);
				} else {
					$this->Model_users->insert($name, $password, $email, 0);
				}
				$this->session->set_flashdata('success', 'Utilisateur "' . $name . '" créé');
				redirect(base_url($this->url));
			}

			$this->template->load($this->layout, 'admin/users/view_signup', $data);

		} else {
			$this->session->set_flashdata('warning', 'Vous n\'avez pas les droits nécessaires pour cette action.');
			redirect(base_url($this->url));
		}
	}

	// Modifier un utilisateur
	public function edit($id)
	{
		if ($this->functions->getLoged()) {

			$user = $this->Model_users->find($id);

			if ($user->num_rows() == 1) {
				$data['connected']  = $this->functions->getUser();

				if ( ($data['connected']['level'] !== 0) && ($data['connected']['id'] !== intval($id)) ) {
					$this->session->set_flashdata('warning', 'Vous n\'avez pas les droits pour modifier cet utilisateur.');
					redirect(base_url($this->url));
				}

				if ($id == $data['connected']['id']) {
					$data['title_page'] = 'Mon profil';
				} else {
					$data['title_page'] = 'Profil de '. $user->row()->name;
				}

				// Préparation des champs
				$data['name'] = array(
								'class' => 'form-control',
								'name'  => 'name',
								'id'    => 'name',
								'value' => $user->row()->name);

				$data['password_old'] = array(
								'class' => 'form-control',
								'name'  => 'password_old',
								'id'    => 'password_old',
								'type'  => 'password',
								'value' => set_value('password_old'));

				$data['password_new'] = array(
								'class' => 'form-control',
								'name'  => 'password_new',
								'id'    => 'password_new',
								'type'  => 'password',
								'value' => set_value('password_new'));

				$data['password_confirm'] = array(
								'class' => 'form-control',
								'name'  => 'password_confirm',
								'id'    => 'password_confirm',
								'type'  => 'password',
								'value' => set_value('password_confirm'));

				$data['email'] = array(
								'class' => 'form-control',
								'name'  => 'email',
								'id'    => 'email',
								'type'  => 'email',
								'value' => $user->row()->email);

				$data['options_level'] = $this->option_level;
				$data['level']		   = $user->row()->level;

				if ($this->input->post('name') == $user->row()->name) {
					$unique_name = '';
				} else {
					$unique_name = '|is_unique[users.name]';
				}
				
				if ($this->input->post('email') == $user->row()->email) {
					$unique_email = '';
				} else {
					$unique_email = '|is_unique[users.email]';
				}

				$data['password_length'] = $this->password_length;

				// Règles de chaque champs
				$this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]' . $unique_name);
				$this->form_validation->set_rules('password_old', 'Ancien Password', 'callback__passwordCheck');
				$this->form_validation->set_rules('password_new', 'Nouveau Password', 'trim|min_length[' . $data['password_length'] . ']|differs[password_old]');
				$this->form_validation->set_rules('password_confirm', 'Password', 'trim|matches[password_new]');
				$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $unique_email);

				// Délimiteurs pour les erreurs de chaque champ
				$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');

				// Stockage des valeurs
				$name		  = $this->input->post('name');
				$password_old = $this->input->post('password_old');
				$password_new = $this->input->post('password_new');
				$email		  = $this->input->post('email');

				if ($this->form_validation->run() !== FALSE) {
					if (!empty($password_new)) {
						$password_new = $this->_crypt($password_new);
						$this->Model_users->update($name, $email, $id, $password_new);
					} else {
						$this->Model_users->update($name, $email, $id);
					}

					$this->session->set_flashdata('success', 'Votre profil a été mis à jour.');
					redirect(base_url($this->url));
				}

				$this->template->load($this->layout, 'admin/users/view_signup', $data);
			} else {
				$this->session->set_flashdata('warning', 'Cet utilisateur n\'existe pas ou n\'existe plus.');
				redirect(base_url($this->url));
			}
		}
	}

	// Vérification de l'ancien mot de passe
	function _passwordCheck($password_old)
	{
		if (isset($password_old) && !empty($password_old)) {
			$name     = $this->session->userdata('logged_in')['username'];
			$password = $this->Model_users->check_user($name)->row()->password;

			if ($this->_decrypt($password_old, $password) == TRUE) {
				return TRUE;
			} else {
				$this->form_validation->set_message('_passwordCheck', 'L\'ancien mot de passe n\'est pas bon.');
				return FALSE;
			}
		}
	}

	// Suppression d'un utilisateur
	public function delete($id)
	{
		if ($this->functions->getLoged()) {
			$this->session->unset_userdata('logged_out');

			$user = $this->Model_users->find($id);

			if ($user->num_rows() > 0) {
				$this->session->set_flashdata('success', 'Utilisateur <b>' . $user->row()->name . '</b> supprimé');
				$this->Model_users->delete($id);
				redirect(base_url($this->url));
			} else {
				$this->session->set_flashdata('warning', 'Impossible de supprimer l\'utilisateur #' . $id . ' car il n\'existe pas ou n\'a jamais existé');
				redirect(base_url('admin/articles'));
			}
		}
	}

	// reset du password
	public function reset()
	{
		$data['title_page'] = 'Réinitialiser mon mot de passe';

		$data['email'] = array(
						'class' => 'form-control',
						'name'  => 'email',
						'id'    => 'email',
						'type'  => 'email',
						'value' => set_value('email'));

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback__emailCheck');

		// Délimiteurs pour les erreurs de chaque champ
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');

		$email = $this->input->post('email');

		if ($this->form_validation->run() !== FALSE) {
			$random = md5(uniqid());
			$password = $this->_crypt($random);
			$this->Model_users->passwordReset($password, $email);
			$this->session->set_flashdata('success', 'Un nouveau mot de passe vous a été envoyé.');
			var_dump($random);
			//redirect(base_url('admin'));
		}

		$this->template->load($this->layout, 'admin/users/view_reset', $data);
	}

	function _emailCheck($email)
	{
		$email = $this->Model_users->emailCheck($email);

		if ($email->num_rows() == 1) {
			return TRUE;
		} else {
			$this->form_validation->set_message('_emailCheck', 'Cette adresse email n\'existe pas');
			return FALSE;
		}
	}

	// Déconnexion
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->set_flashdata('success', 'Vous êtes désormais déconnecté(e)');

		session_destroy();

		redirect(base_url('admin'));
	}

	public function backup()
	{
		var_dump($this->_crypt('titi'));
	}

}