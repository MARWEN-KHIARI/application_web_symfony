
function afficherProfile(){
    var data="<label for=\"nameUser\">"+ffos_username+":</label>";
    data+='<input type="text" id="nameUser" name="nameUser" readonly="readonly" value="'+fos_username+'"/>';
    data+="<label for=\"emailUser\">"+ffos_email+":</label>";
    data+='<input type="text" id="emailUser" name="emailUser" readonly="readonly" value="'+fos_email+'"/>';
    data+="<label for=\"passUser\">"+ffos_password+"</label>";
    data+='<input type="text" id="passUser" name="passUser" readonly="readonly" value="**************"/>';


    bootbox.alert(data, "User Details");
}

var scHm = 0;
var android=false;
Menu1Animate();
function Menu1Animate() {
    if( $(window).width()>1024 ){
        android=true;
    }
    $("body").mousemove(function (e) {
        if(android){
            scHm = $(window).height() * 0.5;
            scHm += $(window).scrollTop();
            if (e.pageY > scHm) {
                $(".navbar-fixed-top").fadeOut(1000);
            } else {
                $(".navbar-fixed-top").fadeIn(600);
            }
        }
    });
};


function afficheMess(s) {
    $('#messageInfo').html(s);
    $('#messageInfo').fadeIn('slow').fadeOut(200).fadeIn(200).fadeOut(8000);
}

function changeLanguage(lg) {
    var pathlang=$("#_language").val();
    $("#loading").fadeIn();
    $.post(pathlang,{
        language:lg
    },function (data) {
        afficheMess(data);
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
}


function changePaginationShow() {
    var data='<label for="changePaginationNb" class="required">Nombre d\'article par page</label>'+
        '<input type="number" id="changePaginationNb" required="required" maxlength="5" value="3" placeholder="nombre d\'article par page"/>';

    bootbox.confirm(data, "Annuler", "Envoyer", function (result) {
        if (result) {
            changePagination();
            bootbox.hideAll();
        }
    });
}
function changePagination() {
    var pathpag=$("#_pagination").val();
    $("#loading").fadeIn();
    $.post(pathpag,{
        pagination:$("#changePaginationNb").val()
    },function (data) {
        afficheMess(data);
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
}
function add_bookmark(url,page_title) { 
if(window.sidebar) 
// Firefox / Mozilla 
{ window.sidebar.addPanel(page_title, url,''); } 
else if(window.opera) 
// Opera 
{ var a = document.createElement('A'); 
a.rel = 'sidebar'; a.target = '_search'; 
a.title = page_title; a.href = url; a.click(); } 
else if(window.external) 
// IE
{ window.external.AddFavorite(url, page_title); } 
else { 
    alert(' Your web browser appears to not support adding a bookmark via Javascript. Please manually add one via your browser\'s bookmark menu. '); 
} }
function scrollToBottomx() {
    $("html, body").animate({
        scrollTop: $(window).height()
    }, "slow");
}

function ReloadDocument() {
    location.reload();
}

function GoHref(href) {
    // document.location.replace(href);
    this.location = href;
}

function _activeGallery(){
    $('#li_dashboard').removeClass();
    $('#li_gallery').addClass("active");
}
function _activeDashboard(){
    $('#li_gallery').removeClass();
    $('#li_dashboard').addClass("active");
}
function _activeLibrary(){
    $('#li_dashboard').removeClass();
    $('#li_library').addClass("active");
}


function sleep(milliseconds) {
    var start = new Date().getTime();
    while ((new Date().getTime() - start) < milliseconds) {
    }
}
function sleepNoBlock(milliseconds) {
    setTimeout(function () {
        var start = new Date().getTime();
        while ((new Date().getTime() - start) < milliseconds) {
        }
    }, 0);
}

function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else
        var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0)
            return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

$(document).ready(function () {
    $("#messageInfo").hide();
    $("#loading").css({
        "background": "url(" + $("#loadinfo").val() + ") no-repeat center transparent",
        "float": "left",
        width: "30px",
        height: "30px",
        "padding": "5px",
        "margin": "2px"

    }).hide();
});
/*
 var labelID;
 $('label').click(function() {
 labelID = $(this).attr('for');
 $('#'+labelID).trigger('click');
 });*/


function changeColor(id, c) {
    if (c) {
        $(id).css({
            "background-color": "#fbb"
        })
    } else {
        $(id).css({
            "background-color": "#ddd"
        })
    }
}

var notdeletePage = new Array("dashboard", "prdform");
function deleteCPageAdminAuto() {
    var sp = splitehref("" + window.location, notdeletePage);
    if (sp)eraseCookie("AdminPage");
}

function splitehref(href, t) {
    var myArray = href.split("/");

    // display the result in myDiv
    for (var i = 0; i < myArray.length; i++) {
        for (var j = 0; j < t.length; j++) {
            if (myArray[i] == t[j]) {
                return false;
            }
        }
    }
    return true;
}

function deleteCPageAdmin() {
    eraseCookie("AdminPage");
}
window.onunload = deleteCPageAdmin();