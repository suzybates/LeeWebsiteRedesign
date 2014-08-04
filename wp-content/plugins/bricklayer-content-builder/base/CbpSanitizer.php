<?php
if (!class_exists( 'CbpSanitizer' )):
/**
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpSanitizer
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
