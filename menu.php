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

    <!-- Menu + filtre -->
    <div class="collapse navbar-collapse justify-content-end align-items-center" id="mainNav">
      <?php
      wp_nav_menu([
          'theme_location' => 'menu-principal',
          'container'      => false,
          'menu_class'     => 'navbar-nav gap-4 fw-semibold',
          'fallback_cb'    => false,
          'depth'          => 1,
      ]);

      // === Ton bloc du filtre Format ===
      $all = get_terms([
        'taxonomy'   => 'category',
        'hide_empty' => false,
      ]);
      $formats = [];
      if (!is_wp_error($all)) {
        foreach ($all as $t) {
          if (strpos($t->slug, 'format-tableau-') === 0) {
            $formats[] = $t;
          }
        }
        usort($formats, function($a, $b){
          preg_match('/(\d+)$/', $a->slug, $ma);
          preg_match('/(\d+)$/', $b->slug, $mb);
          return intval($ma[1] ?? 0) <=> intval($mb[1] ?? 0);
        });
      }

      if (!empty($formats)) :
      ?>
        <div class="ms-3">
          <label class="visually-hidden" for="select-format">Format</label>
          <select id="select-format" class="form-select form-select-sm"
                  onchange="if(this.value){window.location.href=this.value;}">
            <option value="">Format</option>
            <?php foreach ($formats as $t): ?>
              <option value="<?php echo esc_url(get_term_link($t)); ?>">
                <?php echo esc_html($t->name); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php endif; ?> 
      <!-- ============================== -->

    </div>

  </div>
</nav>
