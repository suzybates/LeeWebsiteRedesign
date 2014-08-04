<?php
if (!class_exists( 'CbpWidgetHelper' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpWidgetHelper extends WP_Widget
{
    protected $_attrs;

    public function update($newInstance, $oldInstance)
    {
        return $this->walk($newInstance, 'filterStripTags');
    }

    protected function walk($arr, $filter)
    {
        array_walk($arr, array($this, $filter));
        return $this->getMergedAttrs($arr);
    }

    protected function filterWidgetTitle(&$value, $key)
    {
        apply_filters('widget_title', $value);
    }

    protected function filterStripTags(&$value, $key)
    {
        strip_tags($value);
    }

    protected function getMergedAttrs($instance)
    {
        return wp_parse_args((array) $instance, $this->_attrs);
    }
    
    protected function translate($string)
    {
        return CbpTranslate::translateString($string);
    }

    protected function formElementText($name, $value, $description = null)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate(CbpUtils::titleize($name)); ?>:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id($name); ?>" name="<?php echo $this->get_field_name($name); ?>" type="text" value="<?php echo $value; ?>" />
            <?php if ($description): ?>
                <em><?php echo $description; ?></em>
            <?php endif; ?>
        </p>
        <?php
    }

    protected function formElementTextarea($name, $value, $rows = 10)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate(CbpUtils::titleize($name)); ?>:</label> 
            <textarea rows="<?php echo $rows; ?>" class="widefat" id="<?php echo $this->get_field_id($name); ?>" name="<?php echo $this->get_field_name($name); ?>"><?php echo $value; ?></textarea>
        </p>
        <?php
    }

    protected function formElementCheckboks($name, $value)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate(CbpUtils::titleize($name)); ?>:</label> 
            <input type="checkbox" id="<?php echo $this->get_field_id($name); ?>" name="<?php echo $this->get_field_name($name); ?>" <?php checked((bool) $value); ?>>
        </p>
        <?php
    }

    protected function formElementSelect($name, $options, $selectedOption, $desc)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate($desc); ?>:</label>
            <select name="<?php echo $this->get_field_name($name); ?>">
                <?php foreach ($options as $value): ?>
                    <option <?php echo CbpUtils::maybeSelected($value == $selectedOption) ?> value="<?php echo $value; ?>" title="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    protected function formElementSelectSinglePost($name, $selectedOption, $desc)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate($desc); ?>:</label> 
            <!--The Query-->
            <?php query_posts('posts_per_page=-1&orderby=title&order=ASC'); ?>
            <?php global $post; ?>
            <select name="<?php echo $this->get_field_name($name); ?>">
                <?php while (have_posts()) : the_post() ?>
                    <option <?php echo CbpUtils::maybeSelected($post->ID == $selectedOption); ?> value="<?php echo $post->ID; ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
                <?php endwhile; ?>
            </select>
            <!-- Reset Query-->
            <?php wp_reset_query(); ?>
        </p>
        <?php
    }

    protected function formElementSelectCategory($name, $selectedOption, $desc, $multiple = false)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate($desc); ?>:</label> 
            <?php $cats = get_categories(); ?>
            <select name="<?php echo $this->get_field_name($name); ?>" <?php echo $multiple ? 'multiple="multiple"' : ''; ?>>
                <?php foreach ($cats as $category): ?>
                    <option <?php echo CbpUtils::maybeSelected($category->cat_ID == $selectedOption); ?> value="<?php echo $category->cat_ID; ?>"><?php echo $category->name; ?></option>
                <?php endforeach; ?>
            </select>            
        </p>
        <?php
    }

    protected function formElementSelectPostLinks($name, $selectedOption, $desc)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate($desc); ?>:</label>             
            
            <?php query_posts('posts_per_page=-1&orderby=title&order=ASC'); ?>
            <?php global $post; ?>
            <select name="<?php echo $this->get_field_name($name); ?>">
                <?php while (have_posts()) : the_post() ?>
                    <option <?php echo CbpUtils::maybeSelected(get_permalink($post->ID) == $selectedOption); ?> value="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
                <?php endwhile; ?>
            </select>
            <!-- Reset Query-->
            <?php wp_reset_query(); ?>
        </p>
        <?php
    }
    
    protected function formElementSelectPageLinks($name, $selectedOption, $desc)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate($desc); ?>:</label> 
            <select name="<?php echo $this->get_field_name($name); ?>"> 
                <option value=""><?php echo esc_attr(__('Select page', THEME_TEXT_DOMAIN)); ?></option> 
                <?php $pages = get_pages(); ?>
                <?php foreach ($pages as $page): ?>
                    <option <?php echo CbpUtils::maybeSelected(get_page_link($page->ID) == $selectedOption); ?> value="<?php echo get_page_link($page->ID); ?>"><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    protected function formElementSelectPages($name, $selectedOption, $desc)
    {
       ?>
        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $this->translate($desc); ?>:</label> 
            <select name="<?php echo $this->get_field_name($name); ?>"> 
                <option value=""><?php echo esc_attr(__('Select page', THEME_TEXT_DOMAIN)); ?></option> 
                <?php $pages = get_pages(); ?>
                <?php foreach ($pages as $page): ?>
                    <option <?php echo CbpUtils::maybeSelected($page->ID == $selectedOption); ?> value="<?php echo get_page_link($page->ID); ?>"><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    protected function colorPicker($options, $alpha = false)
    {
        ?>
        <label for="use-spectrum"><?php echo $options['description']; ?>:</label>
        <input type="text" name="<?php echo $this->get_field_name($options['name']); ?>" id="<?php echo $this->get_field_id($options['name']); ?>"/>
        <br />

        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready(function($)
            {     
                        
                $('#<?php echo $this->get_field_id($options['name']); ?>').spectrum('destroy').spectrum({
                    preferredFormat: "<?php echo $alpha ? 'rgb' : 'hex'; ?>",
                    color: "<?php echo $options['value']; ?>",    
                    showPalette: true,
                    showInput: true,
                    showAlpha: <?php echo $alpha ? 'true' : 'false'; ?>,
                    showInitial: true,
                    palette: [
                        <?php if (isset($options['defaults']) && $options['defaults']) echo json_encode($options['defaults']) . ','; ?>
                        TOW.colorPresets['default'],
                        TOW.colorPresets.user
                    ]
                });

                $("#<?php echo $this->get_field_id($options['name']); ?>").spectrum("set", "<?php echo $options['value']; ?>");

            });
            //]]>   
        </script>

        <?php
    }

    protected function description($description)
    {
        ?>
        <p>
            <?php echo $description; ?>
        </p>
        <?php
    }

    protected function separator()
    {
        ?>
        <p>
        <hr />    
        </p>
        <?php
    }
}
endif;
