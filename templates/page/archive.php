<?php
/**
 * Page template used for the staff landing page
 */
 
get_header();
	
	// staff manager variables
	$postType = get_post_type();
	$archiveTitle = get_field('pjs_staff_archive_title', 'option');
	$loadMoreLabel = get_field('pjs_staff_load_more_label', 'option');
	
	if (!$loadMoreLabel) {
		$loadMoreLabel = 'Load More Staff';
	}
	
	// total staff count
	$k = 0;
	
	$totalArgs = array(
		'post_type' => $postType,
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'DESC',
	);
	$totalQuery = new WP_Query($totalArgs);
	
	if ($totalQuery->have_posts()) {
		while ($totalQuery->have_posts()) { $totalQuery->the_post();
			$k++;
		}
	}
	
	$campuses = get_terms('pjs-staff-campus');
	$teams = get_terms('pjs-staff-team');
	
	echo '<section class="pjs-staff-archive">';
		echo '<div class="wrapper">';
			if ($archiveTitle) {
				echo '<h1>' . $archiveTitle . '</h1>';
			}
			echo '<div class="filters">';
				if ($campuses) {
					echo '<div class="filter">';
						echo '<div class="container">';
							echo '<select filter="campus">';
								echo '<option value="all">Filter by Campus</option>';
								
								foreach ($campuses as $campus) {
									echo '<option value="' . $campus->term_id . '">' . $campus->name . '</option>';
								}
								
							echo '</select>';
						echo '</div>';
					echo '</div>';
				}
				if ($teams) {
					echo '<div class="filter">';
						echo '<div class="container">';
							echo '<select filter="team">';
								echo '<option value="all">Filter by Team</option>';
								
								foreach ($teams as $team) {
									echo '<option value="' . $team->term_id . '">' . $team->name . '</option>';
								}
								
							echo '</select>';
						echo '</div>';
					echo '</div>';
				}
			echo '</div>';
			echo '<div class="cards" post-type="' . $postType . '" campus="all" team="all" page="1" total="' . $k . '" offset="0">';
				// cards are added through an AJAX call located in ajax/load-more.js
			echo '</div>';
			echo '<div class="no-results"><p>No results found. Try changing the filters.</p></div>';
			echo '<div class="loader"><img src="' . plugins_url('/pjs-staff-manager/images/loader.svg') . '" /></div>';
			echo '<div class="btns center">';
				echo '<a href="javascript:;">' . $loadMoreLabel . '</a>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

get_footer();
