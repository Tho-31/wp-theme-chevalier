<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<?php get_header(); ?>
<?php include get_template_directory() . '/menu.php'; ?>
<main id="main-content" class="container py-5">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
                <header class="mb-4 text-center">
                    <h1 class="display-4 mb-3"><?php the_title(); ?></h1>

                    <?php if (has_post_thumbnail()) { ?>
                        <div class="mb-4">
                            <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded shadow']); ?>
                        </div>
                    <?php } ?>
                </header>

                <div class="post-meta text-muted small mb-4 text-center">
                    <?php
                    echo get_the_date('F j, Y'); // ex : Mai 2, 2025
                    echo ' — par <span class="text-white fw-semibold">' . get_the_author() . '</span>';

                    // Affiche la catégorie si elle existe
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo ' dans <span class="text-white fw-semibold">' . esc_html($categories[0]->name) . '</span>';
                    }
                    ?>
                </div>


                <div class="content fs-6 lh-sm mx-auto" style="max-width:800px;">
                    <?php the_content(); ?>
                </div>

            </article>

        <?php
        }
    } else {
        ?>
        <p>Aucun article trouvé.</p>
<?php } ?>
</main>

<?php get_footer(); ?>



