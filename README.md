# CPT

Class for quick registration of custom post types and taxonomies in WordPress. Automatically generates labels and prefills some basic values depending if the post should be private or public.

## Usage

1. require it in functions.php in your theme
2. add new post types and taxonomies using `$CPT->add_cpt()` and `$CPT->add_tax()` functions

### Add Custom Post Type

	$CPT->add_cpt($slug, $label_sing, $label_pl, $private = true, $args = array())
	
- $slug - identifier of new custom post type
- $label_singular - label for one item
- $label_plural - label for multiple items
- $private - if the post is for internal use only, or if wp should generate single pages, archives, show it on search etc
- $args - any custom attributes for overriding defaults (can be used for override labels, ordering in menu etc.)

### Add Custom Taxonomy

	$CPT->add_tax($slug, $post_type, $label_sing, $label_pl, $hierarchical = true, $args = array())
	
	
- $slug - identifier of new taxonomy
- $post_type - post_type connected with this taxonomy
- $label_singular - label for one item
- $label_plural - label for multiple items
- $hierarchical - if it is hierarchical like categories, or flat like tags
- $args - custom attributes that override defaults

## Examples

Just place the following samples in functions.php of your theme to register your custom post types and taxonomies.

### Basic Sample
	
	<?php
	// require class
	require_once('CPT.php');
	
	$CPT->add_cpt('car', 'Company car', 'Company_cars');
	$CPT->add_tax('types', 'car', 'Type of car', 'Types of cars');

### Customize metaboxes, don't inherit posts URL prefix, non-public taxonomy 

	$CPT->add_cpt('team-member', 'Team Member', 'Team Members', false, [
		'supports' => ['title', 'editor', 'thumbnail'],
		'rewrite' => ['with_front' => false]
	]);
	$CPT->add_tax('specialization', 'team-member', 'Specialization', 'Specializations', true, [
		'publicly_queryable' => false
	]);
	
### Change archive slug, taxonomy doesn't inherit posts URL prefix

	$CPT->add_cpt('course', 'Course', 'Courses', false, [
		'has_archive' => 'courses',
		'rewrite' => ['with_front' => false]
	]);
	$CPT->add_tax('location', 'course', 'Location', 'Locations', true, [
		'rewrite' => ['with_front' => false],
	] );

## Notes

- Custom posts have hidden most of their meta-boxes by default to allow easy adding custom fields using [ACF](https://www.advancedcustomfields.com/) 
