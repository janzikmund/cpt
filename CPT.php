<?php
/**
 * Simple Class for registering custom post types and taxonomies in WordPress
 * Automatically generates labels and prefills some basic values depending if the post should be private or public
 * @author : info@janzikmund.cz
 */

class CPT {

	// variable holding custom post types
	private $custom_post_types = array();

	// variable for custom taxonomies
	private $custom_taxonomies = array();

	/**
	 * Bind the hooks for registration
	 * @return null
	 */
	public function __construct() {
		add_action( 'init', array($this, 'register_cpt'), 0 );
		add_action( 'init', array($this, 'register_tax'), 1 );
	}

	/**
	 * Registers the post types added
	 * @return null
	 */
	public function register_cpt() {
		foreach($this->custom_post_types as $cpt) {
			register_post_type( $cpt['name'], $cpt['args'] );
		}
	}

	// generate labels for new post types
	protected function generate_labels_cpt($sing, $pl) {
		return array(
			'name'               => ucfirst($pl),
			'singular_name'      => ucfirst($sing),
			'menu_name'          => ucfirst($pl),
			'name_admin_bar'     => ucfirst($sing),
			'add_new'            => 'Add ' . ucfirst($sing),
			'add_new_item'       => 'Add New ' . ucfirst($sing),
			'new_item'           => 'New ' . ucfirst($sing),
			'edit_item'          => 'Edit ' . ucfirst($sing),
			'view_item'          => 'View ' . ucfirst($sing),
			'all_items'          => 'All ' . ucfirst($pl),
			'search_items'       => 'Search ' . ucfirst($pl),
			'parent_item_colon'  => 'Parent ' . ucfirst($sing) . ':',
			'not_found'          => 'No ' . $pl . ' found.',
			'not_found_in_trash' => 'No ' . $pl . ' found in Trash.',
		);
	}

	/**
	 * Register new Custom Post Type
	 * @param str $slug  identifier
	 * @param str $label_sing label singular
	 * @param str $label_pl label in plural
	 * @param bool $private only for backend and custom front-end queries
	 * @param array $args additional arguments
	 */
	public function add_cpt($slug, $label_sing, $label_pl, $private = true, $args = array()) {
		// generate labels
		$labels = $this->generate_labels_cpt($label_sing, $label_pl);

		// attributes depending if it is private or public
		if($private) {
			// PRIVATE
			$default_args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => false,
				'exclude_from_search'=> true,
				'show_in_nav_menus'  => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'rewrite'            => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => true,
				'supports'           => array( 'title' )
			);
		} else {
			// PUBLIC
			$default_args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'exclude_from_search'=> false,
				'show_in_nav_menus'  => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'rewrite'            => true,
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title' )
			);
		}

		// merge into new post type arguments
		$new_cpt = array(
			'name' => $slug,
			'args' => wp_parse_args($args, $default_args),
		);

		// attach
		$this->custom_post_types[] = $new_cpt;
	}


	/**
	 * Add new taxonomy
	 * @param str  $slug         taxonomy slug
	 * @param str|arr  $post_type    for which post types the taxonomy is assigned
	 * @param str  $label_sing   label singular
	 * @param str  $label_pl     label plural
	 * @param boolean $hierarchical if it is hierarchical like categories, or not like tags
	 * @param array   $args         user defined arguments
	 */
	public function add_tax($slug, $post_type, $label_sing, $label_pl, $hierarchical = true, $args = array()) {
		// generate labels
		$labels = $this->generate_labels_tax($label_sing, $label_pl);

		// default arguments
		$default_args = array(
			'labels' => $labels,
			'hierarchical' => $hierarchical,
		);

		// merge with defined args
		$new_tax = array(
			'name' => $slug,
			'post_type' => $post_type,
			'args' => wp_parse_args($args, $default_args),
		);

		// add
		$this->custom_taxonomies[] = $new_tax;
	}


	// generate labels for new taxonomy
	protected function generate_labels_tax($sing, $pl) {
		return array(
			'name'              => ucfirst($pl),
			'singular_name'     => ucfirst($sing),
			'search_items'      => 'Search ' . ucfirst($pl),
			'all_items'         => 'All ' . ucfirst($pl),
			'parent_item'       => 'Parent ' . ucfirst($sing),
			'parent_item_colon' => 'Parent ' . ucfirst($sing),
			'edit_item'         => 'Edit ' . ucfirst($sing),
			'update_item'       => 'Update ' . ucfirst($sing),
			'add_new_item'      => 'Add New ' . ucfirst($sing),
			'new_item_name'     => 'New ' . ucfirst($sing),
			'menu_name'         => ucfirst($pl),
		);
	}


	/**
	 * Registers taxonomies added
	 * @return null
	 */
	public function register_tax() {
		foreach($this->custom_taxonomies as $tax) {
			register_taxonomy( $tax['name'], $tax['post_type'], $tax['args'] );
		}
	}
}

$CPT = new CPT();
