<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_pages extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->table = 'articles';
	}

	// Tous les articles
	function findAll($currentPage = '', $perPage = '', $strict = '')
	{
		$this->db->select('articleId, title, content, slug, cdate, state, tags, users.userId, users.name')
				 ->from($this->table)
				 ->where('type', 'page')
				 ->join('users', 'users.userId = ' . $this->table . '.userId');

		if ($strict && $strict == TRUE) {
			$this->db->where('state', 1);
		}

		$this->db->order_by('articleId', 'desc');

		if ($currentPage && $perPage) {
			$this->db->limit($perPage, ($currentPage-1) * $perPage);
		} elseif ($perPage) {
			$this->db->limit($perPage);
		}

		return $this->db->get();
	}

	// Un article
	function find($id)
	{
		$this->db->select('articleId, title, content, slug, cdate, state, tags, users.userId, users.name')
				 ->from($this->table)
				 ->join('users', 'users.userId = ' . $this->table . '.userId')
				 ->where('type', 'page')
				 ->where('articleId', $id)
				 ->limit(1);

		return $this->db->get();
	}


	// Insertion d'une page
	function insert($title, $content, $state, $slug, $tags, $user_id)
	{
		$data = array(
			'title'   => $title,
			'content' => $content,
			'state'	  => $state,
			'slug'    => $slug,
			'cdate'   => date(DATE_ISO8601, time()),
			'tags'    => $tags,
			'type'    => 'page',
			'userId'  => $user_id
		);

		$this->db->insert($this->table, $data);
	}

	// Modification d'une page
	function update($title, $content, $state, $tags, $id)
	{
		$data = array(
			'title'   => $title,
			'content' => $content,
			'state'	  => $state,
			'tags'	  => $tags
		);

		$this->db->where('articleId', $id);
		$this->db->update($this->table, $data);
	}

}