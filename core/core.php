<?php

// Subpackage namespace
namespace LittleBizzy\DisableAttachmentPages\Core;

// Aliased namespaces
use \LittleBizzy\DisableAttachmentPages\Helpers;

/**
 * Core class
 *
 * @package Disable Attachment Pages
 * @subpackage Core
 */
final class Core extends Helpers\Singleton {



	/**
	 * Pseudo constructor
	 */
	protected function onConstruct() {

		// Factory object
		$this->plugin->factory = new Factory($this->plugin);

		// Attempt to run an object
		//$this->plugin->factory->myObject()
	}



}