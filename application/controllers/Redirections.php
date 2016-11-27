<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redirections extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	/* Redirection depuis un fichier JSON
	* Créez un fichier JSON "slugs_redurections.json" dans le dossier "application/database/".
	* [
	*	{
	*		"old_slug": "foo/bar",
	*		"new_slug": "bar"
	*	},
	*	{
	*		"old_slug": "bar/foo",
	*		"new_slug": "foo"
	*	}
	* ]
	* Dans l'exemple ci-dessous, l'URL "foo/bar" redirige sur "foo"
	* Dans l'exemple ci-dessous, l'URL "bar/foo" redirige sur "bar"
	*/
	public function index($param1, $param2) {
		$db_folder = str_replace("\\", "/", APPPATH . 'database/');
		$file      = $db_folder . 'slugs_redirections.json';

		if (file_exists($file)) {
			// Lecture du fichier
			$json = json_decode(file_get_contents($file), false);

			foreach ($json as $row) {
				$params = explode("/", $row->old_slug);

				if ($param2 !== $params[1]) {
					show_404();
				} else {
					redirect($row->new_slug);
				}
			}

		} else {
			show_404();
		}
	}
	
}