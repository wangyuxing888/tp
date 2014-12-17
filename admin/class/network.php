<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: network.php 33828 2008-02-22 09:25:26Z team $
 */

interface iNetwork
{


	public function abort();
	public function getAllResponseHeaders();
	public function getResponseHeader($bstrHeader);
	public function open($bstrMethod, $bstrUrl, $varAsync=true, $bstrUser='', $bstrPassword='');
	public function send($varBody='');
	public function setRequestHeader($bstrHeader, $bstrValue);

}

/**
* NetworkFactory
*/
class NetworkFactory
{

	public $networktype = null;
	public $network_list = array();
	public $curl = false;
	public $fso = false;

	function __construct()
	{
		if (function_exists('curl_init'))
		{
			$this->network_list[] = 'curl';
			$this->curl = true;
		}

		if ((bool)ini_get('allow_url_fopen'))
		{
			if(function_exists('file_get_contents')) $this->network_list[] = 'file_get_contents';
			if(function_exists('fsockopen')) $this->network_list[] = 'fsockopen';
			$this->fso = true;
		}
	}

	function Create($extension = '')
	{
		if ((!$this->fso) && (!$this->curl)) return false;
		$extension = ($extension == '' ? $this->network_list[0] : $extension);
		$type = 'network' . $extension;
		$network = New $type();
		return $network;
	}


}
