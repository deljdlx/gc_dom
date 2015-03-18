<?php


namespace GC\DOM;

class Collection implements \ArrayAccess, \Iterator 
{


	protected $nodes;
	protected $selector;
	
	public function __construct($nodes=null, $selector='') {
		$this->selector=$selector;
		if(!empty($nodes)) {
			$this->nodes=$nodes;
		}
		else {
			$this->nodes=array();
		}
	}
	
	public function each($callback) {
		foreach($this->nodes as $key=>$val) {
			$callback($key, $val);
		}
		return $this;
	}
	
	
	public function get($index) {
		if(isset($this->nodes[$index])) {
			return $this->nodes[$index];
		}
	}
	
	
	public function attr($attribute, $value=false) {
		if($value!==false) {
			foreach($this->nodes as $node) {
				$node->attr($attribute, $value);
			}
		}
		else {
			if(isset($this->nodes[0])) {
				return $this->nodes[0]->attr($attribute);
			}
			else {
				return '';
			}
		}
	}

	public function find($query) {

		$collection=new Collection();
		//foreach ($this->nodes as $node) {
			$subCollection=reset($this->nodes)->find($this->selector.' '.$query);
			$collection->merge($subCollection);
		//}
		return $collection;
	}
	
	public function merge($collection) {
		foreach ($collection->getNodes() as $node) {
			$this->nodes[]=$node;
		}
		return $this;
	}

	public function getNodes() {
		return $this->nodes;
	}
	
	public function html($html=false) {

		if($html!==false) {
			foreach($this->nodes as $node) {
				$node->html($html);
			}
		}
		else {
			if(isset($this->nodes[0])) {
				return $this->nodes[0]->html();
			}
			else {
				return '';
			}
		}
	}

	public function appendChild($child) {
		foreach($this->nodes as $node) {
			$node->appendChild($child);
		}
	}


	public function offsetExists($offset ) {
		return isset($this->nodes[$offset]);
	}
	
	public function offsetGet($offset ) {
		return $this->nodes[$offset];
	}
	
	public function offsetSet($offset , $value ) {
		return $this->nodes[$offset]=$value;
	}
	
	public function offsetUnset($offset ) {
		unset($this->nodes[$offset]);
	}
	
	public function current() {
		return current($this->nodes);
	}
	
	public function key() {
		return key($this->nodes);
	}

	public function next() {
		return next($this->nodes);
	}

	public function rewind() {
		return reset($this->nodes);
	}


	public function valid() {
		return current($this->nodes);
	}
}

