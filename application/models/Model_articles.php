<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_articles extends CI_Model {

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
				 ->where('type', 'article')
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
		$this->db->select('articleId, title, content, slug, cdate, state, tags, demo, download, users.userId, users.name')
				 ->from($this->table)
				 ->where('type', 'article')
				 ->join('users', 'users.userId = ' . $this->table . '.userId')
				 ->where('articleId', $id)
				 ->limit(1);

		return $this->db->get();
	}

	// Un article
	function findSlug($slug)
	{
		$this->db->select('*')
				 ->from($this->table)
				 ->where('slug', $slug)
				 ->where('state', 1)
				 ->limit(1);

		return $this->db->get();
	}

	// Un article
	function findAllSlug($slug)
	{
		$this->db->select('*')
				 ->from($this->table)
				 ->where('slug', $slug)
				 ->limit(1);

		return $this->db->get();
	}

	function findAllByState($state) {
		$this->db->select('*')
				 ->join('users', 'users.userId = ' . $this->table . '.userId')
				 ->from($this->table)
				 ->where('type', 'article')
				 ->where('state', $state)
				 ->order_by('articleId', 'desc');

		return $this->db->get();
	}

	// Tous les tags actives (sans tri)
	function findTagActive($tag, $currentPage = '', $perPage = '')
	{
		$this->db->select('*')
				 ->from($this->table)
				 ->like('tags', $tag)
				 ->where('state', 1);

		if ($currentPage && $perPage) {
			$this->db->limit($perPage, ($currentPage-1) * $perPage);
		} elseif ($perPage) {
			$this->db->limit($perPage);
		}

		return $this->db->get();
	}

	function findTags()
	{
		$this->db->select('tags')
				 ->from($this->table)
				 ->where('state', 1)
				 ->where('type', 'article');

		return $this->db->get();
	}

	// Les autres articles
	function others($slug)
	{
		$this->db->select('articleId, title, slug')
				 ->from($this->table)
				 ->where('type', 'article')
				 ->where('slug <>', $slug)
				 ->where('state', 1)
				 ->order_by('articleId', 'desc');

		return $this->db->get();
	}

	// Articles par tag
	function findTag($tag)
	{
		$this->db->select('*')
				 ->from($this->table)
				 ->join('users', 'users.userId = ' . $this->table . '.userId')
				 ->like('tags', $tag)
				 ->order_by('articleId', 'desc');

		return $this->db->get();
	}

	// Tous les articles d'un utilisateur
	function findByUser($id)
	{
		$this->db->select('*')
				 ->from($this->table)
				 ->join('users', 'users.userId = ' . $this->table . '.userId')
				 ->where('type', 'article')
				 ->where($this->table . '.userId', $id)
				 ->order_by('articleId', 'desc');

		return $this->db->get();
	}
	
	function countFindByUser($id)
	{
		$this->db->select('articleId')
				 ->from($this->table)
				 ->join('users', 'users.userId = ' . $this->table . '.userId')
				 ->where('type', 'article')
				 ->where($this->table . '.userId', $id)
				 ->order_by('articleId', 'desc');

		return $this->db->count_all_results();
	}

	// Recherche
	function search($word, $currentPage = '', $perPage = '')
	{
		$this->db->select('*')
				 ->from($this->table)
				 ->like('content', $word)
				 ->or_like('title', $word)
				 ->where('state', 1)
				 ->order_by('articleId', 'desc');

		if ($currentPage && $perPage) {
			$this->db->limit($perPage, ($currentPage-1) * $perPage);
		} elseif ($perPage) {
			$this->db->limit($perPage);
		}

		return $this->db->get();
	}

	// Tous les tags
	function getAllTags()
	{
		$this->db->distinct()
				 ->select('tags')
				 ->from($this->table)
				 ->order_by('tags', 'asc');

		return $this->db->get();
	}


	// Insertion d'un article
	function insert($title, $content, $state, $slug, $cdate, $tags, $demo, $download, $user_id)
	{
		$data = array(
			'title'    => $title,
			'content'  => $content,
			'state'	   => $state,
			'slug'     => $slug,
			'cdate'    => $cdate,
			'tags'     => $tags,
			'type'     => 'article',
			'demo'     => $demo,
			'download' => $download,
			'userId'   => $user_id
		);

		$this->db->insert($this->table, $data);
	}

	// Vérification de l'existence d'un slug
	function checkSlug($slug)
	{
		$this->db->select('slug')
				 ->from($this->table)
				 ->where('slug', $slug);

		return $this->db->get();
	}

	// Modification d'un article
	function update($title, $content, $state, $tags, $demo, $download, $id)
	{
		$data = array(
			'title'    => $title,
			'content'  => $content,
			'state'	   => $state,
			'tags'	   => $tags,
			'demo'	   => $demo,
			'download' => $download
		);

		$this->db->where('articleId', $id);
		$this->db->update($this->table, $data);
	}

	// Suppression d'un article
	function delete($id)
	{
		$this->db->where('articleId', $id)
				 ->delete($this->table);
	}

}