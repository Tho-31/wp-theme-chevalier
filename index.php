<?php get_header(); ?>

<?php include get_template_directory() . '/menu.php'; ?>

<main class="container py-5">
    <?php if (have_posts()) : ?>
        <div class="row g-4">
            <?php while (have_posts()) : the_post(); ?>
                <div class="col-md-4">
                    <article class="card h-100 bg-dark border-0">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', ['class' => 'card-img-top img-fixed']); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-body">
                            <h2 class="card-title h5">
                                <a href="<?php the_permalink(); ?>" class="text-white text-decoration-none">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                        </div>
                    </article>
                </div>
            <?php endwhile; ?>
        </div>

        <nav class="mt-5">
            <?php the_posts_pagination([
                'prev_text' => '&laquo; Précédent',
                'next_text' => 'Suivant &raquo;',
                'class' => 'pagination justify-content-center',
            ]); ?>
        </nav>
    <?php else : ?>
        <p class="text-center text-muted">Aucun contenu trouvé.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
