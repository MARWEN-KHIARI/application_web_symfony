var sld = getElements(".sldx");
var maxLefth = Array("90", "85", "80");
var maxLeftm = Array("60", "55", "50");
var maxLeftl = Array("50", "45", "40");
var depart = "-40";
var sld_ = "#sld";
function slideSwitch(switchSpeed) {
    onBefore();
    var $active = $("#slideshow img.active");
    if ($active.length == 0) {
        $active = $("#slideshow img:last")
    }
    var $next = $active.next("img").length ? $active.next("img") : $("#slideshow img:first");
    $active.addClass("last-active");
    $next.css({opacity: 0}).addClass("active").animate({opacity: 1}, switchSpeed, function () {
        $active.removeClass("active last-active")
    });
    function_anim(3000)
}
$(function () {
    setInterval("slideSwitch(1000)", 6000)
});
var sld_active;
var p = -1;
onBefore();
function onBefore() {
    p++;
    if (p == sld.length) {
        p = 0
    }
    $(sld_).fadeIn("slow");
    sld_active = sld[p];
    for (var i = 0; i < sld.length; i++) {
        if (i != p) {
            $(sld[i]).hide()
        }
    }
    $(sld_active).css({position: "relative", opacity: 0, left: depart + "%"});
    $(sld_active).show();
    $(sld_).css({left: 0, top: (Math.round(Math.random() * 80)) + "%"})
}
function_anim(3000);
function function_anim(switchSpeed) {
    var dleft = 0;
    if (platform == "h") {
        dleft = maxLefth[p]
    } else {
        if (platform == "m") {
            dleft = maxLeftm[p]
        } else {
            dleft = maxLeftl[p]
        }
    }
    $(sld_active).animate({queue: true, opacity: 1, duration: "slow", left: "+=" + (dleft) + "%", easing: "easeOutBack"}, switchSpeed, function () {
        $(sld_).delay(600).fadeOut("slow")
    })
};