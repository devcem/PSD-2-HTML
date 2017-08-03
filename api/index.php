<?php

	include 'components/group.php';
	include 'components/formatting.php';
	include 'components/renderer.php';

	//$output = shell_exec('node process.js');
	$output  = file_get_contents('temp/cache.json');
	$project = json_decode($output, TRUE);

	//print_r($output);

	$document = array(
		'width'  => 1920,
		'height' => 900
	);

	$output = array();

	foreach ($project as $key => $item) {
		if($item['type'] == 'group'){
			$output[] = createGroup($item, $document);
		}
	}

	$template = renderer($output);

	$css  = styleRender($template['css']);
	$html = htmlRender($template['html']);

	$file = file_get_contents('assets/template.html');
	$file = str_replace('{{css}}', $css, $file);
	$file = str_replace('{{html}}', $html, $file);

	echo $file;