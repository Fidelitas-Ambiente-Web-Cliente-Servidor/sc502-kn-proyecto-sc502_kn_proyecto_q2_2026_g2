document.addEventListener("DOMContentLoaded", function () {
    const formularioLogin = document.getElementById("formLogin");

    if (formularioLogin) {
        formularioLogin.addEventListener("submit", iniciarSesion);
    }

    const correoGuardado = localStorage.getItem("correoRecordado");

    if (correoGuardado && document.getElementById("correoLogin")) {
        document.getElementById("correoLogin").value = correoGuardado;

        if (document.getElementById("recordarSesion")) {
            document.getElementById("recordarSesion").checked = true;
        }
    }
});

function iniciarSesion(evento) {
    evento.preventDefault();

    const correo = document.getElementById("correoLogin").value.trim();
    const password = document.getElementById("passwordLogin").value;
    const recordarSesion = document.getElementById("recordarSesion").checked;
    const mensaje = document.getElementById("mensajeLogin");

    if (correo === "" || password === "") {
        mensaje.textContent = "Debe ingresar correo y contraseña.";
        mensaje.style.color = "red";
        return;
    }

    if (recordarSesion) {
        localStorage.setItem("correoRecordado", correo);
    } else {
        localStorage.removeItem("correoRecordado");
    }

    mensaje.textContent = "Inicio de sesión correcto.";
    mensaje.style.color = "green";

    console.log("Correo ingresado:", correo);
    console.log("Recordar sesión:", recordarSesion);

    setTimeout(function () {
        window.location.href = "index.html";
    }, 1000);
}