<?php


namespace GC\DOM;

Trait TraitElement
{

	public function convertCSSToXPath($cssSelector) {
		
		static $cssToXPathConverter;
		
		if(!isset($cssToXPathConverter)) {
			$cssToXPathConverter=new CSSToXPath();
		}
		
		$cssToXPathConverter->setCss($cssSelector);

		return $cssToXPathConverter->getXPath();
	}

}
