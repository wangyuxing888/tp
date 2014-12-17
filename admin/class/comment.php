<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team 
 * This is NOT a freeware, use is subject to license terms
 * $Id: comment.php 33828 2008-02-22 09:25:26Z team $
 */


class Comment extends Base{

	public $IsThrow=false;
	public $FloorID=0;

	function __construct()
	{
		global $tqb;
		parent::__construct($tqb->table['Comment'],$tqb->datainfo['Comment']);
	}

	function __call($method, $args) {
		foreach ($GLOBALS['Filter_Plugin_Comment_Call'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($this,$method, $args);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}
	}

	static public function GetRootID($parentid){
		global $tqb;
		if($parentid==0)return 0;
		$c = $tqb->GetCommentByID($parentid);
		if($c->RootID==0){
			return $c->ID;
		}else{
			return $c->RootID;
		}
	}

	public function IP(){
		if ($this->AuthorID){
			$IP = '&#42;.&#42;.&#42;.&#42;';
		}else{
			$IP = substr($this->IP, 0, strrpos($this->IP, '.')).'.&#42;';
		}
		return $IP;
	}

	public function Time($s='Y-m-d H:i:s'){
		return date($s,(int)$this->PostTime);
	}

	public function __set($name, $value)
	{
        global $tqb;
		if ($name=='Author') {
			return null;
		}
		if ($name=='Comments') {
			return null;
		}
		if ($name=='Level') {
			return null;
		}
		if ($name=='Post') {
			return null;
		}
		parent::__set($name, $value);
	}

	public function __get($name)
	{
        global $tqb;
		if ($name=='Author') {
			$m=$tqb->GetMemberByID($this->AuthorID);
			if($m->ID==0){
				$m->Name=$this->Name;
				$m->QQ=$this->QQ;
				$m->Email=$this->Email;
				$m->HomePage=$this->HomePage;
			}
			return $m;
		}
		if ($name=='Comments') {
			$array=array();
			foreach ($tqb->comments as $comment) {
				if($comment->ParentID==$this->ID){
					$array[]=&$tqb->comments[$comment->ID];
				}
			}
			return $array;
		}
		if ($name=='Level') {
			if($this->ParentID==0){return 0;}

			$c1=$tqb->GetCommentByID($this->ParentID);
			if($c1->ParentID==0){return 1;}

			$c2=$tqb->GetCommentByID($c1->ParentID);
			if($c2->ParentID==0){return 2;}

			$c3=$tqb->GetCommentByID($c2->ParentID);
			if($c3->ParentID==0){return 3;}

			return 4;
		}
		if ($name=='Post') {
			$p=$tqb->GetPostByID($this->LogID);
			return $p;
		}
		return parent::__get($name);
	}

	function Save(){
        global $tqb;
		foreach ($GLOBALS['Filter_Plugin_Comment_Save'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($this);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}
		return parent::Save();
	}
	
}
