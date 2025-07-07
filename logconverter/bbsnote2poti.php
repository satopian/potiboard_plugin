<?php
// BBSNote → POTI-board ログ変換ツール
// v0.9.29.0 lot.230828
// (c)2022-2023 さとぴあ(satopian) 
// Licence MIT
//
//https://paintbbs.sakura.ne.jp/	

//免責
//正常に動作する事を期待して作成していますが、なんらかの問題が発生しても作者は一切の責任を負いません。

//BBSNoteのログファイルのバックアップをお願いします。消失しても責任をとれません。

//まだ「何も投稿していないPOTI-board」にBBSNoteのログファイルを移動するためのスクリプトです。
//「すでに運用している」POTI-boardに
//このスクリプトで変換したログファイルを入れると「すべての記事が上書きされます」。
//上書き、つまりこれまでの「ログが消えてしまいます」。
//新規設置したPOTI-boardに変換したBBSNoteのログを入れてください。
//それ以外の用途には対応していません。

//掲示板のデータをあらかじめバックアップして
//いつでも元に戻せる状態にしてから実行してください。
//BBSNoteやPOTI-boardのログファイルが消失したとしても、なにもしてあげられません。
//以上を了解の上、ご利用ください。

//また、完全なログファイルの変換を保証するものではありません。
//いくつかのログファイルは変換できないかもしれません。

//2021.1.15 さとぴあ

/* ------------- 設定項目ここから ------------- */

/* --------------- パスワード ---------------- */

//管理者以外実行できないようにパスワードをセット
$admin_pass='hoge';//必ず変更してください

/* ------------- BBSNoteログ設定 ------------- */

//BBSNoteのconfig.cgiの設定にあわせます。
//参考例は、BBSNotev7、BBSNotev8のデフォルト値

//ログファイルのディレクトリ
$bbsnote_log_dir = 'data/';

// BBSNoteのログファイルの頭文字

// $bbsnote_filehead_logs = 'MSG';//v7は、'MSG'
$bbsnote_filehead_logs = 'LOG';//v8は'LOG'

//BBSNoteのログファイルの拡張子

// $bbsnote_log_ext = 'log';//v7は、'log'
$bbsnote_log_ext = 'cgi';//v8は'cgi'

/* --------------- relmから変換 --------------- */

// BBSNoteと仕様が近いrelmのログも変換できます。
// relmが何かわからない方は変更しないでください。

$relm=0; //relmのログを変換する時は 1
// $relm=1; でrelmから変換。 
// デフォルト 0 


/* ------------- 画像ファイル名 -------------- */


//同じファイル名の画像が出力先にあるときは別名で保存
$save_at_synonym=0;// 1.する 0.しない

//別名で保存オプションは、
//秒単位の同時刻の投稿画像を別名で保存するためのものです。

//1.する の時にコンバートを複数回行うと
//同じ画像が別名で出力されてしまいます。

//デフォルト 0

/* -------------- サムネイル設定 -------------- */

$usethumb=1;//サムネイルを作成する する 1 しない 0
$max_w=800;//この幅を超えたらサムネイル
$max_h=800;//この高さを超えたらサムネイル
// この値をあまり小さくしないでください。例えば100に設定すると幅や高さが100以上のときにサムネイルを作ります。
//しかし、全ログファイルの一括処理のためそれではサーバに大きな負荷がかかります。
//もしもサーバ負荷の懸念がある場合は、「サムネイルを作成しない」にしたほうが無難です。

define('THUMB_Q', 92);//サムネイルのjpg劣化率

/* ----------------- url設定 ----------------- */

//BBSNoteのログには'http://'、'https://'が記録されていないため
//どちらにするか選んでください。
$http='http://';//または 'https://'

/* ----------------- 題名が空欄の時 ----------------- */

$defalt_subject = '無題';

/* ----------------- 名前が空欄の時 ----------------- */

$defalt_name = '';//初期値は空欄のまま

/* --------------- タイムゾーン --------------- */

define('DEFAULT_TIMEZONE','Asia/Tokyo');

/* -------------- パーミッション -------------- */

//正常に動作しているときは変更しない。
//画像やHTMLファイルのパーミッション。
define('PERMISSION_FOR_DEST', 0606);//初期値 0606
//ブラウザから直接呼び出さないログファイルのパーミッション
define('PERMISSION_FOR_LOG', 0600);//初期値 0600
//POTIディレクトリのパーミッション
define('PERMISSION_FOR_POTI', 0705);//初期値 0705
//画像や動画ファイルを保存するディレクトリのパーミッション
define('PERMISSION_FOR_DIR', 0707);//初期値 0707

/* ----------- ここから下設定項目なし ----------- */

//サムネイル
define('RE_SAMPLED', 1);
define('THUMB_DIR', 'poti/thumb/');

date_default_timezone_set(DEFAULT_TIMEZONE);

/* ------------- 記事番号の降り直し -------------- */
//再計算して記事番号を通し番号で振りなおす。
$renumbering = 1; // 1.する 0.しない
//設定項目として残してありますができるだけ変更しないでください。

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
	body {
	margin: 1px 10.5px 1em;
	padding: 2px 0;
	line-height:3;
	color:#626262;
	}
	input[type="text"],input[type="submit"]{
	border: 1px #686868 solid;
	background-color: #FFFFFE;
	border-radius: 0;
	margin: 1px;
	padding:10px;
	color: #555555;
	}
</style>
<title>ログコンバータ</title>
</head>
<body>
<?php
$lets_convert=filter_input(INPUT_POST,'lets_convert',FILTER_VALIDATE_BOOLEAN);
?>
<?php if(!$lets_convert):?>
<form action="./bbsnote2poti.php" method="post">
パスワード : <input type="password" name="pwd" value=""><br>
<input type="hidden" name="lets_convert" value="true">
<input type="submit" value="変換開始" class="paint_button">
<label class="checkbox"><input type="checkbox" value="true" name="unlink_php_self" checked="">変換後このスクリプトを消す</label>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
//送信ボタンを押した際に送信ボタンを無効化する（連打による多数送信回避）
$(function(){
$('[type="submit"]').click(function(){
$(this).prop('disabled',true);//ボタンを無効化する
$(this).closest('form').submit();//フォームを送信する
});
});
</script>
</body>
</html>
<?php endif;?>

<?php
$pwd=filter_input(INPUT_POST,'pwd',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password_is_matched=($pwd===$admin_pass);

if($lets_convert && !$password_is_matched){
	error('パスワードが違います。');
}
if(!$lets_convert || !$password_is_matched){
	exit();
}
$unlink_php_self=filter_input(INPUT_POST,'unlink_php_self',FILTER_VALIDATE_BOOLEAN);
$logfiles_arr =(glob($bbsnote_log_dir.'{'.$bbsnote_filehead_logs.'*.'.$bbsnote_log_ext.'}', GLOB_BRACE));//ログファイルをglob

if(!$logfiles_arr){
	error('BBSNoteのログファイルの読み込みに失敗しました。BBSNoteのログファイルの頭文字や拡張子の設定が間違っている可能性があります。');
}
$time_start = microtime(true);//計測開始

echo"変換開始<br>";
ob_flush();
flush();
sleep( 1 );
	
check_poti ("poti");//変換されたログファイルが入るディレクトリ
check_dir ("poti/src");//変換された画像が入るディレクトリ
check_dir ("poti/thumb");//変換されたサムネイルが入るディレクトリ

sort($logfiles_arr);
$__no=1;
$oya=[];
$newlog=[];
$treeline=[];
foreach($logfiles_arr as $logfile){//ログファイルを一つずつ開いて読み込む
	$log=[];
	$fp=fopen($logfile,"r");
	while($line =fgets($fp)){
		$_no=null;
		if(!trim($line)){
			continue;
		}
		$line=mb_convert_encoding($line, "UTF-8", "sjis");
		$line = str_replace(",", "&#44;", $line);
		if($relm){//relm
			$arr_line=explode("<>",$line);
			$count_arr_line=count($arr_line);
			if($count_arr_line<5){
				error('ログファイルの読み込みに失敗しました。設定が間違っている可能性があります。');
			}
			if($count_arr_line>20){//スレッドの親?
				$_no=$arr_line[1];
			}
		}else{//BBSNote
			$arr_line=explode("\t",$line);
			$count_arr_line=count($arr_line);
			if($count_arr_line<5){
				error('ログファイルの読み込みに失敗しました。設定が間違っている可能性があります。');
			}
			if($count_arr_line>11){//スレッドの親?
				$_no=$arr_line[0];
			}
		}
		$_no= isset($_no) ? (int)$_no+1 : null;
		$oya[$_no]= true ;
		$log[]=$line;//1スレッド分
	}
	fclose($fp);
	$tree=[];
	foreach($log as $i=>$val){//1スレッド分のログを処理

		if($i===0){//スレッドの親
			if($relm){//relm
			list($threadno,$no,$now,$name,,$sub,$email,$url,$com,$time,$ip,$host,,,,,$agent,,$filename,$W,$H,,$thumbnail,$pch,,,$ptime,)
				=explode("<>",$val);
			}else{//BBSNote
			list($no,$name,$now,$sub,$email,$url,$com,$host,$ip,$agent,$filename,$W,$H,,,$pch,$ptime,$applet,$thumbnail)
			=explode("\t",$val."\t"."\t"."\t"."\t"."\t"."\t"."\t"."\t"."\t");
			$time= $now ? preg_replace('/\(.+\)/', '', $now):0;//曜日除去
			$time=(int)strtotime($time);//strからUNIXタイムスタンプ
			}
			$thumbnail="";//元のログファイルのthumbnailは使用しない。
			$no= $renumbering ? $__no : (int)$no+1;//記事番号0を回避
			$time=$time ? $time.'000' : 0; 
			$time=basename($time);
			$ext = $filename ? '.'.pathinfo($filename,PATHINFO_EXTENSION ) :'';
			$pchext = pathinfo($pch,PATHINFO_EXTENSION );

			$ext = (!in_array($ext, ['.pch', '.spch'])) ? basename($ext) : ''; 
			$pchext =  (in_array($pchext, ['pch', 'spch'])) ? $pchext : '';
			//POTI-board形式のファイル名に変更してコピー
			if($ext && is_file("data/$filename")){//画像
				if($save_at_synonym && is_file("poti/src/{$time}{$ext}")){
						$time=$time+1;
				}
				if(!$save_at_synonym && !is_file("poti/src/{$time}{$ext}")){
					copy("data/$filename","poti/src/{$time}{$ext}");
					chmod("poti/src/{$time}{$ext}",PERMISSION_FOR_DEST);
				}
				if($usethumb&&($thumbnail_size=thumb("poti/src/",$time,$ext,$max_w,$max_h))){//作成されたサムネイルのサイズ
					$W=$thumbnail_size['w'];
					$H=$thumbnail_size['h'];
					$thumbnail="thumbnail";
				}else{
					list($W,$H)=getimagesize("poti/src/{$time}{$ext}");
				}
			}

			if($pchext && is_file("data/$pch")){//動画
				copy("data/$pch","poti/src/$time.$pchext");
				chmod("poti/src/$time.$pchext",PERMISSION_FOR_DEST);
			}

			if(!$url||stripos('sage',$url)!==false){
				$url="";
			}
			$url=$url ? $http.$url :'';
			if(!$url||!filter_var($url,FILTER_VALIDATE_URL)){
				$url="";
			}
			$sub = $sub ? $sub : $defalt_subject;
			$name = $name ? $name : $defalt_name;
			$no=(int)$no;
			$pchext = $pchext ? '.'.$pchext : "";

			switch($pchext){
				case '.pch':
					$tool='PaintBBS';
					break;
				case '.spch':
					$tool='Shi-Painter';
					break;
				default:
					if($ext){
						$tool='???';
					}
					break;
			}

			$newlog[$no]="$no,$now,$name,$email,$sub,$com,$url,$host,,$ext,$W,$H,$time,,$ptime,,$pchext,$thumbnail,$tool,6\n";

			$tree[]=$no;
			$resub=$sub ? "Re: {$sub}" :'';

		}else{//スレッドの子
			unset($threadno,$no,$now,$name,$sub,$email,$url,$com,$time,$ip,$host,$agent,$filename,$W,$H,$ptime,$thumbnail,$pch,$applet);
			$W=$H=$pch=$ptime=$ext=$time=$ip='';
			if($relm){//relm
				list($threadno,$no,$now,$name,,$sub,$email,$url,$com,$time,$ip,$host)
				=explode("<>",$val);
			}else{//BBSNote
				list($no,$name,$now,$com,,$host,$email,$url)
				=explode("\t",$val);
				$time= $now ? preg_replace('/\(.+\)/', '', $now):0;//曜日除去
				$time=(int)strtotime($time);//strからUNIXタイムスタンプ
			}
			$no= $renumbering ? $__no : (int)$no+1;//記事番号0を回避

			$time=$time ? $time.'000' : 0; 

			if(!$url||stripos('sage',$url)!==false){
				$url="";
			}
			$url=$url ? $http.$url :'';
			if(!$url||!filter_var($url,FILTER_VALIDATE_URL)){
				$url="";
			}
			if($renumbering || !isset($oya[$no])){//記事No重複回避 画像がある親優先
				$name = $name ? $name : $defalt_name;
				$thumbnail= "";
				$pchext = "";
				$tool = "";
					$newlog[$no]="$no,$now,$name,$email,$resub,$com,$url,$host,,$ext,$W,$H,$time,,$ptime,,$pchext,$thumbnail,$tool,6\n";

				$tree[]=$no;
			}

		}

		++$__no;
	}

	$treeline[]=implode(",",$tree)."\n";
	unset($log,$tree);

}
unset($oya);

//ツリーログ
foreach($treeline as $val){
	list($_oya,)=explode(',',rtrim($val));
	$_treeline[$_oya]=$val;
}
$treeline=$_treeline;
ksort($treeline);
foreach($treeline as $i => $val){
	$ko=explode(',',rtrim($val));
	$oya=$ko[0];

	unset($ko[0]);
	foreach($ko as $k =>$v){
		if(isset($treeline[$v])){
			unset($ko[$k]);
			$_ko=implode(",",$ko);
			if($_ko){
				$treeline[$i]="$oya,$_ko\n";
			}else{
				$treeline[$i]="$oya\n";
			}
		}
	}
}
krsort($treeline);
file_put_contents('poti/tree.log',implode("",$treeline),LOCK_EX);
chmod('poti/tree.log',PERMISSION_FOR_LOG);
krsort($newlog);
file_put_contents('poti/img.log',implode("",$newlog),LOCK_EX);
chmod('poti/img.log',PERMISSION_FOR_LOG);


function check_dir ($path) {

	if (!is_dir($path)) {
			mkdir($path, PERMISSION_FOR_DIR,true);
			chmod($path, PERMISSION_FOR_DIR);
	}
}
function check_poti ($path) {

	if (!is_dir($path)) {
			mkdir($path, PERMISSION_FOR_POTI,true);
			chmod($path, PERMISSION_FOR_POTI);
	}
}

//サムネイル

//GD版が使えるかチェック
function gd_check(){
	$check = array("ImageCreate","ImageCopyResized","ImageCreateFromJPEG","ImageJPEG");

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


function thumb($path,$tim,$ext,$max_w,$max_h){
	if(!gd_check()||!function_exists("ImageCreate")||!function_exists("ImageCreateFromJPEG"))return;
	$fname=$path.$tim.$ext;
	$size = GetImageSize($fname); // 画像の幅と高さとタイプを取得
	if(!$size){
		return;
	}
	// リサイズ
	if($size[0] > $max_w || $size[1] > $max_h){
		$key_w = $max_w / $size[0];
		$key_h = $max_h / $size[1];
		($key_w < $key_h) ? $keys = $key_w : $keys = $key_h;
		$out_w = ceil($size[0] * $keys);//端数の切り上げ
		$out_h = ceil($size[1] * $keys);
	}else{
		return;
	}
	
	switch (mime_content_type($fname)) {
		case "image/gif":
		if(function_exists("ImageCreateFromGIF")){//gif
				$im_in = @ImageCreateFromGIF($fname);
				if(!$im_in)return;
			}
			else{
				return;
			}
		break;
		case "image/jpeg":
		$im_in = @ImageCreateFromJPEG($fname);//jpg
			if(!$im_in)return;
		break;
		case "image/png":
		if(function_exists("ImageCreateFromPNG")){//png
				$im_in = @ImageCreateFromPNG($fname);
				if(!$im_in)return;
			}
			else{
				return;
			}
			break;
		case "image/webp":
		if(function_exists("ImageCreateFromWEBP")){//webp
			$im_in = @ImageCreateFromWEBP($fname);
			if(!$im_in)return;
		}
		else{
			return;
		}
		break;

		default : return;
	}
	// 出力画像（サムネイル）のイメージを作成
	$nottrue = 0;
	if(function_exists("ImageCreateTrueColor")&&get_gd_ver()=="2"){
		$im_out = ImageCreateTrueColor($out_w, $out_h);
		if(function_exists("ImageColorAlLocate") && function_exists("imagefill")){
			$background = ImageColorAlLocate($im_out, 0xFF, 0xFF, 0xFF);//背景色を白に
			imagefill($im_out, 0, 0, $background);
		}
		// コピー＆再サンプリング＆縮小
		if(function_exists("ImageCopyResampled")&&RE_SAMPLED){
			ImageCopyResampled($im_out, $im_in, 0, 0, 0, 0, $out_w, $out_h, $size[0], $size[1]);
		}else{$nottrue = 1;}
	}else{$im_out = ImageCreate($out_w, $out_h);$nottrue = 1;}
	// コピー＆縮小
	if($nottrue) ImageCopyResized($im_out, $im_in, 0, 0, 0, 0, $out_w, $out_h, $size[0], $size[1]);
	// サムネイル画像を保存
	ImageJPEG($im_out, THUMB_DIR.$tim.'s.jpg',THUMB_Q);
	// 作成したイメージを破棄
	if(PHP_VERSION_ID < 80000) {//PHP8.0未満の時は
		ImageDestroy($im_in);
		ImageDestroy($im_out);
	}
	if(!chmod(THUMB_DIR.$tim.'s.jpg',PERMISSION_FOR_DEST)){
		return;
	}

	$thumbnail_size = [
		'w' => $out_w,
		'h' => $out_h,
	];
return $thumbnail_size;

}
$time = microtime(true) - $time_start; echo "完了しました {$time} 秒";

if($unlink_php_self){
	chmod('bbsnote2poti.php',PERMISSION_FOR_DEST);
	unlink('bbsnote2poti.php');
}
?>
</body>
</html>

<?php
function error($str) {
?>
<?=htmlspecialchars($str,ENT_QUOTES,"utf-8",false)?><br>
</body>
</html>
<?php
exit();
}
?>
