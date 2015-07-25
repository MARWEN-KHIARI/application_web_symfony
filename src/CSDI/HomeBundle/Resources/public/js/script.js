var platform;
var lastPlatform = "n";
testPlatform();
function testPlatform() {
    var widthPlatform = window.getComputedStyle(document.getElementById("container"), null).width;
    if (widthPlatform >= "960px") {
        platform = "h"
    } else {
        if (widthPlatform >= "670px") {
            platform = "m"
        } else {
            platform = "l"
        }
    }
    if (lastPlatform != "n") {
        if (lastPlatform != platform) {
            document.location.reload()
        }
    }
    lastPlatform = platform
}
function getElements(tag) {
    return $("div").find(tag)
}
$(".slogan").animate({queue: false, duration: "slow", opacity: "+=1", left: "-=100%", easing: "swing"}, 4000);
$(function () {
    $(window).scroll(function () {
        if ($(window).scrollTop() <= "100") {
            $("nav").removeClass("floatMenu");
            $("nav").show()
        } else {
            $("nav").addClass("floatMenu");
            $("nav").hide()
        }
    })
});
function showMenuFloat() {
    if (platform == "h") {
        $("nav").css({left: "90%"})
    } else {
        if (platform == "m") {
            $("nav").css({left: "95%"})
        } else {
            $("nav").css({left: "80%"})
        }
    }
    $("nav").show().animate({queue: false, duration: "slow", left: "-=80%", easing: "ease-in-out"}, 400)
}
$("#menuFloat").fadeOut("slow")
$(function () {
    $.fn.scrollToTop = function () {
        $(this).hide().removeAttr("href");
        if ($(window).scrollTop() != "0") {
            $(this).fadeIn("slow")
        }
        var scrollDiv = $(this);
        $(window).scroll(function () {
            if ($(window).scrollTop() == "0") {
                $(scrollDiv).fadeOut("slow")
                $("#menuFloat").fadeOut("slow")

            } else {
                $(scrollDiv).fadeIn("slow")
                $("#menuFloat").fadeIn("slow")
            }
        });
        $(this).click(function () {
            $("html, body").animate({scrollTop: 0}, "slow")
        })
    }
});
$(function () {
    $("#toTop").scrollToTop()
});
function scrolltop() {
    $("html, body").animate({scrollTop: 0}, "slow")
};