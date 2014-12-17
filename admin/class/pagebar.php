<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: pagebar.php 33828 2008-02-22 09:25:26Z team $
 */

/**
* PageBar
*/
class PageBar{
	#内容总数
	public $Count = null;

	#Pagebar长度数量
	public $PageBarCount=0;

	#每页数量
	public $PageCount  = 0;

	#总页数
	public $PageAll  = 0;

	#当前页
	public $PageNow  = 0;

	public $PageFirst  = 0;
	public $PageLast  = 0;
	public $PagePrevious  = 0;
	public $PageNext  = 0;

	public $UrlRule  = null;

	public function __construct($url,$makereplace=true){
		$this->UrlRule=new UrlRule($url);
		$this->UrlRule->MakeReplace=$makereplace;
	}

	public function Make(){
		global $tqb;
		if($this->PageCount==0)return null;

		$this->PageAll = ceil($this->Count / $this->PageCount);
		$this->PageFirst = 1;
		$this->PageLast = $this->PageAll;

		$this->PagePrevious=$this->PageNow-1;
		if($this->PagePrevious<1){$this->PagePrevious=1;}

		$this->PageNext=$this->PageNow+1;
		if($this->PageNext>$this->PageAll){$this->PageNext=$this->PageAll;}

		$this->UrlRule->Rules['{%page%}']=$this->PageFirst;
		$this->buttons['‹‹']=$this->UrlRule->Make();

		if($this->PageNow <> $this->PageFirst){
			$this->UrlRule->Rules['{%page%}']=$this->PagePrevious;
			$this->buttons['‹'] = $this->UrlRule->Make();
			$this->prevbutton=$this->buttons['‹'];
		}

		$j=$this->PageNow;
		if($j+$this->PageBarCount > $this->PageAll){
			$j=$this->PageAll-$this->PageBarCount+1;
		}
		if($j<1){$j=1;}

		for ($i=$j; $i < $j+$this->PageBarCount; $i++) {
			if($i > $this->PageAll){break;}
			$this->UrlRule->Rules['{%page%}']=$i;
			$this->buttons[$i]=$this->UrlRule->Make();
		}
		if($this->PageNow <> $this->PageNext){
			$this->UrlRule->Rules['{%page%}']=$this->PageNext;
			$this->buttons['›']=$this->UrlRule->Make();
			$this->nextbutton=$this->buttons['›'];
		}

		$this->UrlRule->Rules['{%page%}']=$this->PageLast;
		$this->buttons['››']=$this->UrlRule->Make();

	}


	public $buttons = array();

	public $prevbutton = null;

	public $nextbutton = null;

}
