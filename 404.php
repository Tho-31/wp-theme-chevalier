<?php get_header(); ?>

<?php include get_template_directory() . '/menu.php'; ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-1 fw-bold text-white">404</h1>
            <h2 class="mb-4">Page introuvable</h2>
            <p class="text-muted mb-4">
                La page que vous recherchez n'existe pas ou a été déplacée.
            </p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline-light">
                Retour à l'accueil
            </a>
        </div>
    </div>
</main>

<?php get_footer(); ?>
