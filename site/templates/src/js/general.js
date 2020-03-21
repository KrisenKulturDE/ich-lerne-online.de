/* jshint -W024 */
// import "@babel/polyfill";
import { ready } from "./classes/helpers.js";
import Scrollinator from "./classes/Scrollinator.js";
import './classes/animated-header';
import './classes/events-box';

import components from "./components/*.js";

const scrollinator = new Scrollinator({
	scrollOffset: 40,
	headerOffset: document.querySelector('header>nav')
});

scrollinator.addObservedLinkElements(".highlight-navigation .nav-item, .highlight-navigation .dropdown-menu .dropdown-item");
scrollinator.addObservedElements('section');

// Bootstrap will be reloaded:
(async () => {
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/util');
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/alert');
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/button');
	// await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/carousel');
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/collapse');
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/dropdown');
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/modal');
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/popover');
	// await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/scrollspy');
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/tab');
	// await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/toast');
	await import (/* webpackChunkName: "bootstrap-js" */ 'bootstrap/js/src/tooltip');

	window.lazySizesConfig = window.lazySizesConfig || {};
	window.lazySizesConfig.init = false;
	const {ImageHandler: ImageHandler} = await import(/* webpackChunkName: "image-handler" */ './classes/ImageHandler');

	ready(function() {
		ImageHandler.init();
	});
})();
