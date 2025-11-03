// Inicializa o Owl Carousel
$('.owl-carousel').each(function () {
    $(this).owlCarousel({
        items: 1, // Mostra um item por vez
        loop: false, // Não repete os slides
        nav: false, // Remove os botões de navegação padrão
        dots: false, // Remove os pontos de navegação padrão
        mouseDrag:false,
        touchDrag:false,
    });
    });
    const owl = $('.owl-carousel');
    $('#prev').click(function () {
    owl.trigger('prev.owl.carousel');
    });

    $('#next').click(function () {
    owl.trigger('next.owl.carousel');
    });