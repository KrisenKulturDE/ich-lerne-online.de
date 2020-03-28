/* jshint -W024 */
// import "@babel/polyfill";
import { ready } from "./classes/helpers.js";
import Scrollinator from "./classes/Scrollinator.js";

import components from "./components/*.js";

const scrollinator = new Scrollinator({
	scrollOffset: 40,
	headerOffset: document.querySelector('header>nav')
});

scrollinator.addObservedLinkElements(".highlight-navigation .nav-item, .highlight-navigation .dropdown-menu .dropdown-item");
scrollinator.addObservedElements('section');

(async () => {
	window.lazySizesConfig = window.lazySizesConfig || {};
	window.lazySizesConfig.init = false;
	const {ImageHandler: ImageHandler} = await import(/* webpackChunkName: "image-handler" */ './classes/ImageHandler');

	await import(/* webpackChunkName: "like-button" */ "./classes/LikeButton");

	ready(function() {
		ImageHandler.init();
	});
})();
