<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_users extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->table = 'users';
	}

	function findAll()
	{
		$this->db->select('userId, name, email, level')
				 ->from($this->table)
				 ->order_by('userId', 'desc');

		return $this->db->get();
	}

	function find($id)
	{
		$this->db->select('userId, name, email, level')
				 ->from($this->table)
				 ->where('userId', $id)
				 ->limit(1);

		return $this->db->get();
	}

	function check_user($name)
	{
		$this->db->select('userId, name, password, email, level')
				 ->from($this->table)
				 ->where('name', $name)
				 ->limit(1);

		return $this->db->get();
	}

	function emailCheck($email)
	{
		$this->db->select('id')
				 ->from($this->table)
				 ->where('email', $email)
				 ->limit(1);

		return $this->db->get();
	}

	function insert($name, $password, $email, $level)
	{
		$data = array(
			'name'     => $name,
			'password' => $password,
			'email'	   => $email,
			'level'	   => $level
		);

		$this->db->insert($this->table, $data);
	}

	function update($name, $email, $id, $password)
	{
		if (!empty($password)) {
			$data = array(
				'name'	   => $name,
				'email'	   => $email,
				'password' => $password
			);
		} else {
			$data = array(
				'name'  => $name,
				'email' => $email
			);
		}

		$this->db->where('userId', $id);
		$this->db->update($this->table, $data);
	}

	function delete($id)
	{
		$this->db->where('userId', $id)
				 ->delete($this->table);
	}

	function passwordReset($password, $email)
	{
		$data = array(
			'password' => $password
		);

		$this->db->where('email', $email);
		$this->db->update($this->table, $data);
	}

}