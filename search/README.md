# potiboard_plugin
お絵かき掲示板 POTI-boardのための外部phpプログラムです。 https://pbbs.sakura.ne.jp/

## search.php

[お絵かき掲示板](https://pbbs.sakura.ne.jp/)交流サイトで使っている新着画像表示プログラムを一般配布用にカスタマイズしたものです。

### 特徴

POTI-board改二に検索機能を追加します。
コメント検索画面と画像検索画面を切り替えて使う事もできます。

### 設置方法

[POTI改公式サイト](https://poti-k.info/)からPOTI-boardをダウンロードして設置します。

search.phpをpotiboard.phpと同じディレクトリにアップロードします。 
search.html、search.cssを、config.phpで設定したthemeディレクトリにアップロードします。

[POTI-board改二](https://github.com/sakots/poti-kaini) v2.7.6移行のバージョンには標準で入っていますので何もしなくていいはずです。

### Skinny.php

search.phpはSkinny.phpを使っています。
POTI-board改二には最初から入っていますので何もしなくていいはずです。  
しかしその他のバージョンのPOTI-boardで使う場合は[Skinny.php](http://skinny.sx68.net/)をアップロードしないと動きません。  
search.phpと同じディレクトリにアップロードします。

### テーマ

デフォルトテーマと、[PINK](https://github.com/satopian/pink)が対応しています。
見た目を変更したい時はテーマを変更してみてください。
search.htmlとsearch.cssをカスタマイズする事もできます。

### 使い方

search.phpにリンクするだけです。  
search.htmlの検索窓のHTMLをPOTI-boardのテーマのhtmlに追加したり掲示版外部のHTMLに記述して検索窓を作って使う事もできます。    
検索オプションが何も指定されていない時は投稿者名部分一致のコメントの検索になります。  
getで指定していますので検索結果のurlをリンクすれば同じ検索結果がでてきます。  
2020/07/14、POTI-board改二のデフォルトテーマと[PINK](https://github.com/satopian/pink)のsearch.php対応版ができました。    
名前をクリックするとその人が描いたイラストの一覧がでてきます。  

### 仕様

負荷がやや高いため、検索できる件数はデフォルトで120件。設定値をあまり大きくしないでください。

### 免責

このプログラムを利用した事によるいかなる損害にもさとぴあは一切の責任を負いません。

## 履歴
#### [2020/07/14] lot.200714
ｖ1.2 lot.200714 POTI-board改二に統合。開発はここで継続。
#### [2020/07/14] lot.200714
ｖ0.2 lot.200714 負荷削減。ページングで表示している記事の分だけレス先を探して見つけるようにした。
#### [2020/07/13] lot.200713
ｖ0.1 lot.200713 GitHubに公開。
