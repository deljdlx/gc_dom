<?php


namespace GC\DOM;

class Element extends \GC\DOM\XML
{


	use TraitElement;

	protected $cssToXPathConverter;

	public function getElementById($id) {
		$xPath='//*[@id="'.$id.'"]';
		$nodes=$this->xPath($xPath);
		if(isset($nodes[0])) {
			return $nodes[0];
		}
		else {
			return false;
		}
	}
	
	
	public function html($content=null) {

		if($content===null) {
			$buffer=preg_replace('`^<.*?>`i', '', $this->render());
			$buffer=preg_replace('`</[^<]+?>$`i', '', $buffer);
			return $buffer;
		}
		$toDelete=array();
		foreach($this->children() as $key=>$child) {
			if($key!='style') {
				$toDelete[]=$key;
			}
		}
		
		foreach($toDelete as &$val) {
			unset($this->$val);
		}
		
		//cet appel permet de corriger un bug bizarre, il semblerait que l'appel à asHTML modifie le comportement du moteur interne
		//vérifier que celà ne plombe pas trop les performances
		$this->asHTML();
		
		$this->setValue('');



		$content=str_replace("&", "&amp;", $content);

		$node=new Element('<?xml version="1.0"?><innerHTML>'.$content.'</innerHTML>');


		if($node->count()<1) {
			$toAppend=$node;
			$this->setValue(
				$toAppend
			);
		}
		else {

			if(preg_match('`^[^<]+?<`si', trim($content))) {
				$this->setValue(
					preg_replace('`^([^<]+?)<.*`si', '$1', $content)
				);
			}

			foreach($node->children() as $child) {
				$this->appendChild(
					$child
				);
			}
		}
	}
	
	public function appendHTML($content) {
		
		$this->html($this->html().$content);

	}

	
	
	public function appendChild($node) {
		if(is_string($node)) {
			$node=new Element('<?xml version="1.0"?><innerHTML>'.$node.'</innerHTML>');
		}

		return parent::appendChild($node);
		
	}
	
	
	public function setStyle($string) {
		
		//removing commented rules======================================
		$string=preg_replace('`/\*.*?\*/`s', '', $string);
		
		preg_match_all('`(.*?):(.*?)(;|$)`ms', $string, $data);
		
		foreach($data[1] as $key=>$attribute) {
			preg_match_all('`(.*?)-(.*)`', trim($attribute), $attributeData);
			

			if(count($attributeData[1])) {
				$attribute=$attributeData[1][0].ucfirst($attributeData[2][0]);
			}
			$attribute=trim($attribute);

			if($attribute) {
				$value=trim($data[2][$key]);
				$this->style->$attribute=$value;
			}
		}
	}
	
	
	
	
	public function getElementsByTagName($tagName) {
		$xPath='//'.$tagName;
		$nodes=$this->xPath($xPath);
		return $nodes;
	}
	
	
	public function attr($attribute, $value=false) {
		if($value!==false) {
			$this[$attribute]=$value;
		}
		else if(isset($this[$attribute])){
			return $this[$attribute];
		}
		else {
			return false;
		}
	}


	
	public function find($query) {
	

	
		$xpath=$this->$this->convertCSSToXPath($query);
		

		$xpath=str_replace('///', '//', $xpath);
		$xpath=str_replace('//*//*', '//*', $xpath);
		$xpath=str_replace('//*//', '//', $xpath);
		$xpath=preg_replace('`\(//(\w+)\)`', '/$1', $xpath);

		return new Collection($this->xPath($xpath), $query);
	}
	
	public function each($selector, $function) {
	
		$nodes=$this->find($selector);
		foreach($nodes as $key=>$node) {
			$function($key, $node);
		}
	}
	
	

	

	public function asHTML() {
		
		
		
		
		//recupere tous les <style> de l'élément et les transforme en <nodeParent style=".....">
		/*
			de <tag><style><unAttribut>uneValeur</unAttribut>...</tag>
			vers <tag style="unAttribut: uneValeur...">...</tag>
		*/
		$nodes=$this->xPath('//style[not(parent::head)]/..');
		
		if(is_array($nodes)) {
			foreach($nodes as $child) {	
				$this->formatStyle($child);
			}
		}
		
		//$this->formatStyle($this);

		return parent::asHTML();
	}

	
	public function render() {
		$buffer=$this->asHTML();
		$buffer=preg_replace('`</?innerHTML>`', '', $buffer);
		$buffer=preg_replace('`<\?xml version="1\.0".*?\?>`', '', $buffer);
		
		$buffer=$this->normalizeTags($buffer);
		
		return trim($buffer);
	}
	
	public function normalizeTags($buffer) {
		
		$buffer=preg_replace('`<br>`i', '<br/>', $buffer);
		$buffer=preg_replace('`<hr>`i', '<hr/>', $buffer);
		$buffer=preg_replace('`<(input.*?)>`i', '<$1 />', $buffer);
		
		return $buffer;
		
	}
	
	
	
	
	protected function formatId($node) {
		if($node->id) {
			$node['id']= (string) $node->id;
			unset($node->id);
		}
	}



	protected function formatStyle($node) {

		if($node->style) {
			$style='';

			foreach($node->style->children() as $definition) {
				$attribute=strtolower(preg_replace('`([A-Z])`', '-$1', $definition->getName()));
				$style.=$attribute.':'. (string) $definition.';';
				$node['style']=preg_replace('`\s+'.$attribute.'\s*:.*;|$`Umsi', '', $node['style']);
			}
			unset($node->style);
			$node['style'].=';'.$style;
		}
	}

	
	
	public function __set($attribute, $value) {
		echo '<pre id="'.__FILE__.'-'.__LINE__.'" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">'; print_r($attribute); echo '</pre>';
	}
	
	
	
	
	
	
}









