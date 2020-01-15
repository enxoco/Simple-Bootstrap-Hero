<?php define('WP_USE_THEMES', false);?>

<?php require('../../../wp-blog-header.php');?>
<?php header("Content-type: text/css");?>

<?php
$heading_color = get_theme_mod('heading_color_picker');
$customcss = "
    body{
        background: {$heading_color}
    }
";

echo $customcss;