$(function(){
    $("[data-button='dashboard']").click(function(event){
        event.preventDefault();
        window.location = $(this).data('url');
    });
});