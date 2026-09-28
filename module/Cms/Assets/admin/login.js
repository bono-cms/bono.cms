
$(window).on('load', function(){
    $("button[type='submit']").prop('disabled', false);
});

$(function(){
    $("[data-captcha='button-refresh']").click(function(event){
        event.preventDefault();

        // Grab image's element
        var $image = $("[data-captcha='image']");
        var link = $image.attr('src');

        $image.attr('src', link + Math.random());
    });

    $("form").submit(function(event){
        event.preventDefault();
        var $self = $(this);
        var data = $self.serialize();

        $.ajax({
            url : $self.data('submit-url'),
            data : data,
            success : function(response){
                if (response.refresh == true){
                    window.location.reload();
                }

                if (response.redirect){
                    window.location = response.redirect;
                }

                if (response.errors){
                    $.showErrors(response.errors);
                }
            }
        });
    });
});