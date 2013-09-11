(function ($) {
	"use strict";
	$(function () {

		var articleRead = false;

		$("article").waypoint(function(direction) {

			// If we have not already logged an action for this pageview
			if(!articleRead){

				articleRead = true;
				
                var sArticleId, iPostId;
 
                // Get the article ID and split it - the second index is always the post ID in Twenty Eleven
                sArticleId = $("article").attr('id');
                iPostId = parseInt(sArticleId.split('-')[1]);
				
				$.ajax({  
  					type: 'POST',  
  					url: ajaxurl,  
  					data: {
	  					action: 'log_read',  
	  					ID: iPostId,  
	  				}
  				});    
	        } // end if
		}, {
			// Offset the article scroll amount by 100 to combat false postitves.
			offset: function() {
				return $.waypoints('viewportHeight') - $(this).height() - 100;
			}
		});

	});
}(jQuery));