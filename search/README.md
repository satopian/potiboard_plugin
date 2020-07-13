# potiboard_plugin
お絵かき掲示板 POTI-boardのための外部phpプログラムです。 https://pbbs.sakura.ne.jp/

## search.php

[お絵かき掲示板](https://pbbs.sakura.ne.jp/)交流サイトで使っている新着画像表示プログラムを一般配布用にカスタマイズしたものです。

### 特徴

POTI-boardに検索機能を追加します。
コメント検索画面と画像検索画面を切り替えて使う事もできます。

### 設置方法

[POTI改公式サイト](https://poti-k.info/)からPOTI-boardをダウンロードして設置します。

search.php、search.html、search.cssを
potiboard.phpと同じディレクトリにアップロードします。  

### Skinny.php

search.phpはSkinny.phpを使っています。
POTI-board改二には最初から入っていますので何もしなくていいはずです。  
しかしその他のバージョンのPOTI-boardで使う場合は[Skinny.php](http://skinny.sx68.net/)をアップロードしないと動きません。  
search.phpと同じディレクトリにアップロードします。

### 使い方

search.phpにリンクするだけです。 
search.htmlの検索窓のHTMLをPOTI-boardのテーマのhtmlに追加したり掲示版外部のHTMLに記述して検索窓を作って使う事もできます。  
検索オプションが何も指定されていない時は投稿者名部分一致のコメントの検索になります。  
getで指定していますので検索結果のurlをリンクすれば同じ検索結果がでてきます。  

### 仕様

負荷がやや高いため、検索できる件数はデフォルトで120件。設定値をあまり大きくしないでください。

### 免責

このプログラムを利用した事によるいかなる損害にもさとぴあは一切の責任を負いません。

## 履歴
#### [2020/07/13] lot.200713
GitHubに公開
