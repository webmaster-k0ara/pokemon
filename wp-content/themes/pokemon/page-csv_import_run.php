<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');

if (is_uploaded_file($_FILES["csv_file"]["tmp_name"])) {
  if (move_uploaded_file($_FILES["csv_file"]["tmp_name"], dirname(__FILE__) . "/csv/" . $_FILES["csv_file"]["name"])) {
    $upload_message = $_FILES["csv_file"]["name"] . "をアップロードしました。";
  } else {
    $upload_message = "ファイルをアップロードできません。";
  }
}

$filepath = __DIR__ . "/csv/";
// ファイル自体をUTF-8に変換
system('nkf --overwrite -w ' . $filepath);

$file = new SplFileObject($filepath . "pokemon_status.csv");
$file->setFlags(
  \SplFileObject::READ_CSV |
    \SplFileObject::READ_AHEAD |
    \SplFileObject::DROP_NEW_LINE |
    \SplFileObject::SKIP_EMPTY
);
$i = 0;
// ファイル取得
foreach ($file as $key => $line) {
  // ヘッダを読込
  if ($i === 0) {
    $csv_heads = $line;
    $csv_heads_key = array_flip($csv_heads);  // ←ここがポイント！！
    $head_count = count($csv_heads);
    $i++;
    continue;
  }
  // echo "<pre>";
  // print_r($line);
  // echo "</pre>";

      $args = array(
      'post_type'      => 'pokemon',
      'posts_per_page' => 1,
      'post_status'    => 'any',
      'name'    => $line[$csv_heads_key['no']],
    );
    $the_query = new WP_Query($args);
    if($the_query->have_posts()){
      while($the_query->have_posts()):$the_query->the_post();
        $target_post_id = $post->ID;
        // 更新処理
        $update_post = array(
          'ID' => $target_post_id,
          'post_title' => $line[$csv_heads_key['name']],
          'post_modified' => date('Y-m-d H:i:s'),
          'post_name'    => $line[$csv_heads_key['no']],
        );
        wp_update_post( $update_post );
      echo $line[$csv_heads_key['name']] . "の更新完了<br>";
      endwhile;
    } else {
      // 新規登録処理
      $insert_post = array(
        'post_type'      => 'pokemon',
        'post_title' => $line[$csv_heads_key['name']],
        'post_name'    => $line[$csv_heads_key['no']],
        'post_status'   => 'publish',
      );
      $target_post_id = wp_insert_post( $insert_post );
    echo $line[$csv_heads_key['name']] . "の新規作成完了<br>";
    }

  update_post_meta($target_post_id, 'type1', $line[$csv_heads_key['type1']]);
  update_post_meta($target_post_id, 'type2', $line[$csv_heads_key['type2']]);
  update_post_meta($target_post_id, 'spec1', $line[$csv_heads_key['spec1']]);
  update_post_meta($target_post_id, 'spec2', $line[$csv_heads_key['spec2']]);
  update_post_meta($target_post_id, 'dream_spec', $line[$csv_heads_key['dream_spec']]);
  update_post_meta($target_post_id, 'hp', $line[$csv_heads_key['hp']]);
  update_post_meta($target_post_id, 'ap', $line[$csv_heads_key['ap']]);
  update_post_meta($target_post_id, 'df', $line[$csv_heads_key['df']]);
  update_post_meta($target_post_id, 'sp', $line[$csv_heads_key['sp']]);
  update_post_meta($target_post_id, 'sdf', $line[$csv_heads_key['sdf']]);
  update_post_meta($target_post_id, 'spd', $line[$csv_heads_key['spd']]);
  update_post_meta($target_post_id, 'total', $line[$csv_heads_key['total']]);

  $i++;
}
