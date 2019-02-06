<?php
/*
Plugin Name: Disable Attachment Pages
Plugin URI: https://www.littlebizzy.com/plugins/disable-attachment-pages
Description: 
Completely disables media attachment pages which then become 404 errors to avoid thin content SEO issues and better guard against snoopers and bots.
Version: 1.0.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
WC requires at least: 3.3
WC tested up to: 3.5
Prefix: DSATCH
*/

// Plugin namespace
namespace LittleBizzy\DisableAttachmentPages;

// Plugin constants
const FILE = __FILE__;
const PREFIX = 'dsatch';
const VERSION = '1.0.0';

// Boot
require_once dirname(FILE).'/helpers/boot.php';
Helpers\Boot::instance(FILE);