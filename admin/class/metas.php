<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: metas.php 33828 2008-02-22 09:25:26Z team $
 */

class Metas {

	public $Data=array();

	public function __set($name, $value)
	{
		$this->Data[$name] = $value;
	}

	public function __get($name)
	{
		if(!isset($this->Data[$name]))return null;
		return $this->Data[$name];
	}

	public static function ConvertArray($a){
		$m = new Metas;
		if(is_array($a)){
			$m->Data=$a;
		}
		return $m;
	}

	public function HasKey($name){
		return array_key_exists($name,$this->Data);
	}

	public function CountItem(){
		return count($this->Data);
	}

	public function Del($name){

		 unset($this->Data[$name]);
	}

	public function Serialize(){
		global $tqb;
		if(count($this->Data)==0)return '';
		foreach ($this->Data as $key => $value) {
			if(is_string($value)){
				$this->Data[$key]=str_replace(($tqb->option['CFG_PERMANENT_DOMAIN_ENABLE']==false?$tqb->host:$tqb->option['CFG_BLOG_HOST']),'{#CFG_BLOG_HOST#}',$value);
			}
		}
		return serialize($this->Data);
	}

	public function Unserialize($s){
		global $tqb;
		if($s=='')return false;
		$this->Data=unserialize($s);
		if(count($this->Data)==0)return false;
		foreach ($this->Data as $key => $value) {
			if(is_string($value)){
				$this->Data[$key]=str_replace('{#CFG_BLOG_HOST#}',($tqb->option['CFG_PERMANENT_DOMAIN_ENABLE']==false?$tqb->host:$tqb->option['CFG_BLOG_HOST']),$value);
			}
		}
		return true;
	}


}
