<?php
/* ------------------------------------------------------
	アイキャッチを有効化
------------------------------------------------------- */
add_theme_support( 'post-thumbnails' );

/* ------------------------------------------------------
  カスタム投稿タイプ作成
------------------------------------------------------- */
add_action( 'init', 'create_posttype' );

function create_posttype() {
 register_post_type(
  'pokemon', //カスタム投稿タイプ名を定義
  array(
   'labels' => array(
    'name' => 'ポケモン登録',//管理画面などで表示する名前
    'all_items' => 'ポケモン一覧'//管理画面で表示する一覧の名前
   ),

  'public' => true, //投稿タイプをパブリックにする
  'exclude_from_search' => false, //サイトの検索結果から除外しない
  'has_archive' => true, //アーカイブページを作る
  'hierarchical' => false, //固定ページのような階層構造にしない
  'menu_position' => 5, // 管理画面上での配置場所(5だと投稿の下)
  'show_in_rest' => true, //新エディタ Gutenberg を有効化

  'supports' => array( //投稿画面に表示するもの
   'title', //タイトル
   'editor', //本文の編集機能
   'thumbnail', //アイキャッチ画像 (add_theme_support('post-thumbnails')も有効化されていること)
   'revisions',//リビジョンを保存
   //'custom-fields', //カスタムフィールド
   //'excerpt', //抜粋
   //'author', //作成者
   //'trackbacks', //トラックバック送信
   //'comments', //ディスカッション
   //'page-attributes' //hierarchicalをtrueにした場合はコチラも必須
  ),

  // 'rewrite' => true, //パーマリンクのリライトを可能にするかどうか (true/false)
  // //下記はパーマリンクをより詳細に変更する時
  // //'rewrite' => array(
  //  //'slug' => 'blog', //書き換え後のスラッグ
  //  // 'with_front' => false //通常投稿のパーマリンク構造を引き継ぐか
  // //)
  )
 );

 register_taxonomy( //カテゴリー形式
  'types', //カスタムタクソノミー名
  'pokemon', //紐づける投稿タイプ名
  array(
   'label' => 'タイプ',  //管理画面で表示する名前
   'labels' => array(
    'add_new_item' => 'タイプを追加' //管理画面で表示する名前
   ),
   'public' => true, //投稿タイプをパブリックにする
   'hierarchical' => true, //カテゴリーのような階層構造にする
   'show_in_rest' => true, //新エディタ Gutenberg を有効化
   'show_admin_column'=> true, //管理画面の記事一覧に項目を作る

  //  'rewrite' =>true,//パーマリンクのリライトを可能にするかどうか (true/false)
   //下記はパーマリンクをより詳細に変更する時
  //  'rewrite' => array(
  //   'slug' => 'blog', //書き換え後のスラッグ
  //   'with_front' => false, //通常投稿のパーマリンク構造を引き継ぐかどうか (true/false)
    // 'hierarchical' => true //階層化したURLを使用可能にする
  //  )
  )
 );

//  register_taxonomy( //カテゴリー形式
//   'specification_type', //カスタムタクソノミー名
//   'products', //紐づける投稿タイプ名
//   array(
//    'label' => '仕様分類',  //管理画面で表示する名前
//    'labels' => array(
//     'add_new_item' => '仕様分類を追加' //管理画面で表示する名前
//    ),
//    'public' => true, //投稿タイプをパブリックにする
//    'hierarchical' => true, //カテゴリーのような階層構造にする
//    'show_in_rest' => true, //新エディタ Gutenberg を有効化
//    'show_admin_column'=> true, //管理画面の記事一覧に項目を作る

//    'rewrite' =>true,//パーマリンクのリライトを可能にするかどうか (true/false)
//   //  下記はパーマリンクをより詳細に変更する時
//    'rewrite' => array(
//     //'slug' => 'blog', //書き換え後のスラッグ
//     //'with_front' => false //通常投稿のパーマリンク構造を引き継ぐかどうか (true/false)
//     'hierarchical' => true //階層化したURLを使用可能にする
//    )
//   )
//  );


}
