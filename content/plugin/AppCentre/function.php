<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: function.php 33828 2008-02-22 09:25:26Z team $
 */

function AppCentre_SubMenus($id){
	//m-now
	global $tqb;
	
	echo '<a href="main.php"><span class="m-left '.($id==1?'m-now':'').'">浏览在线应用</span></a>';
	echo '<a href="main.php?method=check"><span class="m-left '.($id==2?'m-now':'').'">检查应用更新</span></a>';
	echo '<a href="../../../admin/admin/?act=PluginAdmin"><span class="m-left '.($id==3?'m-now':'').'">插件管理</span></a>';

	if($tqb->Config('AppCentre')->shop_username&&$tqb->Config('AppCentre')->shop_password){
		echo '<a href="client.php"><span class="m-left '.($id==9?'m-now':'').'">我的应用仓库</span></a>';
	}else{
		echo '<a href="client.php"><span class="m-left '.($id==9?'m-now':'').'">登录应用商城</span></a>';
	}
	
	echo '<a href="app_developer.php"><span class="m-left '.($id==4?'m-now':'').'">开发者中心</span></a>';
}

function GetCheckQueryString(){
	global $tqb;
	$check= '';
	$app=new app;			
	if($app->LoadInfoByXml('theme', $tqb->theme)==true){
		$check.=$app->id . ':' .$app->modified . ';';
	}
	foreach (explode('|',$tqb->option['CFG_USING_PLUGIN_LIST']) as  $id) {
		$app=new app;
		if($app->LoadInfoByXml('plugin', $id)==true){
			$check.=$app->id . ':' .$app->modified . ';';
		}
	}
	return $check;
}

function Server_Open($method){
	global $tqb,$blogversion;

	switch ($method) {
		case 'down':
			Add_Filter_Plugin('Filter_Plugin_Tqb_ShowError','ScriptError',PLUGIN_EXITSIGNAL_RETURN);
			header('Content-type: application/x-javascript; Charset=utf-8');
			ob_clean();
			$s=Server_SendRequest(APPCENTRE_URL .'?down=' . GetVars('id','GET'));
			if(App::UnPack($s)){
				$tqb->SetHint('good','下载APP并解压安装成功!');
			};
			die();
			break;		
		case 'search':
			if(trim(GetVars('q','GET'))=='')continue;
			$s=Server_SendRequest(APPCENTRE_URL .'?search=' . urlencode(GetVars('q','GET')));
			echo str_replace('%bloghost%', $tqb->host . 'content/plugin/AppCentre/main.php' ,$s);
			break;
		case 'view':
			$s=Server_SendRequest(APPCENTRE_URL .'?'. GetVars('QUERY_STRING','SERVER'));
			if(strpos($s,'<!--developer-nologin-->')!==false){
				if($tqb->Config('AppCentre')->username){
					$tqb->Config('AppCentre')->username='';
					$tqb->Config('AppCentre')->password='';
					$tqb->SaveConfig('AppCentre');
				}
			}
			echo str_replace('%bloghost%', $tqb->host . 'content/plugin/AppCentre/main.php' ,$s);
			break;
		case 'check':
			$s=Server_SendRequest(APPCENTRE_URL .'?check=' . urlencode(GetCheckQueryString())) . '';
			echo str_replace('%bloghost%', $tqb->host . 'content/plugin/AppCentre/main.php' ,$s);
			break;
		case 'checksilent':
			header('Content-type: application/x-javascript; Charset=utf-8');
			ob_clean();
			$s=Server_SendRequest(APPCENTRE_URL .'?blogsilent=1'. ($tqb->Config('AppCentre')->checkbeta?'&betablog=1':'') .'&check=' . urlencode(GetCheckQueryString())) . '';
			if(strpos($s,';')!==false){
				$newversion=substr($s,0,6);
				$s=str_replace(($newversion.';'),'',$s);
				if((int)$newversion>(int)$blogversion){
					echo '$(".main").prepend("<div class=\'hint\'><p class=\'hint hint_tips\'>提示:TQBlog有新版本,请用APP应用中心插件的<a href=\'../../content/plugin/AppCentre/update.php\'>“系统更新与校验”</a>升级'.$newversion.'版('. ($tqb->Config('AppCentre')->checkbeta?'Beta':'') .').</p></div>");';
				}
			}
			if($s!=0){
				echo '$(".main").prepend("<div class=\'hint\'><p class=\'hint hint_tips\'>提示:有'.$s.'个应用需要更新,请在应用中心的<a href=\'../../content/plugin/AppCentre/main.php?method=check\'>“检查应用更新”</a>页升级.</p></div>");';
			}
			die();
			break;
		case 'vaild':
			$data=array();
			$data["username"]=GetVars("app_username");
			$data["password"]=md5(GetVars("app_password"));
			$s=Server_SendRequest(APPCENTRE_URL .'?vaild',$data);
			return $s;
			break;
		case 'submitpre':
			$s=Server_SendRequest(APPCENTRE_URL .'?submitpre=' . urlencode(GetVars('id')));
			return $s;
		case 'submit':
			$app=New App;
			$app->LoadInfoByXml($_GET['type'],$_GET['id']);
			$data["tqp"]=$app->Pack();
			$s=Server_SendRequest(APPCENTRE_URL .'?submit=' . urlencode(GetVars('id')),$data);
			return $s;
		case 'shopvaild':
			$data=array();
			$data["shop_username"]=GetVars("shop_username");
			$data["shop_password"]=md5(GetVars("shop_password"));
			$s=Server_SendRequest(APPCENTRE_URL .'?shopvaild',$data);
			return $s;
			break;
		case 'shoplist':
			$s=Server_SendRequest(APPCENTRE_URL .'?shoplist');
			echo str_replace('%bloghost%', $tqb->host . 'content/plugin/AppCentre/main.php' ,$s);
			break;
		default:
			# code...
			break;
	}
}

function Server_SendRequest($url,$data=array(),$u='',$c=''){
	global $tqb;

	$un=$tqb->Config('AppCentre')->username;
	$ps=$tqb->Config('AppCentre')->password;
	$c='';
	if($un&&$ps){
		$c="username=".urlencode($un) ."; password=".urlencode($ps);
	}
	
	$shopun=$tqb->Config('AppCentre')->shop_username;
	$shopps=$tqb->Config('AppCentre')->shop_password;

	if($shopun&&$shopps){
		if($c!=='')$c.='; ';
		$c.="shop_username=".urlencode($shopun) ."; shop_password=".urlencode($shopps);
	}
	
	$u='TQBlogPHP/' . substr(CFG_BLOG_VERSION,-8,8) . ' '. GetGuestAgent();
	
	
	if(class_exists('Network'))return Server_SendRequest_Network($url,$data,$u,$c);
	if(function_exists("curl_init"))return Server_SendRequest_CUrl($url,$data,$u,$c);
	if(!ini_get("allow_url_fopen"))return "";	
	
	if($data){//POST
		$data=http_build_query($data);
		$opts=array(
			'http'=>array(
				'method'=>'POST',
				'header'=>"Content-Type:application/x-www-form-urlencoded\r\n".
					'Content-Length: '.strlen($data)."\r\n".
					"Cookie: ".$c."\r\n",
				'user_agent'=> $u,
				'content'=>$data
			)
		);
		$content=stream_context_create($opts);
	}else{//GET
		$opts=array(
			'http'=>array(
				'method'=>'GET',
				'header'=>"Cookie: ".$c."\r\n",
				'user_agent'=> $u,
			)
		);
		$content=stream_context_create($opts);
	}

	ini_set('default_socket_timeout',120);
	return file_get_contents($url,false,$content);
}

function Server_SendRequest_CUrl($url,$data=array(),$u,$c){
	global $tqb;

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_USERAGENT, $u);
	if($c)curl_setopt($ch,CURLOPT_COOKIE,$c);
	
	if($data){//POST
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}else{//GET
	}
	
	$r = curl_exec($ch);
	curl_close($ch);
	
	return $r;
}

function Server_SendRequest_Network($url,$data=array(),$u,$c){
	global $tqb;

	$ajax = Network::Create();
	if(!$ajax) throw new Exception('主机没有开启访问外部网络功能');

	if($data){//POST
		$ajax->open('POST',$url);
		if(get_class($ajax)<>'Networkfile_get_contents')$ajax->enableGzip();
		$ajax->setTimeOuts(120,120,0,0);
		$ajax->setRequestHeader('User-Agent',$u);
		$ajax->setRequestHeader('Cookie',$c);
		$ajax->send($data);
	}else{
		$ajax->open('GET',$url);
		if(get_class($ajax)<>'Networkfile_get_contents')$ajax->enableGzip();
		$ajax->setTimeOuts(120,120,0,0);
		$ajax->setRequestHeader('User-Agent',$u);
		$ajax->setRequestHeader('Cookie',$c);
		$ajax->send();
	}
	
	return $ajax->responseText;
}

function CreateOptoinsOfVersion($default){
	global $tqb;

	$s=null;
	$array=$GLOBALS['tqbvers'];
	krsort($array);
	foreach ($array as $key => $value) {
		$s .= '<option value="' . $key . '" ' . ($default==$key?'selected="selected"':'') . ' >' . $value . '</option>';
	}
	return $s;
}

function AppCentre_GetHttpContent($url){
	$r=null;
	if(function_exists("curl_init")){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		$r = curl_exec($ch);
		curl_close($ch);
	}elseif(ini_get("allow_url_fopen")){
		ini_set('default_socket_timeout',60);
		$r=file_get_contents($url);
	}
	return $r;
}

function crc32_signed($num){ 
    $crc = crc32($num); 
    if($crc & 0x80000000){ 
        $crc ^= 0xffffffff; 
        $crc += 1; 
        $crc = -$crc; 
    } 
    return $crc; 
} 