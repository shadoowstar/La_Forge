$('.search-form input').keyup(function(){
    var entry = $(this).val();
    var requestUrl = $(this).data('ajax');
    $('#search-suggestion').css('width', '100%');

    $.ajax({
      url: requestUrl,
      type: 'GET',
      dataType: 'JSON',
      data: {
        title: entry
      },
      timeout: 8000,

      success: function (response) {
        $('#search-suggestion').html('');
        if(response instanceof Array)
        {
            $('#search-suggestion').css('display', 'block');
          response.forEach(function(item){
              if($('#search-suggestion a').length <= 5)
              {
                $('#search-suggestion').append('<a href="'+indexPath +'article/'+ item.id +'">'+ item.name +'</a>');
              }
          });
        }
        else
        {
            $('#search-suggestion').css('display', 'none');
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
});