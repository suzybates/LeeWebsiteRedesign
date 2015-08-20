<?php

if (!function_exists('array_replace_recursive'))
{
    function array_replace_recursive($base, $replacements) 
    { 
        foreach (array_slice(func_get_args(), 1) as $replacements) { 
            $bref_stack = array(&$base); 
            $head_stack = array($replacements); 

            do { 
                end($bref_stack); 

                $bref = &$bref_stack[key($bref_stack)]; 
                $head = array_pop($head_stack); 

                unset($bref_stack[key($bref_stack)]); 

                foreach (array_keys($head) as $key) { 
                    if (isset($key, $bref) && is_array($bref[$key]) && is_array($head[$key])) { 
                        $bref_stack[] = &$bref[$key]; 
                        $head_stack[] = $head[$key]; 
                    } else { 
                        $bref[$key] = $head[$key]; 
                    } 
                } 
            } while(count($head_stack)); 
        } 

        return $base; 
    } 
}


if ( ! function_exists( "sanitize_html_classes" ) && function_exists( "sanitize_html_class" ) ) {
  /**
	 * sanitize_html_class works just fine for a single class
	 * Some times le wild <span class="blue hedgehog"> appears, which is when you need this function,
	 * to validate both blue and hedgehog,
	 * Because sanitize_html_class doesn't allow spaces.
	 *
	 * @uses   sanitize_html_class
	 * @param  (mixed: string/array) $class   "blue hedgehog goes shopping" or array("blue", "hedgehog", "goes", "shopping")
	 * @param  (mixed) $fallback Anything you want returned in case of a failure
	 * @return (mixed: string / $fallback )
	 */
	function sanitize_html_classes( $class, $fallback = null ) {
 
		// Explode it, if it's a string
		if ( is_string( $class ) ) {
			$class = explode(" ", $class);
		} 
 
 
		if ( is_array( $class ) && count( $class ) > 0 ) {
			$class = array_map("sanitize_html_class", $class);
			return implode(" ", $class);
		}
		else { 
			return sanitize_html_class( $class, $fallback );
		}
	}
}
