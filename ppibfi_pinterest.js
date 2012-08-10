jQuery(window).ready(function(jQuery) {
	jQuery('.xc_pinterest').each(function(index, element) {
		var	$this = jQuery(this),
				$theImage = jQuery($this.find('img:first-child')[0]),
				theImageWidth = $theImage.width(), //Gets the images width
				theImagePosition = $theImage.position(), //Gets the images position (left/right/center)
				$theButton = jQuery($this.find('.xc_pin')[0]);
				
		theImageWidth = $theImage.outerWidth(); // Width of image + padding + border, etc
			//Center image = (ContentWidth / 2) + (ImageWidth /2) - ButtonWidth
		if ($theImage.hasClass('aligncenter')) {
			theContentCenter = ContentWidth/2;
			halfOfImgWidth = theImageWidth/2;
			newPosition = theContentCenter + halfOfImgWidth - $theButton.width();
		}
			//Right image = ContentWidth - ButtonWidth
		else if ($theImage.hasClass('alignright')) {
			newPosition = ContentWidth - $theButton.width(); 
		}
			//Left image and unknown alignment = ImageWidth - ButtonWidth
		else {
			//For imgs with captions / zero width
			if (theImageWidth == 0) newPosition = 0;
			//Known width / !zero width
			else newPosition = theImageWidth - $theButton.width();		
		}
		$theButton.css('left', newPosition);
		$theButton.css('display', 'block');
	});	
	jQuery('.xc_pin').hover
	(
		function(e){
			jQuery(this).prev().addClass('xc_pinterest_hover');
		},
		function(e){
			jQuery(this).prev().removeClass('xc_pinterest_hover');
		}
	);
	jQuery('.xc_pin').click(function(e){
		var $this = jQuery(this),
				pin_base_url = $this.attr('data-xc_pinterest_base_url'),
				pin_post_url = encodeURIComponent($this.attr('data-xc_pinterest_post_url')),
				pin_media = encodeURIComponent($this.attr('data-xc_pinterest_media')),
				pin_desc = encodeURIComponent($this.attr('data-xc_pinterest_description')),
				url = pin_base_url + '?' + 'url=' + pin_post_url + '&media=' + pin_media + '&description=' + pin_desc;
		window.open(url, 'pinterest', 'screenX=100,screenY=100,height=580,width=730');
		e.preventDefault();
	});
});