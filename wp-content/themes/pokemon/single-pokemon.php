<?php get_header(); ?>


<h2><?php the_title(); ?></h2>
<?php
global $post;
$slug = $post->post_name;
$slugs[] = $slug;
// $file = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $slug);

if ($json = @file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $slug)) {
  $b = json_decode($json, true);
  $pokemon_image = $b["sprites"]["front_default"];
} else {
  $pokemon_image = "";
  echo "このポケモンは存在しません。";
}

?>
<div class="pokemon_image">
  <img src="<?= $pokemon_image ?>" alt="">
</div>

<a href="<?php echo home_url('/pokemon'); ?>">一覧へ</a>


<?php get_footer(); ?>
