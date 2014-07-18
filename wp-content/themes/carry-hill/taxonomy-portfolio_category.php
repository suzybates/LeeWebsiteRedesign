<?php
/**
 * The template for displaying Category pages.
 *
 * @package WordPress
 * @subpackage Carry Hill
 */
// get specific tags only for items listed here
$tagsArr = array();
if (have_posts()) : while (have_posts()) : the_post();
        $tags = get_the_terms(get_the_ID(), 'portfolio_tag');
        if ($tags) {
            foreach ($tags as $tag) {
                $tagsArr[$tag->slug] = $tag;
            }
        }

    endwhile;
endif;
wp_reset_query();
?>

<?php get_header(); ?>
<?php get_template_part('header-bottom'); ?>

<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
    <?php if (count($tagsArr)): ?>
        <div class="cbp-container">
            <div class="one whole double-padded">
                <div class="filter cbp_widget_link" data-filter="all"><?php echo ChtUtils::translate('All'); ?></div>
                <?php foreach ($tagsArr as $tag): ?>
                    <div class="filter cbp_widget_link" data-filter="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="cbp-container">
        <div class="one whole double-padded">
            <?php if (have_posts()): ?>
                <?php if (category_description()): // Show an optional category description    ?>
                    <div class="ch-border-title"><?php echo category_description(); ?></div>
                <?php endif; ?>
                <div class="ch-gallery">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php $tags      = get_the_terms(get_the_ID(), 'portfolio_tag'); ?>
                        <?php $tagsClass = ''; ?>
                        <?php if ($tags) : ?>
                            <?php foreach ($tags as $tag) : ?>
                                <?php $tagsClass .= ' ' . $tag->slug; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="ch-gallery-item mix one fourth <?php echo $tagsClass; ?> double-pad-right">
                            <div class="ch-gallery-item-overlay">
                                <a href="<?php the_permalink(); ?>" class="ch-gallery-item-link"><i class="fa fa-link"></i></a>
                                <?php $get_the_image_as_array = cbp_get_the_image(array('format' => 'array', 'size'   => 'full')); ?>
                                <?php if ($get_the_image_as_array): ?>
                                    <a class="ch-gallery-item-link-lightbox" href="<?php echo $get_the_image_as_array['src']; ?>" data-lightbox="gallery" title="<?php the_title(); ?>"><i class="fa fa-search-plus"></i></a>
                                <?php endif; ?>
                            </div>
                            <?php cbp_get_the_image(array('link_to_post' => false, 'size'         => 'ch-gallery-thumb-fourth', 'image_class'  => 'ch-portfolio-image')); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <?php get_template_part('content', 'none'); ?>
            <?php endif; ?>
            <?php ChtUtils::pagination(); ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
