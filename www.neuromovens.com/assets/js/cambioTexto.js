document.addEventListener("DOMContentLoaded", ()=>{
    let cerrarSesion = document.getElementById("cerrarSesion");
    let nombreUsuario = document.getElementById('nombreUsuario').innerText;
    cerrarSesion.addEventListener("mouseover", () =>{
       cerrarSesion.innerHTML = "Cerrar Sesion";
    });
    cerrarSesion.addEventListener("mouseleave",()=>{
        cerrarSesion.innerHTML = nombreUsuario + '\<i class="fa-solid fa-user">';
    })
});

