$(document).ready(function() {
    // Selecciona todas las secciones usando jQuery
    const $secciones = $('.section-container');

    // Aplica clases alternas para deslizar desde la izquierda o derecha
    $secciones.each(function(index) {
        const $seccion = $(this);
        if (index % 2 === 0) {
            $seccion.addClass('slide-in-left');
        } else {
            $seccion.addClass('slide-in-right');
        }
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Agrega la clase 'slide-in' cuando el elemento es visible usando jQuery
                $(entry.target).addClass('slide-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        threshold: 0.05
    });

    // Observa cada sección usando get() para obtener elementos DOM nativos
    $secciones.each(function() {
        observer.observe(this);
    });
});