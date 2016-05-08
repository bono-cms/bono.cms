
$(window).load(function(){
    $("button[type='submit']").removeClass('disabled');
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
                if (response == "1") {
                    window.location = $self.data('success-url');
                } else if (response == "-1"){
                    window.location.reload();
                } else {
                    $.showErrors(response);
                }
            }
        });
    });
});