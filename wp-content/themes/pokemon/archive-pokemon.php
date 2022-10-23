<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article class="works-list__list-item">
      <a href="<?php the_permalink(); ?>" class="works-item">
        <div class="works-item__content">

          <h3 class="works-item__content-title"><?php the_title(); ?></h3>

        </div>
      </a>
      <?php
      global $post;
      $slug = $post->post_name;
      $slugs[] = $slug;
      ?>
      <div id="pokemon_img_<?= $slug ?>" class="pokemon_no_<?= $slug ?>">
        <img src="" alt="">
      </div>

    </article>
<?php endwhile;
endif;
wp_reset_postdata();
$db_slugs = json_encode($slugs);
?>


<div class="pagination">
  <?php echo paginate_links(array(
    'type' => 'list',
    'prev_text' => '«',
    'next_text' => '»'
  )); ?>
</div>

<script>
  jQuery(function($) {
        // $('#pokemon_img')
        // $('.pokemon_no_')
        let db_slugs = JSON.parse('<?php echo $db_slugs; ?>');
        console.log(db_slugs);

        function sendData() {
          $.ajax({
              type: "POST",
              // url: "./register_submit.php",
              url: ajaxUrl,
              data: {
                action: 'my_ajax',
                db_slugs: db_slugs,
              },
              dataType: 'json'
            })
            .done((data) => {
                console.log(data.length);
                for (let i = 0; i < data.length; i++) {
                  console.log(data[i]);
                  $(`#pokemon_img_${data[i].db_slug} img`).attr('src', data[i].url);
                  }

                  // $('.pokemon_no_')
                })
              .fail((error) => {
                alert("サーバー内でエラーがあったか、サーバーから応答がありませんでした。");
                console.log(error);
              });
            }

          sendData();



        });
</script>

<?php get_footer(); ?>
