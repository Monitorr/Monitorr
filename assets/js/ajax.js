/*
 *  ajax.js - v0.0.1
 *  Request URL header in javascript
 *  http://github.com/wjbeckett/
 *
 *  Made by William Beckett
 *  Under MIT License
 */

var AReq = function() { this._version = "0.0.1" };
AReq.prototype.ajaxreq = function(url, cb) {
  jQuery.ajax({
    url: url,
    cache: false,
    dataType: 'text',
    type: 'head',
    complete: function(xhr) {
      if (typeof cb === 'function')
        cb.apply(this, [xhr.status]);
    }
  });
}
