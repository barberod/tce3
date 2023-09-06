"use strict";

var missingFeatures = [];

var $showFeatureAlert = function () {

    if (!Modernizr.cookies) { missingFeatures.push('Cookies') }

    if (!Modernizr.eventlistener) { missingFeatures.push('Event Listener') }
    if (!Modernizr.inputtypes) { missingFeatures.push('Form input types') }
    if (!Modernizr.json) { missingFeatures.push('JSON') }
    if (!Modernizr.queryselector) { missingFeatures.push('QuerySelector') }

    if (!Modernizr.cssanimations) { missingFeatures.push('CSS Animations') }
    if (!Modernizr.bgpositionshorthand) { missingFeatures.push('Background Position Shorthand') }
    if (!Modernizr.bgrepeatspace) { missingFeatures.push('Background Repeat') }
    if (!Modernizr.backgroundsize) { missingFeatures.push('Background Size') }

    if (!Modernizr.bgsizecover) { missingFeatures.push('Background Size Cover') }
    if (!Modernizr.borderradius) { missingFeatures.push('Border Radius') }
    if (!Modernizr.boxshadow) { missingFeatures.push('Box Shadow') }
    if (!Modernizr.cssgradients) { missingFeatures.push('CSS Gradients') }

    if (!Modernizr.cssinvalid) { missingFeatures.push('CSS :invalid pseudo-class') }
    if (!Modernizr.lastchild) { missingFeatures.push('CSS :last-child pseudo-selector') }
    if (!Modernizr.cssvalid) { missingFeatures.push('CSS :valid pseudo-class') }
    if (!Modernizr.opacity) { missingFeatures.push('CSS Opacity') }

    if (!Modernizr.cssremunit) { missingFeatures.push('CSS Font rem Units') }
    if (!Modernizr.rgba) { missingFeatures.push('CSS rgba') }
    if (!Modernizr.classlist) { missingFeatures.push('classList') }
    if (!Modernizr.filereader) { missingFeatures.push('File API') }

    if (!Modernizr.fileinput) { missingFeatures.push('input[file] Attribute') }
    if (!Modernizr.svgasimg) { missingFeatures.push('SVG as an img tag source') }
    if (!Modernizr.inlinesvg) { missingFeatures.push('Inline SVG') }
    if (!Modernizr.urlparser) { missingFeatures.push('URL parser') }

    if (missingFeatures.length === 1 ) {
        featureAlert(false);
    } else if (missingFeatures.length > 1) {
        featureAlert(true);
    }
}

function featureAlert(plural) {
    if (plural) {
        var first = 'important capabilities';
        var second = 'these features';
        var third = 'these missing web technologies';
    } else {
        var first = 'an important capability';
        var second = 'this feature';
        var third = 'this missing web technology';
    }

    var alert = '<p class="mb-0"><strong>Your browser lacks ' + first + '.</strong> Automatic tests could not detect ' + second + ': ';
    alert += missingFeaturesNiceString() + '. ';
    alert += 'The site will not function properly unless you update your browser and/or adjust your ';
    alert += 'settings to add or restore ' + third + '.</p>';
    document.getElementById('features').innerHTML = '<div class="alert alert-info" role="alert">' + alert + '</div>';
}

function missingFeaturesNiceString() {
    var output = '';
    for (let i = 0, j = 1; i < missingFeatures.length; i++, j++) {
        output += '(' + j + ') ' + missingFeatures[i];
        if (i < missingFeatures.length - 1) {
            output += ', ';
        }
    }
    return output;
}

$showFeatureAlert();
