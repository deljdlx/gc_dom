<?php

namespace GC;

class XML extends \SimpleXMLElement
{


	public function removeChild(\SimpleXMLElement $old) {
		$tmp = dom_import_simplexml($this);
		$old = dom_import_simplexml($old);
		if ($old->ownerDocument !== $tmp->ownerDocument) {
			throw new DOMException('The reference node does not come from the same document as the context node', DOM_WRONG_DOCUMENT_ERR);
		}

		$node = $tmp->removeChild($old);
		return simplexml_import_dom($node, get_class($this));
	}
	
	
	public function createElement($name, $content) {
        $dom = dom_import_simplexml($this);
        $node=$dom->ownerDocument->createElement($name, $content);
		return simplexml_import_dom($node);
		
	}
	
	
	
    public function appendChild($node) {

		
		$dom=dom_import_simplexml($this);
		$node=dom_import_simplexml($node);
		$node=$dom->ownerDocument->importNode($node, true);
        $dom->appendChild($node);
    }

    public function prependChild($node) {
        $dom = dom_import_simplexml($this);
		$node=dom_import_simplexml($node);
		$node=$dom->ownerDocument->importNode($node, true);
		
        $new = $dom->insertBefore(
            $node,
            $dom->firstChild
        );

        return simplexml_import_dom($new, get_class($this));
    }


	
	public function cloneNode($node) {
		$dom_thing = dom_import_simplexml($this);
		$dom_node  = dom_import_simplexml($node);
		
		$dom_new   = $dom_thing->appendChild($dom_node->cloneNode(true));

		$new_node  = simplexml_import_dom($dom_new);
		return simplexml_import_dom($node, get_class($this));
	}

	
	public function setValue($value) {
		$dom=dom_import_simplexml($this);

		$dom->nodeValue=htmlspecialchars($value);
		//$simplexml=simplexml_import_dom($dom, get_class($this));
		//return $simplexml;
	}
	
	
	public function asHTML() {
		$node=dom_import_simplexml($this);
		$document=new \DomDocument();
		$document->loadXML('<?xml version="1.0" encoding="utf-8"?><GC_container></GC_container>');
		
		
		$newNode=$document->importNode($node, true);
		$document->documentElement->appendChild($newNode);
		
		
		$buffer=$document->saveHTML();
		
		//pour gérer bug php qui ne ferme pas les balise link
		$buffer=preg_replace('`(<link.*?)>`', '$1/>', $buffer);
		
		
		return preg_replace('`</?GC_container>`','', $buffer);
	}

	
}
	
	
