<?php
/*** パンくずリストを出力する関数 my_breadcrumbs 2019/07/14 updated ***
  * 2019/07/14 カスタム投稿タイプにカスタム分類が登録されていない場合に対応（161行目追加）
  *
  https://www.webdesignleaves.com/pr/wp/wp_breadcrumbs.html
  * カスタム投稿タイプ日付アーカイブには未対応。
  *
  * カスタムフィールド myterm を使って優先するカテゴリーやタームを指定可能に　2019/07/10
  *
  * ［注意］この関数は別途 get_deepest_term()   が必要です。
  *
  * パラメータ $args ：引数の連想配列（全てオプション）。以下概要。
  * nav_div　リスト（ul 要素）を囲む要素を指定。デフォルト: nav（要素）
  * aria_label リストを囲む要素に指定する aria_label 属性。値を指定すると出力
  * id リストを囲む要素に指定する id 属性。値を指定すると出力
  * nav_div_class リストを囲む要素に指定する class 属性。値を指定すると出力
  * ul_class ul 要素に指定する class 属性。値を指定すると出力
  * li_class リンクを含む li 要素に指定する class 属性。値を指定すると出力 
  * li_active_class リンクを含まない li 要素に指定する class 属性。値を指定すると出力  
  * aria_current リンクを含まない li 要素に指定する aria-current 属性。値を指定すると出力 
  * show_home ホームの場合にホームの文字列を表示するかどうか。初期値 true（表示する）
  * show_current 個別投稿ページの場合に現在のページを表示するかどうか。 初期値 true（表示する）2019/07/06 Added
  * show_cpta カスタム投稿タイプ個別ページでアーカイブページを表示するかどうか。 初期値 true（表示する）2019/07/06 Added
  * home ホームの文字列。初期値：Home
  * blog_home 管理画面のホームページの表示で「固定ページ」→「投稿ページ（メインブログページ）」に
  * 指定したページで表示する文字。初期値：Blog
  * search 検索結果ページの文字列。初期値： で検索した結果
  * tag タグページの文字列。初期値：タグ : 
  * author  投稿者ページの文字列。初期値：投稿者
  * notfound 404ページの文字列。初期値： 404 Not found
  * separator 区切り文字列。''を指定すると出力しない。初期値：<li class="separator"> > </li>
  * cat_off 個別ページでカテゴリーを表示しない。初期値： false（表示する）
  * cat_parents_off 個別ページで親のカテゴリーを表示しない。初期値： false（表示する）
  * tax_off カスタム投稿タイプ個別ページでタームを表示しない。初期値：false（表示する）
  * tax_parents_off カスタム投稿タイプ個別ページで親のタームを表示しない。初期値：false（表示する）
  * show_cat_tag_for_cpt カスタム投稿タイプ個別ページでカテゴリーを表示する（その場合は、カスタムタクソノミーは表示されない）。初期値：false（表示しない）
  * 記事が複数のカテゴリーやタームに属する場合、カスタムフィールド（myterm）を使って優先するカテゴリーやタームを指定可能
  * カスタム投稿タイプに複数のカスタムタクソノミーが登録されている場合、カスタムフィールド（my_pref_tax）を使ってタクソノミーを指定可能 
*/
 
function my_breadcrumbs($args = array()){
  global $post;
  // デフォルトの値
  $defaults = array(
    'nav_div' => 'nav',
    'aria_label' => '',
    'id' => 'breadcrumb',
    'nav_div_class' => 'breadcrumb',
    'ul_class' =>'breadcrumb__wrap',
    'li_class' => 'breadcrumb__list',
    'li_active_class' => 'breadcrumb__list active',
    'aria_current' => '',
    'show_home' => true, 
    'show_current' => true,
    'home' => 'Home',
    'blog_home' => 'Blog',
    'search' => 'で検索した結果',
    'tag' => 'タグ : ',
    'author' => '投稿者',
    'notfound' => '404 Not found',
    'separator' => "\n".'<li class="separator">&nbsp;&gt;&nbsp;</li>'."\n",
    'cat_off' => false,
    'cat_parents_off' => false,
    'tax_off' => false,
    'tax_parents_off' => false,
    'show_cpta' => true,
    'show_cat_tag_for_cpt' => false,
  );
  //引数の値とデフォルトをマージ
  $args = wp_parse_args( $args, $defaults );
  //マージした値を変数として抽出
  extract( $args, EXTR_SKIP );
 
  //マージした値を元に出力するかどうかを設定
  $aria_label = $aria_label ? ' aria-label="' .$aria_label . '" ' : '';
  $id = $id ? ' id="' .$id . '" ' : '';
  $nav_div_class = $nav_div_class ? ' class="' .$nav_div_class . '" ' : '';
  $ul_class = $ul_class ? ' class="' .$ul_class . '" ' : '';
  $li_class = $li_class ? ' class="' .$li_class . '" ' : '';
  $li_active_class = $li_active_class ? ' class="' .$li_active_class . '" ' : '';
  $aria_current = $aria_current ? ' aria-current="' .$aria_current . '"' : '';
 
  //パンくずリストのマークアップ文字列の初期化
  $str ='';  
 
  //ホーム・フロントページの場合  
  if(is_front_page() || is_home()) {
    if($show_home) {
      $label = is_front_page() ? $home: $blog_home;
      echo  '<'.$nav_div . $id . $nav_div_class. $aria_label. '><ul'. $ul_class .'><li'. $li_active_class. $aria_current. '>'. $label .'</li></ul></'. $nav_div .'>';
    }
  }
  //ホーム・フロントページでない場合（且つ管理ページでない場合）
  if(!is_front_page() && !is_home() && !is_admin()){
    //ホームへのリンクを含むリストを生成
    $str.= '<'.$nav_div . $id . $nav_div_class. $aria_label.'>'."\n";
    $str.= '<ul'. $ul_class .'>'."\n";
    $str.= '<li'. $li_class .'><a href="'. home_url() .'/">'. $home .'</a></li>';
    //$wp_query の query_vars から get_query_var() でクエリ変数の値を取得
    //タクソノミー名を取得（タクソノミーアーカイブの場合のみ取得可能）
    $my_taxonomy = get_query_var('taxonomy');  
    //投稿タイプ名を取得（カスタム投稿タイプ個別ページの場合のみ取得可能）
    $cpt = get_query_var('post_type'); 
    //カスタムタクソノミーアーカイブページ
    //タクソノミー名が取得できて且つカスタムタクソノミーアーカイブページの場合
    if($my_taxonomy &&  is_tax($my_taxonomy)) {
      //タームオブジェクト（現在のページのオブジェクト）を取得
      $my_term = get_queried_object(); 
      //タクソノミーの object_type プロパティは配列
      $post_types = get_taxonomy( $my_taxonomy )->object_type;
      //配列の0番目からカスタム投稿タイプのスラッグ（カスタム投稿タイプ名）を取得
      $cpt = $post_types[0]; 
      //get_post_type_archive_link()：指定した投稿タイプのアーカイブページのリンク
      //get_post_type_object($cpt)->label：指定した投稿タイプのオブジェクトのラベル（名前）
      //カスタム投稿のアーカイブページへのリンクを追加
      $str.= $separator; 
      $str.='<li'. $li_class .'><a href="' .esc_url(get_post_type_archive_link($cpt)).'">'. get_post_type_object($cpt)->label.'</a></li>';  
      //タームオブジェクトに親があればそれらを取得してリンクを生成してリストに追加
      if($my_term->parent != 0) { 
        //祖先タームオブジェクトの ID の配列を取得し逆順に（取得される配列の並びは階層の下から上）
        $ancestors = array_reverse(get_ancestors( $my_term->term_id, $my_term->taxonomy ));
        //全ての祖先タームオブジェクトのアーカイブページへのリンクを生成してリストに追加
        foreach($ancestors as $ancestor){
          $str.= $separator; 
          $str.='<li'. $li_class .'><a href="'. esc_url(get_term_link($ancestor, $my_term->taxonomy)) .'">'. get_term($ancestor, $my_term->taxonomy)->name .'</a></li>';
        }
      }
      //ターム名を追加 
      $str.= $separator; 
      $str.='<li' .$li_active_class. $aria_current.'>'. $my_term->name . '</li>';
    //カテゴリーのアーカイブページ
    }elseif(is_category()) { 
      //カテゴリーオブジェクトを取得
      $cat = get_queried_object();
      //取得したカテゴリーオブジェクトに親があればそれらを取得してリンクを生成してリストに追加
      if($cat->parent != 0){
        $ancestors = array_reverse(get_ancestors( $cat->term_id, 'category' ));
        foreach($ancestors as $ancestor){
          $str.= $separator; 
          $str.='<li'. $li_class .'><a href="'. esc_url(get_category_link($ancestor)) .'">'. get_cat_name($ancestor) .'</a></li>';
        }
      }
      //カテゴリー名を追加
      $str.= $separator; 
      $str.='<li' .$li_active_class. $aria_current.'>'. $cat->name . '</li>'; 
    //カスタム投稿のアーカイブページ
    }elseif(is_post_type_archive()) { 
      //カスタム投稿タイプ名を取得
      $cpt = get_query_var('post_type');
      //カスタム投稿タイプ名を追加
      $str.= $separator; 
      $str.='<li' .$li_active_class. $aria_current.'>'. get_post_type_object($cpt)->label . '</li>'; 
    //カスタム投稿タイプの個別記事ページ
    }elseif($cpt && is_singular($cpt)){ 
      if($show_cpta) {
        //カスタム投稿タイプアーカイブページへのリンクを生成してリストに追加
        $str.= $separator; 
        $str.='<li'. $li_class .'><a href="' .esc_url(get_post_type_archive_link($cpt)).'">'. get_post_type_object($cpt)->label.'</a></li>'; 
      }  
      //このカスタム投稿タイプに登録されている全てのタクソノミーオブジェクトの名前を取得
      $taxes = get_object_taxonomies( $cpt );
      //タクソノミーオブジェクトの名前が取得できれば
      if(count($taxes) !== 0) {
        //タクソノミーを表示する場合
        if(!$tax_off) {
          //配列の先頭のタクソノミーオブジェクトの名前（複数ある可能性があるので先頭のものを使う）
          //デフォルトでは標準のカテゴリーやタグが追加されている場合はインデックスを変更 
          //但し、show_cat_tag_for_cpt が true の場合はカテゴリーを取得可能に
          $tax_index = 0;
          if(!$show_cat_tag_for_cpt) {
            for ($i = 0; $i < count($taxes); $i++) {
             if($taxes[$i] !== 'category' && $taxes[$i] !== 'post_tag' && $taxes[$i] !== 'post_format') {
               $tax_index = $i;
               break;
             }
            }
          }
          $mytax = $taxes[$tax_index] ? $taxes[$tax_index] : null;
          //カスタムフィールドに優先するタクソノミーのラベルが記載されていればそのタクソノミーを選択
          //タクソノミーのラベルを取得
          $my_pref_tax_label = get_post_meta( get_the_ID(), 'my_pref_tax', true) ? esc_attr(get_post_meta( get_the_ID(), 'my_pref_tax', true)) : null;
          //ラベルからタクソノミーを取得（戻り値はタクソノミーの名前の配列）
          $my_pref_tax_name = get_taxonomies(array('label'=> $my_pref_tax_label));
          //タクソノミー名の初期化
          $my_pref_tax = '';
          //取得した配列が1つの場合、その値が優先されるタクソノミーの名前
          if(count($my_pref_tax_name) == 1 ){
            $my_pref_tax = $my_pref_tax_name[key($my_pref_tax_name)];
          }
          //タクソノミーの名前が取得できて且つそのタクソノミーが現在の投稿タイプに属している場合は、そのタクソノミーを使用
          if($my_pref_tax && is_object_in_taxonomy($post->post_type, $my_pref_tax)) {
            $mytax = $my_pref_tax;
          }
          //投稿に割り当てられたタームオブジェクト（配列）を取得
          $terms = get_the_terms($post->ID, $mytax); 
          //カスタムフィールドに優先するタームが記載されていればその値を取得して $myterm へ
          $myterm = get_post_meta( get_the_ID(), 'myterm', true) ? esc_attr(get_post_meta( get_the_ID(), 'myterm', true)) : null;
          //$terms が取得できていれば一番下の階層のタームを取得（できない場合は null に）  
          $my_term = $terms ? get_deepest_term($terms, $mytax, $myterm) : null;
          //タームが取得できていれば
          if( !empty($my_term) ) {
            //$tax_parents_off がfalse（初期値）でタームに親があればそれらを取得してリンクを生成してリストに追加
            if($my_term->parent != 0 && !$tax_parents_off){
              $ancestors = array_reverse(get_ancestors( $my_term->term_id, $mytax ));
              foreach($ancestors as $ancestor){
                $str.= $separator; 
                $str.='<li'. $li_class .'><a href="'. esc_url(get_term_link($ancestor, $mytax)).'">'. get_term($ancestor, $mytax)->name . '</a></li>';
              }
            }
            //タームのリンクを追加
            $str.= $separator; 
            $str.='<li'. $li_class .'><a href="'. esc_url(get_term_link($my_term, $mytax)).'">'. $my_term->name . '</a></li>';  
          }
        }
      }
      if($show_current) {
        $str.= $separator;
        //$post->post_title には HTML タグが入っている可能性があるのでタグを除去
        //wp_strip_all_tags() の代わりに PHP の strip_tags() でも
        $str.= '<li' .$li_active_class. $aria_current.'>'. wp_strip_all_tags($post->post_title) .'</li>';
      }  
    //個別投稿ページ（添付ファイルも true と判定されるので除外）
    }elseif(is_single() && !is_attachment()){
      //投稿が属するカテゴリーオブジェクトの配列を取得
      $categories = get_the_category($post->ID);
      //カテゴリーを表示する場合
      if(!$cat_off) {
        //カスタムフィールドに優先するカテゴリーが記載されていればその値を取得して $myterm へ
        $myterm = get_post_meta( get_the_ID(), 'myterm', true) ? esc_attr(get_post_meta( get_the_ID(), 'myterm', true)) : null;
        //一番下の階層のカテゴリーを取得
        $cat = get_deepest_term($categories, 'category', $myterm);
        //$cat_parents_off が false（初期値）でカテゴリーに親があればそれらを取得してリンクを生成してリストに追加
        if($cat->parent != 0 && !$cat_parents_off){
          $ancestors = array_reverse(get_ancestors( $cat->term_id, 'category' ));
          foreach($ancestors as $ancestor){
            $str.= $separator; 
            $str.='<li'. $li_class .'><a href="'. esc_url(get_category_link($ancestor)).'">'. get_cat_name($ancestor). '</a></li>';
          }
        }
        //カテゴリーのリンクを追加
        $str.= $separator; 
        $str.='<li'. $li_class .'><a href="'. esc_url(get_category_link($cat->term_id)). '">'. $cat->name . '</a></li>';
      }
      if($show_current) {
        $str.= $separator; 
        $str.= '<li' .$li_active_class. $aria_current.'>'. wp_strip_all_tags($post->post_title) .'</li>';
      }
    //固定ページ
    } elseif(is_page()){
      //固定ページに親があればそれらを取得してリンクを生成してリストに追加
      if($post->post_parent != 0 ){
        $ancestors = array_reverse(get_post_ancestors( $post->ID ));
        foreach($ancestors as $ancestor){
          $str.= $separator; 
          $str.='<li'. $li_class .'><a href="'. esc_url(get_permalink($ancestor)).'">'. get_the_title($ancestor) .'</a></li>';
        }
      }
      //固定ページ名を追加
      $str.= $separator; 
      $str.= '<li' .$li_active_class. $aria_current.'>'. wp_strip_all_tags($post->post_title) .'</li>';
    //日付ベースのアーカイブページ
    } elseif(is_date()){
      //年別アーカイブ
      if(get_query_var('day') != 0){
        //日付アーカイブページでは get_query_var() でアーカイブページの年・月・日を取得できる
        //取得した値と get_year_link() などを使ってリンクを生成
        $str.= $separator; 
        $str.='<li'. $li_class .'><a href="'. get_year_link(get_query_var('year')). '">' . get_query_var('year'). '年</a></li>';
        $str.= $separator;
        $str.='<li'. $li_class .'><a href="'. get_month_link(get_query_var('year'), get_query_var('monthnum')). '">'. get_query_var('monthnum') .'月</a></li>';
        $str.= $separator;
        $str.='<li' .$li_active_class. $aria_current.'>'. get_query_var('day'). '日</li>';
      //月別アーカイブ
      } elseif(get_query_var('monthnum') != 0){
        $str.= $separator; 
        $str.='<li'. $li_class .'><a href="'. get_year_link(get_query_var('year')) .'">'. get_query_var('year') .'年</a></li>';
        $str.= $separator;
        $str.='<li' .$li_active_class. $aria_current.'>'. get_query_var('monthnum'). '月</li>';
      //年別アーカイブ
      } else {
        $str.= $separator; 
        $str.='<li' .$li_active_class. $aria_current.'>'. get_query_var('year') .'年</li>';
      }
    //検索結果表示ページ
    } elseif(is_search()) {
      $str.= $separator; 
      $str.='<li' .$li_active_class. $aria_current.'>「'. get_search_query() .'」'. $search .'</li>';
    //投稿者のアーカイブページ
    } elseif(is_author()){
      $str.= $separator; 
      $str .='<li' .$li_active_class. $aria_current.'>'. $author .' : '. get_the_author_meta('display_name', get_query_var('author')).'</li>';
    //タグのアーカイブページ
    } elseif(is_tag()){
      $str.= $separator; 
      //$str.='<li' .$li_active_class. $aria_current.'>'. $tag .' '. single_tag_title( '' , false ). '</li>';
      $str.='<li' .$li_active_class. $aria_current.'>'. single_tag_title( $tag , false ). '</li>';
    //添付ファイルページ
    } elseif(is_attachment()){
      $str.= $separator; 
      $str.= '<li' .$li_active_class. $aria_current.'>'. wp_strip_all_tags($post->post_title) .'</li>';
    //404 Not Found ページ
    } elseif(is_404()){
      $str.= $separator; 
      $str.='<li' .$li_active_class. $aria_current.'>'.$notfound.'</li>';
    //その他
    } else {
      $str.= $separator; 
      $str.='<li' .$li_active_class. $aria_current.'>'. wp_get_document_title() .'</li>';
    }
    $str.="\n".'</ul>'."\n";
    $str.='</' .$nav_div .'>'."\n";
  }
  echo $str;
}

/**
 * 一番深い階層のタームオブジェクトを返す関数  
 * 2019/07/11 updated （不要な記述 $top_ancestor を削除）
 * 引数 $terms：（投稿が属する）タームオブジェクトの配列
 * 引数 $mytaxonomy：タクソノミー名
 * 引数 $myterm：優先するターム  
 * 戻り値 $deepest：タームオブジェクト
*/
 
function get_deepest_term($terms, $mytaxonomy, $myterm = null){
  global $post;
  if($myterm) {
    //$myterm が指定されていれば値からタームオブジェクトを生成
    $my_pref_term =  get_term_by( 'name', $myterm, $mytaxonomy );
    //タームオブジェクトが取得できて且つそのタームが現在の投稿に属していれば
    if($my_pref_term && is_object_in_term( $post->ID, $mytaxonomy, $my_pref_term->term_id )) {
      //優先的にそのタームを返す
      return $deepest =  $my_pref_term;
    }
  }
  //配列の要素が１つの場合その要素を最も深いタームとする
  if(count($terms) == 1 ){
    $deepest = $terms[key($terms)];
  }else{
    $deepest = $terms[key($terms)];
    //祖先オブジェクトの最大数の初期化
    $max = 0;
    //それぞれのタームについて調査
    for($i = 0; $i < count($terms); $i ++) {
      //上の階層から順番に取得した祖先オブジェクトの ID の配列
      $ancestors = array_reverse(get_ancestors( $terms[$i]->term_id, $terms[$i]->taxonomy ));
      //祖先オブジェクトの数
      $ancestors_count = count($ancestors);
      //祖先オブジェクトの数を比較して最大数より大きければ
      if($ancestors_count > $max) {
        //祖先オブジェクトの最大数を更新
        $max = $ancestors_count;
        //その要素を最も深いタームとする
        $deepest = $terms[$i];
      }
    }
  }
  return $deepest;
}
