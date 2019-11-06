<?php
/**
 * Page template used for staff members
 */

get_header();
	
	// variables from staff settings page
	$slug = get_field('pjs_staff_slug', 'option');
	$viewAllLabel = get_field('pjs_staff_view_all_label', 'option');
	if (!$slug) {
		$slug = 'staff';
	}
	if (!$viewAllLabel) {
		$viewAllLabel = 'View All Staff';
	}
	
	// variables for staff member
	$postType = get_post_type();
	$title = get_the_title();
	$graphic = get_field('image');
	$role = get_field('role');
	$bio = get_field('bio');
	$email = get_field('email');
	$phone = get_field('phone');
	$facebook = get_field('facebook');
	$twitter = get_field('twitter');
	$instagram = get_field('instagram');
	$linkedin = get_field('linkedin');
	if ($graphic) {
		$graphicURL = $graphic['sizes']['large'];
	} else {
		$graphicURL = '/wp-content/plugins/pjs-staff-manager/images/placeholder.jpg';
	}
	
	// build campus(es)
	$campuses = '';
	$campusList = wp_get_post_terms($post->ID, 'pjs-staff-campus');
	$campusCount = count($campusList);
	$i = 1;
	
	foreach($campusList as $campus) {
		if ($i == $campusCount) {
			$campuses .= $campus->name;
		} else {
			$campuses .= $campus->name . ', ';
		}
		$i++;
	}
	
	// build team(s)
	$teams = '';
	$teamsList = wp_get_post_terms($post->ID, 'pjs-staff-team');
	$teamsCount = count($teamsList);
	$k = 1;
	
	foreach ($teamsList as $team) {
		if ($k == $teamsCount) {
			$teams .= $team->name;
		} else {
			$teams .= $team->name . ', ';
		}
		$k++;
	}
	
	echo '<section class="pjs-staff-single">';
		echo '<div class="wrapper">';
			echo '<div class="image">';
				echo '<div class="img" style="background:url(' . $graphicURL . ') no-repeat center / cover;"></div>';
			echo '</div>';
			echo '<div class="content">';
				echo '<h1>' . $title . '</h1>';
				echo '<h4>' . $role . '</h4>';
				if ($email || $phone) {
					echo '<p><em>';
						if ($email) {
							echo '<a href="mailto:' . $email . ';">' . $email . '</a>';
						}
						if ($phone) {
							if ($email) {
								echo '<br>';
							}
							echo '<a href="tel:' . $phone . ';">' . $phone . '</a>';
						}
					echo '</em></p>';
				}
				echo '<div class="social">';
					if ($facebook) {
						echo '<div class="icon">';
							echo '<a href="' . $facebook . '" target="_blank"><i class="fab fa-facebook-f"></i></a>';
						echo '</div>';
					}
					if ($twitter) {
						echo '<div class="icon">';
							echo '<a href="' . $twitter . '" target="_blank"><i class="fab fa-twitter"></i></a>';
						echo '</div>';
					}
					if ($instagram) {
						echo '<div class="icon">';
							echo '<a href="' . $instagram . '" target="_blank"><i class="fab fa-instagram"></i></a>';
						echo '</div>';
					}
					if ($linkedin) {
						echo '<div class="icon">';
							echo '<a href="' . $linkedin . '" target="_blank"><i class="fab fa-linkedin"></i></a>';
						echo '</div>';
					}
				echo '</div>';
				echo $bio;
				echo '<div class="btns">';
					echo '<a href="/' . $slug . '">' . $viewAllLabel . '</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

get_footer();