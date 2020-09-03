# やこうさんパレット

[BBS Noteの雑記帳](http://bbsnote.s17.xrea.com/)で配布されている動的パレットスクリプトのパレットデータファイルです。
>アーカイブ内容を改変しない限りは再配布を自由といたします。

という再配布条件のため y_palette_utf101.zip のまま再配布します。   
y_palette_utf101.zipを展開して以下のファイルを取り出してください。 

## 同梱ファイル一覧

>  palette.dat        (BBS Note差し替え用パレットデータ UTF-8版)  
>  palette.txt        (Palette Matrix貼り付け用パレットデータ UTF-8版)  
>  colorlist.html     (パレット色一覧表HTML HTML5版)  
>  palette_readme.txt (取り扱いマニュアル (このファイル))  

POTI-boardへのパレットの追加で必要なのはこの中の

>palette.dat  

だけです。

## palette.dat を potiboard.phpと同じディレクトリにアップロード

palette.datをpotiboard.phpと同じディレクトリにアップロードします。

## palette.txt を上書きしないようにする

y_palette_utf101.zipに入っている

>palette.txt  

をpotiboard.phpと同じディレクトリにアップロードすると元のパレットが使えなくなります。  
標準のパレットデータファイルとファイル名が同じだからです。  
このzipファイルに入っているpalette.txtは、パレットマトリクスで使うためのものなので注意が必要です。  

## config.php の設定を変更

### パレットをやこうさんパレットに変更

`//パレットデータファイル名  
define('PALETTEFILE', 'palette.dat');`  

### パレットデータファイル切り替え機能を使って追加

`//パレットデータファイル切り替え機能を使用する しない:0 する:1 `    
`define('USE_SELECT_PALETTES', '1');//←切り替えるので 1`  

`//パレットデータファイル切り替え機能を使用する する:1 の時のパレットデーターファイル名`  
`//初期パレットpalette.txtとやこうさんパレットpalette.datを切り替えて使う時`  
`//↓`  
`$pallets_dat=['palette.txt','palette.dat'];`  

切り替えるパレットが初期パレットとやこうさんパレットなら上記設定で。
