(function () {
    "use strict";

    // Unique Google Analytics identifier
    // var disableStr = 'ga-disable-G-0JX343VW9C';

    var cookieAlert = document.querySelector(".cookie-alert");
    var acceptCookies = document.querySelector(".accept-cookies");
    var rejectCookies = document.querySelector(".reject-cookies");

    if (!cookieAlert) {
        return;
    }

    cookieAlert.offsetHeight;

    if (!getCookie("acceptCookies")) {
        cookieAlert.classList.add("show");
    }

    acceptCookies.addEventListener("click", function () {
        setCookie("acceptCookies", true, 365);
        cookieAlert.classList.remove("show");
        window.dispatchEvent(new Event("cookieAlertAccept"))
    });

    // Privacy & legal policy also includes server logs.
    /*
    rejectCookies.addEventListener("click", function () {
        gaOptout();
        cookieAlert.classList.remove("show");
        window.dispatchEvent(new Event("cookieAlertReject"))
    });
    */

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    /*
    function gaOptout() {
		document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
		window[disableStr] = true;
	}
    */
})();
