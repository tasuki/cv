<?php
require_once('lib/common.php');

/**
 * Convert from Markdown to TeX
 *
 * @param   string  markdown
 * @param   bool    escape for pdf info
 * @return  string  tex
 */
function format($string, $pdf_info = false) {
	if ($pdf_info === true) {
		$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
	}

	$replace = array(
		'/&/' => '\\&', // escape ampersand
		'/_/' => '\\_', // escape underscore
		'/\[(.*)\]\((.*)\)/U' => '\\href{\\2}{\\1}', // link links
		'/\\*(.*)\\*/' => '\textit{\\1}', // transform italics
	);

	return preg_replace(array_keys($replace), array_values($replace), $string);
}

/**
 * Display category
 *
 * @param  string  category name
 */
function show_category($category) {
	echo "\n\\section{" . format($category) . "}";
}

/**
 * Display category item
 *
 * @param  string  left side
 * @param  string  right side
 */
function show_item($item, $desc) {
	echo "\n\\cvitem{" . format($item) . "}{" . format($desc) . "}";
}

/**
 * Avoid breaking page inside a category
 */
function start_category() {
	echo "\n\\begin{samepage}";
}
function end_category() {
	echo "\n\\end{samepage}";
}

?>

\documentclass[11pt,a4paper,serif]{moderncv}
\usepackage{mathpazo}
\renewcommand{\sfdefault}{\rmdefault}

\pdfinfo{
	/Title (<?php echo format($data['title'], true); ?>)
	/Author (<?php echo format($data['author'], true); ?>)
	/Subject (<?php echo format($data['description'], true); ?>)
	/Keywords (<?php echo format($data['keywords'], true); ?>)
}
\pdfcatalog{
	/PdfStartView /FitW
}

% moderncv themes
\moderncvstyle{casual}
\moderncvcolor{black}
\nopagenumbers{}

% character encoding
\usepackage[utf8]{inputenc}

% adjust the page margins
\usepackage[scale=0.75]{geometry}
\setlength{\hintscolumnwidth}{3.5cm}

% personal data
\name{}{V\kern-.06emít Brunner}
\title{\textit{\href{https://cv.tasuki.org}{https://cv.tasuki.org}}}

\begin{document}
\makecvtitle

<?php show_data($data); ?>

\end{document}
