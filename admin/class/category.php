<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team 
 * This is NOT a freeware, use is subject to license terms
 * $Id: category.php 33828 2008-02-22 09:25:26Z team $
 */


class Category extends Base{

	public $SubCategorys=array();

	function __construct()
	{
		global $tqb;
		parent::__construct($tqb->table['Category'],$tqb->datainfo['Category']);

		$this->Name	= $GLOBALS['lang']['msg']['unnamed'];
	}

	function __call($method, $args) {
		foreach ($GLOBALS['Filter_Plugin_Category_Call'] as $fpname => &$fpsignal) {
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
		if ($name=='Symbol') {
			return null;
		}
		if ($name=='Level') {
			return null;
		}
		if ($name=='SymbolName') {
			return null;
		}
		if ($name=='Parent') {
			return null;
		}		
		if ($name=='Template') {
			if($value==$tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'])$value='';
			return $this->data[$name]  =  $value;
		}
		if ($name=='LogTemplate') {
			if($value==$tqb->option['CFG_POST_DEFAULT_TEMPLATE'])$value='';
			return $this->data[$name]  =  $value;
		}
		parent::__set($name, $value);
	}

	public function __get($name)
	{
        global $tqb;
		if ($name=='Url') {
			$u = new UrlRule($tqb->option['CFG_CATEGORY_REGEX']);
			$u->Rules['{%id%}']=$this->ID;
			$u->Rules['{%alias%}'] = $this->Alias==''?urlencode($this->Name):$this->Alias;
			return $u->Make();
		}
		if ($name=='Symbol') {
			if($this->ParentID==0){
				return ;
			}else{
				$l=$this->Level;
				if($l==1){
					return '&nbsp;└';	
				}elseif($l==2){
					return '&nbsp;&nbsp;&nbsp;└';	
				}elseif($l==3){
					return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└';	
				}
				return ;
			}
		}
		if ($name=='Level') {
			if($this->ParentID==0){
				$this->RootID=0;
				return 0;
			}
			if($tqb->categorys[$this->ParentID]->ParentID==0){
				$this->RootID=$this->ParentID;
				return 1;
			}
			if($tqb->categorys[$tqb->categorys[$this->ParentID]->ParentID]->ParentID==0){
				$this->RootID=$tqb->categorys[$this->ParentID]->ParentID;
				return 2;
			}
			if($tqb->categorys[$tqb->categorys[$tqb->categorys[$this->ParentID]->ParentID]->ParentID]->ParentID==0){
				$this->RootID=$tqb->categorys[$tqb->categorys[$this->ParentID]->ParentID]->ParentID;				
				return 3;
			}

			return 0;
		}
		if ($name=='SymbolName') {
			return $this->Symbol . htmlspecialchars($this->Name);
		}
		if ($name=='Parent') {
			if($this->ParentID==0){
				return null;
			}else{
				return $tqb->categorys[$this->ParentID];
			}
		}	
		if ($name=='Template') {
			$value=$this->data[$name];
			if($value=='')$value=$tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'];
			return $value;
		}
		if ($name=='LogTemplate') {
			$value=$this->data[$name];
			if($value=='')$value=$tqb->option['CFG_POST_DEFAULT_TEMPLATE'];
			return $value;
		}
		return parent::__get($name);
	}

	function Save(){
        global $tqb;
		if($this->Template==$tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'])$this->data['Template'] = '';
		if($this->LogTemplate==$tqb->option['CFG_POST_DEFAULT_TEMPLATE'])$this->data['LogTemplate'] = '';
		foreach ($GLOBALS['Filter_Plugin_Category_Save'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($this);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}
		return parent::Save();
	}

}
