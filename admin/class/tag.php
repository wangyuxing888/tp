<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team 
 * This is NOT a freeware, use is subject to license terms
 * $Id: tag.php 33828 2008-02-22 09:25:26Z team $
 */


class Tag extends Base{

	function __construct()
	{
		global $tqb;
		parent::__construct($tqb->table['Tag'],$tqb->datainfo['Tag']);
	}

	function __call($method, $args) {
		foreach ($GLOBALS['Filter_Plugin_Tag_Call'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($this,$method, $args);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}
	}

	public function __set($name, $value)
	{
        global $tqb;
		if ($name=='Url') {
			return null;
		}
		if ($name=='Template') {
			if($value==$tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'])$value='';
			return $this->data[$name]  =  $value;
		}
		parent::__set($name, $value);
	}

	public function __get($name)
	{
        global $tqb;
		if ($name=='Url') {
			$u = new UrlRule($tqb->option['CFG_TAGS_REGEX']);
			$u->Rules['{%id%}']=$this->ID;
			$u->Rules['{%alias%}']=$this->Alias==''?urlencode($this->Name):$this->Alias;
			return $u->Make();
		}
		if ($name=='Template') {
			$value=$this->data[$name];
			if($value=='')$value=$tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'];
			return $value;
		}
		return parent::__get($name);
	}

	function Save(){
        global $tqb;
		if($this->Template==$tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'])$this->data['Template'] = '';
		foreach ($GLOBALS['Filter_Plugin_Tag_Save'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($this);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}
		return parent::Save();
	}
	
}
