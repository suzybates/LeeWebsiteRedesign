<?php
if (!class_exists( 'CbpTranslate' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpTranslate
{
    public static function translateString($string)
    {
        return __($string, CBP_APP_TEXT_DOMAIN);
    }
}
endif;