let contador = 1;

function cambiarImagen() {
    let imagen = document.getElementById('imagen_equipo');
    let imagenes = [
        'assets/images/imagen_personal.jpg',
        'assets/images/imagen_personal2.jpg',
        'assets/images/imagen_personal3.jpg',
        'assets/images/imagen_personal4.jpg'
    ];

    setInterval(function () {
        imagen.style.opacity = 0;


        setTimeout(function () {
            imagen.src = imagenes[contador];
            imagen.style.opacity = 1;


            contador++;
            if (contador === imagenes.length) {
                contador = 0; 
            }
        }, 500); 
    }, 4000); 
}

cambiarImagen(); 
