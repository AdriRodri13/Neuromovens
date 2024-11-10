// Selecciona todas las secciones
const secciones = document.querySelectorAll('.section-container');

// Aplica clases alternas para deslizar desde la izquierda o derecha
secciones.forEach((seccion, index) => {
    if (index % 2 === 0) {
        seccion.classList.add('slide-in-left');
    } else {
        seccion.classList.add('slide-in-right');
    }
});

// Configura el Intersection Observer
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Agrega la clase 'slide-in' cuando el elemento es visible
            entry.target.classList.add('slide-in');
            observer.unobserve(entry.target);
        }
    });
}, {
    root: null,
    threshold: 0.1
});

// Observa cada sección
secciones.forEach(seccion => observer.observe(seccion));
