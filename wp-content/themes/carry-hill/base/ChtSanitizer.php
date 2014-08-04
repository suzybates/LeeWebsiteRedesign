<?php
if (!class_exists( 'ChtSanitizer' )):
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtSanitizer
{

    public function disallow($value, array $disallowedValues)
    {
        foreach ($disallowedValues as $disallowedValue) {
            if ($value == $disallowedValue) {
                return false;
            }
        }
        return $value;
    }

    public function numOnly($value)
    {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
    }
}
endif;
