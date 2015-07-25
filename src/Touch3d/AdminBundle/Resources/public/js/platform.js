/*-----------------------
 * jQuery Plugin: Platform
 * This version is created by Khiari Marwen for Touch3d.tn
 * Version: 1.0, 09/05/2014
 * Help: Implement this function onResizeFunction(); in your class and att SIZE for test
 * -----------------------*/

var SIZE_ = {
    SMALL: {value: 0, name: "SMALL", platform: "MOBILE", code: "S"},
    MEDIUM: {value: 1, name: "MEDIUM", platform: "TABLET", code: "M"},
    LARGE: {value: 2, name: "LARGE", platform: "COMPUTER", code: "L"}
};
var SIZE = SIZE_.LARGE;
var lastSIZE = "n";
var widthPlatform;
testPlatform();
function testPlatform() {
    widthPlatform = window.getComputedStyle(document.body, null).width;
    widthPlatform = parseInt(widthPlatform, 10);//10 to remove px
    if (widthPlatform < 670)SIZE = SIZE_.SMALL.name;
    else if (widthPlatform > 960)SIZE = SIZE_.LARGE.name;
    else SIZE = SIZE_.MEDIUM.name;
    if (lastSIZE != "n") {
        //if (lastSIZE != SIZE)document.location.reload();
        if (lastSIZE != SIZE)onResizeFunction();
    }

    lastSIZE = SIZE;
}
window.onresize = function () {
    testPlatform();
}
/*
 $(window).on('resize', function(){
 testPlatform();
 });
 */