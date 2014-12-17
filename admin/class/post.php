<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team 
 * This is NOT a freeware, use is subject to license terms
 * $Id: post.php 33828 2008-02-22 09:25:26Z team $
 */

 
class Post extends Base{

	function __construct()
	{
		global $tqb;
		parent::__construct($tqb->table['Post'],$tqb->datainfo['Post']);

		$this->ID = 0;
		$this->Title	= $GLOBALS['lang']['msg']['unnamed'];
		$this->PostTime	= time();
	}


	function __call($method, $args) {
		foreach ($GLOBALS['Filter_Plugin_Post_Call'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($this,$method,$args);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}
	}


	public function Time($s='Y-m-d H:i:s'){
		return date($s,(int)$this->PostTime);
	}

	function TagsToNameString(){
		global $tqb;
		$s=$this->Tag;
		if($s=='')return '';
		$s=str_replace('}{', '|', $s);
		$s=str_replace('{', '', $s);
		$s=str_replace('}', '', $s);
		$b=explode('|', $s);
		$b=array_unique($b);

		$a=$tqb->LoadTagsByIDString($this->Tag);
		$s='';
		$c='';
		foreach ($b as $key) {
			if(isset($tqb->tags[$key])){
				$c[] = $tqb->tags[$key]->Name;
			}
		}
		if(!$c)return '';
		$s=implode(',', $c);
		return $s;
	}

	public function __set($name, $value) 
	{
        global $tqb;
		switch ($name) {
			case 'Category':
			case 'Author':
			case 'TypeName':
			case 'Url':
			case 'Tags':
			case 'TagsName':
			case 'TagsCount':
			case 'CommentPostUrl':
			case 'Prev':
			case 'Next':
			case 'RelatedList':
				return null;
				break;
			case 'Template':
				if($value==$tqb->option['CFG_POST_DEFAULT_TEMPLATE'])$value='';
				return $this->data[$name]  =  $value;
				break;
			default:
				parent::__set($name, $value);
				break;
		}
	}

	public function __get($name) 
	{
        global $tqb;
		switch ($name) {
			case 'Category':
				return $tqb->GetCategoryByID($this->CateID);
				break;
			case 'Author':
				return $tqb->GetMemberByID($this->AuthorID);
				break;
			case 'StatusName':
				return $tqb->lang['post_status_name'][$this->Status];
				break;
			case 'OriginName':
				return $tqb->lang['post_origin_name'][$this->Origin];
				break;
			case 'Url':
				if($this->Type==CFG_POST_TYPE_ARTICLE){
					$u = new UrlRule($tqb->option['CFG_ARTICLE_REGEX']);
				}else{
					$u = new UrlRule($tqb->option['CFG_PAGE_REGEX']);
				}
				$u->Rules['{%id%}']=$this->ID;
				if($this->Alias){
					$u->Rules['{%alias%}']=$this->Alias;
				}else{
					$u->Rules['{%alias%}']=urlencode($this->Title);
				}
				$u->Rules['{%year%}']=$this->Time('Y');
				$u->Rules['{%month%}']=$this->Time('m');
				$u->Rules['{%day%}']=$this->Time('d');
				if($this->Category->Alias){
					$u->Rules['{%category%}']=$this->Category->Alias;
				}else{
					$u->Rules['{%category%}']=urlencode($this->Category->Name);
				}
				if($this->Author->Alias){
					$u->Rules['{%author%}']=$this->Author->Alias;
				}else{
					$u->Rules['{%author%}']=urlencode($this->Author->Name);
				}
				return $u->Make();
				break;
			case 'Tags':
				return $tqb->LoadTagsByIDString($this->Tag);
				break;
			case 'TagsCount':
				return substr_count($this->Tag, '{');
				break;				
			case 'TagsName':
				return $this->TagsToNameString;
			case 'Template':
				$value=$this->data[$name];
				if($value==''){
					$value=GetValueInArray($this->Category->GetData(),'LogTemplate');
					if($value==''){
						$value=$tqb->option['CFG_POST_DEFAULT_TEMPLATE'];
					}
				}
				return $value;
			case 'CommentPostUrl':
				foreach ($GLOBALS['Filter_Plugin_Post_CommentPostUrl'] as $fpname => &$fpsignal) {
					$fpreturn=$fpname($this);
					if($fpsignal == PLUGIN_EXITSIGNAL_RETURN)return $fpreturn;
				}
				$key='&amp;key=' . md5($tqb->guid . $this->ID . date('Y-m-d'));
				return $tqb->host . 'admin/admin.php?act=cmt&amp;postid=' . $this->ID . $key;
				break;
			case 'CaptchaUrl':
				return $tqb->captchaurl . '?id=cmt';
				break;
			case 'QRcode':
				return $tqb->host . 'admin/script/qrcode.php?url=' . $tqb->host . '?id=' .$this->ID;
				break;
			case 'Prev':
				static $_prev=null;
				if($_prev!==null)return $_prev;
				$articles=$tqb->GetPostList(
					array('*'),
					array(array('=','log_Type',0),array('=','log_Status',0),array('<','log_PostTime',$this->PostTime)),
					array('log_PostTime'=>'DESC'),
					array(1),
					null
				);
				if(count($articles)==1){
					$_prev=$articles[0];
				}else{
					$_prev='';
				}
				return $_prev;
				break;
			case 'Next':
				static $_next=null;
				if($_next!==null)return $_next;
				$articles=$tqb->GetPostList(
					array('*'),
					array(array('=','log_Type',0),array('=','log_Status',0),array('>','log_PostTime',$this->PostTime)),
					array('log_PostTime'=>'ASC'),
					array(1),
					null
				);
				if(count($articles)==1){
					$_next=$articles[0];
				}else{
					$_next='';
				}
				return $_next;
				break;
			case 'RelatedList':
				foreach ($GLOBALS['Filter_Plugin_Post_RelatedList'] as $fpname => &$fpsignal) {
					$fpreturn=$fpname($this);
					if($fpsignal == PLUGIN_EXITSIGNAL_RETURN)return $fpreturn;
				}
				return GetList($tqb->option['CFG_RELATEDLIST_COUNT'],null,null,null,null,null,array('is_related'=>$this->ID));
			default:
				return parent::__get($name);
				break;
		}

	}

	function Save(){
        global $tqb;
		if($this->Type==CFG_POST_TYPE_ARTICLE){
			if($this->Template==GetValueInArray($this->Category->GetData(),'LogTemplate'))$this->data['Template'] = '';
		}
		if($this->Template==$tqb->option['CFG_POST_DEFAULT_TEMPLATE'])$this->data['Template'] = '';
		foreach ($GLOBALS['Filter_Plugin_Post_Save'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($this);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}
		return parent::Save();
	}
	
}
