<?php
//POTI-board plugin search(c)2020 さとぴあ
//https://pbbs.sakura.ne.jp/
//フリーウェアですが著作権は放棄しません。
//使用条件。
//テンプレートの著作表記のリンクを削除したり見えなくしないでください。
//免責
//このプログラムを利用した事によるいかなる損害にもさとぴあは一切の責任を負いません。
//サポート
//ご質問は
//GitHubのこのプログラムのリポジトリのIssuesにお願いします。
//GitHubの開発配布のためのリポジトリ
//https://github.com/satopian/potiboard_plugin/

//設定
//何件までしらべるか？
//初期値120 あまり大きくしないでください。
$max_search=120;

//更新履歴
//ｖ0.1 2020.07.13 GitHubに公開

//設定を変更すればより多く検索できるようになりますが、サーバの負荷が高くなります。

//設定の読み込み
require(__DIR__.'/config.php');
//HTMLテンプレート Skinny
require_once(__DIR__.'/Skinny.php');

// $time_start = microtime(true);
//タイムゾーン
date_default_timezone_set('Asia/Tokyo');
//filter_input

$imgsearch=filter_input(INPUT_GET,'imgsearch',FILTER_VALIDATE_BOOLEAN);
$page=filter_input(INPUT_GET,'page',FILTER_VALIDATE_INT);
$query=filter_input(INPUT_GET,'query');
$query=urldecode($query);
$query=htmlspecialchars($query,ENT_QUOTES,'utf-8');
$query=mb_convert_kana($query, 'rn', 'UTF-8');
$query=str_replace(array(" ", "　"), "", $query);
$radio =filter_input(INPUT_GET,'radio',FILTER_VALIDATE_INT);

// $imgsearch=true;

//クエリを検索窓に入ったままにする
$dat['query']=$query;
//ラジオボタンのチェック
$dat['radio_chk1']='';//作者名
$dat['radio_chk2']='';//完全一致
$dat['radio_chk3']='';//本文題名	
$query_l='&query='.urlencode($query);//クエリを次ページにgetで渡す
if($query!==''&&$radio===3){//本文題名
	$query_l.='&radio=3';
	$dat['radio_chk3']='checked="checked"';//本文題名	
}
elseif($query!==''&&$radio===2){//完全一致
	$query_l.='&radio=2';
	$dat['radio_chk2']='checked="checked"';	
}
elseif($query!==''&&($radio===null||$radio===1)){//作者名
	$query_l.='&radio=1';
	$dat['radio_chk1']='checked="checked"';
}
else{//作者名	
	$query_l='';
	$dat['radio_chk1']='checked="checked"';
	$radio_chk1='checked="checked"';
}
$dat['query_l']=$query_l;
if($imgsearch){
	$dat['imgsearch']=true;
}

if(!$page){
	$page=1;
}
$dat['page']=$page;
$dat['artist_l']=$artist_l;	

//ログの読み込み
$i=1;$j=1;$l=1;
$arr=array();
// $files=array();
$tree=file(TREEFILE);
$fp = fopen(LOGFILE, "r");
while ($line = fgets($fp ,4096)) {
	list($no,,$name,,$sub,$com,,
	,,$ext,,,$time,,,,) = explode(",", $line);
	//画像はあるか?
	$is_img=false;
	if($ext&&is_file(IMG_DIR.$time.$ext)){
		$is_img=true;
	}
	$continue_to_search=false;
	if($imgsearch){//画像検索の場合
		if($is_img){
			$continue_to_search=true;//検索続行
		}
	}
		else{//それ以外
			$continue_to_search=true;
		}

	if($continue_to_search){
		$s_name=mb_convert_kana($name, 'rn', 'UTF-8');//全角英数を半角に
		$s_name=str_replace(array(" ", "　"), "", $s_name);
		//ログとクエリを照合
		if($query===''||//空白なら
				$query!==''&&$radio===3&&stripos($com,$query)!==false||//本文を検索
				$query!==''&&$radio===3&&stripos($sub,$query)!==false||//題名を検索
				$query!==''&&($radio===1||$radio===null)&&stripos($s_name,$query)!==false||//作者名が含まれる
				$query!==''&&($radio===2&&$s_name===$query)//作者名完全一致
		){

			$k=1;
				foreach($tree as $treeline){
					$treeline=','.rtrim($treeline).',';//行の両端にコンマを追加
				if(strpos($treeline,','.$no.',')!==false){
					$treenos=explode(",",$treeline);
					$no=$treenos[1];//スレッドの親
						$link=PHP_SELF.'?res='.$no;
				$img='';		
				if(is_file(THUMB_DIR.$time.'s.jpg')){//サムネイルはあるか？
					$img=THUMB_DIR.$time.'s.jpg';
				}
				else{
				if($is_img){
					$img=IMG_DIR.$time.$ext;
					}
				}
						$arr[]=$no.','.$name.','.$sub.','.$com.','.$link.','.$img.','.$time;


						++$i;
				}
				// if($k>=2000){//スレッド数
				// 	break;
				// }
					// ++$k;
			}	
				if($i>$max_search){break;}//1掲示板あたりの最大検索数
				++$l;
		}
		
	}

	if($j>=5000){break;}//1掲示板あたりの最大行数
	++$j;

}

	fclose($fp);

//検索結果の出力
$j=0;$countimg=0;
if($arr){
	foreach($arr as $i => $val){
		if($i > $page-2){//カウンタの$iが表示するページになるまで待つ
			list($no,$name,$sub,$com,$link,$img,$time)=explode(",",$val);
			// $img='';
			// if($ext){//ファイルの存在確認を最小限に表示しているページの分だけ
			// 	if(is_file(THUMB_DIR.$time.'s.jpg')){//サムネイルはあるか？ファイルの存在確認は表示しているページの分だけ
			// 		$img=THUMB_DIR.$time.'s.jpg';}
			// 		else{
			// 			$img=IMG_DIR.$time.$ext;
			// 		}
			// 		++$countimg;
			// }
			$time=substr($time,-13,10);
			$postedtime = date ("m/d G:i", $time);
			$sub=strip_tags($sub);
			$com=strip_tags($com);
			$com=mb_strcut($com,0,180);
			$name=strip_tags($name);
			$encoded_name=urlencode($name);
			//変数格納
			$dat['comments'][]= compact('no','name','encoded_name','sub','img','com','link','postedtime');

		}
		// if($imgsearch){
		// 	$j=$countimg+1;
		// 	if($countimg>=$page+30-1){break;}
		// }
		// else{
			$j=$i+1;//表示件数
			if($i >= $page+30-2){break;}
		// }
	}
}
unset($sub,$name,$no,$boardname);
unset($i,$val);

$search_type='';
if($imgsearch){
$img_or_com='イラスト';
$search_type='&imgsearch=on';
}
else{
$img_or_com='コメント';
}
$dat['img_or_com']=$img_or_com;


$dat['pageno']='';
if($j&&$page>=2){
	$dat['pageno'] = $page.'-'.$j.'件';
}
elseif($j){
		$dat['pageno'] = $j.'件';
}
if($query!==''&&$radio===3){
	$dat['title']=$query.'の'.$img_or_com;//titleタグに入る
	$dat['h1']=$query.'の';//h1タグに入る
}
elseif($query!==''){
	$dat['title']=$query.'さんの'.$img_or_com;
	$dat['h1']=$query.	'さんの';
}
else{
	$dat['title']='掲示板に投稿された最新の'.$img_or_com;
	$dat['h1']='掲示板に投稿された最新の';
}

//ページング

$nxetpage=$page+30;//次ページ
$prevpage=$page-30;//前のページ
$countarr=count($arr);//配列の数
$dat['prev']=false;
$dat['nxet']=false;

if($page<=30){
	$dat['prev']='<a href="./">掲示板にもどる</a>';//前のページ
if($countarr>=$nxetpage){
	$dat['nxet']='<a href="?page='.$nxetpage.$search_type.$query_l.'">次の30件≫</a>';//次のページ
}
}

// if($page>=151){//表示する最大ページ数
// 	$dat['prev']='<a href="?page='.$prevpage.$search_type.$query_l.'">≪前の30件</a>'; 
// 	$dat['nxet']='<a href="./">掲示板にもどる</a>';
		
// }
elseif($page>=31){
	$dat['prev']= '<a href="?page='.$prevpage.$search_type.$query_l.'">≪前の30件</a>'; 
	if($countarr>=$nxetpage){
		$dat['nxet']='<a href="?page='.$nxetpage.$search_type.$query_l.'">次の30件≫</a>';
	}
	else{
		$dat['nxet']='<a href="./">掲示板にもどる</a>';
	}
}
//最終更新日時を取得
if($arr){
	list(,,,,,,$postedtime)=explode(",",$arr[0]);
	$postedtime=substr($postedtime,-13,10);
	$dat['lastmodified']=date("Y/m/d G:i", $postedtime);
}

unset($arr);
//HTML出力
$Skinny->SkinnyDisplay('search.html', $dat );

?>
