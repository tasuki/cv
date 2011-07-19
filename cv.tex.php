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
\frenchspacing

\pdfcatalog{
/PdfStartView /FitW
}

\def\category#1{
	\vskip 20pt
	\hskip\leftcol\bf #1 \rm
}

\def\item#1#2{
	\vskip .1cm
	\hbox to \hsize {
		\vtop {
			\it\hsize 4cm \hfill #1
			\rm\hskip .1cm \guill ~
		}
		\vtop { \hsize 10cm #2 }
		\hfill
	}
}

\def\palatino{
	\font\tenrm=pplr8z at 10pt
	\font\tenbf=pplb8z at 10pt
	\font\tenit=pplri8z at 10pt
	\font\title=pplr8z scaled \magstep 3
	\def\guill{\char 159}
	\def\leftcol{4.17cm}
}
\def\torun{
	\font\tenrm=qx-anttr at 10pt
	\font\tenbf=qx-anttb at 10pt
	\font\tenit=qx-anttr at 10pt
	\font\title=qx-anttr scaled \magstep 3
	\def\guill{\char 175}
	\def\leftcol{4.22cm}
}
\palatino

\title \hskip\leftcol V\kern-.05em\char 237\kern-.02em t B\kern-.03em runn\kern-0.02em e\kern-0.02em r

\tenrm

<?php show_data($data); ?>

\bye
