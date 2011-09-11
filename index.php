<?php
require_once('lib/common.php');
require_once('lib/markdown.php');

/**
 * Convert from Markdown to HTML
 *
 * @param   string  markdown
 * @return  string  html
 */
function format($string) {
	$string = trim(Markdown($string));

	return preg_replace(
		array('/--/', '/<\/?p>/'),
		array('&ndash;', ''),
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
		. format($category)
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
			<span class='left'>" . format($item)
			. "<span class='separator'>&rsaquo;</span></span>
			<span class='right'>" . format($desc) . "</span>
			<div></div>
		</div>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo format($data['title']); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="<?php echo format($data['description']); ?>" />
	<meta name="keywords" content="<?php echo format($data['keywords']); ?>" />
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
		a:link, a:visited { color: #000; }
		a:hover { text-decoration: none; }
	</style>
</head>
<body>

<div id="wrap">
<h1 class="right"><?php echo format($data['author']); ?></h1>

<?php show_data($data); ?>

</div>
</body>
</html>
