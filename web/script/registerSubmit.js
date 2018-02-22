$(document).ready(function() {
    var inProcess = false;
    $('#registerForm').submit(function(e){

    e.preventDefault();

    if (inProcess) {
        return false;
    }
    inProcess = true;

    clearAll($(this));
    removeMessageOverlay($(this));

    var eventEmailValue = $('#email').val();
    var eventPasswordValue = $('#password').val();
    var eventPasswordVerifValue = $('#passwordVerif').val();
    var eventNameValue = $('#name').val();
    var eventFirstnameValue = $('#firstname').val();
    var eventAddressLineValue = $('#addressLine').val();
    var eventCityValue = $('#addressCity').val();
    var eventAdressPostalCodeValue = $('#addressPostalCode').val();

    var success = true ;

    if (eventEmailValue == "") {
        setError($('#email'), "veuillez renseigner une adresse email");
        success = false;
        inProcess = false;
    }else {
        setSuccess($('#email'));
    }
    if (eventPasswordValue == "") {
        setError($('#password'), "veuillez renseigner un Mot De Passe");
        success = false;
        inProcess = false;
    }else {
        setSuccess($('#password'));
    }
    if (eventPasswordVerifValue == "") {
        setError($('#passwordVerif'), "veuillez veriffier votre Mot De Passe");
        success = false;
        inProcess = false;
    }else {
        setSuccess($('#passwordVerif'));
    }
    if (eventFirstnameValue == "") {
        setError($('#firstname'), "veuillez renseigner votre prénom");
        success = false;
        inProcess = false;
    }else {
        setSuccess($('#firstname'));
    }
    if (eventAddressLineValue == "") {
        setError($('#addressLine'), "veuillez renseigner une adresse");
        success = false;
        inProcess = false;
    }else {
        setSuccess($('#addressLine'));
    }
    if (eventCityValue == "") {
        setError($('#addressCity'), "veuillez renseigner une ville");
        success = false;
        inProcess = false;
    }else {
        setSuccess($('#addressCity'));
    }
    if (eventNameValue == "") {
        setError($('#name'), "veuillez renseigner votre prénom");
        success = false;
        inProcess = false;
    }else {
        setSuccess($('#name'));
    }
    if (eventAdressPostalCodeValue == "") {
        setError($('#addressPostalCode'), "veuillez renseigner une date");
        success = false;
        inProcess = false;
    }else {
        setSuccess($('#addressPostalCode'));
    }


    $.ajax({
        url: pageUrl + 'registerSubmit/',
        type: $(this).attr('method'),
        dataType: 'JSON',
        data: $(this).serialize(),
        timeout: 8000,
        success : function(data){
            if(data.success){
                displayMessageOverlay(1, 'Votre compte a bien été créé', pageUrl );

            } else {
                if(data.errors.firstname){
                    setError($('#firstname'), 'Format incorrect utilisé pour le champ prénom. Le nom doit contenir au moins 3 caractères et seulement des lettres!');
                } else {
                    setSuccess($('#firstname'));
                }
                if(data.errors.name){
                    setError($('#name'), 'Format incorrect utilisé pour le champ nom de famille. Le nom doit contenir au moins  caractères 3 et seulement des lettres!');
                }else {
                    setSuccess($('#name'));
                }
                if(data.errors.email){
                    setError($('#email'), 'Format incorrect utilisé pour l\'adresse Email est incorrecte!');
                }else {
                    setSuccess($('#email'));
                }
                if(data.errors.alreadyExists){
                    setError($('#email'), 'Cette adresse email es dejà utiliser pour un autre compte. Veuillez utiliser une autre adresse email');
                }else {
                    setSuccess($('#email'));
                }
                if(data.errors.password){
                    setError($('#password'), 'Format invalide utilisé pour le champ mot de passe. Le mot de passe doit comporter au moins 4 caractères!');
                }else {
                    setSuccess($('#password'));
                }
                if(data.errors.passwordVerif){
                    setError($('#passwordVerif'), 'Non concordance des mots de passe. Veuillez taper le même mot de passe pour confirmation!');
                }else {
                    setSuccess($('#passwordVerif'));
                }
                if(data.errors.addressLine){
                    setError($('#addressLine'), 'Format invalide utilisé pour le champ Adresse . l\'Adresse doit comporter entre 5 et 200 caractères!');
                }else {
                    setSuccess($('#addressLine'));
                }
                if(data.errors.addressCity){
                    setError($('#addressCity'), 'Format invalide utilisé pour le champ Ville . la Ville doit comporter entre 3 et 200 caractères!');
                }else {
                    setSuccess($('#addressCity'));
                }
                if(data.errors.addressPostalCode){
                    setError($('#addressPostalCode'), 'Format invalide utilisé pour le champ Code postal . l\'Adresse doit comporter entre 5 et 200 caractères!');
                }else {
                    setSuccess($('#addressPostalCode'));
                }
            }

        },
        error : function(){
                displayMessageOverlay(0,'Problème de connexion , réessayez plus tard')
            inProcess = false;
        },
        beforeSend : function(){
            displayLoadOverlay();
        },
        complete : function(){
            removeLoadOverlay();
            inProcess = false;
        }
    });
});

});
