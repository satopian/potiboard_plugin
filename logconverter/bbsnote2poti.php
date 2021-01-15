<?php
//BBSNote → POTI-board ログ変換ツール
//V0.5 lot.210115 
//(c)さとぴあ 2021
//
//https://pbbs.sakura.ne.jp/

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

/* ------------- タイムゾーン ------------- */

define('DEFAULT_TIMEZONE','Asia/Tokyo');

/* ------------- BBSNoteログ設定 ------------- */

//BBSNoteのconfig.cgiの設定にあわせます。
//参考例は、BBSNotev7、BBSNotev8のデフォルト値

//ログファイルのディレクトリ
$bbsnote_log_dir = 'data/';

// BBSNoteのログファイルの頭文字

// $bbsnote_filehead_logs = 'MSG';//v7は、'MSG'
$bbsnote_filehead_logs = 'LOG';//v8は'LOG'

//BBSNoteのログファイルの拡張子

// $bbsnote_log_exe = 'log';//v7は、'log'
$bbsnote_log_exe = 'cgi';//v8は'cgi'

/* ----------------- url設定 ----------------- */
//BBSNoteのログには'http://'、'https://'が記録されていないため
//どちらにするか選んでください。
$http='http://';//または 'https://'
/* -------------- サムネイル設定 -------------- */
$usethumb=1;//サムネイルを作成する する 1 しない 0
//
$max_w=600;//この幅を超えたらサムネイル
$max_h=600;//この高さを超えたらサムネイル
// この値をあまり小さくしないでください。例えば100に設定すると幅や高さが100以上のときにサムネイルを作ります。
//しかし、全ログファイルの一括処理のためそれではサーバに大きな負荷がかかります。
//もしもサーバ負荷の懸念がある場合は、「サムネイルを作成しない」にしたほうが無難です。

/* -------------- パーミッション -------------- */

//正常に動作しているときは変更しない。
//画像やHTMLファイルのパーミッション。
define('PERMISSION_FOR_DEST', 0606);//初期値 0606
//ブラウザから直接呼び出さないログファイルのパーミッション
define('PERMISSION_FOR_LOG', 0600);//初期値 0600
//画像や動画ファイルを保存するディレクトリのパーミッション
define('PERMISSION_FOR_POTI', 0705);//初期値 0705
//画像や動画ファイルを保存するディレクトリのパーミッション
define('PERMISSION_FOR_DIR', 0707);//初期値 0705


/* ------------- ここから下設定項目なし ------------- */
$time_start = microtime(true);


date_default_timezone_set(DEFAULT_TIMEZONE);

//サムネイル作成ファンクション
require(__DIR__.'/bbsnote2poti_thumb_gd.php');

check_poti ("poti");//変換されたログファイルが入るディレクトリ
check_dir ("poti/src");//変換された画像が入るディレクトリ
check_dir ("poti/thumb");//変換されたサムネイルが入るディレクトリ

$logfiles_arr =(glob($bbsnote_log_dir.'{'.$bbsnote_filehead_logs.'*.'.$bbsnote_log_exe.'}', GLOB_BRACE));//ログファイルをglob
asort($logfiles_arr);
foreach($logfiles_arr as $logfile){//ログファイルを一つずつ開いて読み込む
	$fp=fopen($logfile,"r");
	$i=0;
	while($line =fgets($fp ,4096)){
		list($no,)
		=explode("\t",$line);
		$log[]=$line;//1スレッド分
		$tree[]=$no;
		
	}
	foreach($log as $i=>$val){//1スレッド分のログを処理

		if($i===0){//スレッドの親
			list($no,$name,$now,$sub,$email,$url,$com,$host,$ip,$agent,$filename,$W,$H,,,$pch,$ptime,$applet,$thumbnail)
			=explode("\t",$val);

			$ext = '.'.pathinfo($filename,PATHINFO_EXTENSION );
			$pchext = pathinfo($pch,PATHINFO_EXTENSION );
			$time=preg_replace('/\(.+\)/', '', $now);
			$time=strtotime($time)*1000;

			$ext = (!in_array($ext, ['.pch', '.spch'])) ? $ext : ''; 
			$pchext =  (in_array($pchext, ['.pch', '.spch'])) ? $pchext : '';
			
			//POTI-board形式のファイル名に変更してコピー
			if($ext && is_file("data/$filename")){//画像
				if(is_file("poti/src/{$time}{$ext}")){
					$time=$time+1;
				}	
				copy("data/$filename","poti/src/{$time}{$ext}");
				chmod("poti/src/{$time}{$ext}",PERMISSION_FOR_DEST);
			}

			if($pchext && is_file("data/$pch")){//動画
				copy("data/$pch","poti/src/$time.$pchext");
				chmod("poti/src/$time.$pchext",PERMISSION_FOR_DEST);
			}
			// var_dump($time);
			if($usethumb&&$filename&&$thumbnail_size=thumb("poti/src/",$time,$ext,$max_w,$max_h)){//作成されたサムネイルのサイズ
				$W=$thumbnail_size['w'];
				$H=$thumbnail_size['h'];
			}
			$ext = (!in_array($ext, ['.pch', '.spch'])) ? $ext : ''; 
			//BBSNoteはpchファイルのみのアップロードに対応。POTIは非対応。
			$url=$url ? $http.$url :'';
			$newlog[$no]="$no,$now,$name,$email,$sub,$com,$url,$host,$ip,$ext,$W,$H,$time,,$ptime,.\n";

		}else{//スレッドの子
			unset($no,$name,$now,$email,$url,$com,$host,$ip,$agent,$filename,$W,$H,$pch,$ptime,$applet,$thumbnail,$ext,$time);
			$W=$H=$pch=$ptime=$ext=$time=$ip='';
			list($no,$name,$now,$com,,$host,$email,$url)
			=explode("\t",$val);
			$time=preg_replace('/\(.+\)/', '', $now);
			$time=strtotime($time).'000';

			$url=$url ? $http.$url :'';

			if(!isset($newlog[$no])){//記事No重複回避 画像がある親優先
				$newlog[$no]="$no,$now,$name,$email,$sub,$com,$url,$host,$ip,$ext,$W,$H,$time,,$ptime,.\n";
			}
		}
		//POTI-board形式のログファイルに変換

	}

	$treeline[]=implode(",",$tree)."\n";
	unset($log);
	unset($tree);
	fclose($fp);
}
arsort($treeline);
file_put_contents('poti/tree.log',$treeline, LOCK_EX);
chmod('poti/tree.log',PERMISSION_FOR_LOG);
$newlog=mb_convert_encoding($newlog, "UTF-8", "sjis");
krsort($newlog);
file_put_contents('poti/img.log',$newlog,LOCK_EX);
chmod('poti/img.log',PERMISSION_FOR_LOG);

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

$time = microtime(true) - $time_start; echo "完了しました {$time} 秒";

// exit;
// chmod('bbsnote2poti.php',PERMISSION_FOR_DEST);
// unlink('bbsnote2poti.php');


