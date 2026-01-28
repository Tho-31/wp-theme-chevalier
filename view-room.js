(function($){
  function clamp(n, a, b){ return Math.max(a, Math.min(b, n)); }

  function initModal($modal){
    const $stage = $modal.find('.vr-stage');
    const $art   = $modal.find('.vr-art');

    if (!$stage.length || !$art.length) return;

    const artURL = $stage.data('artimg');
    const artWcm = parseFloat($stage.data('artw')) || 80;
    const artHcm = parseFloat($stage.data('arth')) || 60;

    if (!artURL) {
      console.warn('view-room: pas d’URL d’image (data-artimg vide).');
      return;
    }

    // charge l’œuvre
    $art.attr('src', artURL);

    // état
    let scale = 1;
    let rot   = 0;
    let pos   = { x: 0.5, y: 0.5 }; // en proportions 0..1

    function apply(){
      const wallWcm = clamp(parseFloat($modal.find('.vr-wall-width').val()) || 300, 50, 2000);
      const wallHcm = clamp(parseFloat($modal.find('.vr-wall-height').val()) || 250, 50, 2000);

      const stageW = $stage.width();
      const stageH = $stage.height();

      const pxPerCmW = stageW / wallWcm;
      const pxPerCmH = stageH / wallHcm;
      const pxPerCm  = Math.min(pxPerCmW, pxPerCmH);

      const targetWpx = artWcm * pxPerCm * scale;
      const targetHpx = artHcm * pxPerCm * scale;

      $art.css({
        width:  targetWpx + 'px',
        height: targetHpx + 'px',
        left:   (pos.x * 100) + '%',
        top:    (pos.y * 100) + '%',
        transform: `translate(-50%, -50%) rotate(${rot}deg)`
      });
    }

    // upload photo → fond
    $modal.find('.vr-file').off('change.vr').on('change.vr', function(e){
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function(ev){
        $stage.css('background-image', `url(${ev.target.result})`);
      };
      reader.readAsDataURL(file);
    });

    // sliders / inputs
    $modal.find('.vr-wall-width, .vr-wall-height').off('input.vr').on('input.vr', apply);
    $modal.find('.vr-rotate').off('input.vr').on('input.vr', function(){
      rot = parseFloat(this.value) || 0;
      apply();
    });
    $modal.find('.vr-reset').off('click.vr').on('click.vr', function(){
      scale = 1; rot = 0; pos = {x:.5, y:.5};
      $modal.find('.vr-rotate').val(0);
      apply();
    });

    // drag
    let dragging = false;
    let start = {x:0, y:0};
    $art.off('pointerdown.vr').on('pointerdown.vr', function(ev){
      dragging = true;
      start = {x: ev.clientX, y: ev.clientY};
      $art[0].setPointerCapture(ev.pointerId);
      ev.preventDefault();
    });
    $(document).off('pointermove.vr').on('pointermove.vr', function(ev){
      if (!dragging) return;
      const dx = ev.clientX - start.x;
      const dy = ev.clientY - start.y;
      start = {x: ev.clientX, y: ev.clientY};

      const stageW = $stage.width();
      const stageH = $stage.height();

      pos.x = clamp(pos.x + dx / stageW, 0.05, 0.95);
      pos.y = clamp(pos.y + dy / stageH, 0.05, 0.95);
      apply();
    });
    $(document).off('pointerup.vr').on('pointerup.vr', function(){
      dragging = false;
    });

    // zoom molette
    $stage.off('wheel.vr').on('wheel.vr', function(ev){
      ev.preventDefault();
      const delta = ev.originalEvent.deltaY;
      const factor = (delta > 0) ? 0.95 : 1.05;
      scale = clamp(scale * factor, 0.2, 5);
      apply();
    });

    // pinch zoom (mobile)
    let pinch = {active:false, dist:0};
    $stage.off('touchstart.vr').on('touchstart.vr', function(ev){
      if (ev.originalEvent.touches.length === 2) {
        pinch.active = true;
        const [t1, t2] = ev.originalEvent.touches;
        pinch.dist = Math.hypot(t2.clientX - t1.clientX, t2.clientY - t1.clientY);
      }
    });
    $stage.off('touchmove.vr').on('touchmove.vr', function(ev){
      if (!pinch.active || ev.originalEvent.touches.length !== 2) return;
      const [t1, t2] = ev.originalEvent.touches;
      const d = Math.hypot(t2.clientX - t1.clientX, t2.clientY - t1.clientY);
      const factor = d / (pinch.dist || d);
      pinch.dist = d;
      scale = clamp(scale * factor, 0.2, 5);
      apply();
    });
    $stage.off('touchend.vr touchcancel.vr').on('touchend.vr touchcancel.vr', function(){
      pinch.active = false;
    });

    // première mise à l’échelle
    apply();
  }

  // ouverture / fermeture de la modale
  $(document).on('click', '.view-room .vr-open', function(){
    const $modal = $(this).closest('.view-room').find('.vr-modal');
    $modal.attr('aria-hidden', 'false');
    initModal($modal);
  });

  $(document).on('click', '.view-room .vr-close', function(){
    $(this).closest('.vr-modal').attr('aria-hidden', 'true');
  });

})(jQuery);
