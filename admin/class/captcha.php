<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: captcha.php 33828 2008-02-22 09:25:26Z team $
 */

//验证码类
class Captcha extends Base{
	private $charset = 'ABCDEFGHKMNPRSTUVWXYZ123456789';
	private $code;//验证码
	private $width = 100;//宽度
	private $height = 30;//高度
	private $codelen = 5;//位数
	private $img;//图形
	private $font;//字体
	private $fontsize = 15;//字体大小
	private $fontcolor;//字体颜色

	//构造方法初始化
	public function __construct() {
		global $tqb;
		$this->font = $tqb->contentdir .'../admin/image/fonts/arial.ttf';
		$this->charset = $tqb->option['CFG_VERIFYCODE_STRING'];
		$this->width = $tqb->option['CFG_VERIFYCODE_WIDTH'];
		$this->height = $tqb->option['CFG_VERIFYCODE_HEIGHT'];
	}

	//生成随机码
	private function createCode() {
		$_len = strlen($this->charset)-1;
		for ($i=0;$i<$this->codelen;$i++) {
			$this->code .= $this->charset[mt_rand(0,$_len)];
		}
	}

	//生成文字
	private function createFont() {
		$_x = $this->width / $this->codelen;
		for ($i=0;$i<$this->codelen;$i++) {
			$this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
		}
	}

	//生成线条、雪花
	private function createLine() {
		for ($i=0;$i<6;$i++) {
			$color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
		}
		for ($i=0;$i<100;$i++) {
			$color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
		}
	}
	
	//生成背景
	private function createBg() {
		$this->img = imagecreatetruecolor($this->width, $this->height);
		$color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
		imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
	}

	//输出
	private function outPut() {
		header('Content-type:image/png');
		imagepng($this->img);
		imagedestroy($this->img);
	}

	//对外生成
	public function GetImg() {
		$this->createBg();
		$this->createCode();
		$this->createLine();
		$this->createFont();
		$this->outPut();
	}

	//获取验证码
	public function GetCode() {
		return strtolower($this->code);
	}

}