<?php

// Subpackage namespace
namespace LittleBizzy\DisableAttachmentPages\Core;

// Aliased namespaces
use \LittleBizzy\DisableAttachmentPages\Helpers;

/**
 * Attachments class
 *
 * @package Disable Attachment Pages
 * @subpackage Core
 */
class Attachments extends Helpers\Singleton {



	/**
	 * Constructor
	 */
	public function onConstruct() {

		// Update rewrite rules
		add_filter('rewrite_rules_array', [$this, 'rewrite']);
		
		// Filter the unique post slug
		add_filter('wp_unique_post_slug', [$this, 'post_slug'], 10, 6);

		// Update query vars
		add_filter('request', [$this, 'query_vars']);

		// Change attachment link
		add_filter('attachment_link', [$this, 'attachment_link'], 10, 2);

		// Redirect attachment page
		add_action('template_redirect', [$this, 'redirect']);
	}



	/**
	 * Remove attachment rewrites
	 */
	public function rewrite($rules) {
		foreach ($rules as $pattern => $rewrite) {
			if (preg_match('/([\?&]attachment=\$matches\[)/', $rewrite)) {
				unset($rules[$pattern]);
			}
		}

		return $rules;
	}



	/**
	 * Overwrite the logic of wp_unique_post_slug() in wp-includes/post.php
	 */
	public function post_slug($slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug) {
		global $wpdb, $wp_rewrite;

		if ($post_type =='nav_menu_item')
			return $slug;

		if (!is_post_type_hierarchical($post_type))
			return $slug;

		$feeds = $wp_rewrite->feeds;

		if (!is_array($feeds))
			$feeds = array();

		$slug = $original_slug;
		$check_sql = "SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND post_type IN (%s) AND ID != %d AND post_parent = %d LIMIT 1";
		$post_name_check = $wpdb->get_var($wpdb->prepare($check_sql, $slug, $post_type, $post_ID, $post_parent));

		/** This filter is documented in wp-includes/post.php */
		if ($post_name_check || in_array($slug, $feeds) || 'embed' === $slug || preg_match("@^($wp_rewrite->pagination_base)?\d+$@", $slug) || apply_filters('wp_unique_post_slug_is_bad_hierarchical_slug', false, $slug, $post_type, $post_parent)) {
			$suffix = 2;

			do {
				$alt_post_name = _truncate_post_slug($slug, 200 - (strlen($suffix) + 1))."-$suffix";
				$post_name_check = $wpdb->get_var($wpdb->prepare($check_sql, $alt_post_name, $post_type, $post_ID, $post_parent));
				$suffix++;
			} while ($post_name_check);
	
			$slug = $alt_post_name;
		}

		return $slug;
	}



	/**
	 * Remove attachment query var
	 */
	public function query_vars($vars) {
		if (!empty($vars['attachment'])) {
			$vars['page'] = '';
			$vars['name'] = $vars['attachment'];
			unset($vars['attachment']);
		}

		return $vars;
	}



	/**
	 * Replace attachment link with file URL
	 */
	public function attachment_link($url, $id) {
		$attachment_url = wp_get_attachment_url($id);

		if ($attachment_url) {
			return $attachment_url;
		}

		return $url;
	}



	/**
	 * Redirect attachment page to 404 page
	 */
	public function redirect() {
		if (is_attachment()) {
			global $wp_query;
			$url = wp_get_attachment_url(get_the_ID());
			
			// Redirect to 404
			if ($url) {
				$wp_query->set_404();
				status_header(404);
				get_template_part(404);

				die;
			}
		}
	}



}