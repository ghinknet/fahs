<?php 
//Copyright GHINK Network Studio.
//LGPLv3.0
//---------------------------------------------------------------------//
$stream_opts = [
    "ssl" => [
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ]
]; //读取API JSON并转为数组
$teamjson=file_get_contents("https://stats.foldingathome.org/api/team/".$_GET['teamid'],false, stream_context_create($stream_opts));
$donorjson=file_get_contents("https://stats.foldingathome.org/api/donor/".$_GET['donor'],false, stream_context_create($stream_opts));
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
$canvas = imagecreatetruecolor(465, 92);//载入画布
$background = imagecolorallocatealpha($canvas, 0, 0, 0, 127);//设置背景色
imagefill($canvas, 0, 0, $background);//填充透明色
imagecolortransparent($canvas, $background);//设置背景色
$dir = dirname(__FILE__).'\\';//取得运行目录
$text_fonts = $dir.'fnt.ttf';//设置字体
$color = imagecolorallocate($canvas, 255, 255, 255);//设置文字颜色
$text_size = 7;//设置文字大小
$logo_url = $dir.'img.png';//设置源图片目录
$logo = @file_get_contents($logo_url);//读取源文件
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
//---------------------------------------------------------------------//
