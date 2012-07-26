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

	return preg_replace(
		array('/&/', '/\[(.*)\]\((.*)\)/U', '/(href{.*}){(?!([[:alpha:]]*:))(.*)}/U'),
		array('\\&', '\\href{\\1}{\\2}',    '\\1{http://cv.tasuki.org/\\3}'),
		$string
	);
}

/**
 * Display category
 *
 * @param  string  category name
 */
function show_category($category) {
	echo "\n\category{" . format($category) . "}";
}

/**
 * Display category item
 *
 * @param  string  left side
 * @param  string  right side
 */
function show_item($item, $desc) {
	echo "\n\item{" . format($item) . "}{" . format($desc) . "}";
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
\pdflinkmargin 0pt

\pdfinfo{
	/Title (<?php echo format($data['title'], true); ?>)
	/Author (<?php echo format($data['author'], true); ?>)
	/Subject (<?php echo format($data['description'], true); ?>)
	/Keywords (<?php echo format($data['keywords'], true); ?>)
}
\pdfcatalog{
	/PdfStartView /FitW
}

\def\href#1#2{\leavevmode\pdfstartlink user{
		/Subtype /Link
		/C [ .9 .9 1]
		/A <<
			/Type /Action
			/S /URI
			/URI (#2)
		>>
	}\it#1\pdfendlink\rm
}

\def\category#1{
	\vskip 20pt
	\hskip\leftcol\bf #1 \rm
}

\def\item#1#2{
	\vskip .1cm
	\hbox to \hsize {
		\vtop {
			\hsize 4cm \hfill #1
			\hskip .1cm \guill ~
		}
		\vtop { \hsize 11cm #2 }
		\hfill
	}
}

\def\palatino{
	\font\tenrm=pplr8z at 10pt
	\font\tenbf=pplb8z at 10pt
	\font\tenit=pplri8z at 10pt
	\font\title=pplr8z scaled \magstep 3
	\font\link=pplr8z at 7pt
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
\hskip.5em\href{\link http:\kern-.1em/\kern-.25em/\kern-.1em cv.tasuki.org}{http://cv.tasuki.org/}
\vskip 10pt

\tenrm

<?php show_data($data); ?>

\bye
