<?php
namespace App\Functions;

// Written by Mark Brugnoli-Vinten (netniV) @ BV IT Solutions Ltd
class ParsedownID extends \ParsedownExtra {
	protected function inlineImage($excerpt) {
		$image = parent::inlineImage($excerpt);

		if (isset($image)) {
			$src = isset($image['element']['attributes']['class']) ? $image['element']['attributes']['class'] : '';
			$image['element']['attributes']['class'] = trim('markdownImage ' . $src);
		} else {
			$image = null;
		}

		return $image;
	}

	protected function blockHeader($line) {
		$block = parent::blockHeader($line);

		if (isset($block)) {
			$text = trim($block['element']['handler']['argument'], '#');
			$text = trim($text, ' ');
			$link = preg_replace('/[^\p{L}\p{N}\p{M}-]+/u', '', mb_strtolower(mb_ereg_replace(' ','-',$text)));
			$attr = array();

			if (!empty($link)) {
				$attr = $block['element']['attributes'] ?? [];
				$attr['id'] = $link;

				$handler = array(
					'function' => 'lineElements',
					'argument' => $text,
					'destination' => 'elements',
				);

				$block['element']['attributes'] = $attr;
				$block['element']['handler']    = $handler;
			}
		} else {
			$block = null;
		}

	  return $block;
	}
}
