<?php
	$path = $_SERVER['DOCUMENT_ROOT'];
	
	include_once $path . '/wp-config.php';
	include_once $path . '/wp-load.php';
	include_once $path . '/wp-includes/wp-db.php';

	global $wpdb;
	global $wp_query;
	
	$postType = $_POST['post_type'];
	$campus = $_POST['campus'];
	$team = $_POST['team'];
	$page = $_POST['page'];
	$offset = $_POST['offset'];
	
	$results = array();
	$results['page'] = $page + 1;
	
	$viewStaffLabel = get_field('pjs_staff_view_staff_label', 'option');
	$disableBios = get_field('pjs_staff_disable_bio_link', 'option');
	
	if (!$viewStaffLabel) {
		$viewStaffLabel = 'View Staff Member';
	}
	
	$taxQuery = '';

	if ($campus != 'all' || $team != 'all') {
		$taxQuery = array('relation' => 'AND');
		
		if ($campus != 'all') {
			array_push($taxQuery, array(
				'taxonomy' => 'pjs-staff-campus',
				'field'    => 'term_id',
				'terms'    => $campus,
				'operator' => 'IN',
			));
		}
		
		if ($team != 'all') {
			array_push($taxQuery, array(
				'taxonomy' => 'pjs-staff-team',
				'field'    => 'term_id',
				'terms'    => $team,
				'operator' => 'IN',
			));
		}
	}
	
	// staff query
	$k = 0;
	
	$itemsArgs = array(
		'post_type' => $postType,
		'posts_per_page' => 9,
		'orderby' => 'title',
		'order' => 'ASC',
		'paged' => $page,
		'tax_query' => $taxQuery,
	);
	$itemsQuery = new WP_Query($itemsArgs);
	
	if ($itemsQuery->have_posts()) {
		while ($itemsQuery->have_posts()) { $itemsQuery->the_post();
			$title = get_the_title();
			$link = get_the_permalink();
			$graphic = get_field('image');
			$role = get_field('role');
			$email = get_field('email');
			$phone = get_field('phone');
			$facebook = get_field('facebook');
			$twitter = get_field('twitter');
			$instagram = get_field('instagram');
			$linkedin = get_field('linkedin');
			
			if ($graphic) {
				$graphicURL = $graphic['sizes']['medium'];
			} else {
				$graphicURL = '/wp-content/plugins/pjs-staff-manager/images/placeholder.jpg';
			}
			
			if ($disableBios) {
				$results['cards'][$k] .= '<div class="card hidden pjs-staff-trans">';
					$results['cards'][$k] .= '<div class="container">';
						$results['cards'][$k] .= '<div class="image" style="background:url(' . $graphicURL . ') no-repeat center / cover;"></div>';
						$results['cards'][$k] .= '<div class="details">';
							$results['cards'][$k] .= '<h3>' . $title . '</h3>';
							$results['cards'][$k] .= '<p><strong>' . $role . '</strong></p>';
							if ($email || $phone) {
								$results['cards'][$k] .= '<p><em>';
									if ($email) {
										$results['cards'][$k] .= '<a href="mailto:' . $email . ';">' . $email . '</a>';
									}
									if ($phone) {
										if ($email) {
											$results['cards'][$k] .= '<br>';
										}
										$results['cards'][$k] .= '<a href="tel:' . $phone . ';">' . $phone . '</a>';
									}
								$results['cards'][$k] .= '</em></p>';
							}
							$results['cards'][$k] .= '<div class="social">';
							if ($facebook) {
								$results['cards'][$k] .= '<div class="icon">';
									$results['cards'][$k] .= '<a href="' . $facebook . '" target="_blank"><i class="fab fa-facebook-f"></i></a>';
								$results['cards'][$k] .= '</div>';
							}
							if ($twitter) {
								$results['cards'][$k] .= '<div class="icon">';
									$results['cards'][$k] .= '<a href="' . $twitter . '" target="_blank"><i class="fab fa-twitter"></i></a>';
								$results['cards'][$k] .= '</div>';
							}
							if ($instagram) {
								$results['cards'][$k] .= '<div class="icon">';
									$results['cards'][$k] .= '<a href="' . $instagram . '" target="_blank"><i class="fab fa-instagram"></i></a>';
								$results['cards'][$k] .= '</div>';
							}
							if ($linkedin) {
								$results['cards'][$k] .= '<div class="icon">';
									$results['cards'][$k] .= '<a href="' . $linkedin . '" target="_blank"><i class="fab fa-linkedin"></i></a>';
								$results['cards'][$k] .= '</div>';
							}
						$results['cards'][$k] .= '</div>';
						$results['cards'][$k] .= '</div>';
					$results['cards'][$k] .= '</div>';
				$results['cards'][$k] .= '</div>';
			} else {
				$results['cards'][$k] .= '<div class="card hidden pjs-staff-trans">';
					$results['cards'][$k] .= '<div class="container">';
						$results['cards'][$k] .= '<a href="' . $link . '">';
							$results['cards'][$k] .= '<div class="image" style="background:url(' . $graphicURL . ') no-repeat center / cover;">';
								$results['cards'][$k] .= '<div class="tint pjs-staff-trans"></div>';
							$results['cards'][$k] .= '</div>';
						$results['cards'][$k] .= '</a>';
						$results['cards'][$k] .= '<div class="details">';
							$results['cards'][$k] .= '<h3><a href="' . $link . '">' . $title . '</a></h3>';
							$results['cards'][$k] .= '<p><strong>' . $role . '</strong></p>';
							$results['cards'][$k] .= '<div class="btns center">';
								$results['cards'][$k] .= '<a href="' . $link . '">' . $viewStaffLabel . '</a>';
							$results['cards'][$k] .= '</div>';
						$results['cards'][$k] .= '</div>';
					$results['cards'][$k] .= '</div>';
				$results['cards'][$k] .= '</div>';
			}
			
			$k++;
		}
	}
	
	// total query
	$j = 0;
	
	$totalArgs = array(
		'post_type' => $postType,
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC',
		'paged' => $page,
		'tax_query' => $taxQuery,
	);
	$totalQuery = new WP_Query($totalArgs);
	
	if ($totalQuery->have_posts()) {
		while ($totalQuery->have_posts()) { $totalQuery->the_post();
			$j++;
		}
	}
	
	$results['total'] = $j;
	
	echo json_encode($results);