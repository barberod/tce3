"use strict";

var chrome = { 'name': 'Google Chrome', 'url': 'https://www.google.com/chrome/' };
var edge = { 'name': 'Microsoft Edge', 'url': 'https://www.microsoft.com/en-us/edge' };
var firefox = { 'name': 'Mozilla Firefox', 'url': 'https://www.mozilla.org/en-US/firefox/new/' };
var safari = { 'name': 'Apple Safari', 'url': 'https://support.apple.com/en-us/HT204416' };

var $showBrowserAlert = function () {
    var bb = $bu_getBrowser();
    if (bb.t.includes('Chrome')) {
        browserAlert(bb.t, chrome.name, chrome.url);
    } else if (bb.t.includes('Edge')) {
        browserAlert(bb.t, edge.name, edge.url);
    } else if (bb.t.includes('Firefox')) {
        browserAlert(bb.t, firefox.name, firefox.url);
    } else if (bb.t.includes('Safari')) {
        browserAlert(bb.t, safari.name, safari.url);
    } else {
        otherAlert(bb.t);
    }
}

function browserAlert(version, name, url) {
    var alert = '<p class="mb-0">';
    alert += '<strong>Your web browser (' + version + ') is out of date.</strong> Update your browser for more security and the best experience of this site.';
    alert += '<a class="ms-2" href="' + url + '" target="_blank">Update ' + name + '</a>';
    alert += '</p>';
    document.getElementById('browser').innerHTML = '<div class="alert alert-info" role="alert">' + alert + '</div>';
}

function otherAlert(version) {
    var alert = '<p><strong>Your web browser (' + version + ') is not recommended.</strong> Use the most up-to-date version of a web browser from the list.</p>';
    alert += '<ul class="mb-0">';
    alert += '<li><a href="' + chrome.url + '" target="_blank">' + chrome.name + '</a></li>';
    alert += '<li><a href="' + edge.url + '" target="_blank">' + edge.name + '</a></li>';
    alert += '<li><a href="' + firefox.url + '" target="_blank">' + firefox.name + '</a></li>';
    alert += '<li><a href="' + safari.url + '" target="_blank">' + safari.name + '</a></li>';
    alert += '</ul>';
    document.getElementById('browser').innerHTML = '<div class="alert alert-info" role="alert">' + alert + '</div>';
}

$showBrowserAlert();
