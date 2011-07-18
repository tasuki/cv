<?php
require_once('spyc.php');
$data = Spyc::YAMLLoad('cv.yaml');

/**
 * Show categories with items
 *
 * @param  array  categories
 */
function show_data(array $data) {
	foreach ($data['categories'] as $category => $items) {
		if (function_exists('start_category'))
			start_category();

		show_category($category);

		foreach ($items as $item => $desc) {
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

		if (function_exists('end_category'))
			end_category();
	}
}

