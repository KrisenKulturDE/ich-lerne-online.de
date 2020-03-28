import { ready, addClass, removeClass, hasClass } from "./helpers.js";
import { throttle } from "lodash-es";
import AjaxCall from './AjaxCall.js';

const containerElement = document.querySelector('[data-tknname][data-tknvalue]');
if (typeof containerElement === 'object' && containerElement instanceof Element) {
  let headers = {
    'X-API-KEY': 'AONAdsGmwQ0BZXa7uzehL'
  };
  headers['X_' + containerElement.getAttribute('data-tknname')] = containerElement.getAttribute('data-tknvalue');

  let postParams = {};
  postParams[containerElement.getAttribute('data-tknname')] = containerElement.getAttribute('data-tknvalue');

  const request = new AjaxCall({
    path: '/api/like/',
    method: 'POST',
    headers: headers,
    initialiseWithCurrentLocation: false,
    postParams: postParams
  });

  const likeButtons = document.querySelectorAll('.like-button');
  for (const button of likeButtons) {
    if (typeof button !== 'object' || !(button instanceof Element)) {
      continue;
    }
    button.addEventListener('click', throttle(like, 500, { leading: true }));
  }

  async function like(event) {
    const element = this;
  
    if (!element.hasAttribute('data-id')) {
      return false;
    }
  
    const id = element.getAttribute('data-id');
    request.path = '/api/like/' + id;
  
    if (hasClass(element, 'active')) {
      request.path = '/api/unlike/' + id;
      request.method = 'POST';
      const response = await request.fetchJSON({ abortable: true });
      if (typeof response === 'object' && response.success === true) {
        removeClass(element, 'active');
      }
    } else {
      request.method = 'POST';
      const response = await request.fetchJSON({ abortable: true });
      if (typeof response === 'object' && response.success === true) {
        addClass(element, 'active');

        if(typeof response.message === 'string' && response.message.length > 0){
          element.querySelector('.message').innerHTML = response.message;
        }
      }
    }
  }
}