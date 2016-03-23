<?php
if ( ! function_exists('css_url'))
{
	function css_url($file_name)
	{
		return '<link rel="stylesheet" href="' . base_url() . 'assets/css/' . $file_name . '.css" />
	';
	}
}

if ( ! function_exists('js_url'))
{
	function js_url($file_name)
	{
		return '<script src="' . base_url() . 'assets/js/' . $file_name . '.js"></script>
	';
	}
}

if ( ! function_exists('getTags'))
{
	function getTags($tags)
	{
		if (isset($tags) && !empty($tags)) {
			echo '- ';
			$tags = explode(';', $tags);
			foreach ($tags as $tag) {
				if (!empty($tag)) {
					echo '<a href="' . base_url('tag/' . $tag) . '">' . $tag . '</a> ';
				}
			}
		}
	}
}

if ( ! function_exists('getConfig'))
{
	function getFileConfig() {
		return APPPATH . '/database/config.js';
	}

	function getConfig() {
		// Chargement du fichier JSON de configuration
		return json_decode(file_get_contents(getFileConfig()));
	}
}

if ( ! function_exists('layoutAdmin'))
{
	function layoutAdmin() {
		return 'admin/view_layout';
	}
}

if ( ! function_exists('date_fr'))
{
	function date_fr($jour, $mois, $annee, $heure='', $minute='')
	{
		$mois_n = $mois;
		switch ($mois) {
			case '01':
				$mois = 'Janvier';
				break;
			case '02':
				$mois = 'Février';
				break;
			case '03':
				$mois = 'Mars';
				break;
			case '04':
				$mois = 'Avril';
				break;
			case '05':
				$mois = 'Mai';
				break;
			case '06':
				$mois = 'Juin';
				break;
			case '7':
				$mois = 'Juillet';
				break;
			case '8':
				$mois = 'Août';
				break;
			case '9':
				$mois = 'Septembre';
				break;
			case '10':
				$mois = 'Octobre';
				break;
			case '11':
				$mois = 'Novembre';
				break;
			case '12':
				$mois = 'Décembre';
				break;
			
			default:
				break;
		}

		if (!empty($heure) && !empty($annee)) {
			return 'Le <time datetime="' . $annee . '-' . $mois_n . '-' . $jour . '">' .$jour . ' ' . $mois . ' ' . $annee . ' à ' . $heure . 'h' . $minute . '</time>';
		} else {
			return 'Le <time datetime="' . $annee . '-' . $mois_n . '-' . $jour . '">' .$jour . ' ' . $mois . ' ' . $annee . '</time>';
		}
	}
}

if ( ! function_exists('pagination_custom'))
{
	function pagination_custom($total_rows, $base_url, $first_url, $page_query_string) {

		if ($page_query_string == true) {
			$config['page_query_string']    = TRUE;
			$config['enable_query_strings'] = TRUE;
			$config['query_string_segment'] = 'page';
		}

		$config['base_url']   = $base_url;
		$config['first_url']  = $first_url;
		$config['total_rows'] = $total_rows;
		$config['num_links']  = $config['total_rows'];

		$config['per_page']	  		= getConfig()->pagination;
		$config['use_page_numbers'] = TRUE;

		$config['full_tag_open']  = '<ul class="pagination" role="navigation" aria-label="Pagination">';
		$config['full_tag_close'] = '</ul><!--pagination-->';
		$config['num_tag_open']	  = '<li>';
		$config['num_tag_close']  = '</li>';
		$config['cur_tag_open']   = '<li class="current">';
		$config['cur_tag_close']  = '</li>';
		$config['next_link']	  = '';
		$config['next_tag_open']  = '<li class="pagination-next">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link']	  = '';
		$config['prev_tag_open']  = '<li class="pagination-previous">';
		$config['prev_tag_close'] = '</li>';

		return $config;
	}
}
