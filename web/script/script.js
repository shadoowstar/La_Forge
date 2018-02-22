// On recupere la position du bloc par rapport au haut du site
var position_top_raccourci = $("#nav").offset().top;
//Au scroll dans la fenetre on déclenche la fonction
$(window).scroll(function () {
    //si on a defile de plus de 150px du haut vers le bas
    if ($(this).scrollTop() > position_top_raccourci) {
        //on ajoute la classe "fixNavigation" a <div id="navigation">
        $('#nav').addClass("fixNavigation");
    } else {
        //sinon on retire la classe "fixNavigation" a <div id="navigation">
        $('#nav').removeClass("fixNavigation");
    }
});


// Rotations du logo au survol de la souris
$('.logo').mouseover(
    function () {
        $(this).css('transform','rotate(-45deg)');
    }
);

$('.logo').mouseout(
    function () {
        $(this) .css('transform','rotate(0deg)');
    }
);
//fonction pour les creation de message d'erreur ou de succes en dessous des champ de form
function setError(input, msg) {
    input.addClass('is-invalid');
    input.after('<small class="invalid-feedback">'+ msg +'</small>');
}
function setSuccess(input) {
    input.addClass('is-valid');
}
// permet de suprimer tout type de message ajouter avec les fonction precedente
function clearInput(input) {
    input.removeClass('is-valid');
    input.removeClass('is-invalid');
    input.parent().find('small').remove();
}
function clearAll(form) {
    var inputs = form.find('input');
    inputs.removeClass('is-valid');
    inputs.removeClass('is-invalid');
    form.find('small').remove();
}

//overlay de chargement
function displayLoadOverlay() {
    $('body').prepend('<div class="overlay" id="overlayLoader"><img src="'+assetUrl+'img/ajax-loader.gif" alt=""></div>')
}

function removeLoadOverlay() {
    $('#overlayLoader').remove();
}

function displayMessageOverlay(status, msg , url) {
    if (status == 1) {
        var color = '#BDFFB4';
    }else {
        var color = '#FF8688';
    }
    $('body').prepend('<div class="overlay" id="overlayMessage"><div class="overlayMessage" style="background-color:'+ color +' "><p>'+ msg +'</p><button id="overlayButton" type="button" class="btn btn-light">OK</button></div></div>');
    if (status == 1) {
        $('#overlayButton').click(function(){
            document.location.href = url;
        });
    } else {
        $('#overlayButton').click(function() {
            removeMessageOverlay()
        });
    }
}
//fonction de supretion de l'overlay cree avec la fonction precedente
function removeMessageOverlay() {
    $('#overlayMessage').remove();
}
