<?php

function a_la_queue_lele() {
    $bootstrap = get_template_directory_uri() . "/assets/vendor/bootstrap-5.2.3-dist/css/bootstrap.css";
    wp_enqueue_style(
        'mon-bootstrap', 
       $bootstrap,
        [], 
        $bootstrap
    );
}


add_action('wp_enqueue_scripts', 'a_la_queue_lele');


add_action('wp_enqueue_scripts', function () {
    $bootstrap = get_template_directory_uri() . "/assets/css/styles.css";
    wp_enqueue_style(
        'mon-style', 
       $bootstrap,
        ['mon-bootstrap'], 
        $bootstrap
    );
});


