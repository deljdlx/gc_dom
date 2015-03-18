<?php
namespace GC\DOM;

class CSSToXPath
{

	protected $cssSelector='';

	public function __construct($cssSelector=null) {
		$this->setCss($cssSelector);
	}
	
	public function setCss($cssSelector) {
		$this->cssSelector=$cssSelector;
		return $this;
	}
	
	
	
	public function getXPath() {
	
		$query=$this->cssSelector;
	
		$cssToXpathTransformations=$this->getFilters();
	
	
		foreach ($cssToXpathTransformations as $search => $replace) {
			if(is_callable($replace)) {
				$query=preg_replace_callback($search . 'i', $replace, $query);
			}
			else {
				$query=preg_replace($search . 'i', $replace, $query);
			}
		}

		if(!preg_match('`^\W*//`', $query)) {
			$query='//'.$query;
		}
		
		return $query;
	}
	
	



	protected function getFilters() {
		return array(
			//taken from http://my.opera.com/pp-layouts/blog/2009/11/23/css-selectors-2-xpath
			/// Pre-processing:

			'`\s*([+>~,])\s*`'=>'$1',	//sanitize rules like div + span


			'/["\']/' => '',                                     // no quotes please
			'/\s*([[]>+,])\s*/' => '\1',                         // no WS around []>+,
			'/\s{2,}|\n/' => ' ',                                // no duplicate WS

			'/([a-z]+)\:first\-child/' => '*[1]/self::\1',       // E:first-child


			'/(?:^|,)\./' => '*.',                               // .class shorthand

			


			'/(?:^|,)#/' => '//*#',                                // #id shorthand

			'/:(link|visited|active|hover|focus)/' => '.\1',     // not applicable
			'/\[(.*)]/' =>function($matches) {	// dots inside [] to `/// CSS 2 XPath conversion:) 
				return str_replace('.', '`', $matches[0]);
			},
			'/,/' => '|',                                        // E,F
			'/>/' => '/',                                        // E>F
			'/ /' => '//',                                       // E F



			

			'/#([a-z][0-9_a-z]*)/' => '[@id="\1"]',              // E#id

			'/\+/' => '/following-sibling::*[1]/self::',         // E+F

			'/\[([a-z][0-9_a-z]*)\]/' => '[@\1]',                // E[attr]
			'/\[([a-z][0-9_a-z]*)=(.*)\]/' => '[@\1="\2"]',      // E[attr=v]
			'/\[([a-z][0-9_a-z]*)~=(.+?)\]/' =>                  // E[attr~=v]
			  '[contains(concat(" ",@\1," "),concat(" ","\2", " "))]',   
			'/\[[a-z][0-9_a-z]*\|=(.*?)\]/' =>                   // E[attr|=v]
			  '[@\1="\2" or starts-with(@\1,concat("\2","-"))]',         
			'/\.([a-z][0-9_a-z]*)/' =>                           // E.class
			  '[contains(concat(" ",@class," "),concat(" ","\1"," "))]',


			

			
			'/`/' => '.',                                         // ` back to .

			'`~`'=>'/following-sibling::*',
			'`(.*?):nth-child\(\s*(\d+)\s*\)`' =>'(//*/$1)[$2]',
			
			'`/\[`' => '/*[',		//tranform slectors like //*[@id="test"]//[contains(...)] into //*[@id="test"]//*[contains(....)]
			
		);
	}

}
