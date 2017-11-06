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
		<div class='pure-g category'>
			<div class='left pure-u-md-1-4'></div>
			<div class='right pure-u-md-3-4'>" . format($category) . "</div>
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
		<div class='pure-g " . ($item ? "item" : "noitem") . "'>
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
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="<?php echo format($data['description']); ?>" />
	<meta name="keywords" content="<?php echo format($data['keywords']); ?>" />
	<link href="//fonts.googleapis.com/css?family=Alegreya+SC:700|Alegreya:400,400i,700" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pure/0.6.0/pure-min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pure/0.6.0/grids-responsive-min.css">
	<style type="text/css">
		html, button, input, select, textarea, .pure-g [class *= "pure-u"] { font-family: "Alegreya", serif; letter-spacing: 0px; }
		body { background-color: #F2E9C6; color: #242526; font-size: 18px; font-weight: 400; line-height: 22px; text-rendering: optimizeLegibility; }
		div { clear: both; }
		#wrap { padding: 10px; }

		.pure-g { max-width: 880px; padding-top: 7px; }
		.right { text-align: left; }
		.left  { text-align: right; }
		@media screen and (max-width: 48em) {
			#wrap { padding-top: 0px; }
			.pure-g { display: block; }
			.left { text-align: left; }
			.pure-g div { display: inline; }
			.item .left a { font-weight: 700; }
			.noitem .separator { display: none; }
		}

		.separator { padding: 0px 8px; }

		h1 { font-size: 30px; font-weight: 700; font-family: "Alegreya SC"; padding-top: 7px; margin: 0px; }
		.category { font-weight: 700; font-family: "Alegreya SC" !important; padding-top: 22px; text-transform: capitalize; }

		a:link, a:visited { color: #B32C05; }
		a:hover { text-decoration: none; }

		a:link {
			text-decoration: none;
			background: -webkit-linear-gradient(#F2E9C6, #F2E9C6), -webkit-linear-gradient(#F2E9C6, #F2E9C6), -webkit-linear-gradient(#B32C05, #B32C05);
			background: linear-gradient(#F2E9C6, #F2E9C6), linear-gradient(#F2E9C6, #F2E9C6), linear-gradient(#B32C05, #B32C05);
			-webkit-background-size: 0.05em 1px, 0.05em 1px, 1px 1px;
			-moz-background-size: 0.05em 1px, 0.05em 1px, 1px 1px;
			background-size: 0.05em 1px, 0.05em 1px, 1px 1px;
			background-repeat: no-repeat, no-repeat, repeat-x;
			text-shadow: 0.03em 0 #F2E9C6, -0.03em 0 #F2E9C6, 0 0.03em #F2E9C6, 0 -0.03em #F2E9C6, 0.06em 0 #F2E9C6, -0.06em 0 #F2E9C6, 0.09em 0 #F2E9C6, -0.09em 0 #F2E9C6, 0.12em 0 #F2E9C6, -0.12em 0 #F2E9C6, 0.15em 0 #F2E9C6, -0.15em 0 #F2E9C6;
			background-position: 0% 88%, 100% 88%, 0% 88%;
		}
		a:hover {
			background: none;
		}
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
