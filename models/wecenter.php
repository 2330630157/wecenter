<?php
/*
+--------------------------------------------------------------------------
|   Anwsion [#RELEASE_VERSION#]
|   ========================================
|   by Anwsion dev team
|   (c) 2011 - 2012 Anwsion Software
|   http://www.anwsion.com
|   ========================================
|   Support: zhengqiang@gmail.com
|   
+---------------------------------------------------------------------------
*/


if (!defined('IN_ANWSION'))
{
	die;
}

class wecenter_class extends AWS_MODEL
{
	public function mp_server_query($node, $post_data = null)
	{
		if ($post_data)
		{
			foreach ($post_data AS $key => $val)
			{
				$_post_data[] = $key . '=' . rawurlencode($val);
			}
		}
		
		if (!$_post_data)
		{
			return false;
		}
		
		$_post_data[] = 'access_token=' . AWS_APP::config()->get('wecenter')->mp_access_token;
		$_post_data[] = 'version=1';
		
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, AWS_APP::config()->get('wecenter')->mp_server . $node . '/');
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&', $_post_data));
		
		$content = curl_exec($curl);
		
		curl_close($curl);
		
		return json_decode($content, true);
	}
	
	public function set_wechat_fake_id($type, $fake_id, $item_id)
	{
		return $this->insert('weixin_fake_id', array(
			'type' => $type,
			'fake_id' => $fake_id,
			'item_id' => $item_id
		));
	}
	
	public function get_wechat_fake_id_by_message($message)
	{
		$result = $this->mp_server_query('get_wechat_fake_id_by_message', array(
			'message' => $message
		));
		
		if ($result['status'] == 'success')
		{
			return $result['data'];
		}
	}
	
	public function set_wechat_fake_id_by_question($message, $question_id)
	{
		if ($fake_id = $this->get_wechat_fake_id_by_message($message))
		{
			$this->set_wechat_fake_id('question', $fake_id, $question_id);
		}
	}
	
	public function send_wechat_message($fake_id, $message)
	{
		$result = $this->mp_server_query('send_wechat_message', array(
			'message' => $message
		));
		
		if ($result['status'] == 'success')
		{
			return true;
		}
	}
	
	public function get_wechat_fake_id($type, $item_id)
	{
		$fake_info = $this->fetch_row('weixin_fake_id', "`type` = '" . $this->quote($type) . "' AND item_id = " . intval($item_id));
		
		return $fake_info['fake_id'];
	}
}