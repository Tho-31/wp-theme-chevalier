<?php get_header(); ?>

<?php include get_template_directory() . '/menu.php'; ?>

<main id="main-content" class="container-fluid tv-home">
    
<?php
// 🔹 Slider minimal : 3 articles aléatoires
$slider_args = array(
    'category_name' => 'slider',
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'orderby'        => 'rand',
);

$slider_query = new WP_Query($slider_args);

if ($slider_query->have_posts()) { ?>
    <div id="homeCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <?php
            $i = 0;
            while ($slider_query->have_posts()) {
                $slider_query->the_post();
                $active_class = ($i === 0) ? 'active' : '';
                ?>
                <div class="carousel-item <?php echo $active_class; ?>">
                    <?php if (has_post_thumbnail()) { ?>
                        <?php the_post_thumbnail('full', [
                            'class' => 'd-block w-100',
                            'style' => 'object-fit:cover; height:600px;',
                        ]); ?>
                    <?php } else { ?>
                        <img src="https://via.placeholder.com/1920x600?text=Pas+d'image" 
                             class="d-block w-100" 
                             style="object-fit:cover; height:600px;" 
                             alt="Image manquante">
                    <?php } ?>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>

        <!-- Contrôles (facultatifs, tu peux les retirer si tu veux un vrai mode auto) -->
        <!-- button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button-->
    </div>
<?php }
wp_reset_postdata();
?>


    <div class="row">

        <h1 class="tangerine-bold text-center"> Thalye D'Oriam</h1>
    
    <?php
    // La requête pour récupérer 30 articles aléatoires
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 30,
        'orderby' => 'rand',
    );

    $random_query = new WP_Query($args);
    ?>

    <?php if ($random_query->have_posts()) { ?>
        
        <?php  while ($random_query->have_posts()) { ?>
            <?php 
            $random_query->the_post();
            ?>
        <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 text-center" id="post-<?php the_ID(); ?>">
                
                    <?php if (has_post_thumbnail()) { ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php //the_post_thumbnail('large', ['class' => 'img-fixed']); ?>
                            <img class="img-fixed" src="<?php echo get_the_post_thumbnail_url(null, 'large'); ?>"/>
                        </a>
       
                    <?php } ?>

                    <h2 class="text-center"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                  <?php /* <div class="entry-excerpt">
                        <?php the_excerpt(); ?>
                    </div>*/ 
                  ?>

            </div>
        <?php } ?>
    <?php } else { ?>
        <p>Aucun article trouvé.</p>
    <?php } ?>

    <?php        
    // Réinitialise la requête pour éviter les conflits
    wp_reset_postdata();
    ?>
    </div>
</main>

<?php
/*
$found = get_posts([
    'post_type'      => 'wp_block',
    'post_name'          => 'footer', // titre exact
    'posts_per_page' => 1,
]);
if ( $found ) {
    echo do_blocks( $found[0]->post_content );
}*/
?>





<?php get_footer(); ?>
