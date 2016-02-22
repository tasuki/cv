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

	$replace = array(
		'/--/' => '&ndash;', // fix ndash
		"/'/" => '&rsquo;', // fix apostrophe
		'/<\/?p>/' => '', // no paragraphs
	);

	return preg_replace(array_keys($replace), array_values($replace), $string);
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
	<link href="//fonts.googleapis.com/css?family=Alegreya+SC:700|Alegreya:400,400i,700" rel="stylesheet" type="text/css">
	<style type="text/css">
		* { margin: 0px; padding: 0px; }
		body { background-color: #F2E9C6; color: #242526; font-family: "Alegreya", serif; font-size: 18px; font-weight: 400; line-height: 22px; text-rendering: optimizeLegibility; }
		div { clear: both; }
		#wrap { width: 900px; padding: 20px; margin-left: 10px; }
		.right { float: right; text-align: left; width: 650px; padding-top: 7px; }
		.left  { float: left; text-align: right; width: 250px; padding-top: 7px; }
		.separator { padding: 0px 8px; }
		h1 { font-size: 30px; font-weight: 700; font-family: "Alegreya SC"; }
		p { clear: both; padding: 5px 0px; }
		.category { font-weight: 700; font-family: "Alegreya SC"; margin-top: 22px; padding: 0px; text-transform: capitalize; }
		a:link, a:visited { color: #B32C05; }
		a:hover { text-decoration: none; }
	</style>
	<?php include 'lib/analyticstracking.php'; ?>
</head>
<body>

<div id="wrap">
<h1 class="right"><?php echo format($data['author']); ?></h1>

<?php show_data($data); ?>

</div>
</body>
</html>
