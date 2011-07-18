<?php
require_once('spyc.php');
$data = Spyc::YAMLLoad('cv.yaml');

function show_data($data) {
	foreach ($data['categories'] as $category => $items) {
		if (function_exists('start_category'))
			start_category();

		show_category($category);

		foreach ($items as $item => $desc) {
			if (is_int($item))
				$item = '';

			if (is_array($desc)) {
				show_item($item, array_shift($desc));
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

