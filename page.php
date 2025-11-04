
<?php get_header(); ?>

    <?php include get_template_directory() . '/menu.php'; ?>

<main id="page" class="container py-5">
  <?php if (have_posts()) { while (have_posts()) { the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>

      <header class="text-center mb-4">
        <h1 class="display-5 mb-3"><?php the_title(); ?></h1>
        <?php if (has_post_thumbnail()) { ?>
          <div class="mb-4">
            <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded shadow-sm']); ?>
          </div>
        <?php } ?>
      </header>

      <div class="content fs-5 lh-lg mx-auto" style="max-width: 1100px;">
        <?php the_content(); ?>
      </div>

    </article>
  <?php } } else { ?>
    <p>Aucune page trouvée.</p>
  <?php } ?>
</main>




<?php get_footer(); ?>
