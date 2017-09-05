/*
 *  ping.js - v0.0.1
 *  Ping Utilities in Javascript
 *  http://github.com/alfg/ping.js
 *
 *  Made by Alfred Gutierrez
 *  Under MIT License
 */
var Ping = function() { this._version = "0.0.1" };
Ping.prototype.ping = function(a, b, c) {
    function d() { e && clearTimeout(e); var a = new Date - f; "function" == typeof b && b(a) }
    this.img = new Image, c = c || 0;
    var e, f = new Date;
    this.img.onload = this.img.onerror = d, c && (e = setTimeout(d, c)), this.img.src = "//" + a + "/?" + +new Date
};