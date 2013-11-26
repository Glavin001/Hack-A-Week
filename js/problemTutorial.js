/* 
 * Author: Glavin Wiechert
 * Brief Description: This script is used for displaying 
 * the tutor.tutorial steps for each problem in the SMU Hack-A-Week.
 * The video (VideoJs) discusses the steps and the code example (CodeMirror)
 * is displayed below along with a code preview, all in real-time.
 */

// Tutorial namespace
(function(tutor, undefined) {
    // Private variables
    var intervalId = false; // false = not syncing
    var syncInterval = 100; // in milliseconds
    var index = undefined; // undefined when not syncing 
    // Settings
    tutor.videoSettings = {
        "poster": "http://www.smu.ca/webfiles/homebanner.jpg",
        "controls": true,
        "autoplay": false,
        "preload": "auto",
        "width": 640,
        "height": 480
    };
    tutor.tutorial = undefined; // Tutorial JSON data
    // HTML Elements
    var videoElId = "video";        // Video Element Id
    var codeMirrorObj = undefined;  // CodeMirror object instance
    var previewElSel = undefined;   // Preview Element jQuery Selector

    // Public method
    tutor.init = function(settings) {
        videoElId = settings.videoElId;
        codeMirrorObj = settings.codeMirrorObj;
        previewElSel = settings.previewElSel;
        tutor.tutorial = settings.tutorial["data"];
        console.log(tutor.tutorial);
        tutor.initVideo();
    };

    tutor.initVideo = function() {
        // Wait until webpage is fully loaded.
        $(document).ready(function() {
            // Requires VideoJs
            _V_(videoElId, tutor.videoSettings,
                    function( ) {
                        tutor.videoPlayer = this;
                        tutor.videoPlayer.pause(); // Force autoplay = false
                        console.log("Done loading video player.");
                        tutor.startSync();
                    });
        });
    };

    tutor.startSync = function( ) {
        intervalId = setInterval(function() {
            tutor.sync();
        }, syncInterval);
    };

    tutor.sync = function() {
        var currTime = tutor.videoPlayer.currentTime();

        // Find the new index
        var newIndex = 0; // Default is the first index
        for (var i = 1; i < tutor.tutorial.length; i++) {
            if (currTime >= tutor.tutorial[i].timeframe.start
                    && currTime < tutor.tutorial[i].timeframe.end) {
                newIndex = i;
                break;
            }
        }

        // If newIndex is not same as current index
        if (newIndex !== undefined
                && index !== newIndex) {
            console.log(currTime);
            console.log(index, newIndex);

            // Save previous
            if (index !== undefined
                    && codeMirrorObj.getValue() !== tutor.tutorial[index].code) {
                tutor.tutorial[index].saved = codeMirrorObj.getValue();
            }

            index = newIndex;

            // Get values
            var newSavedVal = tutor.tutorial[index].saved;
            var newCodeVal = tutor.tutorial[index].code;// "<h" + parseInt(index%5+1) + ">" + index + "</h" + parseInt(index%5+1) + ">";
            var newPreviewHTML = tutor.tutorial[index].preview; //"<h" + parseInt(index%5+1) + ">" + index + "</h" + parseInt(index%5+1) + ">";

            // Set value of CodeMirror instance
            if (newSavedVal) {
                codeMirrorObj.setValue(newSavedVal);
                // Set preview HTML
                $(previewElSel).html(newSavedVal);
            }
            else {
                codeMirrorObj.setValue(newCodeVal);
                // Set preview HTML
                $(previewElSel).html(newPreviewHTML);
            }
        }
        else { // Has not changed "slides"
            if (codeMirrorObj.getValue() !== tutor.tutorial[index].code
                    && codeMirrorObj.getValue() !== tutor.tutorial[index].saved) {
                // Code has changed
                $(previewElSel).html(codeMirrorObj.getValue());
                tutor.tutorial[index].saved = codeMirrorObj.getValue();

                //tutor.videoPlayer.pause();
            }
        }

    };

    tutor.stopSync = function() {
        clearInterval(intervalId);
        intervalId = false;
    };


})(window.tutor = window.tutor || {});
