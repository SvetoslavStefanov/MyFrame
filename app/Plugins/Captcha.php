<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Captcha
 *
 * @author svetlio
 */
class MineCaptcha
{

    public function loadCaptcha ()
    {
        $height = 40;
        $width = 80;
        $path_to_ttf = 'styles/BabelSans.ttf';
        $font_size = 30;
        $code = substr(genereteCode(), 0, 4);
        $_SESSION['captcha'] = md5($code . 'mys3cr3t\/\/0rD' . $code);

        $image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');

        /* set the colours */
        $tmp_color = $this->captcha_generate_random_color(155, 255);
        $background_color = imagecolorallocate($image, $tmp_color['red'], $tmp_color['green'], $tmp_color['blue']);

        $tmp_color = $this->captcha_generate_random_color(0, 127);
        $text_color = imagecolorallocate($image, $tmp_color['red'], $tmp_color['green'], $tmp_color['blue']);
        $noise_color1 = imagecolorallocate($image, $tmp_color['red'], $tmp_color['green'], $tmp_color['blue']);
        $noise_color2 = imagecolorallocate($image, $tmp_color['red'] + mt_rand(10, 40), $tmp_color['green'] + mt_rand(10, 40), $tmp_color['blue'] + mt_rand(10, 40));


        for ($i = 0; $i < ($width * $height) / 3; $i++) {
            imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color1);
        }


        $textbox = imagettfbbox($font_size, 0, $path_to_ttf, $code) or die('Error in imagettfbbox function');
        //$angle = mt_rand(5,18) * ((mt_rand(1,2) == 1) ? (-1) : 1);
        $x = ($width - $textbox[4]) / 2;

        $y = ($height - $textbox[5]) / 2;
        imagettftext($image, $font_size, 0, $x, $y, $text_color, $path_to_ttf, $code) or die('Error in imagettftext function');

        return $image;
    }

    private function captcha_generate_random_color ($min, $max)
    {
        $ret['red'] = intval(mt_rand($min, $max));
        $ret['green'] = intval(mt_rand($min, $max));
        $ret['blue'] = intval(mt_rand($min, $max));

        return $ret;
    }

}

?>
