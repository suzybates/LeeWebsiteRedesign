<?php

class ChpWidgetTeamMemeber extends CbpWidget
{

    public function __construct()
    {
        parent::__construct(
                /* Base ID */'chp_widget_team_member',
                /* Name */ 'Team Member', array('description' => 'This is Team Member widget.', 'icon'        => 'fa fa-user fa-3x'));
    }

    public function registerFormElements($elements)
    {
        $elements['css_class']            = '';
        $elements['img_src']              = '';
        $elements['image_is_circle']      = '0';
        $elements['name']                 = '';
        $elements['link']                 = '';
        $elements['job_title']            = '';
        $elements['number_of_characters'] = 200;

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
        CbpWidgetFormElements::mediaUpload(array(
            'name'              => $this->getIdString('img_src'),
            'value'             => $instance['img_src'],
            'description_title' => $this->translate('Image'),
        ));
        CbpWidgetFormElements::select(array(
            'options' => array(
                '1'                 => $this->translate('Yes'),
                '0'                 => $this->translate('No')
            ),
            'name'              => $this->getIdString('image_is_circle'),
            'value'             => $instance['image_is_circle'],
            'description_title' => $this->translate('Make the image a circe?'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('name'),
            'value'             => $instance['name'],
            'description_title' => $this->translate('Name'),
            'description_body'  => $this->translate('Team member name.'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('link'),
            'value'             => $instance['link'],
            'description_title' => $this->translate('Link'),
            'description_body'  => $this->translate('If this is set image and name will be links. Please use absolute url.'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('job_title'),
            'value'             => $instance['job_title'],
            'description_title' => $this->translate('Job Title'),
            'description_body'  => $this->translate('Team member job title.'),
        ));
        CbpWidgetFormElements::tinyMce(array(
            'name'              => $this->getIdString('content'),
            'value'             => $instance['content'],
            'description_title' => $this->translate('Content'),
        ));
        CbpWidgetFormElements::text(array(
            'name'              => $this->getIdString('number_of_characters'),
            'value'             => $instance['number_of_characters'],
            'description_title' => $this->translate('Number Of Characters'),
        ));
        ?>

        <?php
    }

    public function sanitize(&$attribute)
    {
        switch ($attribute['name']) {
            case CBP_APP_PREFIX . 'name':
            case CBP_APP_PREFIX . 'job_title':
                $attribute['value'] = sanitize_text_field($attribute['value']);
                break;
            case CBP_APP_PREFIX . 'number_of_characters':
                if (!filter_var($attribute['value'], FILTER_SANITIZE_NUMBER_INT)) {
                    $attribute['value'] = 200;
                }
                break;
        }

        return parent::sanitize($attribute);
    }

    public function widget($atts, $content)
    {
        extract(shortcode_atts(array(
                    'type'                 => 'chp_widget_team_member',
                    'css_class'            => '',
                    'img_src'              => '',
                    'image_is_circle'      => '0',
                    'name'                 => '',
                    'link'                 => '',
                    'job_title'            => '',
                    'number_of_characters' => '',
                        ), $atts));


        $css_class = !empty($css_class) ? ' ' . $css_class : '';
        $imgClass  = (int) $image_is_circle ? 'round' : '';
        $image     = CbpWidgets::parseImageDetails($img_src);
        ?>


        <div class="<?php echo CbpWidgets::getCssClass('row'); ?> <?php echo $type; ?><?php echo $css_class; ?> double-gap-top double-gap-bottom">        

            <div class="one third small-tablet two-up-mobile double-pad-bottom">
                <span class="ch-team-member-img">
                    <?php if (!empty($link)): ?>
                        <a href="<?php echo $link; ?>"><img class="<?php echo $imgClass; ?>" src="<?php echo $image['selected_src']; ?>" alt="team member" /></a>
                    <?php else: ?>
                        <img class="<?php echo $imgClass; ?>" src="<?php echo $image['selected_src']; ?>" alt="team member" />
                    <?php endif; ?>
                </span>
            </div>

            <div class="two thirds align-left double-pad-left no-pad-small-tablet">

                <?php if (!empty($name)): ?>
                    <?php if (!empty($link)): ?>
                        <a href="<?php echo $link; ?>"><h2><?php echo $name; ?></h2></a>
                    <?php else: ?>
                        <h2><?php echo $name; ?></h2>
                    <?php endif; ?>

                <?php endif; ?>
                <hr />
                <?php if (!empty($job_title)): ?>
                    <h6><?php echo $job_title; ?></h6>
                <?php endif; ?>
                <?php echo CbpUtils::trimmer($content, $number_of_characters); ?>
            </div> 
        </div>

        <?php
    }
}

CbpWidgets::registerWidget('ChpWidgetTeamMemeber');