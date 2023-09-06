function scrollToTop() {
    window.scroll({top: 0, left: 0, behavior: 'smooth'});
}

function debounce(func, wait = 10, immediate = true) {
    let timeout;
    return function() {
        let context = this, args = arguments;
        let later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
        };
        let callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

let scrollPos = 0;
const nav = document.querySelector('.scroll-top');

function checkPosition() {
    let windowY = window.scrollY;
    if (windowY < scrollPos && window.pageYOffset > 400) {
        // Scrolling UP
        if (nav.style.display != 'block') {
            fadeIn();
        }
    } else {
        // Scrolling DOWN
        fadeOut();
    }
    scrollPos = windowY;
}

function fadeIn() {
    nav.style.opacity = 0;
    nav.style.display = "block";
    (function fade() {
        var val = parseFloat(nav.style.opacity);
        if (!((val += 0.05) > 1)) {
            nav.style.opacity = val;
            requestAnimationFrame(fade);
        }
    })();
};

function fadeOut() {
    (function fade() {
        if ((nav.style.opacity -= 0.002) < 0) {
            nav.style.display = "none";
        } else {
            requestAnimationFrame(fade);
        }
    })();
};

window.addEventListener('scroll', debounce(checkPosition));