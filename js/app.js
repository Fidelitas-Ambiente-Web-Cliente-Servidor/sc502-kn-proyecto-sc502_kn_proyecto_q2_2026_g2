document.addEventListener("DOMContentLoaded", function () {
    const formularioLogin = document.getElementById("formLogin");
    const formularioRegistro = document.getElementById("formRegistro");

    if (formularioLogin) {
        formularioLogin.addEventListener("submit", iniciarSesion);
    }

    if (formularioRegistro) {
        formularioRegistro.addEventListener("submit", registrarUsuario);
    }

    const correoInput = document.getElementById("correoLogin") || document.getElementById("correo");
    const correoGuardado = localStorage.getItem("correoRecordado");

    if (correoGuardado && correoInput) {
        correoInput.value = correoGuardado;

        if (document.getElementById("recordarSesion")) {
            document.getElementById("recordarSesion").checked = true;
        }
    }
});

function iniciarSesion(evento) {
    evento.preventDefault();

    const correoInput = document.getElementById("correoLogin") || document.getElementById("correo");
    const passwordInput = document.getElementById("passwordLogin") || document.getElementById("password");
    const recordarCheck = document.getElementById("recordarSesion");
    const mensaje = document.getElementById("mensajeLogin");

    const correo = correoInput ? correoInput.value.trim() : "";
    const password = passwordInput ? passwordInput.value : "";
    const recordarSesion = recordarCheck ? recordarCheck.checked : false;

    if (correo === "" || password === "") {
        if (mensaje) {
            mensaje.textContent = "Debe ingresar correo y contraseña.";
            mensaje.style.color = "red";
        }
        return;
    }

    if (recordarSesion) {
        localStorage.setItem("correoRecordado", correo);
    } else {
        localStorage.removeItem("correoRecordado");
    }

    if (mensaje) {
        mensaje.textContent = "Inicio de sesión correcto.";
        mensaje.style.color = "green";
    }

    console.log("Inicio de sesión correcto");
    console.log("Correo ingresado:", correo);

    setTimeout(function () {
        window.location.href = "dashboard.html";
    }, 1000);
}

function registrarUsuario(evento) {
    evento.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const tipoUsuario = document.getElementById("tipoUsuario").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const mensaje = document.getElementById("mensajeRegistro");

    if (
        nombre === "" ||
        correo === "" ||
        telefono === "" ||
        tipoUsuario === "" ||
        password === "" ||
        confirmPassword === ""
    ) {
        mensaje.textContent = "Debe completar todos los campos.";
        mensaje.style.color = "red";
        return;
    }

    if (!/^[0-9]+$/.test(telefono)) {
        mensaje.textContent = "El teléfono solo puede contener números.";
        mensaje.style.color = "red";
        return;
    }

    if (telefono.length !== 8) {
        mensaje.textContent = "El teléfono debe tener 8 dígitos.";
        mensaje.style.color = "red";
        return;
    }

    if (password !== confirmPassword) {
        mensaje.textContent = "Las contraseñas no coinciden.";
        mensaje.style.color = "red";
        return;
    }

    const usuario = {
        nombre: nombre,
        correo: correo,
        telefono: telefono,
        tipoUsuario: tipoUsuario,
        password: password
    };

    localStorage.setItem("usuarioRegistrado", JSON.stringify(usuario));

    mensaje.textContent = "Usuario registrado correctamente.";
    mensaje.style.color = "green";

    console.log("Usuario registrado correctamente");
    console.log(usuario);

    setTimeout(function () {
        window.location.href = "login.html";
    }, 1000);
}