
function addFournisseur(){
    var data='<br/><input type="text" id="touch3d_adminbundle_fournisseur_nom" name="touch3d_adminbundle_fournisseur[nom]" required="required" placeholder="Quel est le nom du fournisseur?" pattern=".{3,}" >';
    data+='<br/><input type="text" id="touch3d_adminbundle_fournisseur_adresse" name="touch3d_adminbundle_fournisseur[adresse]" required="required" placeholder="Quel est l\'adresse du fournisseur?" pattern=".{3,}" ><br/>';
    data+='<button id="touch3d_adminbundle_fournisseur_envoyer" name="touch3d_adminbundle_fournisseur[envoyer]" style="float:left" class="btn btn-primary" onclick="saveFournisseur();">Ajouter</button>'
    //bootbox.alert(data, "Category");
    $("#FournisseurAddform").html(data);
    $("#FournisseurAddform").fadeIn();
}

function removeFournisseur(){
    var id=$("#touch3d_adminbundle_produit_fournisseur").val();
    bootbox.confirm("Etes-vous sûr de supprimer fournisseur n°"+id+" ?", function(result){if(result)deleteFournisseur(id);});
}

function saveFournisseur() {
    $('formFournisseur1').disable=true;
    $("#loading").fadeIn();
    $.post(urlSaveFournisseur, {
        nom: $("#touch3d_adminbundle_fournisseur_nom").val(),
        adresse: $("#touch3d_adminbundle_fournisseur_adresse").val()
    },function (data) {
        afficheMess(data);
    }, 'html').always(function () {
        $("#loading").fadeOut();
        location.reload();
    });
}

function deleteFournisseur(id1) {
    $("#loading").fadeIn();
    $.post(urldeleteFournisseur, {
        id: id1
    },function (data) {
        afficheMess(data);
    }, 'html').always(function () {
        $("#loading").fadeOut();
        location.reload();
    });
}
