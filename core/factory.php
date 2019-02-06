<?php

// Subpackage namespace
namespace LittleBizzy\DisableAttachmentPages\Core;

/**
 * Object Factory class
 *
 * @package Disable Attachment Pages
 * @subpackage Core
 */
class Factory {



	// Properties
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Plugin object
	 */
	private $plugin;



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Constructor
	 */
	public function __construct($plugin) {
		$this->plugin = $plugin;
	}



	// Methods
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Magic GET method
	 */
	public function __get($name) {
		$method = 'create'.ucfirst($name);
		return method_exists($this, $method)? $this->{$method}() : null;
	}



	/**
	 * Magic CALL method
	 */
	public function __call($name, $args = null) {
		$method = 'create'.ucfirst($name);
		$args = (!empty($args) && is_array($args))? $args[0] : null;
		return method_exists($this, $method)? $this->{$method}($args) : null;
	}



	// Core objects creation
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Creates the Attachment Object
	 */
	private function createAttachments() {
		return Attachments::instance($this->plugin);
	}



}