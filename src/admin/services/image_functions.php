<?php
defined('_JEXEC') or die('Restricted access');

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 *
 * @param <type> $path
 * @param <type> $ext
 * @return <type> 
 */
function readImage($path, $ext) {
    $type = strtolower($ext);
    if ($type == 'jpg' || $type == 'jpeg') {
        return imagecreatefromjpeg($path);
    }
    if ($type == 'gif') {
        return imagecreatefromgif($path);
    }
    if ($type == 'png') {
        return imagecreatefrompng($path);
    }
    return FALSE;
}

/**
 *
 * @param <type> $img
 * @param <type> $path
 * @param <type> $ext
 * @return <type> 
 */
function writeImage($img, $path, $ext) {
    $type = strtolower($ext);
    if ($type == 'jpg' || $type == 'jpeg') {
        return imagejpeg($img,$path,97);
    }
    if ($type == 'gif') {
        return  imagegif($img,$path);
    }
    if ($type == 'png') {
        return  imagepng($img,$path,9);
    }
    return FALSE;
}

/**
 * + Info: http://www.phpace.com/image-resizing-with-php/
 *
 * @param string $image - ruta del original
 * @param int $max_width - ancho máximo
 * @param int $max_height - alto máximo
 * @param string $method - scale | crop
 * @param array bgColour - color de fondo
 * @return la ruta de la miniatura generada.
 */
function createThumb($image, $max_width, $max_height, $method = 'scale', $bgColour = null) {
    // get the current dimensions of the image
    $src_width = imagesx($image);
    $src_height = imagesy($image);

    // if either max_width or max_height are 0 or null then calculate it proportionally
    if (!$max_width) {
        $max_width = $src_width / ($src_height / $max_height);
    } elseif (!$max_height) {
        $max_height = $src_height / ($src_width / $max_width);
    }

    // initialize some variables
    $thumb_x = $thumb_y = 0; // offset into thumbination image
    // if scaling the image calculate the dest width and height
    $dx = $src_width / $max_width;
    $dy = $src_height / $max_height;
    if ($method == 'scale') {
        $d = max($dx, $dy);
    }
    // otherwise assume cropping image
    else {
        $d = min($dx, $dy);
    }
    $new_width = $src_width / $d;
    $new_height = $src_height / $d;
    // sanity check to make sure neither is zero
    $new_width = max(1, $new_width);
    $new_height = max(1, $new_height);

    $thumb_width = min($max_width, $new_width);
    $thumb_height = min($max_height, $new_height);

    // if bgColour is an array of rgb values, then we will always create a thumbnail image of exactly
    // max_width x max_height
    if (is_array($bgColour)) {
        $thumb_width = $max_width;
        $thumb_height = $max_height;
        $thumb_x = ($thumb_width - $new_width) / 2;
        $thumb_y = ($thumb_height - $new_height) / 2;
    } else {
        $thumb_x = ($thumb_width - $new_width) / 2;
        $thumb_y = ($thumb_height - $new_height) / 2;
    }

    // create a new image to hold the thumbnail
    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    if (is_array($bgColour)) {
        $bg = imagecolorallocate($thumb, $bgColour[0], $bgColour[1], $bgColour[2]);
        imagefill($thumb, 0, 0, $bg);
    }

    // copy from the source to the thumbnail
    imagecopyresampled($thumb, $image, $thumb_x, $thumb_y, 0, 0, $new_width, $new_height, $src_width, $src_height);
    return $thumb;
}
