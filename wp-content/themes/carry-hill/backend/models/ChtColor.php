<?php
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtColor
{
    private $_argbStr = '#';
    private $_hexStr;
    private $_alpha;
    private $_rgb;
    private $_rgbaString;

    public function __construct($hexStr, $alpha = 1)
    {
        $this->_hexStr = $hexStr;
        $this->_alpha  = $alpha;

        $this->_hexrgb();
        $this->_toARGBString();
        $this->_toRGBAString();
    }

    private function _hexrgb()
    {
        $int        = hexdec($this->_hexStr);
        $this->_rgb = array("red"   => 0xFF & ($int >> 0x10), "green" => 0xFF & ($int >> 0x8), "blue"  => 0xFF & $int);
    }

    private function _callback($val)
    {
        $val = round($val);
        $val = $val > 255 ? 255 : ($val < 0 ? 0 : $val);
        $val = base_convert((string) $val, 10, 16);
        $this->_argbStr .= strlen($val) === 1 ? '0' + $val : $val;
    }

    private function _toARGBString()
    {
        $rgb = $this->_rgb;
        array_unshift($rgb, (int) ($this->_alpha * 255));
        array_walk($rgb, array($this, '_callback'));
    }

    private function _toRGBAString()
    {
        $rgb = $this->_rgb;
        array_push($rgb, $this->_alpha);

        $this->_rgbaString = implode(',', $rgb);
    }

    public function getARGBString()
    {
        return $this->_argbStr;
    }

    public function getRGBAString()
    {
        return $this->_rgbaString;
    }

    // taken from http://lab.clearpixel.com.au/2008/06/darken-or-lighten-colours-dynamically-using-php/
    public function brightness($hex, $percent)
    {
        // Work out if hash given
        $hash = '';
        if (stristr($hex, '#')) {
            $hex  = str_replace('#', '', $hex);
            $hash = '#';
        }
        /// HEX TO RGB
        $rgb  = array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
        //// CALCULATE
        for ($i = 0; $i < 3; $i++) {
            // See if brighter or darker
            if ($percent > 0) {
                // Lighter
                $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
            } else {
                // Darker
                $positivePercent = $percent - ($percent * 2);
                $rgb[$i]         = round($rgb[$i] * $positivePercent) + round(0 * (1 - $positivePercent));
            }
            // In case rounding up causes us to go to 256
            if ($rgb[$i] > 255) {
                $rgb[$i] = 255;
            }
        }
        //// RBG to Hex
        $hex     = '';
        for ($i = 0; $i < 3; $i++) {
            // Convert the decimal digit to hex
            $hexDigit = dechex($rgb[$i]);
            // Add a leading zero if necessary
            if (strlen($hexDigit) == 1) {
                $hexDigit = "0" . $hexDigit;
            }
            // Append to the hex string
            $hex .= $hexDigit;
        }
        return $hash . $hex;
    }
}
