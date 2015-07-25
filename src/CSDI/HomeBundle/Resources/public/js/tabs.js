var tabsmn = getElements(".tabx-menu");
var tabs = getElements(".tabx");
var lastActive = -1;
$(tabs).hide();
affTabs(null, 0);
function affTabs(href, num) {
    if (href != null) {
        var num = numSearch(href)
    }
    desactiveTabs(lastActive);
    activeTabs(num);
    lastActive = num
}
function desactiveTabs(num) {
    $(tabsmn[num]).css({opacity: 1, "background-color": "none"});
    $(tabs[num]).hide()
}
function activeTabs(num) {
    $(tabsmn[num]).css({opacity: 0.4, "background-color": window.getComputedStyle(tabsmn[num], null).background.color});
    $(tabs[num]).fadeIn("slow")
}
function numSearch(href) {
    for (var i = 0; i < tabsmn.length; i++) {
        if (tabsmn[i] == href) {
            return i
        }
    }
};