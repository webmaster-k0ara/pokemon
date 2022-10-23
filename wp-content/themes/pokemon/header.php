<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <?php wp_enqueue_script('jquery'); ?>
  <?php wp_head(); ?>
</head>

<body>
  <header>
    <div class="logo"><a href=""></a></div>
    <?php wp_nav_menu(
      array(
        //カスタムメニュー名
        'theme_location' => 'header-navi',
        //コンテナを表示しない
        'container' => false,
        //カスタムメニューを設定しない際に固定ページでメニューを作成しない
        'fallback_cb' => false,
        //出力されるulに対してidやclassを表示しない
        'items_wrap' => '<ul>%3$s</ul>',
      )
    ); ?>
    </nav>
  </header>
