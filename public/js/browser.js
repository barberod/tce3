var $buoop = {
    required: {e:-4,f:-3,s:-1,c:-3},
    insecure: true,
    api: 2023.07,
    reminder: 0,
    jsshowurl: "../js/browser-alert.js",
    test: false
}; 

function $buo_f(){ 
    var e = document.createElement("script"); 
    e.src = "https://browser-update.org/update.min.js";
    document.body.appendChild(e);
};

function currentMonth() {
    var dt = moment().tz("America/New_York").format('YYYY.MM');
    console.log(dt);
    return dt;
}

try {
    document.addEventListener("DOMContentLoaded", $buo_f, false)
}
catch(e) {
    window.attachEvent("onload", $buo_f)
}