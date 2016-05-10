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
	echo "
		<div class='pure-g'>
			<div class='left pure-u-md-1-4'></div>
			<div class='right pure-u-md-3-4 category'>" . format($category) . "</div>
		</div>";
}

/**
 * Display category item
 *
 * @param  string  left side
 * @param  string  right side
 */
function show_item($item, $desc) {
	echo "
		<div class='pure-g'>
			<div class='left pure-u-md-1-4'>" . format($item)
			. "<span class='separator'>&rsaquo;</span></div>
			<div class='right pure-u-md-3-4'>" . format($desc) . "</div>
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
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pure/0.6.0/pure-min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pure/0.6.0/grids-responsive-min.css">
	<style type="text/css">
		html, body, button, input, select, textarea, .pure-g [class *= "pure-u"] { font-family: "Alegreya", serif; letter-spacing: 0px; }
		body { background-color: #F2E9C6; color: #242526; font-size: 18px; font-weight: 400; line-height: 22px; text-rendering: optimizeLegibility; }
		div { clear: both; }
		#wrap { padding: 10px; }

		.pure-g { max-width: 880px; margin: auto; padding-top: 7px; }
		.right { text-align: left; }
		.left  { text-align: right; }
		@media screen and (max-width: 48em) {
			.pure-g { display: block; }
			.left { text-align: left; }
			.pure-g div { display: inline; }
		}

		.separator { padding: 0px 8px; }

		h1 { font-size: 30px; font-weight: 700; font-family: "Alegreya SC"; }
		.category { font-weight: 700; font-family: "Alegreya SC"; margin-top: 22px; padding: 0px; text-transform: capitalize; }

		a:link, a:visited { color: #B32C05; }
		a:hover { text-decoration: none; }
	</style>
	<?php include 'lib/analyticstracking.php'; ?>
</head>
<body>

<div id="wrap">
	<div class='pure-g '>
		<div class='left pure-u-md-1-4'></div>
		<div class='right pure-u-md-3-4'><h1><?php echo format($data['author']); ?></h1></div>
	</div>

	<?php show_data($data); ?>
</div>
</body>
</html>
