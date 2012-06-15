<?php
setlocale(LC_ALL, "en_US.utf8");

require_once('spyc.php');
$data = Spyc::YAMLLoad('cv.yaml');

/**
 * Show a single item row
 *
 * @param  string  left part
 * @param  string  right part
 */
function show_row($item, $desc) {
	// do not show numbers in left column
	if (is_int($item))
		$item = '';

	if (is_array($desc)) {
		// show first item with title in left column
		show_item($item, array_shift($desc));

		// show other items with no title
		foreach ($desc as $part) {
			show_item('', $part);
		}
	} else {
		show_item($item, $desc);
	}
}

/**
 * Show categories with items
 *
 * @param  array  categories
 */
function show_data(array $data) {
	foreach ($data['categories'] as $category => $items) {
		if (function_exists('start_category'))
			start_category();

		if (is_int($category)) {
			// category is empty, start with content
			show_row('', $items);
		} else {
			// show category and its items
			show_category($category);
			foreach ($items as $item => $desc)
				show_row($item, $desc);
		}

		if (function_exists('end_category'))
			end_category();
	}
}
