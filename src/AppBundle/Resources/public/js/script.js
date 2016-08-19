(function($) {
	
	// Apply classes to form elements for IE6
	// Apply class on hover for buttons
	if( ie6 != 'undefined' && ie6 === true) {
		$('input:checkbox').addClass('checkbox');
		$('input:text').addClass('text');
		$('input:radio').addClass('radio');
		$('input:file').addClass('file');
		$('input:image').addClass('image');
		$('input:reset').addClass('reset');
		$('input:password').addClass('password');
		$('input:submit').addClass('submit');
		$('input:button').addClass('input_button');

		$('button').hover(
			function(){
				$(this).addClass('hover');
			},
			function(){
				$(this).removeClass('hover');
		});
	}
	
	// Clear input text fields on focus
	$.unobtrusivelib(['autoClearInput']);
	
	// Carousel
	var size = 4, 
		$slider = $('.slider-container'),
		$next = $slider.find('.next');
	$slider.scrollable({
		onSeek: function() {
			if (this.getIndex() >= this.getSize() - size) {
				$next.parent('span').addClass('disabled');
			}
		},
		onBeforeSeek: function(event, index) {
			if (this.getIndex() >= this.getSize() - size) {
				if (index > this.getIndex()) {
					return false;
				}
			}
		}
	});
	
	// Accordion
	var $accordion = $('.accordion');
	$accordion.tabs($accordion.find('.pane'), {
		tabs: $accordion.find('.accordion-title'),
		effect: 'slide', initialIndex: 0
	});
	
	// Nav
	var $nav = $('#nav');
	$nav.find('>li').each(function() {
		var $this = $(this);
		if($this.children()) {
			var $sub = $this.find('ul');
			$this.bind('mouseover', function() {
				$this.addClass('hover');
				$sub.stop(true, true).slideDown();
			});
			$this.bind('mouseleave', function() {
				$this.removeClass('hover');
				$sub.stop(true, true).slideUp();
			});
		}
	});

})(jQuery);

