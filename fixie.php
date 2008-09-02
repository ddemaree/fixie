<?php

/* ############################################################

THIS IS FIXIE.

Fixie is a lightweight PHP library for quickly grabbing, parsing
and iterating across YAML fixtures, similar to those used in
Ruby on Rails tests. The idea is to make it easy to mock up web
sites with semi-real data without having to engage in mindlessly
repetitive HTML coding.

############################################################# */

require_once("vendor/spyc.php5");

$_fixieCycleCursors = array();

class Fixie {

	function cycle(){
		global $_fixieCycleCursors;
		
		$arguments = func_get_args();
		
		$cycleName = sha1(print_r($arguments,true));
		
		$cursor = $_fixieCycleCursors[$cycleName];
		
		if(!$_fixieCycleCursors[$cycleName]){
			$_fixieCycleCursors[$cycleName] = 0;
		}
		
		$retVal = $arguments[$_fixieCycleCursors[$cycleName]];
		
		if($arguments[$_fixieCycleCursors[$cycleName] + 1]){
			$_fixieCycleCursors[$cycleName] += 1;
		}
		else {
			$_fixieCycleCursors[$cycleName] = 0;
		}
		
		return $retVal;
	}

	function __construct($fixture_name, $lazyLoad = true){
		$this->fixture_name = $fixture_name;
		$this->cursor = 0;
		$this->rows = array();
		
		if($lazyLoad) {
			$this->rows = Spyc::YAMLLoad($this->fixture_name);
		}
	}
	
	function fixtureName() {
		return $this->fixture_name;
	}
	
	function fetch() {
		$currentRow = $this->rows[$this->cursor];
		$this->cursor += 1;
		return $currentRow;
	}
	
	function hasNext() {
		return !!($this->rows[$this->cursor]);
	}
	
	function reset() {
		$this->cursor = 0;
	}
}

?>