
$(window).load(function(){
    $("button[type='submit']").removeClass('disabled');
});

$(function(){
    
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
                } else {
                    $.showErrors(response);
                }
            }
        });
    });
});