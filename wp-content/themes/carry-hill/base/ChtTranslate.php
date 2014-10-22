<?php
if (!class_exists( 'ChtTranslate' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtTranslate
{
    public static function translateString($string)
    {
        return __($string, CHT_APP_TEXT_DOMAIN);
    }
}
endif;
