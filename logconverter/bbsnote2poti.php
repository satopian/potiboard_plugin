<?php
//BBSNoteログ形式設定
//サムネイル設定
$usethumb=1;//サムネイルを作成する する 1 しない 0
//
$max_w=600;//この幅を超えたらサムネイル
$max_h=600;//この高さを超えたらサムネイル
// この値をあまり小さくしないでください。例えば100に設定すると幅や高さが100以上の画像のサムネイルが作成されます。
// しかし、それではサーバに大きな負荷がかかります。
// もしその懸念がある場合は、いっそ、サムネイルを作成しない設定にしたほうが無難です。

check_dir ("poti");//変換されたログファイルが入るディレクトリ
check_dir ("poti/src");//変換された画像が入るディレクトリ
check_dir ("poti/thumb");//変換されたサムネイルが入るディレクトリ

//サムネイル作成ファンクション
require(__DIR__.'/bbsnote2poti_thumb_gd.php');

$logfiles_arr =(glob('./data/{MSG*.log}', GLOB_BRACE));
asort($logfiles_arr);
foreach($logfiles_arr as $logfile){
	$fp=fopen($logfile,"r");
	$i=0;
	while($line =fgets($fp ,4096)){
		list($no,)
		=explode("\t",$line."\t\t\t\t\t\t\t\t\t");
		$log[]=$line;
		$tree[]=$no;
		
	}
	foreach($log as $i=>$val){

		if($i===0){
			list($no,$name,$now,$sub,$email,$url,$com,$host,$ip,$agent,$filename,$W,$H,,,$pch,$ptime,$applet,$thumbnail)
			=explode("\t",$val."\t");
			$ext = '.'.pathinfo($filename,PATHINFO_EXTENSION );
			$pchext = pathinfo($pch,PATHINFO_EXTENSION );
			$time = pathinfo($filename,PATHINFO_FILENAME);

			if(is_file("data/$filename")){	
				copy("data/$filename","poti/src/$filename");
			}

			if(is_file("data/$pch")){	
				copy("data/$pch","poti/src/$time.$pchext");
			}
			if($usethumb&&$filename&&$thumbnail_size=thumb("data/",$time,$ext,$max_w,$max_h)){//作成されたサムネイルのサイズ
				$W=$thumbnail_size['w'];
				$H=$thumbnail_size['h'];
				// var_dump($W);
			}

		}else{
			unset($no,$name,$now,$email,$url,$com,$host,$ip,$agent,$filename,$W,$H,$pch,$ptime,$applet,$thumbnail,$ext,$time);
			$W=$H=$pch=$ptime=$ext=$time=$ip='';
			list($no,$name,$now,$com,,$host,$email,$url)
			=explode("\t",$val."\t\t\t\t\t\t\t\t\t");
		}

	$newlog[]="$no,$now,$name,$email,$sub,$com,$url,$host,$ip,$ext,$W,$H,$time,,$ptime,.\n";
	
	}

	$treeline[]=implode(",",$tree)."\n";
	unset($log);
	unset($tree);
	fclose($fp);
}
arsort($treeline);
file_put_contents('./poti/tree.log',$treeline, LOCK_EX);
$newlog=mb_convert_encoding($newlog, "UTF-8", "sjis");
arsort($newlog);
file_put_contents('./poti/img.log',$newlog,LOCK_EX);

//GD版が使えるかチェック
function gd_check(){
	$check = array("ImageCreate","ImageCopyResized","ImageCreateFromJPEG","ImageJPEG","ImageDestroy");

	//最低限のGD関数が使えるかチェック
	if(get_gd_ver() && (ImageTypes() & IMG_JPG)){
		foreach ( $check as $cmd ) {
			if(!function_exists($cmd)){
				return false;
			}
		}
	}else{
		return false;
	}

	return true;
}

//gdのバージョンを調べる
function get_gd_ver(){
	if(function_exists("gd_info")){
	$gdver=gd_info();
	$phpinfo=$gdver["GD Version"];
	$end=strpos($phpinfo,".");
	$phpinfo=substr($phpinfo,0,$end);
	$length = strlen($phpinfo)-1;
	$phpinfo=substr($phpinfo,$length);
	return $phpinfo;
	} 
	return false;
}

function check_dir ($path) {

	if (!is_dir($path)) {
			mkdir($path, 705,true);
			chmod($path, 705);
	}
}


