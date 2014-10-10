<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

/**
* On-the-fly CSS Compression
* Copyright (c) 2009 and onwards, Manas Tungare.
* Creative Commons Attribution, Share-Alike.
*
* In order to minimize the number and size of HTTP requests for CSS content,
* this script combines multiple CSS files into a single file and compresses
* it on-the-fly.
*
* To use this in your HTML, link to it in the usual way:
* <link rel="stylesheet" type="text/css" media="screen, print, projection" href="/css/compressed.css.php" />
*/

/* Add your CSS files to this array (THESE ARE ONLY EXAMPLES) */

$params = $_GET;
$direction = $params['direction'];

$ltrFiles = array(
    "bootstrap"                 => "bootstrap.css",
    "bootstrap_responsive"      => "bootstrap_responsive.css",
    "template"                  => "template.css",      
    "template_responsive"       => "template_responsive.css",
    "custom.css"                => "custom.css"
);

$rtlFiles = array(
    "bootstrap_rtl"             => "bootstrap_rtl.css",
    "bootstrap_responsive_rtl"  => "bootstrap_responsive_rtl.css",
    "template"                  => "template.css",
    "template_rtl"              => "template_rtl.css", 
    "template_responsive"       => "template_responsive.css",
    "custom.css"                => "custom.css"
);

$cssFiles = array();

if ($direction != 'rtl') {
    $cssFiles = $ltrFiles;
    if (array_key_exists('rtl', $cssFiles)) {
        unset($cssFiles["rtl"]);
    }
} else {
    $cssFiles = $rtlFiles;
}

$removeSuffix = ($direction == 'rtl') ? '' : '_rtl';
$keepSuffix = ($direction == 'rtl') ? '_rtl' : '';
for ($i = 1; $i <= 3; $i++) {
    if ($i != $style) {
        if (array_key_exists('style'.$i.$keepSuffix, $cssFiles)) {
            unset($cssFiles['style'.$i.$keepSuffix]);
        }
    }
    if (array_key_exists('style'.$i.$removeSuffix, $cssFiles)) {
        unset($cssFiles['style'.$i.$removeSuffix]);
    }
}

/**
* Ideally, you wouldn't need to change any code beyond this point.
*/

$buffer = "";
foreach ($cssFiles as $cssFile) {
  $buffer .= file_get_contents($cssFile);
}

// Remove comments
$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

// Remove tabs, spaces, new lines, etc        
$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    
// Remove unnecessary spaces      
$buffer = str_replace('{ ', '{', $buffer);
$buffer = str_replace(' }', '}', $buffer);
$buffer = str_replace('; ', ';', $buffer);
$buffer = str_replace(', ', ',', $buffer);
$buffer = str_replace(' {', '{', $buffer);
$buffer = str_replace('} ', '}', $buffer);
$buffer = str_replace(': ', ':', $buffer);
$buffer = str_replace(' ,', ',', $buffer);
$buffer = str_replace(' ;', ';', $buffer);

// Enable GZip encoding
ob_start("ob_gzhandler");

// Enable caching
header('Cache-Control: public');

// Expire in one day
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

// Set the correct MIME type, because Apache won't set it for us
header("Content-type: text/css");

// Write everything out
echo($buffer);
?>