
function indicateOutages(xmlDoc, dt) {
    var x = xmlDoc.getElementsByTagName("outage");
    var outageCount = 0;

    if (x.length > 0) {
        for (i = 0; i < x.length; i++) {
            // Parse values from XML data
            var findClass = xmlDoc.getElementsByTagName("findClass")[i].childNodes[0].nodeValue;
            var addClass = xmlDoc.getElementsByTagName("addClass")[i].childNodes[0].nodeValue;
            var fromDT = xmlDoc.getElementsByTagName("inEffect")[i].getElementsByTagName("from")[0].childNodes[0].nodeValue;
            var untilDT = xmlDoc.getElementsByTagName("inEffect")[i].getElementsByTagName("until")[0].childNodes[0].nodeValue;
            var livepage = xmlDoc.getElementsByTagName("audience")[i].getElementsByTagName("livepage")[0].childNodes[0].nodeValue;
            var downpage = xmlDoc.getElementsByTagName("audience")[i].getElementsByTagName("downpage")[0].childNodes[0].nodeValue;

            // Check dates
            if ( (fromDT < dt) && (untilDT > dt) ) {

                // Check audience and page version
                if ( 
                    ( (window.page_version == 'down') && ( (downpage.charAt(0) ==  'Y') || (downpage.charAt(0) ==  'y') || (downpage.charAt(0) ==  '1') ) ) ||
                    ( (window.page_version == 'live') && ( (livepage.charAt(0) ==  'Y') || (livepage.charAt(0) ==  'y') || (livepage.charAt(0) ==  '1') ) )
                    ) {

                    // Disable links by adding "disabled" or "disabled verbose" classes
                    var links = document.getElementsByClassName(findClass);
                    for (var j = 0; j < links.length; j++) {
                        links[j].classList.add(addClass);
                    }

                    // Increment outage count
                    outageCount++;
                }
            }
        }
        if (outageCount === 0) {
            console.log("Scheduled outage data successfully gotten. None to show.");
        }
    } else {
        console.log("No scheduled outage data.");
    }
}

function httpGetAsync2(theUrl, callback) {
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function () {
      if (xmlHttpReq.readyState == 4 && xmlHttpReq.status == 200)
        callback(xmlHttpReq.responseXML);
    }
    xmlHttpReq.open("GET", theUrl, true); // true for asynchronous 
    xmlHttpReq.send(null);
}

function getDtNow2() {
    var dt = moment().tz("America/New_York").format('YYYYMMDDHHmmss');
    return dt;
}

httpGetAsync2('../xml/plan.xml?cache=' + getDtNow2(), function(result){
    indicateOutages(result, getDtNow2());
});