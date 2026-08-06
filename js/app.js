/*
    CarpoolMatch CR
    Archivo JavaScript general del proyecto.

    La sesión de usuario se maneja con PHP.
    Este archivo queda reservado para funciones simples del lado del cliente.
*/

document.addEventListener("DOMContentLoaded", () => {
    const botonesSinAccion = document.querySelectorAll('a[href="#"]');

    botonesSinAccion.forEach((boton) => {
        boton.addEventListener("click", (evento) => {
            evento.preventDefault();
            alert("Esta opción todavía no está disponible.");
        });
    });
});