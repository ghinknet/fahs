<?php 
//Copyright GHINK Network Studio.
//LGPLv3.0
//---------------------------------------------------------------------//
error_reporting(0);//抑制报错
$stream_opts = [//防止https证书错误
    "ssl" => [
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ]
];
//读取API JSON并转为数组
$teamjson=file_get_contents("http://api.ghink.net/fah/json/?team=".$_GET['team'],false, stream_context_create($stream_opts));
$donorjson=file_get_contents("http://api.ghink.net/fah/json/?donor=".$_GET['donor'],false, stream_context_create($stream_opts));
$team=json_decode($teamjson, true);
$donor=json_decode($donorjson, true);
//---------------------------------------------------------------------//
foreach ($team as $key => $value) { //遍历数组，读取数据
	if($key='name'){
		$teamname=$team['name'];
		}
}
foreach ($team as $key => $value) { 
	if($key='team'){
		$teamid=$team['team'];
		}
}
foreach ($team as $key => $value) { 
	if($key='rank'){
		$teamrank=$team['rank'];
		}
}
foreach ($team as $key => $value) { 
	if($key='wus'){
		$teamwus=$team['wus'];
		}
}
foreach ($team as $key => $value) { 
	if($key='credit'){
		$teamscores=$team['credit'];
		}
}
foreach ($donor as $key => $value) { 
	if($key='name'){
		$name=$donor['name'];
		}
}
foreach ($donor as $key => $value) { 
	if($key='rank'){
		$rank=$donor['rank'];
		}
}
foreach ($donor as $key => $value) { 
	if($key='wus'){
		$wus=$donor['wus'];
		}
}
foreach ($donor as $key => $value) { 
	if($key='credit'){
		$scores=$donor['credit'];
		}
}
foreach ($donor as $key => $value) { 
	if($key='last'){
		$lastwus=$donor['last'];
		}
}
//---------------------------------------------------------------------//
if($_GET['mode'] == 'advanced'){//判断是否是高级模式
	if($_GET['donor'] == null){
		echo '{"error": "10001"}';
	}elseif($_GET['team'] == null){
		echo '{"error": "10001"}';
	}else{
	if($_GET['height'] == null){//判断是否有提交画布大小
		if($_GET['weight'] == null){
			$canvas = imagecreatetruecolor(465, 92);//载入默认画布
			$weight = 465;
			$height = 95;
			$weightdefault = 465;
			$heightdefault = 95;
		}else{
			$error = 10001;
		}
	}elseif($_GET['weight'] == null){
		if($_GET['height'] == null){
			$canvas = imagecreatetruecolor(465, 92);//载入默认画布
			$weight = 465;
			$height = 95;
			$weightdefault = 465;
			$heightdefault = 95;
		}else{
			$error = 10001;
		}
	}else{
		$canvas = imagecreatetruecolor($_GET['weight'], $_GET['height']);//根据提交的参数载入画布
		$weightdefault = $_GET['weight'];
		$heightdefalut = $_GET['height'];
	}
	$background = imagecolorallocatealpha($canvas, 0, 0, 0, 127);//设置背景色
	imagefill($canvas, 0, 0, $background);//填充透明色
	imagecolortransparent($canvas, $background);//设置背景色
	if($_GET['fnt'] == null){//判断是否有提交自定义字体
		$text_fonts = 'fnt/default.ttf';//设置默认字体
	}else{
		$text_fonts = 'fnt/'.$_GET['fnt'];//根据提交的字体地址载入自定义字体
	}
	if($_GET['red'] == null){//判断是否有提交自定义字体颜色
		if($_GET['green'] == null){
			if($_GET['blue'] == null){
				$color = imagecolorallocate($canvas, 255, 255, 255);//设置默认文字颜色
			}else{
				$error = 10001;
			}
		}else{
			$error = 10001;
		}
	}else{
		if($_GET['green'] == null){
		}else{
			if($_GET['blue'] == null){
			}else{
				$color = imagecolorallocate($canvas, $_GET['red'], $_GET['green'], $_GET['blue']);//设置自定义文字颜色
			}
		}
	}
	if($_GET['textsize'] == null){//判断是否有提交自定义字体
		$text_size = 7;//设置默认字体大小
	}else{
		$text_size = $_GET['textsize'];//设置自定义字体大小
	}
	if($_GET['img'] == null){//判断是否有提交自定义底图
		$logo_url = 'img.png';//设置默认源图片
	}else{
		$logo_url = $_GET['img'];//设置自定义源图片
	}
	$logo = file_get_contents($logo_url);//读取源文件
	$logo_img = imagecreatefromstring($logo);//解析为图片
	if($_GET['imgx'] == null){//判断是否有提交自定义底图位置
		if($_GET['imgy'] == null){
			$imgx = 0;//设置默认底图位置
			$imgy = 0;
		}else{
			$error = 10001;
		}
	}else{
		if($_GET['imgy'] == null){
			$error = 10001;
		}else{
			$imgx = $_GET['imgx'];//设置自定义底图位置
			$imgy = $_GET['imgy'];
		}
	}
	if($_GET['putweight'] == null){
		if($_GET['putheight'] == null){
			$weight = $weightdefault;
			$height = $heightdefault;
		}else{
			$error = 10001;
		}
	}else{
		if($_GET['putheight'] == null){
			$error = 10001;
		}else{
			$weight = $_GET['putweight'];
			$height = $_GET['putheight'];
		}
	}
	imagecopyresampled($canvas, $logo_img, $imgx, $imgy, 0, 0, $weight, $height, imagesx($logo_img), imagesy($logo_img));//覆盖图层
	//---------------------------------------------------------------------//
	//团队部分
	imagettftext($canvas, $text_size, 0, 90, 13, $color, $text_fonts, $teamname);//团队名称
	imagettftext($canvas, $text_size, 0, 100, 30, $color, $text_fonts, $teamid);//团队编号
	imagettftext($canvas, $text_size, 0, 100, 48, $color, $text_fonts, $teamrank);//团队排名
	imagettftext($canvas, $text_size, 0, 110, 65, $color, $text_fonts, $teamwus);//已完成的任务
	imagettftext($canvas, $text_size, 0, 90, 82, $color, $text_fonts, $teamscores);//总积分
	//---------------------------------------------------------------------//
	//个人部分
	imagettftext($canvas, $text_size, 0, 280, 13, $color, $text_fonts, $name);//用户名
	imagettftext($canvas, $text_size, 0, 290, 32, $color, $text_fonts, $rank);//用户排名
	imagettftext($canvas, $text_size, 0, 300, 50, $color, $text_fonts, $wus);//已完成任务
	imagettftext($canvas, $text_size, 0, 280, 68, $color, $text_fonts, $scores);//总积分
	imagettftext($canvas, $text_size, 0, 310, 82, $color, $text_fonts, $lastwus);//最近一次完成
	//---------------------------------------------------------------------//
	if($error == null){//判断是否有错误
	header("content-type:image/png");//设置网页为图片
	imagepng($canvas);//输出图片
	imagedestroy($canvas);//关闭进程
	}elseif($error == 10001){
		header("Content-type:application/json;charset=utf-8");
		echo '{"error": "10001"}';
	}
	}
}else{
	if($_GET['donor'] == null){
		header("Content-type:application/json;charset=utf-8");
		echo '{"error": "10001"}';
	}elseif($_GET['team'] == null){
		header("Content-type:application/json;charset=utf-8");
		echo '{"error": "10001"}';
	}else{
	$canvas = imagecreatetruecolor(465, 92);//载入画布
	$background = imagecolorallocatealpha($canvas, 0, 0, 0, 127);//设置背景色
	imagefill($canvas, 0, 0, $background);//填充透明色
	imagecolortransparent($canvas, $background);//设置背景色
	$text_fonts = 'fnt/default.ttf';//设置字体
	$color = imagecolorallocate($canvas, 255, 255, 255);//设置文字颜色
	$text_size = 7;//设置文字大小
	$logo_url = 'img.png';//设置源图片目录
	$logo = file_get_contents($logo_url);//读取源文件
	$logo_img = imagecreatefromstring($logo);//解析为图片
	imagecopyresampled($canvas, $logo_img, 0, 0, 0, 0, 465, 92, imagesx($logo_img), imagesy($logo_img));//覆盖图层
	//---------------------------------------------------------------------//
	//团队部分
	imagettftext($canvas, $text_size, 0, 90, 13, $color, $text_fonts, $teamname);//团队名
	imagettftext($canvas, $text_size, 0, 100, 30, $color, $text_fonts, $teamid);//团队编号
	imagettftext($canvas, $text_size, 0, 100, 48, $color, $text_fonts, $teamrank);//团队排名
	imagettftext($canvas, $text_size, 0, 110, 65, $color, $text_fonts, $teamwus);//已完成的任务
	imagettftext($canvas, $text_size, 0, 90, 82, $color, $text_fonts, $teamscores);//总积分
	//---------------------------------------------------------------------//
	//个人部分
	imagettftext($canvas, $text_size, 0, 280, 13, $color, $text_fonts, $name);//用户名
	imagettftext($canvas, $text_size, 0, 290, 32, $color, $text_fonts, $rank);//用户排名
	imagettftext($canvas, $text_size, 0, 300, 50, $color, $text_fonts, $wus);//已完成任务
	imagettftext($canvas, $text_size, 0, 280, 68, $color, $text_fonts, $scores);//总积分
	imagettftext($canvas, $text_size, 0, 310, 82, $color, $text_fonts, $lastwus);//最近一次完成
	//---------------------------------------------------------------------//
	header("content-type:image/png");//设置网页为图片
	imagepng($canvas);//输出图片
	imagedestroy($canvas);//关闭进程
	}
}