<?php


/* ---------------------------------------
「lib」へファイル分割
--------------------------------------- */
get_template_part('lib/create_short_code');  // ショートコード用関数
get_template_part('lib/custom_post');        // カスタム投稿に関するカスタマイズ
// get_template_part('lib/custom_taxonomy');    // カスタム投稿に関するカスタマイズ
get_template_part('lib/breadcrumb');         // パンクズリストに関するカスタマイズ

/* ---------------------------------------
CSS・Javascript読み込み
--------------------------------------- */

function add_styles_scripts()
{
  define("TEMPLATE_DIRE", get_template_directory_uri());
  define("TEMPLATE_PATH", get_template_directory());
  function wp_css($css_name, $file_path)
  {
    wp_enqueue_style($css_name, TEMPLATE_DIRE . $file_path, array(), date('YmdGis', filemtime(TEMPLATE_PATH . $file_path)));
  }
  function wp_script($script_name, $file_path, $bool = true)
  {
    wp_enqueue_script($script_name, TEMPLATE_DIRE . $file_path, array(), date('YmdGis', filemtime(TEMPLATE_PATH . $file_path)), $bool);
  }

  //CSS読み込み
  wp_css('common_style', '/common/css/common.css');

  //script読み込み
  wp_script('common_script', '/common/js/main.js');
}
add_action('wp_enqueue_scripts', 'add_styles_scripts', 1);


/* ---------------------------------------
ウィジェットの有効化・設定
--------------------------------------- */
//ウィジェットを作成し、管理画面で設定できるようにします。
//また、各ウィジェットをくくるHTMLタグなども指定できます。
//表示にはテーマテンプレート内でdynamic_sidebar()に設定したidを指定します。
function theme_slug_widgets_init()
{
  register_sidebar(array(
    'name' => 'サイドナビ',
    'id' => 'sidenavi',
    'before_widget' => '<div class="side_widget">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="side_widget_title">',
    'after_title' => '</h2>'
  ));
  register_sidebar(array(
    'name' => 'フッター',
    'id' => 'footerwidget',
    'before_widget' => '<div class="footer_widget">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="footer_widget_title">',
    'after_title' => '</h2>'
  ));
}
add_action('widgets_init', 'theme_slug_widgets_init');

/* ---------------------------------------
メニューの設定
--------------------------------------- */
//'ロケーションID名' => '管理画面での表示名' となっています。
//ヘッダー用・フッター用の2箇所に設定するメニュー例です。管理画面＞外観＞メニューでメニューを作成後、メニューの位置で下記の設定が選択できるようになっています。
//表示にはテーマテンプレート内でwp_nav_menu()に設定したロケーションIDを指定します。
function menu_setup()
{
  register_nav_menus(array(
    'header_navi' => 'ヘッダーナビ',
    'header_navi_sp' => 'ヘッダーナビ(SP)',
    'footer_navi' => 'フッターナビ'
  ));
}
add_action('after_setup_theme', 'menu_setup');


/* ---------------------------------------
管理画面から「投稿」消去
--------------------------------------- */
function remove_menus()
{
  remove_menu_page('edit.php'); // 投稿.
}
add_action('admin_menu', 'remove_menus', 999);



//filter: manage_edit-{$taxonomy}_columns
function custom_column_header($columns)
{
  $columns['terms_order'] = '順序';
  return $columns;
}
add_filter("manage_edit-products-cat_columns", 'custom_column_header', 10);

//filter: manage_{$taxonomy}_custom_column
function custom_column_content($value, $column_name, $tax_id)
{
  if ($column_name === 'terms_order') {
    $term_icon = get_term_meta($tax_id, 'terms_order', true);
    if ($term_icon)
      echo $term_icon;
  }
}
add_action("manage_products-cat_custom_column", 'custom_column_content', 10, 3);



//カスタム投稿アーカイブページの並び順を変更
function change_posts_per_page($query)
{

  //管理画面,メインクエリに干渉しないために必須
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  // //カスタム投稿「members」アーカイブページの表示件数を10件、ふりがなの昇順でソート
  // if ( $query->is_post_type_archive( 'pokemon' ) //membersのアーカイブページか、もしくは
  //     ||
  //      $query->is_tax() ) //カスタム分類のアーカイブページが表示されているか
  //     {
  $query->set('posts_per_page', '10'); //1ページ最大10件
  $query->set('orderby', 'slug'); //meta_valueの値で並べる
  $query->set('order', 'ASC'); //昇順
  // return;
  // }
}
add_action('pre_get_posts', 'change_posts_per_page'); //pre_get_postsでメインクエリが実行される前のクエリを書き換える


function generate_js_params()
{
?>
  <script>
    let ajaxUrl = '<?php echo esc_html(admin_url('admin-ajax.php')); ?>';
  </script>
<?php
}
add_action('wp_head', 'generate_js_params');


function ajax_sample()
{

  $db_slugs["db"]  = $_POST['db_slugs'];
  foreach ($db_slugs["db"] as $index => $db_slug) {
    if ($json = @file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $db_slug)) {
      $b = json_decode($json, true);
      $pokemon_image[$index]['url'] = $b["sprites"]["front_default"];
      $pokemon_image[$index]['db_slug'] = $db_slug;
    } else {
      $pokemon_image[$index]['url'] = "";
      $pokemon_image[$index]['db_slug'] = $db_slug;
    }
  }
  header("Content-type: application/json; charset=UTF-8");
  echo json_encode($pokemon_image);

  wp_die();
}

add_action('wp_ajax_my_ajax', 'ajax_sample');
add_action('wp_ajax_nopriv_my_ajax', 'ajax_sample');
