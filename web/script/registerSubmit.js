$(document).ready(function() {
    $('#sign-in-form').submit(function(event){

    event.preventDefault();

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'JSON',
        data: $(this).serialize(),
        timeout: 8000,

        success : function(data){

                if(data.success){
                    displayMessageOverlay(1, 'Votre compte a bien été créé');

                } else {
                    grecaptcha.reset();
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
            },
            beforeSend : function(){
                displayLoadOverlay();
            },
            complete : function(){
                removeLoadOverlay();
            }
        });
    });

});
