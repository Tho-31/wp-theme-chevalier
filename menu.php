<!-- HEADER / NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-noir py-3">
  <div class="container-fluid g-5">
    
    <!-- Logo à gauche -->
    <a class="navbar-brand d-flex align-items-center" href="<?php echo esc_url( home_url('/') ); ?>">
      <img
        src="http://nathalie2.loc/wp-content/uploads/2025/05/20241211_Nathalie_Charlier_36-scaled-1.jpg"
        alt="Thalye D’Oriam"
        class="logo-mini rounded me-2"
      >
      <span class="fw-semibold small tangerine-regular">Thalye D’Oriam</span>
    </a>

    <!-- Bouton burger pour mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu à droite -->
    <div class="collapse navbar-collapse justify-content-end" id="mainNav">
      <?php
      wp_nav_menu([
          'theme_location' => 'menu-principal',
          'container'      => false,
          'menu_class'     => 'navbar-nav gap-4 fw-semibold',
          'fallback_cb'    => false,
          'depth'          => 1,
      ]);
      ?>
    </div>

  </div>
</nav>
