<?php

if (!function_exists('cbp_get_the_image')):

    function cbp_get_the_image($args = array())
    {
        if (function_exists('get_the_image')) {

            return get_the_image($args);
        }
        return '';
    }

endif;