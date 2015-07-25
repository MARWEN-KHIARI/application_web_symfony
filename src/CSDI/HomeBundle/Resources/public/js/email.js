$("#subscribe").hide();
var emailValide = false;
function ShowMail() {
    $("#subscribe").fadeIn("slow");
if (document.getElementById("email").value.length > 10) {
    $("#subscribe").fadeOut("slow")
    saveSETNlttr();
}
}
function emailEnter() {
    if (event.keyCode != 13) {
        ShowMail();
    }
};

function saveSETNlttr() {
    $.post(_SETNlttr, {
        email: $("#email").val()
    }, function (data) {
        if (data == "good")emailValide = true;
    }, 'text').always(function () {
        if (emailValide) {
            alert("Vous êtes inscrit! Félicitation");
        }else{
            alert("Verifier votre email!");
            document.getElementById("email").value="";
        }
    });


}