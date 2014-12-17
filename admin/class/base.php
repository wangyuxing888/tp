<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: base.php 33828 2008-02-22 09:25:26Z team $
 */


class Base
{

	protected $table='';
	protected $datainfo = array();
	protected $data = array();
	public $Metas = null;

	function __construct(&$table,&$datainfo){
        global $tqb;
        $this->table=$table;
        $this->datainfo=$datainfo;
		$this->Metas=new Metas;

		foreach ($this->datainfo as $key => $value) {
			$this->data[$key]=$value[3];
		}
	}

	public function __set($name, $value){
		$this->data[$name]  =  $value;
	}

	public function __get($name){
		return $this->data[$name];
	}

	public function __isset($name){
		return isset($this->data[$name]);
	}

	public function  __unset($name){
		unset($this->data[$name]);
	}
	
	function GetTable(){
		return $this->table;
	}
	
	function GetData(){
		return $this->data;
	}
	
	function GetDataInfo(){
		return $this->datainfo;
	}

	function LoadInfoByID($id){
		global $tqb;
		$id=(int)$id;
		$id_field=reset($this->datainfo);
		$id_field=$id_field[0];
		$s = $tqb->db->sql->Select($this->table,array('*'),array(array('=',$id_field,$id)),null,null,null);
		$array = $tqb->db->Query($s);
		if (count($array)>0) {
			$this->LoadInfoByAssoc($array[0]);
			return true;
		}else{
			return false;
		}
	}

	function LoadInfoByAssoc($array){
		global $tqb;

		foreach ($this->datainfo as $key => $value) {
			if(!isset($array[$value[0]]))continue;
			if($value[1] == 'boolean'){
				$this->data[$key]=(boolean)$array[$value[0]];
			}elseif($value[1] == 'string'){
				if($key=='Meta'){
					$this->data[$key]=$array[$value[0]];
				}else{
					$this->data[$key]=str_replace('{#CFG_BLOG_HOST#}',$tqb->host,$array[$value[0]]);
				}
			}else{
				$this->data[$key]=$array[$value[0]];
			}
		}
		if(isset($this->data['Meta']))$this->Metas->Unserialize($this->data['Meta']);
		return true;
	}

	function LoadInfoByArray($array){
		global $tqb;

		$i = 0;
		foreach ($this->datainfo as $key => $value) {
			if(count($array)==$i)continue;
			if($value[1] == 'boolean'){
				$this->data[$key]=(boolean)$array[$i];
			}elseif($value[1] == 'string'){
				if($key=='Meta'){
					$this->data[$key]=$array[$i];
				}else{
					$this->data[$key]=str_replace('{#CFG_BLOG_HOST#}',$tqb->host,$array[$i]);
				}
			}else{
				$this->data[$key]=$array[$i];
			}
			$i += 1;
		}
		if(isset($this->data['Meta']))$this->Metas->Unserialize($this->data['Meta']);
		return true;
	}

	function Save(){
		global $tqb;
		if(isset($this->data['Meta']))$this->data['Meta'] = $this->Metas->Serialize();
		$keys=array();
		foreach ($this->datainfo as $key => $value) {
			if(!is_array($value) || count($value)!=4)continue;
			$keys[]=$value[0];
		}
		$keyvalue=array_fill_keys($keys, '');

		foreach ($this->datainfo as $key => $value) {
			if(!is_array($value)|| count($value)!=4)continue;
			if($value[1]=='boolean'){
				$keyvalue[$value[0]]=(integer)$this->data[$key];
			}elseif($value[1] == 'integer'){
				$keyvalue[$value[0]]=(integer)$this->data[$key];
			}elseif($value[1] == 'float'){
				$keyvalue[$value[0]]=(float)$this->data[$key];
			}elseif($value[1] == 'double'){
				$keyvalue[$value[0]]=(double)$this->data[$key];
			}elseif($value[1] == 'string'){
				if($key=='Meta'){
					$keyvalue[$value[0]]=$this->data[$key];
				}else{
					$keyvalue[$value[0]]=str_replace($tqb->host,'{#CFG_BLOG_HOST#}',$this->data[$key]);
				}
			}else{
				$keyvalue[$value[0]]=$this->data[$key];
			}
		}
		array_shift($keyvalue);

		if ($this->ID  ==  0) {
			$sql = $tqb->db->sql->Insert($this->table,$keyvalue);
			$this->ID = $tqb->db->Insert($sql);
		} else {
			$id_field=reset($this->datainfo);
			$id_field=$id_field[0];
			$sql = $tqb->db->sql->Update($this->table,$keyvalue,array(array('=',$id_field,$this->ID)));
			return $tqb->db->Update($sql);
		}

		return true;
	}

	function Del(){
		global $tqb;
		$id_field=reset($this->datainfo);
		$id_field=$id_field[0];
		$sql = $tqb->db->sql->Delete($this->table,array(array('=',$id_field,$this->ID)));
		$tqb->db->Delete($sql);
		return true;
	}
}
