var prd2 = getElements(".article-slide2");
prd2.hide();
$(prd2[0]).show();
$(function () {
    setInterval("slideSwitchProduit2()", 6000)
});
var numprd2 = 0;
function slideSwitchProduit2() {
    $(prd2[numprd2]).slideUp();
    numprd2++;
    if (numprd2 < prd2.length) {
        $(prd2[numprd2]).slideDown()
    } else {
        numprd2 = 0;
        $(prd2[numprd2]).slideDown()
    }
};