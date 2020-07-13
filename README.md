# potiboard_plugin
お絵かき掲示板 POTI-boardのための外部phpプログラムです。 https://pbbs.sakura.ne.jp/

## newimg.php

[お絵かき掲示板](https://pbbs.sakura.ne.jp/)交流サイトで使っている新着画像表示プログラムを一般配布用にカスタマイズしたものです。

### 特徴

POTI-boardに投稿された一番新しい画像を取得して静的HTMLファイルに表示します。
掲示板に入らなくても新着画像を見ることができるようになります。

### 設置方法

[POTI改公式サイト](https://poti-k.info/)からPOTI-boardをダウンロードして設置します。

newimg.phpを
potiboard.phpと同じディレクトリにアップロードします。

### 使い方
画像と同じようにこのphpのファイルをimgタグで呼び出します。

HTMLファイルに
&lt;img src=&quot;https://hoge.ne.jp/bbs/newimg.php&quot; alt=&quot;&quot; width=&quot;300&quot;&gt;
のように書きます。

画像が無い時にデフォルト画像を表示させる事もできます。

### 仕様

サムネイルがある時はサムネイル画像を表示しサムネイルが無い時は元の画像を表示します。

## search.php

[お絵かき掲示板](https://pbbs.sakura.ne.jp/)交流サイトで使っている新着画像表示プログラムを一般配布用にカスタマイズしたものです。

### 特徴

POTI-boardに検索機能を追加します。
コメント検索画面と画像検索画面を切り替えて使う事もできます。

### 設置方法

[POTI改公式サイト](https://poti-k.info/)からPOTI-boardをダウンロードして設置します。

search.php、search.html、search.cssを
potiboard.phpと同じディレクトリにアップロードします。

### 使い方

search.phpにリンクするだけです。
search.htmlのフォームのHTMLをPOTI-boardのテーマのhtmlに追加したり、掲示版外部のHTMLに記述して検索窓を作る事もできます。
検索オプションが何も指定されていない時は投稿者名部分一致のコメントの検索になります。
getで指定していますので検索結果のurlをリンクしてクリックすれば、同じ検索結果がでてきます。

### 仕様

負荷がやや高いため、検索できる件数はデフォルトで120件。設定値をあまり大きくしないでください。

### 免責

このプログラムを利用した事によるいかなる損害にもさとぴあは一切の責任を負いません。

## 履歴
#### [2020/05/19] lot.200519
GitHubに公開
