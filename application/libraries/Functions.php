<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions {

	function getLoged()
	{
		$CI =& get_instance();
		if ($CI->session->userdata('logged_in')) {
			return TRUE;
		} else {
			return $this->getRedirect(current_url());
		}
	}

	function getRedirect($url)
	{
		$CI =& get_instance();
		$newdata = array('url' => $url);
		$CI->session->set_userdata($newdata);
		$data = $CI->session->set_userdata('logged_out', TRUE).redirect(base_url('admin'));

		return $data;
	}

	function getUser()
	{
		$data = array(
			'id'		=> $this->getUserId(),
			'level'		=> $this->getUserLevel(),
			'logged_in' => $this->getUserLogged(),
			'username' 	=> $this->getUsername()
		);

		return $data;
	}

	function getUserId()
	{
		$CI   =& get_instance();
		$data = $CI->session->userdata('logged_in');

		return $data['id'];
	}

	function getUserLevel()
	{
		$CI   =& get_instance();
		$data = $CI->session->userdata('logged_in');

		return $data['level'];
	}

	function getUserLogged()
	{
		$CI   =& get_instance();
		$data = $CI->session->userdata('logged_in');

		return $data['logged_in'];
	}

	function getUsername()
	{
		$CI   =& get_instance();
		$data = $CI->session->userdata('logged_in');

		return $data['username'];
	}

	function getInputValue($first, $second) {
		if (isset($first)) {
			return $first;
		} elseif (isset($second)) {
			return $second;
		} else {
			return null;
		}
	}
}