# BBSNote → POTI-board ログコンバータ
BBSNote v7またはv8のログファイルからPOTI-boardのログファイルを作ります。  
relmのログもPOTI-board形式に変換できます。

## 免責
何らかの損害が発生しても作者は一切の責任を負いません。

BBSNoteのログファイルのバックアップをお願いします。消失しても責任をとれません。

## ダウンロード

[このリポジトリのトップページ](https://github.com/satopian/potiboard_plugin)の緑色のボタンからzipファイルを入手して展開します。  
その中の`logconverter`フォルダの中にある `bbsnote2poti.php` を取り出して使います。 

## ログファイルの変換方法

BBSNoteのログファイルが入っている`data`フォルダを用意します。  
`bbsnote2poti.php`をこのように配置します。  

![image](https://user-images.githubusercontent.com/44894014/108525797-d4ff3500-7313-11eb-9ec7-8c39fa253cd0.png)

## ローカルサーバで実行

webで実行するには高負荷です。そして変換した画像やアニメファイルは移動ではなくコピーによって生成されるためサーバの容量を圧迫します。  
またログコンバータがwebに残っていると誰かが実行してしまうかもしれません。  
もし誰でも何度でも実行できる状態になっていたらサーバに大きな負担がかかります。  
そうならないようにパスワードを任意に設定できるようにしていますが、設定を忘れてサーバにアップロードしてしまうケースが発生するかもしれません。

[XAMPP](https://www.apachefriends.org/jp/index.html)というアプリを使えば簡単にPHP環境を作ることができます。  
ぜひローカル(たとえばWindowsのPCの中で)実行してください。  

## bbsnote2poti.php の設定を変更

エディタで`bbsnote2poti.php`を開くと、パスワードの設定欄があります。  
ここを自分にしかわからないパスワードに変更します。  
まちがってwebにあげてしまった時のための保険です。  

ログファイルの頭文字や拡張子の設定項目があります。  
独自に変更していたり、BBSNoteのバージョンがv7で頭文字や拡張子が異なっている時は、`data`ディレクトリに入っているログファイルの形式に合わせます。  

`$relm=0; //relmのログを変換する時は 1`

BBSNoteではなく、relmというお絵かき掲示板のログファイルを変換する時は`1`です。

`//同じファイル名の画像が出力先にあるときは別名で保存
$save_at_synonym=0;// 1.する 0.しない`
デフォルトでは`0`。
`1.する`にすると、二度コンバートしてしまった時に、同じ画像のファイルが二重にできてしまいます。  
POTI-board形式に画像のファイル名を変換するときに、ログの投稿日時を読み込んでUNIXタイムに変換しています。  
しかし、秒までしか記録されていないので、同じ秒の投稿を処理できません。
そこで、もし同じ秒の投稿があったら秒以下の3桁に+1して別の画像として残せるようにしました。  
しかし、該当ケースはあまりないのかもしれません。  
そのため、デフォルトでは`0.しない`になっています。

### 変換実行 パスワードを入力して変換ボタンを押下

`bbsnote2poti.php`をローカルサーバで開きます。  
パスワードを入力して、`変換開始ボタン`を押します。

画面が切り替わり`変換開始`とでます。
完了すると、  
`完了しました`という表示がでます。

![image](https://user-images.githubusercontent.com/44894014/108527147-48ee0d00-7315-11eb-9bc5-d346def0bdd3.png)
![image](https://user-images.githubusercontent.com/44894014/108527173-50151b00-7315-11eb-8ba3-5c156db0223d.png)
![image](https://user-images.githubusercontent.com/44894014/108527184-56a39280-7315-11eb-95bf-d076ab287316.png)

### 新規設置したPOTI-boardにログファイルを移動

`poti`フォルダにPOTI-board形式のログファイル一式ができます。
図のようなファイルがフォルダに入っていると思います。

![image](https://user-images.githubusercontent.com/44894014/108527216-602cfa80-7315-11eb-8680-5e997c4a05e7.png)
![image](https://user-images.githubusercontent.com/44894014/108527205-5c00dd00-7315-11eb-8192-44e93e3542a7.png)

しかし、このファイルの取り扱いには注意が必要です。  
`img.log`に書き込んだ内容が入り、`tree.log`にスレッドのツリーの構造が入っています。  
POTI-boardのログファイルはこの2つしかないので、すでに運用中のPOTI-boardにこの2つのログファイルを上書きすると、これまでのすべての投稿が消えてなくなります。  
したがって、この変換したログファイルは、新しく設置したまだ何も投稿していないPOTI-boardにしか使えません。  
BBSNoteやPOTI-boardのログファイルが消えてしまっても、何もできませんので、消えてしまうと困るものはバックアップしてください。  
よろしくお願いいたします。

### サポート
[Issues](https://github.com/satopian/potiboard_plugin/issues)または、[POTI改 設置サポート掲示板](https://paintbbs.sakura.ne.jp/cgi/neosample/support/)をご利用ください。  

