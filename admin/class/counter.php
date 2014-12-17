<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team 
 * This is NOT a freeware, use is subject to license terms
 * $Id: counter.php 33828 2008-02-22 09:25:26Z team $
 */


class Counter extends Base{


	function __construct()
	{
		global $tqb;
		parent::__construct($tqb->table['Counter'],$tqb->datainfo['Counter']);
	}


}
