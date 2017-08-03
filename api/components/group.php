<?php

	function createGroup($item, $parent){
		$element = array();

		$element['tag']   = 'div';
		$element['class'] = sanitize(@$item['name']);

		$style          = array();
		$style['width'] = @$item['width'] > $parent['width'] ? '100%' : @$item['width'].'px';

		if($style['width'] == '100%'){
			$style['padding-left']  = @$item['left'] > 0 ? @$item['left'].'px' : '0px';
			$style['padding-right'] = @$item['width']-@$item['right'] > 0 ? @($item['width']-$item['right']).'px' : '0px';

			$style['margin-top']    = $item['top'] > 0 ? $item['top'].'px' : '0px';
			$style['margin-bottom'] = $item['height']-$item['bottom'] > 0 ? ($item['height']-$item['bottom']).'px' : '0px';
		}

		$element['style'] = $style;

		$element['children'] = array();

		if(is_array(@$item['children'])){
			foreach (@$item['children'] as $object) {
				if($item['type'] == 'group'){
					$element['children'][] = createGroup($object, $item);
				}
			}
		}

		return $element;
	}