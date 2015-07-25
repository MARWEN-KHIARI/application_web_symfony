
var organization=0;
$(".organization_menu").hide();
var organizationObj = document.getElementById('touch3d_adminbundle_client_organization');
if(organizationObj.checked){
    $(".organization_menu").fadeIn();
    $('label').addClass('required');
    $('input[type=text]').attr("required", "required");
    organization=1;
    $("#organization_checkbox").hide();
}


$(document).on("click", "#touch3d_adminbundle_client_organization", function () {
    if (this.checked) {
        bootbox.confirm("You are authorized representative ?", function (result) {
            if (result) {
                $(".organization_menu").fadeIn();
                $('label').addClass('required');
                $('input[type=text]').attr("required", "required");
                organization=1;
                $("#organization_checkbox").hide();

            } else {
                organization=0;
                $("#touch3d_adminbundle_client_organization").attr("checked", false);

            }
        });
    } else {
        $(".organization_menu").hide();
    }

});
$(document).on("click", "#touch3d_adminbundle_client_reset", function () {
    $("#organization_checkbox").fadeIn();

    $(".organization_menu").hide();
});

function save() {
    if (!$('#form1')[0].checkValidity()){
        //alert('form is not valid');
        var data1="<p>Nous vous remercions de votre intérêt pour les solutions et services de CSDI.<br/><div class='alert alert-error'> Merci de bien vouloir remplir le formulaire ci-dessous pour que nous puissions vous contacter et répondre à toutes vos questions.</div><p>";
        afficheMess(data1);
        return false;
    }else{
        $('#touch3d_adminbundle_client_save').attr('type','button');
    }

    //$(document).on("submit", "#touch3d_adminbundle_client_save", function () {afficheMess("azdsq");e.preventDefault();return false;});
    $('#form1')[0].disable = true;
    $("#loading").fadeIn();

    $.post(urlSave,
        $('#form1').serialize()
        ,function (data) {
            afficheMess(data);
        }, 'html').always(function () {
            $("#loading").fadeOut();
        });


    return false;
}