<?php

class CL_User {

	public $language = 'b';
	public $test;
	
	public function choose_language_fr() {
		
		$this->language = 'fr';
	}
	
	public function choose_language_en() {
		
		$this->language = 'en';
	}
}