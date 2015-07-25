var prd = getElements(".article-slide");
prd.hide();
$(prd[0]).show();
$(function () {
    setInterval("slideSwitchProduit()", 9000)
});
var numprd = 0;
function slideSwitchProduit() {
    $(prd[numprd]).slideUp();
    numprd++;
    if (numprd < prd.length) {
        $(prd[numprd]).slideDown()
    } else {
        numprd = 0;
        $(prd[numprd]).slideDown()
    }
};