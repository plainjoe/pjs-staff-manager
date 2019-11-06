<?php
/**
 * Register and setup pjs_staff post type
 */
 
	// set slug for CPT from ACF field
	$slug = get_field('pjs_staff_slug', 'option');

	if (!$slug) {
		$slug = 'staff';
	}
	
	// register pjs_staff post type
	register_post_type('pjs_staff', 
		array(
			'labels'             => array(
				'name'               => _x('PJS Staff Manager', 'post type general name'),
				'singular_name'      => _x('PJS Staff', 'post type singular name'),
				'menu_name'          => _x('PJS Staff', 'admin menu'),
				'name_admin_bar'     => _x('Staff Member', 'add new on admin bar'),
				'add_new'            => _x('Add New', 'staff'),
				'add_new_item'       => __('Add New Staff'),
				'new_item'           => __('New Staff'),
				'edit_item'          => __('Edit Staff'),
				'view_item'          => __('View Staff'),
				'all_items'          => __('All Staff'),
				'search_items'       => __('Search Staff'),
				'parent_item_colon'  => __('Parent Staff:'),
				'not_found'          => __('No staff found.'),
				'not_found_in_trash' => __('No staff found in Trash.')
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => $slug),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-businessman',
			'supports'           => array('title', 'revisions'),
			'taxonomies'         => array('pjs-staff-campus', 'pjs-staff-team'),
		)
	);
	
	// register pjs-staff-campus taxonomy
	register_taxonomy('pjs-staff-campus', 'pjs_staff',
		array(
			'labels'       => array(
				'name'         => 'Campus',
				'not_found'    => 'No Campus Found',
				'parent_item'  => 'Parent Campus',
				'search_items' => 'Search Campus',
				'add_new_item' => 'Add New Campus',
			),
			'rewrite'      => array('slug' => 'pjs-staff-campus'),
			'hierarchical' => false,
		)
	);
	
	// register pjs-staff-team taxonomy
	register_taxonomy('pjs-staff-team', 'pjs_staff',
		array(
			'labels'       => array(
				'name'         => 'Teams',
				'not_found'    => 'No Teams Found',
				'parent_item'  => 'Parent Team',
				'search_items' => 'Search Teams',
				'add_new_item' => 'Add New Team',
			),
			'rewrite'      => array('slug' => 'pjs-staff-team'),
			'hierarchical' => false,
		)
	);
	
	if (function_exists('acf_add_options_page')) {
		acf_add_options_sub_page(array(
			'page_title' 	=> 'PJS Staff Manager Settings',
			'menu_title' 	=> 'Staff Settings',
			'parent_slug' => 'edit.php?post_type=pjs_staff',
			'position' => 99,
		));
	}