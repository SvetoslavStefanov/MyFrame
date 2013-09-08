<?php

/**
 * Uploading operation and file manipulation functions and defenitions
 *
 */
class Upload {

    /**
     * Generate unique valid file name
     *
     * @param	string		$fname		original file name
     * @param	string		$dir		directory name for validaton
     * @return	string		$fname		fixed file name
     */
    public static function filename($fname, $dir = false) {
        // escape string
        $fname = preg_replace('/[^\w0-9\._-]+/', '_', $fname);

        // no dir
        if (!$dir)
            return $fname;

        // get basename and extension
        $pathinfo = pathinfo($fname);
        $exten = $pathinfo['extension'];
        $bname = $pathinfo['filename'];

        // get unique file name
        //for ($i=0; is_file($dir . '/' . ($fname = $bname . ($i ? $i : '') . '.' . $exten)); $i++);
        $rand = rand(0, 9999);
        $fname = $rand . '_' . time() . '_' . md5($bname) . '.' . $exten;

        return $fname;
    }

    /**
     * Get file type
     *
     * @param	string		$fname		file name
     * @return	string					file type
     */
    public static function type($fname) {
        // types (defined here because we spend less memory for those)
        $types = array(
            'image' => 'jpg|bmp|gif|png|jpeg',
                /* 'jpg|bmp|gif|png|psd|raw|jpeg|tiff',
                 * 'doc'			=> 'doc|rtf',
                  'pdf'			=> 'pdf',
                  'text'			=> 'txt',
                  'flv'			=> 'flv',
                  'media'			=> 'mov|avi|mp4|flv',
                  'audio'			=> 'mp3|wav',
                  'excel'			=> 'xls',
                  'powerpoint'	=> 'ppt',
                  'flash'			=> 'swf',
                  'html'			=> 'html|htm',
                  'css'			=> 'css',
                  'archiv'		=> 'bz2|gz|tgz|tar|zip|rar',
                  'script'		=> 'php|js|rb|py|asp|jsp' */
        );

        // cover to lower for the matching
        $fname = strtolower($fname);

        // match
        foreach ($types as $type => $pattern) {
            if (preg_match('/\.(' . $pattern . ')$/i', $fname)) {
                return $type;
            }
        }

        return 'unknown';
    }

    /**
     * Check if file name is of given type(s) 
     *
     * @param	string			$fname		file name for matching
     * @param	string/array	$type		available type(s)
     * @return	boolean						if filename is for match the type options
     */
    public static function is($fname, $searched_type) {
        // get type
        //$type  = self::type($fname);
        //$types = (array) $types;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        //get file type => from php 5.3+
        $type = explode("/", finfo_file($finfo, $fname));
        finfo_close($finfo);
        //$file_type = $type['0'];
        $file_ext = $type['1'];

        // types (defined here because we spend less memory for those)
        $types = array(
            'image' => 'jpg|bmp|gif|png|jpeg',
                /* 'jpg|bmp|gif|png|psd|raw|jpeg|tiff',
                 * 'doc'			=> 'doc|rtf',
                  'pdf'			=> 'pdf',
                  'text'			=> 'txt',
                  'flv'			=> 'flv',
                  'media'			=> 'mov|avi|mp4|flv',
                  'audio'			=> 'mp3|wav',
                  'excel'			=> 'xls',
                  'powerpoint'	=> 'ppt',
                  'flash'			=> 'swf',
                  'html'			=> 'html|htm',
                  'css'			=> 'css',
                  'archiv'		=> 'bz2|gz|tgz|tar|zip|rar',
                  'script'		=> 'php|js|rb|py|asp|jsp' */
        );

        if (isset($types[$searched_type])) {
            if (in_array($file_ext, explode("|", $types[$searched_type]))) {
                return true;
            }
        }

        return in_array('*', $types);

        // match types
        /* foreach($types as $value){
          if ($type == strtolower($value)){
          return true;
          }
          }

          return in_array('*', $types); */
    }

    /**
     * Add measure unit to bytes
     *
     * @param	int			$size		size in bytes
     * @return	string					size with measure unit
     */
    public static function size($size) {
        if ($size <= 0)
            return '';

        $names = array(' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
        $index = floor(log($size, 1024));

        return round($size / pow(1024, $index), 2) . $names[$index];
    }

    /**
     * Upload file
     *
     * @param	array				$file		file for upload
     * @param	string				$dir		directory for upload
     * @param	boolean|array		$types		all valid file uploads mimetypes
     * @param	boolean|string		$fname		upload file under this name
     * @return	boolean|string		$fname		uploaded file name(or false on failure) 
     */
    public static function file($file, $directory, $types = false, $fname = false) {
        // validate 1st level
        if (!is_array($file) || !empty($file['error']) || empty($file['name']) || strlen($file['name']) == 0) {
            return false;
        }
        
        // validate 2nd level: file type
        if ($types) {
            // if types['image'] is definded and file is an image
            if (is_array($types) && isset($types['image'])) {
                // check if this is image if so, preccess image by Upload::image
                if (self::is($file['tmp_name'], 'image')) {
                    return self::image($file, $directory, $types['image']);
                }

                unset($types['image']);
            }
            
            if (empty($types)) {
                return false;
            }
            
            if (!self::is($file['tmp_name'], $types)) {
                return false;
            }
        }

        // set file name if needed
        if (!$fname) {
            $fname = self::filename($file['tmp_name'], $directory);
        }

        // upload file
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $fname)) {
            return false;
        }

        // change permissions
        chmod($directory . '/' . $fname, 0664);

        return $fname;
    }

    /**
     * Upload and resize image
     *
     * @param	array		$file			image for upload
     * @param	string		$dir			directory for upload
     * @param	array		$options		array of options
     * @return	string		$fname			new image urls
     */
    public static function image($file, $directory, $options = null) {
        // validate
        if (!is_array($file) || !empty($file['error']) || strlen($file['name']) == 0 || !preg_match('/image/', $file['type'])) {
            return false;
        }

        // get file name and the name of the temporary file used in the resize process
        $fname = self::filename($file['name'], $directory);
        
        $tempfile = $directory . '/' . self::filename('temp_' . $fname, $directory);
        // upload file to temporary file
        if (!move_uploaded_file($file['tmp_name'], $tempfile)) {
            return false;
        }
        // if there are no resizing options, means that temporary file is the needed files
        if (!$options) {
            chmod($tempfile, 0664); // set persmissions for the image
            rename($tempfile, $directory . '/' . $fname);
            return $fname;
        }

        // array containg all uploaded files ( used if for deleting leter process )
        $uploaded = array();

        // make thumbnail for every option
        foreach ((array) $options as $key => $val) {
            // get width and height
            $sizes = explode('x', $val);
            $width = (int) array_cut($sizes, 0, 0);
            $height = (int) array_cut($sizes, 1, 0);

            // set prefix
            if (is_numeric($key)) {
                switch ($key) {
                    case 0: $prefix = '';
                        break; // main image
                    case 1: $prefix = 'thumb_';
                        break; // first thumbnails: thumb_{$src}
                    default: $prefix = "thumb{$key}_";
                        break; // other thumbnails: thumb2_{$src}, thumb3_{$src} ...
                }
            } else {
                $prefix = $key . '_';
            }

            // what we need to with the image
            if ($width != 0 || $height != 0) {
                $src = self::resize($tempfile, "{$directory}/{$prefix}{$fname}", $width, $height);
                /*  these 3 lines below may provoke an unusual actions
                 *  becouse of second uploading issue in admin_country while uploading two attachments at once
                 *  this code adds prefix before the main file's name 
                 */
                if (count($options) == 1)
                    $fname = $prefix . $fname;
            } else {
                $src = "{$directory}/{$prefix}{$fname}";
                if (copy($tempfile, $src)) {
                    chmod($src, 0664);
                } else {
                    $src = false;
                }
            }

            if ($src) {
                $uploaded[] = $src;
            } else {
                // if one resize failed delete all previous resized images
                foreach ($uploaded as $fname) {
                    unlink($directory . '/' . $fname);
                }
                unlink($tempfile);
                return false;
            }
        }
        // delete temp file
        unlink($tempfile);

        return $fname;
    }

    /**
     * A function that creates a thumbnail image given its maximum height and width
     *
     * @param	string		$src				image src
     * @param	string		$dest				thumbnail dest(we create the new file)
     * @param	int			$maxWidth			max thumbnaul width
     * @param	int			$maxHeight			max thumbnail height
     * @return	boolean							is image resized succesfully
     */
    public static function resize($src, $dest, $maxWidth = 0, $maxHeight = 0) {
        // check if there is image for resize
        if (!file_exists($src) || !$dest) {
            return false;
        }

        // path info 
        $destInfo = pathinfo($dest);

        // image src size 
        $srcSize = getimagesize($src);

        // calculate max width and height 
        if (!$maxWidth)
            $maxWidth = $srcSize[0];
        if (!$maxHeight)
            $maxHeight = $srcSize[1];

        // do we need a resizing ?
        if ($srcSize[0] <= $maxWidth && $srcSize[1] <= $maxHeight) {
            copy($src, $dest);
            chmod($dest, 0664);
            return true;
        }

        // image dest size $destSize[0] = width, $destSize[1] = height
        $srcRatio = $srcSize[0] / $srcSize[1]; // width/height ratio 
        $destRatio = $maxWidth / $maxHeight;
        $destSize = array();

        if ($destRatio > $srcRatio) {
            $destSize[1] = $maxHeight;
            $destSize[0] = round($maxHeight * $srcRatio);
        } else {
            $destSize[0] = $maxWidth;
            $destSize[1] = round($maxWidth / $srcRatio);
        }

        // true color image, with anti-aliasing 
        $destImage = imagecreatetruecolor($destSize[0], $destSize[1]);

        // fix for transperant background
        $background = imagecolorallocate($destImage, 0, 0, 0);
        imagecolortransparent($destImage, $background);  // make the new temp image all transparent
        imagealphablending($destImage, false);    // turn off the alpha blending to keep the alpha channel
        imagesavealpha($destImage, true);
        // src image
        if (!$srcImage = self::_imagecreate($src, $srcSize[2])) {
            return false;
        }

        // resampling 
        imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $destSize[0], $destSize[1], $srcSize[0], $srcSize[1]);

        // just create empty file for the image and set its permission
        $file = fopen($dest, 'w');
        fclose($file);
        chmod($dest, 0664);

        // save image
        return self::_imagesave($srcSize[2], $destImage, $dest);
    }

    /**
     * Get thumbnails name and src from options (same as self::image)
     * @param	string			$src			src to main image file
     * @param	string|array	$options		options
     * @param	array			$thumbs			thumbnails hash
     * @return	array			$thumbs			array with thumbnails names and src
     */
    public static function thumbnails($src, $options, $thumbs = array()) {
        if (!$src)
            return array();

        $directory = dirname($src); // directory path
        $src = basename($src); // base name of the file
        $counter = 1;    // this is the counter for the thumb numering

        foreach ((array) $options as $key => $val) {
            if ($key === 0)
                continue;

            // there isn't thumbnail name given
            if (is_numeric($key)) {
                // names goes for: thumb, thumb2, thumb3, .... thumb{$counter}
                $key = 'thumb';
                if ($counter > 1) {
                    $key .= $counter;
                }
                $counter++;
            }

            // generate thumb name
            $thumbs[$key] = "{$directory}/{$key}_{$src}";
        }
        return $thumbs;
    }

    /**
     * Create gd image resource
     *
     * @param	string		$src		image src
     * @param	array		$type		image size given by getimagesize(if some)
     * @return	object		$img		image resource
     */
    private static function _imagecreate($src, $type = false) {
        // get image size(if needed)
        if (!is_numeric($type)) {
            $size = getimagesize($src); // 1:width; 2:height; 3:(int)type
            $type = $size[2];
        } else if (is_array($type)) {
            $type = $type[2];
        }

        // generate image resource
        switch ($type) {
            case 1: // type: gif
                return imagecreatefromgif($src);
            case 2: // type: jpg
                return imagecreatefromjpeg($src);
            case 3: // type: png
                return imagecreatefrompng($src);
        }

        return false;
    }

    /**
     * Save image object to file
     *
     * @param	string		$type	image type
     * @param	object		$img	GD2 image object
     * @param	string		$src	src to save the image
     * @return	boolean				is saving succesfully
     */
    private static function _imagesave($type, $img, $src) {
        switch ($type) {
            case 1: // type: gif
                imagegif($img, $src);
                break;
            case 2: // type: jpg
                imagejpeg($img, $src, 100);
                break;
            case 3: // type: png
                imagepng($img, $src);
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * Make image grayscale
     *
     * @param	string		$src		image src
     * @return	boolean					is operation succesful
     */
    public static function grayscale($src) {
        if (!$src || !is_file($src))
            return false;

        $size = getimagesize($src);
        $img = self::_imagecreate($src, $size);

        if (imageistruecolor($img)) {
            imagetruecolortopalette($img, false, 256);
        }

        for ($i = 0, $c = imagecolorstotal($img); $i < $c; $i++) {
            $color = imagecolorsforindex($img, $i);
            $gray = round(0.299 * $color['red'] + 0.587 * $color['green'] + 0.114 * $color['blue']);

            imagecolorset($img, $i, $gray, $gray, $gray);
        }

        return self::_imagesave($size[2], $img, $src);
    }

    /**
     * Add watermark to image
     *
     * @param	string			$src				image src
     * @param	string			$watermark			watermark for the image
     * @param	int|string		$posx				X position (could be strings: left,right, center)
     * @param	int|string		$posy				Y position (could be strings: top, bottom)
     * @param	int	$opacity						watermark opacity
     * @return	boolean								is watermark added succesfully
     */
    public static function watermark($src, $watermark, $posx = 0, $posy = 0, $opacity = 100) {
        // validate paths
        if (!is_file($src) || !is_file($watermark))
            return false;

        // get image sizes
        $isize = getimagesize($src);
        $size = getimagesize($watermark);

        // try to get the image resources for main image and whater mark
        $img = self::_imagecreate($src, $isize);
        $mark = self::_imagecreate($watermark, $size);

        if (!$img || !$mark)
            return false;

        // re-calculate watermark positions(if needed)
        if (is_string($posx)) {
            if ($posx == 'center') {
                $posx = (int) ($isize[0] / 2 - $size[0] / 2);
                // if there is no possition y, this means that y is ceneter too
                if (!$posy) {
                    $posy = (int) ($isize[1] / 2 - $size[0] / 2);
                }
            } elseif ($posx == 'right') {
                $posx = $isize[0] - $size[0];
            } else { // when $posx is 'left' or something else
                $posx = 0;
            }
        }

        if (is_string($posy)) {
            if ($posy == 'center') {
                $posy = (int) ($isize[1] / 2 - $size[0] / 2);
            } else if ($posy == 'bottom') {
                $posy = $isize[1] - $size[1];
            } else {// when $posy is 'top' or something else
                $posy = 0;
            }
        }

        // validate watermark postions
        $posx = $posx > $isize[0] ? $isize[0] : $posx;
        $posy = $posy > $isize[1] ? $isize[1] : $posy;

        // apply wather mark
        if ($opacity != 100) {
            imagecopymerge($img, $mark, $posx, $posy, 0, 0, $size[0], $size[1], $opacity);
        } else {
            imagecopy($img, $mark, $posx, $posy, 0, 0, $size[0], $size[1]);
        }

        return self::_imagesave($isize[2], $img, $src);
    }

    /**
     * Create image from given text
     *
     * @param	string		$text			text
     * @param	string		$src			path to image file
     * @param	string		$font			path to font 
     * @param	int			$size			text size
     * @param	string		$color			color 
     * @param	int			$angle			angle
     * @param	array 		$opt			base optins
     * @return	string		$src			path to image
     */
    public static function textToImage($text, $src, $font, $size, $color, $angle = 0, $opt = array()) {
        // fix text and color infos
        $color = Encode::hex2rgb($color, 'hex');

        // box size: 
        $bbox = imagettfbbox($size, $angle, $font, $text);

        if (!$opt['left'])
            $opt['left'] = min($bbox[0], $bbox[2], $bbox[4], $bbox[6]);

        if (!$opt['top'])
            $opt['top'] = min($bbox[1], $bbox[3], $bbox[5], $bbox[7]);

        if (!$opt['width'])
            $opt['width'] = max($bbox[0], $bbox[2], $bbox[4], $bbox[6]) - min($bbox[0], $bbox[2], $bbox[4], $bbox[6]);

        if (!$opt['height'])
            $opt['height'] = max($bbox[1], $bbox[3], $bbox[5], $bbox[7]) - min($bbox[1], $bbox[3], $bbox[5], $bbox[7]);

        // create the image
        $image = imagecreate($opt['width'], $opt['height']);

        // create some colors
        imagecolortransparent($image, imagecolorallocate($image, 255, 255, 255));
        $colshad = imagecolorallocate($image, $color[0], $color[1], $color[2]);

        // gen image text
        imagettftext($image, $size, $angle, 0, $size, $colshad, $font, $text);

        // save image
        imagepng($image, $src);

        return $src;
    }

    /**
     * Move file from one dir to another
     *
     * @param 		string		$file			file name
     * @param 		string		$from			from dir
     * @param 		string		$to				to dir
     * @param 		boolean		$over			overwrite if exists
     * @return		string						file name with new dir
     */
    public static function move($file, $from, $to, $overwrite = false) {
        $src1 = $from . '/' . $file;
        $src2 = $to . '/' . $file;

        if (!$overwrite && is_file($src2)) {
            $src2 = $to . '/' . self::filename($file, $to);
        }

        copy($src1, $src2);
        unlink($src1);

        return $src2;
    }

    /**
     * Delete file (or directory)
     * @param	string			$path			path for delete
     * @return	boolean							is deleting was succesful
     */
    public static function unlink($path) {
        if (!file_exists($path)) {
            return false;
        }

        if (is_dir($path)) {
            return rmdir($path);
        }

        return unlink($path);
    }

}
