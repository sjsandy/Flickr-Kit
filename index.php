<?php
/*
Plugin Name: FlickrKit
Plugin URI: http://shawnsandy.com/
Description: Adds magic wand button to TinyMCE editor.
Author: Grzegorz Winiarski
Version: 1.0.0
Author URI: http://shawnsandy.com
*/


/**
 * flickr shortcode
 */


//if(!class_exists($phpFlickr))
//    include_once plugins_url ('phpFlickr.php', __FILE__).'/';


    $fkit = new phpFlickr("7a6a7aeecaac6a16f62b8dc968e49d59");

function flickr_img($attr, $content=null) {
    //$f = new ss_flickr($key, null);
    $url = $attr['url'];
    $width = isset($attr['width']) ? $attr['width'] : 500;
    $i = ss_image_link($url, null, $width);
    $img = "<div class=\"ss_flickr\">";
    $img .= $i;
    if ($content != null)
        $img .= "<p style=\"width:" . $width . "px;\">" . $content . "</p>";
    $img .= "</div>";
    return $img;
}

function fkimage_detail($attr, $content=null) {
    //$f = new ss_flickr($key, null);
    $url = $attr['url'];
    $width = isset($attr['width']) ? $attr['width'] : 500;
    $i = ss_image_link($url, null, $width);
    $img = "<div class=\"ss_flickr\">";
    $img .= $i;
    if ($content != null)
        $img .= "<p style=\"width:" . $width . "px;\">" . $content . "</p>";
    $img .= "</div>";
    return $img;
}

function fkimage_info($attr, $content=null) {
    //$f = new ss_flickr($key, null);
    $url = $attr['url'];
    $width = isset($attr['width']) ? $attr['width'] : 500;
    $i = ss_image_info($url, null, $width);
    $img = "<div class=\"ss_flickr\">";
    if ($content != null)
        $img .= "<p class=\"desc\" style=\"width:" . $width . "px;\">" . $content . "</p>";
    $img .= $i;
    $img .= "<div class=\"clear\"></div>";
    $img .= "</div>";
    return $img;
}

function fkset_info($attr, $content=null) {
    $id = $attr['url'];
    $size = $attr['width'];
    ob_start();
    if ($content != null)
        "<p class=\"set-info\">" . $content . "</p>";
    ss_set_info($id, $size);
    $content = ob_get_contents();
    ob_get_clean();
    return $content;
}

function fkset($attr, $content=null) {
    $id = $attr['url'];
    ob_start();
    ss_set($id);
    $content = ob_get_contents();
    ob_get_clean();
    return $content;
}


function fkgallery($attr, $content=null) {
    $id = esc_url($attr['url']);
    ob_start();
    ss_gallery($id);
    $content = ob_get_contents();
    ob_get_clean();
    return $content;
}




add_shortcode('flickr_img', 'flickr_img');
add_shortcode('fkimage', 'flickr_img');
add_shortcode('fkimage_info', 'fkimage_info');
add_shortcode('fkset_info', 'fkset_info');
add_shortcode('fkset', 'fkset');
add_shortcode('fkgal_info', 'fkgallery');



/**
 * php flickr functions
 */




function image($id=null, $width="640", $echo=false) {
    global $fkit;
    //$f = new phpFlickr("7a6a7aeecaac6a16f62b8dc968e49d59");
    $src;
    $image = $fkit->photos_getSizes($id);
    if ($image):
        foreach ($image as $_photo):
            if ($_photo['label'] == 'Medium 640') {
                $src = $_photo['source'];
            } elseif($_photo['label'] == 'Medium'){
                 $src = $_photo['source'];
            }


        endforeach;
        $img = "<img src=" . $src . " />";
        return $img;
    endif;
}

function ss_image_link($url, $id=null, $width="640") {
    if ($id == NULL)
        $id = img_id($url);
    $img = image($id, $width);
    if ($img) {
        $link = "<a href=\"" . $url . "\" target=\"_blank\">";
        $link .= $img;
        $link .= "</a>";
        return $link;
    }
}

function ss_image_info($url, $id=null, $width="640") {
    global $fkit;
    if ($id == NULL)
        $id = img_id($url);
    $info = $fkit->photos_getInfo($id);
    if ($info) {
        $user = $info['photo']['owner'];
        $img = image($id, $width);
        $link = "<a href=\"" . $url . "\" target=\"_blank\" class=\"ss_flickr_image\">";
        $link .= $img;
        $link .= "</a>";
        $link .= "<h3>" . $info['photo']['title'] . "</h3>";
        $link .= "<p class=\"meta\"><span class=\"user\">Photog : " . $user['username']
                . " | Realname " . ($user['realname'] ? $user['realname'] : "N/A");
        $link .= "";
        return $link;
    }
}

function img_id($url=null, $node=5) {
    $data = explode('/', $url, 8);
    // print_r($data);
    return $id = $data[$node];
}

function user_id($url) {

}

function ss_set($url) {
    global $fkit;
    $set_id = img_id($url, 6);
    $s = $fkit->photosets_getPhotos($set_id, $width = 'Medium');

    $set = $s['photoset']['photo'];
    if ($set) {
        foreach ($set as $photo) {

            $src = "<div class=\"ss_flickr_set\">";

            $src .= "<img src=\" " . $fkit->buildPhotoURL($photo, $width) . " \">";
            $src .= "</div>";
            echo $src;
        }
    }
}

function ss_set_info($url, $width='Medium') {
    global $fkit;
    $set_id = img_id($url, 6);
    $s = $fkit->photosets_getPhotos($set_id);

    $set = $s['photoset']['photo'];

    if ($set) {
        foreach ($set as $photo) {

            $src = "<div class=\"ss_flickr_set\">";
            $src .= "<a href=\"" . $url . "\" target=\"_blank\" class=\"ss_flickr_image\">";
            $src .= "<img src=\" " . $fkit->buildPhotoURL($photo) . " \">";
            $src .= "</a>";
            $src .= "<h3>" . $photo['title'] . "</h3>";
            $src .= "</div>";
            echo $src;
        }
    }
}

function ss_gallery($url){

    global $fkit;
    $furl = $fkit->urls_lookupGallery("{$url}");
    $gallery = $furl['gallery'];
    $id = $gallery['id'];
    $photos = $fkit->galleries_getPhotos($gallery['id']);
    $pr = $photos['photos']['photo'];
    if ($pr):
        //$img = $photos['photo'];
        foreach ($pr as $key) {
            //echo $key['id'];
            echo ss_image_info(null, $key['id']);
        }
    endif;
}


/*
 * ******************************************************
 */


add_filter('mce_external_plugins', "flickrkit_register");
add_filter('mce_buttons', 'flickrkit_add_button', 0);

add_filter('mce_external_plugins', "flickrset_register");
add_filter('mce_buttons', 'flickrset_add_button', 0);
//gallery
add_filter('mce_external_plugins', "flickrgal_register");
add_filter('mce_buttons', 'flickrgal_add_button', 0);

function flickrkit_add_button($buttons)
{
    array_push($buttons, "separator", "fkimage");
    return $buttons;
}

function flickrkit_register($plugin_array)
{
    $url = trim(get_bloginfo('url'), "/")."/wp-content/plugins/flickr-kit/fkimage_plugin.js";

    $plugin_array['fkimage'] = $url;
    return $plugin_array;
}

function flickrset_add_button($buttons)
{
    array_push($buttons, "separator", "fkset");
    return $buttons;
}

function flickrset_register($plugin_array)
{
    $url = trim(get_bloginfo('url'), "/")."/wp-content/plugins/flickr-kit/fkset_plugin.js";
    $plugin_array['fkset'] = $url;
    return $plugin_array;
}


function flickrgal_add_button($buttons)
{
    array_push($buttons, "separator", "fkgal");
    return $buttons;
}

function flickrgal_register($plugin_array)
{
    $url = trim(get_bloginfo('url'), "/")."/wp-content/plugins/flickr-kit/fkgal_plugin.js";
    $plugin_array['fkgal'] = $url;
    return $plugin_array;
}

