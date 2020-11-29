$('a[href*="#"]').not('[href="#"]').not('[href="#0"]').on('click', function(e) {
    e.preventDefault();

    var hash = this.hash;

    $('html, body').animate({
        scrollTop: $(hash).offset().top
    }, 500, function(){
        window.location.hash = hash;
    });
});