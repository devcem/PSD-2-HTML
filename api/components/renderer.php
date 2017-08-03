<?php

	$globalStyle = array();

	function createStyle($class, $styles){
		$output = '.'.$class.'{'."\n";
		foreach ($styles as $key => $value) {
			$output.= '	'.$key.':'.$value.";\n";
		}
		$output.='}';

		return $output;
	}

	function createTag($item){
		global $globalStyle;

		$element = '<'.$item['tag'].' class="'.$item['class'].'">'."\n";

		foreach (@$item['children'] as $key => $object) {
			$innerElement = createTag($object);

			$globalStyle[] = $innerElement['css'];
			$element.=" ".$innerElement['html']."\n";
		}

		$element.= '</'.$item['tag'].'>'."\n";

		return array('html' => $element, 'css' => createStyle($item['class'], $item['style']));
	}

	function renderer($output){
		global $globalStyle;

		$template = '';
		$styles   = array();
	
		foreach ($output as $key => $item) {
			$element  = createTag($item);
			$template.= $element['html'];
			$styles[] = $element['css'];
		}

		$styles = array_merge($styles, $globalStyle);

		return array('html' => $template, 'css' => $styles);
	}

	function styleRender($output){
		$template = '<style>'."\n";
		$template.= implode("\n", $output);
		$template.= '</style>'."\n";

		return $template;
	}

	function htmlRender($output){
		$template = $output;

		return $template;
	}