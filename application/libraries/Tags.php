<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags {

	public function tagsList() 
	{
		$CI =& get_instance();
		$tags = $CI->Model_articles->findTags();

		if ($tags->num_rows() > 0) {
			foreach ($tags->result_array() as $tag) {
				if (!empty(current($tag))) {
					$tags_str[] = implode('', $tag);
				}
			}

			$implode = implode('', $tags_str);
			$explode = explode(';', $implode);

			return array_unique($explode);
		}
	}

}