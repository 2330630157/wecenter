<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2013 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|   
+---------------------------------------------------------------------------
*/


if (!defined('IN_ANWSION'))
{
	die;
}

class page_class extends AWS_MODEL
{
	public function get_page_by_url_token($url_token)
	{
		return $this->fetch_row('pages', "url_token = '" . $this->quote($url_token) . "'");
	}
	
	public function get_page_by_url_id($id)
	{
		return $this->fetch_row('pages', 'id = ' . intval($id));
	}
	
	public function add_page($title, $keywords, $description, $content, $url_token)
	{
		return $this->insert('pages', array(
			'title' => $title,
			'keywords' => $keywords,
			'description' => $description,
			'contents' => $contents,
			'url_token' => $url_token
		));
	}
	
	public function update_page($id, $title, $keywords, $description, $content)
	{
		return $this->update('pages', array(
			'title' => $title,
			'keywords' => $keywords,
			'description' => $description,
			'contents' => $contents
		), 'id = ' . intval($id));
	}
}
