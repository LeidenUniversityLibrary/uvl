
jQuery(document).ready( function($) {

	// insert an element to register whether we are mobile or not - show/hide using media queries
	$('body').append("<div id='mobcheck'></div>");
	
	$(window).resize( function() {
		
		if ( $('#mobcheck').is(':hidden') ) {
			// whatever you like
		}
	});	
	
	// jQuery equiv of object-fit - hide img and set as covering background of its parent 
	$('XXX.dc-object-fit').each( function() {
		
		var parent = $(this).parent();
		
		parent.css( 'background-image', 'url("' + $(this).attr('src') + '")' );
		parent.css( 'background-size', 'cover' );
		parent.css( 'background-position', 'center' );
		$(this).hide();
	});

	cbpHorizontalMenu.init();

	//Islandora object detail page: URL popup
	$("#link-button").click(function(e){
		var url = jQuery(this).attr('href');
		prompt('Copy / paste the URL below',url);
		e.preventDefault();
	});

	$('.dc-searchresults-btn-save').click(function(e){
		console.log('In click');
		var url = window.location.href;
		prompt('Copy / paste the URL below',url);
		e.preventDefault();
	});
	

	var button = $(".pager-last > a");
	button.html("<span>last</span>");
	var button = $(".pager-first > a");
	button.html("<span>last</span>");
	var button = $(".pager-previous > a");
	button.html("<span>previous</span>");
	var button = $(".pager-next > a");
	button.html("<span>next</span>");
	var button = $(".dc-searchresults-tools a.display-default");
	button.html("<span>List</span>");
	var button = $(".dc-searchresults-tools a.display-grid");
	button.html("<span>Grid</span>");

        /* book buttons */
        var tabs = $("body.page-islandora-object main div.tabs ul.tabs.primary");

        var pagesTab = tabs.find('a[href$="pages"]').parent();
        if (pagesTab.size() > 0) {
          var pagesButton = pagesTab.find('a').clone().insertBefore(tabs.parent()).html('View pages').wrap('<DIV/>');
          if (pagesTab.is('.active')) {
            pagesButton.parent().hide();
          }

          var viewTab = tabs.find('li').first();
          var viewWhat = (tabs.find('a[href$="issue_pages"]').size() > 0) ? 'View issue' : 'View book';
          var viewButton = viewTab.find('a').clone().insertBefore(tabs.parent()).html(viewWhat).wrap('<DIV/>'); 
          if (viewTab.is('.active')) {
            viewButton.parent().hide();
          }

          viewButton.add(pagesButton).parent().addClass('bookNavButton');
          tabs.css({'clear' : 'both'});
          if (tabs.find('li').size() == 2) {
            //tabs.css({'visibility' : 'hidden'});
          }
          viewButton.add(pagesButton).each(function() {
            var oldhref = $(this).attr('href');
            if (oldhref.indexOf('?') == -1 && location.search != '') {
              $(this).attr('href', oldhref + location.search); 
            }
          });
        }
});



