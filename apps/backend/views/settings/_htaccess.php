<?php declare(strict_types=1);
if (!defined('MW_PATH')) {
    exit('No direct script access allowed');
}

/**
 * This file is part of the MailWizz EMA application.
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.0
 */

/** @var Controller $controller */
$controller = controller();

/** @var string $baseUrl */
$baseUrl = (string)$controller->getData('baseUrl');

/** @var array $webApps */
$webApps = (array)$controller->getData('webApps');

?>
# BEGIN rewrite rules
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteBase <?php echo html_encode((string)$baseUrl); ?>
    
    
    <?php foreach ($webApps as $app) { ?>
# <?php echo strtoupper((string)$app); ?> APP
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} !.*\.(ico|gif|jpg|jpeg|png|js|css)
    RewriteCond %{REQUEST_URI} ^<?php echo html_encode((string)$baseUrl); ?><?php echo html_encode((string)$app); ?>(/.*)?$
    RewriteRule <?php echo html_encode((string)$app); ?>/.* <?php echo html_encode((string)$app); ?>/index.php
    
    <?php } ?>

    # FRONTEND APP
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} !.*\.(ico|gif|jpg|jpeg|png|js|css)
    RewriteRule . index.php
</IfModule>
# END rewrite rules

# ----------------------------------------------------------------------
# CORS-enabled images (@crossorigin)
# ----------------------------------------------------------------------
# Send CORS headers if browsers request them; enabled by default for images.
# developer.mozilla.org/en/CORS_Enabled_Image
# blog.chromium.org/2011/07/using-cross-domain-images-in-webgl-and.html
# hacks.mozilla.org/2011/11/using-cors-to-load-webgl-textures-from-cross-domain-images/
# wiki.mozilla.org/Security/Reviews/crossoriginAttribute
<IfModule mod_setenvif.c>
  <IfModule mod_headers.c>
    # mod_headers, y u no match by Content-Type?!
    <FilesMatch "\.(gif|png|jpe?g|svg|svgz|ico|webp)$">
      SetEnvIf Origin ":" IS_CORS
      Header set Access-Control-Allow-Origin "*" env=IS_CORS
    </FilesMatch>
  </IfModule>
</IfModule>
# ----------------------------------------------------------------------
# Webfont access
# ----------------------------------------------------------------------
# Allow access from all domains for webfonts.
# Alternatively you could only whitelist your
# subdomains like "subdomain.example.com".
<IfModule mod_headers.c>
  <FilesMatch "\.(ttf|ttc|otf|eot|woff?|woff2|font.css|css)$">
    Header set Access-Control-Allow-Origin "*"
  </FilesMatch>
</IfModule>