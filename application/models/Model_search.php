<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_search extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->table = 'search';
	}

	function findAll()
	{
		$this->db->select('searchId, name, date, score')
				 ->from($this->table)
				 ->order_by('searchId', 'desc');

		return $this->db->get();
	}

	function distinctWords()
	{
		$this->db->distinct()
				 ->select('name, count(name) as occurence')
				 ->from($this->table)
				 ->group_by('name')
				 ->order_by('occurence', 'desc');

		return $this->db->get();
	}

	function insert($name, $score)
	{
		$data = array(
			'name' => $name,
			'date' => unix_to_human(now(), TRUE, 'eu'),
			'score' => $score
		);

		$this->db->insert($this->table, $data);
	}

}