# potiboard_plugin
お絵かき掲示板 POTI-boardのための外部phpプログラムです。 https://pbbs.sakura.ne.jp/

## search.php

[お絵かき掲示板](https://pbbs.sakura.ne.jp/)交流サイトで使っている新着画像表示プログラムを一般配布用にカスタマイズしたものです。

### 特徴

POTI-boardに検索機能を追加するプラグインです。
コメント検索画面と画像検画面を切り替える事ができます。

### 設置方法

[POTI-board改二](https://github.com/sakots/poti-kaini) v2.7.6以降のバージョンには標準で入っていますので何もしなくていいはずです。

#### 改二以外のPOTI-boardの場合は

potiboard.phpと同じディレクトリに
search.phpと
themeディレクトリ一式をアップロードして[Skinny.php](http://skinny.sx68.net/)をsearch.phpと同じディレクトリにアップロードします。

potiboard.php  
Skinny.php  
search.php  
-----+/theme  
-------search.html  
-------search.css  

### 対応ずみのテーマ

改二のデフォルトテーマと、[PINK](https://github.com/satopian/pink)が対応ずみです。  
名前をクリックするとその人が描いたイラストの一覧がでます。  

### その他の使い方

search.phpの検索結果にリンクしたり、search.htmlの検索窓のformのhtmlで検索窓を作る事もできます。    

### 仕様

負荷がやや高いため、検索できる件数はデフォルトで120件。  
設定値をあまり大きくしないでください。

### 免責

このプログラムを利用した事によって発生したいかなる損害も作者は一切の責任を負いません。

## 履歴
#### [2020/08/15] lot.20200815
radioボタン未チェックの時の動作を修正。
#### [2020/08/13] lot.200813
削除ずみのスレッドのレスが表示されるバグを修正。  
本文も全角英数、半角英数どちらでも検索できるようにした。  
#### [2020/07/19] lot.200719
v1.5 古いバージョンのPOTI-boardにも対応。  
改二以外のPOTI-boardで使う時はSkinny.phpが別途必要です。  
#### [2020/07/19] lot.200719
v1.4 負荷削減。画像のis_fileの処理の見直し。 
#### [2020/07/14] lot.200714
v1.2 POTI-board改二に統合。開発はここで継続。
#### [2020/07/14] lot.200714
v0.2 負荷削減。ページングで表示している記事の分だけレス先を探すようにした。
#### [2020/07/13] lot.200713
v0.1 GitHubに公開。
