
function printAnnouncements(xmlDoc, dt) {
    var x = xmlDoc.getElementsByTagName("announcement");
    var announcementCount = 0;
    if (x.length > 0) {
        
        // Assemble announcements list
        var announcementsList = '<ul id="announcements-list">';
        for (i = 0; i < x.length; i++) {

            // Parse values from XML data

            var itemID = xmlDoc.getElementsByTagName("itemID")[i].childNodes[0].nodeValue;
            var customClass = xmlDoc.getElementsByTagName("customClass")[i].childNodes[0].nodeValue;
            var customStyle = xmlDoc.getElementsByTagName("customStyle")[i].childNodes[0].nodeValue;
            var title = xmlDoc.getElementsByTagName("title")[i].childNodes[0].nodeValue;
            var body = xmlDoc.getElementsByTagName("body")[i].childNodes[0].nodeValue;
            var fromDT = xmlDoc.getElementsByTagName("display")[i].getElementsByTagName("from")[0].childNodes[0].nodeValue;
            var untilDT = xmlDoc.getElementsByTagName("display")[i].getElementsByTagName("until")[0].childNodes[0].nodeValue;
            var livepage = xmlDoc.getElementsByTagName("audience")[i].getElementsByTagName("livepage")[0].childNodes[0].nodeValue;
            var downpage = xmlDoc.getElementsByTagName("audience")[i].getElementsByTagName("downpage")[0].childNodes[0].nodeValue;

            // Check dates
            if ( (fromDT < dt) && (untilDT > dt) ) {

                // Check audience and page version
                if ( 
                    ( (window.page_version == 'down') && ( (downpage.charAt(0) ==  'Y') || (downpage.charAt(0) ==  'y') || (downpage.charAt(0) ==  '1') ) ) ||
                    ( (window.page_version == 'live') && ( (livepage.charAt(0) ==  'Y') || (livepage.charAt(0) ==  'y') || (livepage.charAt(0) ==  '1') ) )
                    ) {

                    // Create list item
                    if ((title !== 'null') && (body !== 'null')) {
                        announcementsList += '<li class="' + customClass + ' mb-4" id="' + itemID + '" style="' + customStyle + '">' ;
                        announcementsList += '<strong>' + title + '</strong>';
                        announcementsList += '<ul class="mt-2"><li>' + body + '</li></ul>';
                        announcementsList += '</li>';
                    } else if (title !== 'null') {
                        announcementsList += '<li class="' + customClass + ' mb-4" id="' + itemID + '" style="' + customStyle + '">' ;
                        announcementsList += '<strong>' + title + '</strong>';
                        announcementsList += '</li>';
                    } else if (body !== 'null') {
                        announcementsList += '<li class="' + customClass + ' mb-4" id="' + itemID + '" style="' + customStyle + '">' ;
                        announcementsList += body;
                        announcementsList += '</li>';
                    }

                    // Increment announcement count
                    announcementCount++;

                }

            }
        }

        // Print announcements list or don't.
        if (announcementCount > 0) {
            document.getElementById("announcements").innerHTML = announcementsList;
            document.getElementById("mobile-announcements").innerHTML = announcementsList;
        } else {
            console.log("Announcements data successfully gotten. None to show.");
        }
        
    } else {
        console.log("No announcements data.");
    }
}

function httpGetAsync(theUrl, callback) {
    let xmlHttpReq = new XMLHttpRequest();
    xmlHttpReq.onreadystatechange = function () {
      if (xmlHttpReq.readyState == 4 && xmlHttpReq.status == 200)
        callback(xmlHttpReq.responseXML);
    }
    xmlHttpReq.open("GET", theUrl, true); // true for asynchronous 
    xmlHttpReq.send(null);
}

function getDtNow() {
    var dt = moment().tz("America/New_York").format('YYYYMMDDHHmmss');
    return dt;
}

httpGetAsync('../xml/feed.xml?cache=' + getDtNow(), function(result){
    printAnnouncements(result, getDtNow());
});