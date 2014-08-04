<?php
if (!class_exists( 'ChtFormElement' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtFormElement
{

    public function label($text, $name)
    {
        $full_name = CHT_APP_PREFIX . $name;
        return '<label for="' . $full_name . '">' . __($text, CHT_APP_TEXT_DOMAIN) . '</label>';
    }

    public function textField($name, $class = false)
    {
        $full_name = CHT_APP_PREFIX . $name;
        $dbvalue   = get_option($full_name);
        $class     = $class ? 'class="' . $class . '"' : '';
        return '<input type="text" name="' . $full_name . '" id="' . $full_name . '" ' . $class . ' value="' . $dbvalue . '" />';
    }

    public function hidden($name)
    {
        $full_name = CHT_APP_PREFIX . $name;
        $dbvalue   = get_option($full_name);
        return '<input type="hidden" name="' . $full_name . '" id="' . $full_name . '" value="' . $dbvalue . '" />';
    }

    public function textarea($name, $rows = 10, $cols = 30)
    {
        $full_name = CHT_APP_PREFIX . $name;
        $dbvalue   = get_option($full_name);
        return '<textarea id="' . $full_name . '" name="' . $full_name . '" rows="' . $rows . '" cols="' . $cols . '">' . $dbvalue . '</textarea>';
    }

    public function checkbox($name, $value = 'true')
    {
        $full_name = CHT_APP_PREFIX . $name;
        $dbvalue   = get_option($full_name);
        $checked   = (isset($dbvalue) && $dbvalue == $value) ? 'checked="checked"' : null;

        $output = '<input type="hidden" name="' . $full_name . '"  value="false" />';
        $output .= '<input type="checkbox" name="' . $full_name . '" id="' . $full_name . '" ' . $checked . ' value="' . $value . '" />';
        return $output;
    }

    public function radioButton($name, $value = null)
    {
        $full_name = CHT_APP_PREFIX . $name;
        $dbvalue   = get_option($full_name);
        $checked   = (isset($dbvalue) && $dbvalue == $value) ? 'checked="checked"' : null;

        return '<input type="radio" name="' . $full_name . '" id="' . $full_name . '" ' . $checked . ' value="' . $value . '" />';
    }

    public function submitButton($name, $value = 'Save', $class = null)
    {
        return '<input class="button ' . $class . '" type="submit" value="' . $value . '" name="' . $name . '" id="' . $name . '"/>';
    }

//    public function nonceField($param)
//    {
//        wp_nonce_field('custom-' . $param, '_wpnonce-custom-' . $param);
//    }
//    public function uploadButton($name)
//    {
//        return '<input type="file" name="' . $name . '"  />';
//    }


    public function select($name, $options = array(), $associative = false)
    {
        $full_name = CHT_APP_PREFIX . $name;
        $dbvalue   = get_option($full_name);
        $output    = '<select id="' . $full_name . '" name="' . $full_name . '">';
        if ($associative) {
            foreach ($options as $key => $value) {
                if ($key == $dbvalue) {
                    $output .= '<option selected="selected" value="' . $key . '">' . $value . '</option>';
                } else {
                    $output .= '<option value="' . $key . '">' . $value . '</option>';
                }
            }
        } else {
            foreach ($options as $value) {
                if ($value == $dbvalue) {
                    $output .= '<option selected="selected" value="' . $value . '">' . $value . '</option>';
                } else {
                    $output .= '<option value="' . $value . '">' . $value . '</option>';
                }
            }
        }
        $output .= '</select>';
        return $output;
    }

    public function dropdownCategories($name)
    {
        $full_name = CHT_APP_PREFIX . $name;
        $dbvalue   = get_option($full_name);
        $selected  = (isset($dbvalue)) ? '&selected=' . $dbvalue : null;

        wp_dropdown_categories('name=' . $full_name . $selected);
    }

    public function uploadField($name)
    {
        $full_name = CHT_APP_PREFIX . $name;
        $output    = '';
        $upload    = get_option($full_name);

        $output .= '<input class="upload_field" name="' . $full_name . '" id="' . $full_name . '_upload" type="text" value="" />';


        //--upload button
        $output .= '<div class="upload_button_div"><span class="button image_upload_button" id="' . $full_name . '">Upload Image</span>';

        if (!empty($upload)) {
            $hidden = '';
        } else {
            $hidden = 'hidden';
        }

        //--reset button    
        $output .= '<span class="button image_reset_button ' . $hidden . '" id="reset_' . $full_name . '" title="' . $full_name . '">Remove</span>';
        $output .='</div>' . "\n";
        $output .= '<div class="clear"></div>' . "\n";
        if (!empty($upload)) {
            $output .= '<a class="vl_uploaded_image" href="' . $upload . '">';
            $output .= '<img class="vl_favicon_image" id="image_' . $full_name . '" src="' . $upload . '" alt="" />';
            $output .= '</a>';
        }
        $output .= '<div class="clear"></div>' . "\n";


        return $output;
    }
    
    public function upload($name)
    {
        $full_name = CHT_APP_PREFIX . $name;
        $dbvalue   = get_option($full_name);
        ?>
        <div class="field">
            <input id="<?php echo $full_name; ?>" name="<?php echo $full_name; ?>" class="upload-field"  type="text" value="<?php echo $dbvalue; ?>" />
            <input class="button upload-button" type="button" value="Browse" />
            <div class="image-container"><?php if ($dbvalue): ?><img src="<?php echo $dbvalue; ?>" alt="<?php echo $name; ?>" /><?php endif; ?></div>
        </div>
        <?php
    }

}
endif;
