<?php
add_action('wp_enqueue_scripts', function () {
    $version = '5.2.3';
    $uri = get_template_directory_uri();

    wp_enqueue_style(
            'mon-bootstrap',
            $uri . '/assets/vendor/bootstrap-5.2.3-dist/css/bootstrap.css',
            [],
            $version
    );

    wp_enqueue_style(
            'mon-style',
            $uri . '/assets/css/styles.css',
            ['mon-bootstrap'],
            $version
    );

    wp_enqueue_script(
            'mon-bootstrap-js',
            $uri . '/assets/vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js',
            [], // pas de dépendances
            $version,
            true         // chargement dans le footer
    );
});

// Déclarer les emplacements de menu
add_action('after_setup_theme', function () {
    register_nav_menus([
        'menu-principal' => 'Menu principal du haut',
    ]);
});

add_theme_support('post-thumbnails');

// Alias : [oeuvres_lumineuses limit="30" orderby="date" order="DESC"]
add_shortcode('oeuvres_lumineuses', function ($atts = []) {
    $atts = shortcode_atts([
        'limit' => 30,
        'orderby' => 'date',
        'order' => 'DESC',
            ], $atts, 'oeuvres_lumineuses');

    return do_shortcode(sprintf(
                    '[oeuvre category="oeuvres-lumineuses" limit="%d" orderby="%s" order="%s"]',
                    (int) $atts['limit'],
                    esc_attr($atts['orderby']),
                    esc_attr($atts['order'])
            ));
});

// [oeuvres_lumineuses_full limit="30" orderby="date" order="DESC"]
add_shortcode('galerie', function ($atts) {
    $atts = shortcode_atts([
        'category' => null, // slug de la catégorie
        'limit' => 30, // nombre d’articles à afficher
        'orderby' => 'date',
        'order' => 'DESC',
            ], $atts, 'galerie');

    $cat_slug = sanitize_title($atts['category']);
    $limit = absint($atts['limit']);
    $orderby = sanitize_text_field($atts['orderby']);
    $order = sanitize_text_field($atts['order']);

    $args = [
        'post_type' => 'post',
        'posts_per_page' => $limit,
            // 'orderby'             => $orderby,
            //'order'               => $order,
            // 'ignore_sticky_posts' => true,
    ];
    if (isset($cat_slug)) {
        $args ['category_name'] = $cat_slug;
    }
    // Requête WordPress
    $q = new WP_Query($args);

    ob_start();

    if ($q->have_posts()) {
        ?>
        <div class="container py-5">
            <div class="row g-4 justify-content-center">
                <?php
                while ($q->have_posts()) {
                    $q->the_post();
                    ?>

                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 text-center" id="post-<?php the_ID(); ?>">
            <?php if (has_post_thumbnail()) { ?>
                            <a href="<?php the_permalink(); ?>">
                                <img
                                    class="img-fixed img-fluid rounded shadow-sm"
                                    src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>"
                                    alt="<?php the_title_attribute(); ?>"
                                    loading="lazy"
                                    />
                            </a>
            <?php } ?>

                        <h2 class="text-center mt-3">
                            <a href="<?php the_permalink(); ?>" class="text-white text-decoration-none">
            <?php the_title(); ?>
                            </a>
                        </h2>
                    </div>

        <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <p class="text-muted">Aucun article trouvé.</p>
        <?php
    }

    wp_reset_postdata();
    return ob_get_clean();
});

// Optionnel : pour "Ma démarche", un shortcode stylé pour ton texte libre
// Usage: [demarche]Ton texte ici...[/demarche]
/* add_shortcode('demarche', function ($atts, $content = null) {
  $content = do_shortcode($content ?? '');
  return '<div class="demarche-lead">' . wpautop($content) . '</div>';
  });
 */
/*
  add_shortcode('the_coucou', function ($atts = []) {
  $atts = shortcode_atts([
  'texte'   => 'Ha que coucou',

  ], $atts, 'the_coucou');

  return $atts['texte'];
  });
 */

// [slider_random limit="3" interval="5000" height="600" category="" post_type="post" controls="false" ids="" images="" image_size="large" link="post"]
add_shortcode('slider_random', function ($atts) {
    $a = shortcode_atts([
        'limit' => 3, // nb d’items si aléatoire
        'interval' => 5000, // ms entre slides
        'height' => 600, // hauteur en px
        'category' => '', // slug de catégorie (pour posts)
        'post_type' => 'post', // type de contenu (pour posts)
        'controls' => 'false', // conservé pour compat, pas utilisé ici
        'ids' => '', // liste d'IDs de posts (image à la une)
        'images' => '', // liste d'IDs de médias (médiathèque)
        'image_size' => 'large', // thumbnail, medium, large, full, etc.
        'link' => 'post', // "post" | "none"
            ], $atts, 'slider_random');

    // Sanitize / types
    $limit = max(1, (int) $a['limit']);
    $interval = max(0, (int) $a['interval']);
    $height = max(100, (int) $a['height']);
    $category = $a['category'];
    $posttype = sanitize_key($a['post_type']);
    $imageSize = sanitize_key($a['image_size']);
    $linkMode = in_array($a['link'], ['post', 'none'], true) ? $a['link'] : 'post';

    $ids_posts = array_filter(array_map('absint', array_filter(array_map('trim', explode(',', (string) $a['ids'])))));
    $ids_media = array_filter(array_map('absint', array_filter(array_map('trim', explode(',', (string) $a['images'])))));

    $items = [];

    // 1) MODE "images" (IDs de médias)
    if (!empty($ids_media)) {
        $attachments = get_posts([
            'post_type' => 'attachment',
            'post__in' => $ids_media,
            'orderby' => 'post__in',
            'posts_per_page' => count($ids_media),
        ]);
        foreach ($attachments as $att) {
            $src = wp_get_attachment_image_url($att->ID, $imageSize);
            if (!$src) {
                continue;
            }
            $items[] = [
                'img' => $src,
                'alt' => get_post_meta($att->ID, '_wp_attachment_image_alt', true) ?: get_the_title($att->ID),
                'url' => '',
                'caption' => '', // pas de caption affichée
                'type' => 'media',
            ];
        }

        // 2) MODE "ids" (IDs de posts)
    } elseif (!empty($ids_posts)) {
        $q = new WP_Query([
            'post_type' => $posttype,
            'post__in' => $ids_posts,
            'orderby' => 'post__in',
            'posts_per_page' => count($ids_posts),
            'ignore_sticky_posts' => true,
        ]);
        while ($q->have_posts()) {
            $q->the_post();
            if (!has_post_thumbnail()) {
                continue;
            }
            $thumb_id = get_post_thumbnail_id();
            $src = wp_get_attachment_image_url($thumb_id, $imageSize);
            if (!$src) {
                continue;
            }
            $items[] = [
                'img' => $src,
                'alt' => get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: get_the_title(),
                'url' => ('post' === $linkMode ? get_permalink() : ''),
                'caption' => '', // on n'affiche pas le titre
                'type' => 'post',
            ];
        }
        wp_reset_postdata();

        // 3) MODE "aléatoire" d’origine (posts)
    } else {
        $args = [
            'post_type' => $posttype,
            'posts_per_page' => $limit,
            'orderby' => 'rand',
            'ignore_sticky_posts' => true,
        ];
        if (!empty($category)) {
            // Permet plusieurs slugs séparés par des virgules ou espaces
            $cats = array_filter(array_map('trim', explode(',', $category)));
            if (!empty($cats)) {
                $args['tax_query'] = [[
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $cats,
                'operator' => 'AND', // au moins une des catégories
                ]];
            }
        }

        $q = new WP_Query($args);
        while ($q->have_posts()) {
            $q->the_post();
            if (!has_post_thumbnail()) {
                continue;
            }
            $thumb_id = get_post_thumbnail_id();
            $src = wp_get_attachment_image_url($thumb_id, $imageSize);
            if (!$src) {
                continue;
            }
            $items[] = [
                'img' => $src,
                'alt' => get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: get_the_title(),
                'url' => ('post' === $linkMode ? get_permalink() : ''),
                'caption' => '', // pas de caption
                'type' => 'post',
            ];
        }
        wp_reset_postdata();
    }

    if (empty($items)) {
        return '';
    }

    // ID unique si plusieurs sliders sur la page
    $carousel_id = 'homeCarousel_' . wp_generate_uuid4();

    ob_start();
    ?>
    <div class="container-fluid px-0">
        <div id="<?php echo esc_attr($carousel_id); ?>"
             class="carousel slide mb-5"
             data-bs-ride="carousel"
             data-bs-interval="<?php echo esc_attr($interval); ?>">

            <div class="carousel-inner">
                        <?php foreach ($items as $i => $it): ?>
                    <div class="carousel-item <?php echo ($i === 0) ? 'active' : ''; ?>">
                        <div class="position-relative w-100" style="height: <?php echo esc_attr($height); ?>px; overflow: hidden;">
                            <?php
                            $img_src = !empty($it['img']) ? $it['img'] : 'https://via.placeholder.com/1920x' . (int) $height . '?text=Sans+image';
                            $alt = !empty($it['alt']) ? $it['alt'] : '';
                            $img_tag = '<img src="' . esc_url($img_src) . '" class="d-block w-100" style="object-fit:cover; height:' . (int) $height . 'px;" alt="' . esc_attr($alt) . '" loading="lazy">';

                            if (!empty($it['url'])) {
                                echo '<a href="' . esc_url($it['url']) . '">' . $img_tag . '</a>';
                            } else {
                                echo $img_tag;
                            }
                            ?>
                        </div>
        <?php /* pas de caption, pas de contrôles, pas d'indicateurs */ ?>
                    </div>
    <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
});

