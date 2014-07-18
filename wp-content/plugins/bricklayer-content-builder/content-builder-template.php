<?php
/**
 * @package WordPress
 * @subpackage Content Builder Plugin
 *
 * Template Name: Content Builder
 */
?>

<?php if ($themeOverride): ?>
    <?php include_once 'header.php'; ?>
<?php else: ?>
    <?php get_header(); ?>
<?php endif; ?>

<?php if ($layout): ?>
    <?php echo do_shortcode(str_replace(array('<p>[', ']</p>'), array('[', ']'), $layout->post_content)); ?>
<?php endif; ?>

<?php if ($backgroundImage): ?> 
    <script>
        cbp_content_builder.data.bgImage = '<?php echo $backgroundImage; ?>';
    </script>
<?php endif; ?>
<?php if ($themeOverride): ?>
    <?php include_once 'footer.php'; ?>
<?php else: ?>
    <?php get_footer(); ?>
<?php endif; ?>
<?php wp_reset_query(); ?>


