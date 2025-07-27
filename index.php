<?php include_once __DIR__ . '/config.php'; ?>
<?php include_once APP_ROOT . '/inc/header.php'; ?>

<section id="main" style="padding: 20px;">
  <div style="column-count: 4; column-gap: 15px;">
    <?php
      $images = glob(__DIR__ . "/images/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
      foreach($images as $img):
        $imgUrl = site_base('images/' . basename($img));
    ?>
      <div style="break-inside: avoid; margin-bottom: 15px;">
        <a href="<?= $imgUrl ?>" data-lightbox="galeria">
          <img src="<?= $imgUrl ?>" style="width: 100%; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include_once APP_ROOT . '/inc/footer.php'; ?>
