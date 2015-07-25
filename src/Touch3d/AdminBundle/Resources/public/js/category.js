
function addCat(){
    var data='<br/><form id="formCat1" method="post"><input type="text" id="touch3d_adminbundle_categorie_nom" name="touch3d_adminbundle_categorie[nom]" required="required" placeholder="Quel est le nom du categorie?" pattern=".{3,}" >';
    data+='<button id="touch3d_adminbundle_categorie_envoyer" name="touch3d_adminbundle_categorie[envoyer]" style="float:left" class="btn btn-primary" onclick="saveCat();">Ajouter</button></form>'
    //bootbox.alert(data, "Category");
    $("#catAddform").html(data);
    $("#catAddform").fadeIn();
}

function removeCat(){
    var id=$("#touch3d_adminbundle_produit_categorie").val();
    bootbox.confirm("Etes-vous sûr de supprimer cette categorie?", function(result){if(result)deleteCat(id);});
}

function saveCat() {
    $('formCat1').disable=true;
    $("#loading").fadeIn();
    $.post(urlSaveCat, {
        nom: $("#touch3d_adminbundle_categorie_nom").val()
    },function (data) {
        afficheMess(data);
    }, 'html').done(function () {
        //reloadTimed();//
        location.reload();
    }).always(function () {
        $("#loading").fadeOut();
        $("#catAddform").fadeOut();
    });
}

function deleteCat(id1) {
    $("#loading").fadeIn();
    $.post(urldeleteCat, {
        id: id1
    },function (data) {
        afficheMess(data);
    }, 'html').done(function () {
        location.reload();
        //reloadTimed();
    }).always(function () {
        $("#loading").fadeOut();
    });
}
