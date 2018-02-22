$(document).ready(function() {
    var inProcess = false;
    $('#sign-in-form').submit(function(e){
        e.preventDefault();

        if (inProcess) {
            return false;
        }
        inProcess = true;

        clearAll($(this));
        removeMessageOverlay($(this));

        var eventEmailValue = $('#email').val();
        var eventPasswordValue = $('#password').val();
        var success = true ;

        if (eventEmailValue == "") {
            setError($('#email'), "veuillez renseigner un titre");
            success = false;
            inProcess = false;
        }else {
            setSuccess($('#email'));
        }
        if (eventPasswordValue == "") {
            setError($('#password'), "veuillez renseigner une date");
            success = false;
            inProcess = false;
        }else {
            setSuccess($('#password'));
        }

        $.ajax({
            url: pageUrl + 'sign-in/',
            type : $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data){
                if(data.success){
                    displayMessageOverlay(1, 'Vous etes bien connecté', pageUrl );

                } else {
                    if(data.errors.email){
                        setError($('#email'), 'Adresse Email incorrecte!');
                    }else {
                        setSuccess($('#email'));
                    }
                    if(data.errors.password){
                        setError($('#password'), 'Format invalide utilisé pour le champ mot de passe. Le mot de passe doit comporter au moins 4 caractères!');
                    }else {
                        setSuccess($('#password'));
                    }
                    if(data.errors.notExist){
                        setError($('#email'), 'le compte n\'existe pas');
                    }

                    if(data.errors.invalidPassword){
                        setError($('#password'), 'mot de passe n\'est pas valide ');
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
                inProcess = false;

            }
        });
    });
});
