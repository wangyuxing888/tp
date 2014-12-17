<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team 
 * This is NOT a freeware, use is subject to license terms
 * $Id: module.php 33828 2008-02-22 09:25:26Z team $
 */


class Module extends Base{


	function __construct()
	{
		global $tqb;
		parent::__construct($tqb->table['Module'],$tqb->datainfo['Module']);
	}

	public function __set($name, $value)
	{
        global $tqb;
		if ($name=='SourceType') {
			return null;
		}
		parent::__set($name, $value);
	}

	public function __get($name)
	{
        global $tqb;
		if ($name=='SourceType') {
			if($this->Source=='system'){
				return 'system';
			}elseif($this->Source=='user'){
				return 'user';
			}elseif($this->Source=='theme'){
				return 'theme';
			}else{
				return 'plugin';
			}
		}
		return parent::__get($name);
	}

}
