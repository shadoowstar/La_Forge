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