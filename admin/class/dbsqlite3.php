<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: dbsqlite3.php 33828 2008-02-22 09:25:26Z team $
 */



/**
*
*/
class DbSQLite3 implements iDataBase
{

	public $dbpre = null;
	private $db = null;

	public $sql=null;

	function __construct()
	{
		$this->sql=new DbSql;
		$this->sql->type=__CLASS__;
	}

	public function EscapeString($s){
		return SQLite3::escapeString($s);
	}

	function Open($array){
		if ($this->db = new SQLite3($array[0]) ){
			$this->dbpre=$array[1];
			return true;
		} else{
			return false;
		}
	}

	function Close(){
		$this->db->close();
	}

	function QueryMulit($s){
		$a=explode(';',str_replace('%pre%', $this->dbpre, $s));
		foreach ($a as $s) {
			$this->db->query($s);
		}

	}

	function Query($query){
		$query=str_replace('%pre%', $this->dbpre, $query);
		// 遍历出来
		$results =$this->db->query($query);
		$data = array();
		if($results){
			while($row = $results->fetchArray()){
				$data[] = $row;
			}
		}
		return $data;
	}

	function Update($query){
		$query=str_replace('%pre%', $this->dbpre, $query);
		return $this->db->query($query);
	}

	function Delete($query){
		$query=str_replace('%pre%', $this->dbpre, $query);
		return $this->db->query($query);
	}

	function Insert($query){
		$query=str_replace('%pre%', $this->dbpre, $query);
		$this->db->query($query);
		return $this->db->lastInsertRowID();
	}

	function CreateTable($tablename,$datainfo){
		$this->QueryMulit($this->sql->CreateTable($tablename,$datainfo));
	}

	function DelTable($tablename){
		$this->Query($this->sql->DelTable($tablename));
	}

	function ExistTable($tablename){
		$tqb=TQBlogPHP::GetInstance();
		$a=$this->Query($this->sql->ExistTable($tablename));
		if(!is_array($a))return false;
		$b=current($a);
		if(!is_array($b))return false;
		$c=(int)current($b);
		if($c>0){
			return true;
		}else{
			return false;
		}
	}
}
