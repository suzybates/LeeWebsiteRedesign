<?php
/**
 * The template for displaying Category pages.
 *
 * @package WordPress
 * @subpackage Glide
 */
$args = array(
    'orderby'  => 'name',
    'order'    => 'ASC',
    'taxonomy' => 'portfolio_tag',
);
$tags      = get_categories($args);
?>

<div class="row double-pad-top">
    <?php foreach ($tags as $tag): ?>
        <div class="filter cbp_widget_link" data-filter="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></div>
    <?php endforeach; ?>
</div>
<div class="row double-pad-top">
    <?php if (have_posts()): ?>
        <?php if (category_description()): // Show an optional category description   ?>
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
                        <a href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
                        <?php $get_the_image_as_array = cbp_get_the_image(array('format' => 'array', 'size'   => 'full')); ?>
                        <?php if ($get_the_image_as_array): ?>
                            <a href="<?php echo $get_the_image_as_array['src']; ?>" data-lightbox="gallery" title="<?php the_title(); ?>"><i class="fa fa-eye"></i></a>
                        <?php endif; ?>
                    </div>
                    <?php cbp_get_the_image(array('link_to_post' => false)); ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <?php get_template_part('content', 'none'); ?>
    <?php endif; ?>
    <div class="row double-pad-top">

        <?php ChtUtils::pagination(); ?>
    </div>
</div>
