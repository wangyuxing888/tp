<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: upload.php 33828 2008-02-22 09:25:26Z team $
 */


class Upload extends Base{


	function __construct()
	{
		global $tqb;
		parent::__construct($tqb->table['Upload'],$tqb->datainfo['Upload']);

		$this->ID = 0;
		$this->PostTime = time();
	}

	function CheckExtName($extlist=''){
		global $tqb;
		$e=GetFileExt($this->Name);
		$extlist=strtolower($extlist);
		if(trim($extlist)=='')$extlist=$tqb->option['CFG_UPLOAD_FILETYPE'];
		if(HasNameInString($extlist,$e)){
			return true;
		}else{
			return false;
		}
	}

	function CheckSize(){
		global $tqb;
		$n=1024*1024*(int)$tqb->option['CFG_UPLOAD_FILESIZE'];
		if($n>=$this->Size){
			return true;
		}else{
			return false;
		}
	}

	function DelFile(){
	
		foreach ($GLOBALS['Filter_Plugin_Upload_DelFile'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($this);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}
		if (file_exists($this->FullFile)) { @unlink($this->FullFile);}
		return true;

	}

	function SaveFile($tmp){
		global $tqb;

		foreach ($GLOBALS['Filter_Plugin_Upload_SaveFile'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($tmp,$this);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}

		if(!file_exists($tqb->contentdir . $this->Dir)){
			@mkdir($tqb->contentdir . $this->Dir, 0755,true);	
		}
		if(PHP_OS=='WINNT'||PHP_OS=='WIN32'||PHP_OS=='Windows'){
			$fn=iconv("UTF-8","GBK//IGNORE",$this->Name);
		}else{
			$fn=$this->Name;
		}
		@move_uploaded_file($tmp, $tqb->contentdir . $this->Dir . $fn);
		return true;
	}

	function SaveBase64File($str64){
		global $tqb;

		foreach ($GLOBALS['Filter_Plugin_Upload_SaveBase64File'] as $fpname => &$fpsignal) {
			$fpreturn=$fpname($str64,$this);
			if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
		}

		if(!file_exists($tqb->contentdir . $this->Dir)){
			@mkdir($tqb->contentdir . $this->Dir, 0755,true);	
		}
		$s=base64_decode($str64);
		$this->Size=strlen($s);
		if(PHP_OS=='WINNT'||PHP_OS=='WIN32'||PHP_OS=='Windows'){
			$fn=iconv("UTF-8","GBK//IGNORE",$this->Name);
		}else{
			$fn=$this->Name;
		}
		file_put_contents($tqb->contentdir . $this->Dir . $fn, $s);
		return true;
	}

	public function Time($s='Y-m-d H:i:s'){
		return date($s,$this->PostTime);
	}

	public function __set($name, $value)
	{
        global $tqb;
		if ($name=='Url') {
			return null;
		}
		if ($name=='Dir') {
			return null;
		}
		if ($name=='FullFile') {
			return null;
		}
		if ($name=='Author') {
			return null;
		}		
		parent::__set($name, $value);
	}

	public function __get($name)
	{
        global $tqb;
		if ($name=='Url') {
			foreach ($GLOBALS['Filter_Plugin_Upload_Url'] as $fpname => &$fpsignal) {
				return $fpname($this);
			}
			return $tqb->host . 'content/' . $this->Dir . urlencode($this->Name);
		}
		if ($name=='Dir') {
			return 'upload/' .date('Y',$this->PostTime) . '/' . date('m',$this->PostTime) . '/';
		}
		if ($name=='FullFile') {
			return  $tqb->contentdir . $this->Dir . $this->Name;
		}
		if ($name=='Author') {
			return $tqb->GetMemberByID($this->AuthorID);
		}
		return parent::__get($name);
	}

}
