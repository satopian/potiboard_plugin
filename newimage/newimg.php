<?php
// POTI-boardの最新画像をサイトの入り口のHTMLファイルに呼び出すphp
// newimg.php(c)さとぴあ 2020 lot.200519
// https://pbbs.sakura.ne.jp/
//フリーウェアですが著作権は放棄しません。

// 使い方
//potiboard.phpと同じディレクトリにアップロードして
//HTMLファイルに画像を表示する時のように
//newimg.php ←このファイルの名前をurlで指定します。
//例）
// <img src="https://hoge.ne.jp/bbs/newimg.php" alt="" width="300">
//↑
//この例では横幅300px、高さの指定なし。

//------------- 設定 -------------
// 画像がない時に表示する画像を指定
$default='';
//例
// $default='https://hoge.ne.jp/image.png';
//設定しないなら初期値の
// $default='';
//で。

//------------- 説明と設定ここまで -------------

include(__DIR__.'/config.php');//config.phpの設定を読み込む
$fp = fopen(LOGFILE, "r");//ログを開く
	$filename='';
	$i=0;
	while ($line = fgets($fp ,4096)) {
		list(,,,,,,,,,$ext,,,$time,) = explode(",", $line);
		if ($ext&&is_file(IMG_DIR.$time.$ext)){
			if(is_file(THUMB_DIR.$time.'s.jpg')){//サムネイルはあるか?
				$filename=THUMB_DIR.$time.'s.jpg';

			}
			else{//サムネイルが無かったら
				$filename=IMG_DIR.$time.$ext;
			}
		break;
		}
		if($i>=500){break;}//500発言チェックして終了
		++$i;
	
	}
	if(!$filename){//画像が無かったら
		$filename=$default;//デフォルト画像を表示
	}
	fclose($fp);
//画像を出力
header('Content-Type: image/png');
readfile($filename);
?>