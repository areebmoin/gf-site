<?php
header('Content-type: text/xml');
echo('<?xml version="1.0" encoding="UTF-8"?>');
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
  xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">

  <?php for($i = 0; $i < count($urls); $i++) { ?>
  <url>
    <loc><?php echo(base_url().$urls[$i]); ?></loc>
  </url>
  <?php } ?>

</urlset>
