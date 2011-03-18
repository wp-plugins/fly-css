<?php
include_once('../../../wp-config.php');
include_once('../../../wp-load.php');
include_once('../../../wp-includes/wp-db.php');


$options = get_option('mcw__flycss_options');

$side = $_GET['side'];

if ($side == 'front') {
	$out = $options['custom_css'];
} else {
	$out = $options['admin_custom_css'];
}


header("Content-type: text/css");

echo "/* " . ucwords($side) . " side CSS added by Fly CSS\r\n(c) 2011 Craig Williams\r\n\r\n*/\r\n\r\n";
echo $out;
echo "\r\n/* /END Fly CSS */\r\n";
?>