$(document).ready(function() {
    var inProcess = false;
    $('#calendarAdminForm').submit(function(e){
        e.preventDefault();

        if (inProcess) {
            return false;
        }
        inProcess = true;

        clearAll($(this));
        removeMessageOverlay($(this));

        var eventTitleValue = $('#eventTitle').val();
        var eventDateValue = $('#eventDate').val();
        var eventDescValue = $('#eventDesc').val();
        var success = true ;

        if (eventTitleValue == "") {
            setError($('#eventTitle'), "veuillez renseigner un titre");
            success = false;
            inProcess = false;
        }else {

        }
        if (eventDateValue == "") {
            setError($('#eventDate'), "veuillez renseigner une date");
            success = false;
            inProcess = false;
        }else {
            setSuccess($('#eventDate'));
        }
        if (eventDescValue == "") {
            setError($('#eventDesc'), "veuillez renseigner la description de l'evenement");
            success = false;
            inProcess = false;
        }else {
            setSuccess($('#eventDesc'));
        }


        if(success){
            //connexion AJAX
            $.ajax({
                type : $(this).attr('method'),
                url : pageUrl + "event-submit/",
                dataType : 'json',
                data : $(this).serialize(),
                success : function(data){

                    if(data.success){
                        displayMessageOverlay(1, 'L\'evenement a bien été créé', pageUrl + "calendarAdmin/");
                    } else {
                        if(data.errors.title){
                            setError($('#eventTitle'), 'Format incorrect utilisé pour le champ titre. Le titre doit contenir au moins 2 caractères et seulement des lettres!');
                        } else {
                            setSuccess($('#eventTitle'));
                        }
                        if(data.errors.date){
                            setError($('#eventDate'), 'la date n\'es pas une date valide');
                        } else {
                            setSuccess($('#eventDate'));
                        }
                        if(data.errors.desc){
                            setError($('#eventDesc'), 'Format incorrect utilisé pour le champ description de l\'evenement. Le contenue ne doit pas contenir plus de 20 000 caractères et seulement des lettres!');
                        } else {
                            setSuccess($('#eventDesc'));
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

        }
    });
});
