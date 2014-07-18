<?php
/**
 * The template for displaying Archive pages.
 * 
 * @package WordPress
 * @subpackage Carry Hill
 */
get_header();
get_template_part('header-bottom');

global $paged;
if (empty($paged))
    $paged = 1;

$per_page         = 5;
$number_of_series = count(get_terms('portfolio_category'));
$pages            = ceil($number_of_series / $per_page);
$offset           = $per_page * ( $paged - 1);


$args = array(
    'number'    => $per_page,
    'orderby'   => 'name',
    'order'     => 'ASC',
    'taxonomy'  => 'portfolio_category',
    'offset'    => $offset
);
$categories = get_categories($args);
?>

<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
    <div class="cbp-container">
        <?php foreach ($categories as $category): ?>
            <div class="one whole double-padded">
                <h2><a href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a></h2>
                <?php if ($category->description): // Show an optional category description    ?>
                    <div class="ch-border-title"><?php echo $category->description; ?></div>
                <?php endif; ?>
                <?php
                $args = array(
                    'post_type'   => 'portfolio',
                    'numberposts' => 3,
                    'tax_query'   => array(
                        array(
                            'taxonomy'  => 'portfolio_category',
                            'field'     => 'slug',
                            'terms'     => $category->slug
                        )
                    )
                );
                ?> 
                <?php $portfolios = get_posts($args); ?>
                <?php foreach ($portfolios as $portfolio) : ?>
                    <?php $imgArgs = array('post_id'     => $portfolio->ID, 'size'        => 'ch-gallery-thumb-third', 'image_class' => 'ch-portfolio-image'); ?>
                    <div class="one third double-gap-top double-pad-right">
                        <?php cbp_get_the_image($imgArgs); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="one whole double-padded">
                <a class="cbp_widget_link" href="<?php echo get_term_link($category); ?>"><?php echo ChtUtils::translate('More Photos'); ?></a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="cbp-container">
        <div class="ch-pagination one whole double-padded">
            <?php ChtUtils::pagination($pages); ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>