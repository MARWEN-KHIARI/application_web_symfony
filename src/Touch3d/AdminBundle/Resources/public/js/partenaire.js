function deleteProduct(id1) {
    $("#loading").fadeIn();
    $.post(_deleteProduct, {
        id: id1
    },function (data) {
        afficheMess(data);
        ReloadPage();//location.reload();
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
    return false;
}


function viewProduct(id1) {
    $("#loading").fadeIn();
    $.post(_selectXProduct, {
        id: id1
    },function (data) {
        bootbox.alert(data, "Partenaire Details");
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
    e.preventDefault();
    return false;
}

function saveProduct(id1) {
    $("#loading").fadeIn();
    var urlSaveProduct = _editProduct;
    if (id1 == null) {
        urlSaveProduct = _newProduct;
        id1 = -1;
    }
    $("form").hide();
    $.post(urlSaveProduct, {
        id: id1,
        nom: $("#touch3d_adminbundle_partenaire_nom").val(),
        logo: $("#touch3d_adminbundle_partenaire_logo").val(),
        lien: $("#touch3d_adminbundle_partenaire_lien").val(),
        matricule: $("#touch3d_adminbundle_partenaire_matricule").val(),
        responsable: $("#touch3d_adminbundle_partenaire_responsable").val(),
        adresse: $("#touch3d_adminbundle_partenaire_adresse").val(),
        ville: $("#touch3d_adminbundle_partenaire_ville").val(),
        poste: $("#touch3d_adminbundle_partenaire_poste").val(),
        pays: $("#touch3d_adminbundle_partenaire_pays").val(),
        tel: $("#touch3d_adminbundle_partenaire_tel").val(),
        fax: $("#touch3d_adminbundle_partenaire_fax").val(),
        email: $("#touch3d_adminbundle_partenaire_email").val(),
        status: $("#touch3d_adminbundle_partenaire_status").val()
    },function (data) {
        afficheMess(data);
    }, 'html').always(function () {
        $("#loading").fadeOut();
        location.reload();
    });
    //e.preventDefault();
    return false;
}

function findProduct() {
    $("#loading").fadeIn();
    $.post(_findProduct, {
        req: $("#searchText").val()
    },function (data) {
        $("#tableProducts").empty();
        $('#tableProducts').html(data).hide();
    }, 'text').always(function () {
        $('#tableProducts').fadeIn("slow");
        $("#pagination").html("");
        $("#loading").fadeOut();
    });
    return false;
}

function viewAllProduct(page) {
    reset();
    $("#loading").fadeIn();
    statistique();
    page = GetPage(page);
    $.post(_viewPaginatedProduct, {
        pageActive: page
    },function (data) {
        $("#tableProducts").empty();
        $('#tableProducts').html(data).hide();
    }, 'text').always(function () {
        $('#tableProducts').fadeIn("slow");
        pagination(page);
    });
    //e.preventDefault();
    return false;
}

function statistique() {
    $("#info_canvas_product").load(_statisticProduct).hide().fadeIn();
}

function pagination(page) {
    $.post(_paginateProduct, { pageActive: page },function (data) {
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
    viewAllProduct();
}

