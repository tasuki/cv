<?php
require_once('lib/common.php');

/**
 * Escape for HTML output
 *
 * @param   string  to be escaped
 * @return  string  escaped
 */
function escape($string) {
	return preg_replace(
		array('/&/', '/--/'),
		array('&amp;', '&ndash;'),
		$string
	);
}

/**
 * Display category
 *
 * @param  string  category name
 */
function show_category($category) {
	echo "<div class='right category'>"
		. escape($category)
		. "<div></div></div><br/>";
}

/**
 * Display category item
 *
 * @param  string  left side
 * @param  string  right side
 */
function show_item($item, $desc) {
	echo "
		<div>
			<span class='left'>" . escape($item)
			. "<span class='separator'>&rsaquo;</span></span>
			<span class='right'>" . escape($desc) . "</span>
			<div></div>
		</div>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $data['name'] ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="CV of Vit Brunner" />
	<style type="text/css">
		* { margin: 0px; padding: 0px; }
		body { background-color: #FFF; color: #000; font-family: Candara, Verdana, sans-serif; font-size: 14px; }
		div { clear: both; }
		#wrap { width: 700px; padding: 20px; margin-left: 10px; }
		.right { float: right; width: 500px; text-align: left; padding-top: 5px; }
		.left { float: left; width: 200px; text-align: right; padding-top: 5px; }
		.separator { padding: 0px 8px; }
		h1 { letter-spacing: 1px; font-size: 25px; font-weight: normal; }
		p { clear: both; padding: 5px 0px; }
		.category { font-weight: bold; margin-top: 30px; padding: 0px; }
		a:link { color: #000; }
		a:visited { color: #000; }
		a:hover { color: #69A; text-decoration: none; }
	</style>
</head>
<body>

<div id="wrap">
<h1 class="right"><?php echo $data['name'] ?></h1>

<?php show_data($data); ?>

</div>
</body>
</html>
