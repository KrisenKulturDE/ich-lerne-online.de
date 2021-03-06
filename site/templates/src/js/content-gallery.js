/* jshint -W024 */
import {ready} from './classes/helpers.js';

(async () => {
	let lightGalleryCounter = 0;

	// Load Lightslider:
	const sliderElements = document.querySelectorAll('.lightslider');
	if(sliderElements.length > 0){
		await import('lightslider/src/js/lightslider.js');

		await import('lightgallery/dist/js/lightgallery.js');
		await import('lg-autoplay/dist/lg-autoplay.js');
		await import('lg-fullscreen/dist/lg-fullscreen.js');
		await import('lg-hash/dist/lg-hash.js');
		await import('lg-pager/dist/lg-pager.js');
		await import('lg-thumbnail/dist/lg-thumbnail.js');
		await import('lg-zoom/dist/lg-zoom.js');

		ready(function(){
			for(let index in sliderElements){
				const element = sliderElements[index];
				if(typeof element !== 'object' || !(element instanceof Element)){
					continue;
				}

				const slider = $(element).lightSlider({
					autoWidth: true,
					loop: true,
					gallery: true,
					slideMargin: 0,

					onSliderLoad: function(el) {
						el.lightGallery({
							gallerId: ++lightGalleryCounter
						});
					}
				});

				element.addEventListener("lazyloaded", function () {
					slider.refresh();
				});
			}
		});
	}

	// load Lightgallery [jQuery-Version]:
	const galleryElements = document.querySelectorAll('.lightgallery');
	if(galleryElements.length > 0){
		await import('lightgallery/dist/js/lightgallery.js');
		await import('lg-autoplay/dist/lg-autoplay.js');
		await import('lg-fullscreen/dist/lg-fullscreen.js');
		await import('lg-hash/dist/lg-hash.js');
		await import('lg-pager/dist/lg-pager.js');
		// await import('lg-share/dist/lg-share.js');
		await import('lg-thumbnail/dist/lg-thumbnail.js');
		await import('lg-zoom/dist/lg-zoom.js');

		ready(function(){
			for(let index in galleryElements){
				const element = galleryElements[index];
				if(typeof element !== 'object' || !(element instanceof Element)){
					continue;
				}

				$(element).lightGallery({
					selector: '.lightgallery-item',
					galleryId: ++lightGalleryCounter
				});
			}
		});
	}

})();