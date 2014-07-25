<?php

class ChpWidgetPortfolioArchive extends CbpWidget
{
 
    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_portfolio_archive',
                /* Name */ 'Portfolio Archive', array('description' => '', 'icon'        => 'fa fa-picture-o fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements['css_class']       = '';
        $elements['items_per_page']  = 3;
        $elements['number_of_posts'] = 3;
        $elements['order']           = 'name';


        return $elements;
    }

    public function form($instance)
    {
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('css_class'),
            'value'             => $instance['css_class'],
            'description_title' => $this->translate('CSS Class'),
            'description_body'  => $this->translate('This class/classes will be set to the container element of this text block in case you need to add extra styling.'),
        ));

        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('items_per_page'),
            'value'             => $instance['items_per_page'],
            'description_title' => $this->translate('Items Per Page'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('number_of_posts'),
            'value'             => $instance['number_of_posts'],
            'description_title' => $this->translate('Number of Posts per Category'),
        ));

        CbpWidgetFormElements::select(array(
            'options' => array(
                'name'              => $this->translate('Name'),
                'rand'              => $this->translate('Random')
            ),
            'name'              => $this->getIdString('order'),
            'value'             => $instance['order'],
            'description_title' => $this->translate('Order'),
        ));
    }

    public function widget($atts, $content)
    {
        extract(shortcode_atts(array(
                    'type'            => '',
                    'css_class'       => '',
                    'items_per_page'  => 3,
                    'number_of_posts' => 3,
                    'order'           => 'name',
                        ), $atts));

        $css_class = !empty($css_class) ? ' ' . $css_class : '';
        global $paged;
        if (empty($paged))
            $paged     = 1;

        $per_page         = $items_per_page;
        $number_of_series = count(get_terms('portfolio_category'));
        $pages            = ceil($number_of_series / $per_page);
        $offset           = $per_page * ( $paged - 1);


        $args = array(
            'number'    => $per_page,
            'orderby'   => $order,
            'order'     => 'ASC',
            'taxonomy'  => 'portfolio_category',
            'offset'    => $offset
        );
        $categories = get_categories($args);
        ?>


        <div class="container">
            <?php foreach ($categories as $category): ?>
                <div class="one whole">
                    <h2><a href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a></h2>
                    <?php if ($category->description): // Show an optional category description    ?>
                        <div class="ch-border-title"><?php echo $category->description; ?></div>
                    <?php endif; ?>
                    <?php
                    $args = array(
                        'post_type'   => 'portfolio',
                        'numberposts' => $number_of_posts,
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
                        <?php $imgArgs = array('post_id' => $portfolio->ID); ?>
                        <div class="one third double-gap-top">
                            <?php cbp_get_the_image($imgArgs); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="one whole double-pad-top">
                    <a class="cbp_widget_link" href="<?php echo get_term_link($category); ?>"><?php echo ChtUtils::translate('More Photos'); ?></a>

                </div>
            <?php endforeach; ?>
        </div>
        <div class="container">
            <div class="ch-pagination one whole double-pad-top">
                <?php ChtUtils::pagination($pages); ?>
            </div>
        </div>

        <?php
    }
}

CbpWidgets::registerWidget('ChpWidgetPortfolioArchive');