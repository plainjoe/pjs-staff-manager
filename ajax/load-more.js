$(document).ready(function() {
	
	// filter change
	$('.pjs-staff-archive .wrapper .filters select').change(function() {
		var filterType = $(this).attr('filter');
		var filterValue = $(this).val();
		
		$('.pjs-staff-archive .wrapper .cards').attr(filterType, filterValue);
		$('.pjs-staff-archive .wrapper .cards').attr('page', 1);
		
		clearColumns();
		loadMore();
	});
	
	// load items on button click
	$('.pjs-staff-archive .wrapper > .btns a').click(function() {
		loadMore();
	});
	
	// load initial items
	loadMore();
	
	// loadMore function
	function loadMore() {
		var lmBtn = $('.pjs-staff-archive .wrapper > .btns');
		var loader = $('.pjs-staff-archive .wrapper .loader');
		var noResults = $('.pjs-staff-archive .wrapper .no-results');
		var container = $('.pjs-staff-archive .wrapper .cards');
		var postType = container.attr('post-type');
		var campus = container.attr('campus');
		var team = container.attr('team');
		var page = container.attr('page');
		var offset = container.attr('offset');
		
		lmBtn.slideUp(300);
		noResults.slideUp(300);
		loader.slideDown(300);
		
		$.ajax({
			type: 'POST',
			url: '/wp-content/plugins/pjs-staff-manager/ajax/load-more.php',
			data: {post_type:postType, campus:campus, team:team, page:page, offset:offset}
		}).done(function(results) {
			
			results = $.parseJSON(results);
			
			// console.log(results);
			
			if (results.cards) {
				for (i = 0; i < results.cards.length; i++) {
					container.append(results.cards[i]);
				}
			} else {
				noResults.slideDown(300);
			}
			
			container.attr('page', results.page);
			container.attr('total', results.total);
			container.attr('offset', results.offset);
			loader.slideUp(300);
			
			setTimeout(function() {
				if (results.total > $('.pjs-staff-archive .wrapper .cards .card').length) {
					lmBtn.slideDown(300);
				}
			}, 300);
			
			$('.pjs-staff-archive .wrapper .cards .card').each(function() {
				$(this).removeClass('hidden');
			});
			
		});
	}
	
	// clearColumns function
	function clearColumns() {
		$('.pjs-staff-archive .wrapper .cards .card').each(function() {
			var thisCard = $(this);
			
			thisCard.addClass('hidden');
			
			setTimeout(function() {
				thisCard.remove();
			}, 300);
		});
	}
	
});