<?php
require_once('lib/common.php');

/**
 * Escape for TeX output
 *
 * @param   string  to be escaped
 * @return  string  escaped
 */
function escape($string) {
	$string = strip_tags($string);
	return preg_replace(
		array('/&/'),
		array('\\&'),
		$string
	);
}

/**
 * Display category
 *
 * @param  string  category name
 */
function show_category($category) {
	echo "\n\category{" . escape($category) . "}";
}

/**
 * Display category item
 *
 * @param  string  left side
 * @param  string  right side
 */
function show_item($item, $desc) {
	echo "\n\item{" . escape($item) . "}{" . escape($desc) . "}";
}

/**
 * Avoid breaking page inside a category
 */
function start_category() {
	echo "\n\n\\vbox{";
}
function end_category() {
	echo "\n}";
}
?>

% A4 size paper
\pdfpagewidth=210 true mm
\pdfpageheight=297 true mm

\parindent 0pt
\nopagenumbers

\pdfcatalog{
/PdfStartView /FitW
}

\def\category#1{
	\vskip 20pt
	\hskip 4.17cm \bf #1 \rm
}

\def\item#1#2{
	\vskip .1cm
	\hbox to \hsize {
		\vtop {
			\hsize 4cm \hfill #1
			\hskip .1cm \char 159 ~
		}
		\vtop { \hsize 10cm #2 }
		\hfill
	}
}

\font\tenrm=pplr8z at 10pt
\font\tenbf=pplb8z at 10pt
\font\tenit=pplri8z at 10pt
\font\title=pplr8z scaled \magstep 3

\title \hskip 4.17cm V\kern-.05em\char 237\kern-.02em t B\kern-.03em runn\kern-0.02em e\kern-0.02em r

\tenrm

<?php show_data($data); ?>

\bye
