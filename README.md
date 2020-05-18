# potiboard_plugin
お絵かき掲示板 POTI-boardのための外部phpプログラムです。 https://pbbs.sakura.ne.jp/

## newimg.php

[お絵かき掲示板](https://pbbs.sakura.ne.jp/)交流サイトで使っている新着画像表示プログラムを一般配布用にカスタマイズしたものです。

### 設置方法

[POTI改公式サイト](https://poti-k.info/)からPOTI-boardをダウンロードして
potiboard.phpと同じディレクトリにアップロードします。

### 特徴

POTI-boardに投稿された一番新しい画像を取得して静的HTMLファイルに表示する事ができます。
掲示板にはいらなくても新着画像を見ることができるようになります。

### 使い方
画像のかわりに、このphpのファイルをimgタグで呼び出します。

HTMLファイルに
&lt;img src=&quot;https://hoge.ne.jp/bbs/newimg.php&quot; alt=&quot;&quot; width=&quot;300&quot;&gt;
のように書きます。

画像が無い時にデフォルト画像を表示させる事もできます。

### 仕様

サムネイルがある時はサムネイル画像を表示します。サムネイルが無かったら元の画像を表示します。

## 履歴
#### [2020/05/19] lot.200519
GitHubに公開
