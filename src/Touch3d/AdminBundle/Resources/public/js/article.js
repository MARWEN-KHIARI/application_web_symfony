function deleteArticle(id1) {
    $("#loading").fadeIn();
    $.post(_deleteArticle, {
        id: id1
    },function (data) {
        afficheMess(data);
        ReloadPage();//location.reload();
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
    return false;
}


function viewArticle(id1) {
    $("#loading").fadeIn();
    $.post(_selectXArticle, {
        id: id1
    },function (data) {
        bootbox.alert(data, "Articles Details");
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
    e.preventDefault();
    return false;
}

function saveArticle(id1) {
    $("#loading").fadeIn();
    var date_ = $("#touch3d_adminbundle_article_date_year").val()+"-"+$("#touch3d_adminbundle_article_date_month").val()+"-"+$("#touch3d_adminbundle_article_date_day").val();

    var outContent = tinymce.activeEditor.getContent();
    var urlSaveArticle = _editArticle;
    if (id1 == null) {
        urlSaveArticle = _newArticle;
        id1 = -1;
    }
    $.post(urlSaveArticle, {
        id: id1,
        nom:  $("#touch3d_adminbundle_article_nom").val(),
        date: date_,
        lieu: $("#touch3d_adminbundle_article_lieu").val(),
        img:  $("#touch3d_adminbundle_article_img").val(),
        resumer: $("#touch3d_adminbundle_article_resumer").val(),
        contenu: outContent,
        status: $("#touch3d_adminbundle_article_status").val()
    },function (data) {
        afficheMess(data);
    }, 'html').always(function () {
        $("#loading").fadeOut();
        //location.reload();
    });
    //e.preventDefault();
    return false;
}

function findArticle() {
    $("#loading").fadeIn();
    $.post(_findArticle, {
        req: $("#searchText").val()
    },function (data) {
        $("#tableArticles").empty();
        $('#tableArticles').html(data).hide();
    }, 'text').always(function () {
        $('#tableArticles').fadeIn("slow");
        $("#pagination").html("");
        $("#loading").fadeOut();
    });
    return false;
}

function viewAllArticle(page) {
    reset();
    $("#loading").fadeIn();
    statistique();
    page = GetPage(page);
    $.post(_viewPaginatedArticle, {
        pageActive: page
    },function (data) {
        $("#tableArticles").empty();
        $('#tableArticles').html(data).hide();
    }, 'text').always(function () {
        $('#tableArticles').fadeIn("slow");
        pagination(page);
    });
    //e.preventDefault();
    return false;
}

function statistique() {
    $("#info_canvas_article").load(_statisticArticle).hide().fadeIn();
}

function pagination(page) {
    $.post(_paginateArticle, { pageActive: page },function (data) {
        $("#pagination").html(data);
    }, 'text').always(function () {
        $("#loading").fadeOut("slow");
        scrollToBottomx();
    });
}

function GetPage(page) {
    if (page == null) {
        var ck = readCookie('AdminPage');
        if (ck) {
            page = ck;
        } else {
            page = 1;
        }
    } else {
        createCookie('AdminPage', page, 2);
    }
    return page;
}

function reset() {
    document.getElementById('searchText').value = "";
}


function ReloadPage() {
    // var timer = setInterval("ReloadPage()", 1000);
    viewAllArticle();
}

