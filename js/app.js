document.addEventListener("DOMContentLoaded", function () {
    const formularioLogin = document.getElementById("formLogin");
    const formularioRegistro = document.getElementById("formRegistro");
    const usuarioDashboard = document.getElementById("usuarioDashboard");

    actualizarMenuSesion();
    mostrarUsuarioHeader();
    prepararValidacionesEnTiempoReal();
    prepararBotonesSinFuncion();
    redirigirSiYaInicioSesion();

    if (formularioLogin) {
        cargarCorreoRecordado();
    }

    //if (formularioRegistro) {
    //formularioRegistro.addEventListener("submit", registrarUsuario);
    //}


    if (usuarioDashboard) {
        cargarUsuarioDashboard();
    }
});

function actualizarMenuSesion() {
    const usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));
    const nav = document.querySelector(".nav");

    if (!nav) {
        return;
    }

    if (usuarioActivo) {
        nav.innerHTML = `
            <a href="index.html">Inicio</a>
            <a href="acerca.html">Acerca de nosotros</a>
            <a href="faq.html">Preguntas frecuentes</a>
            <a href="contacto.html">Contacto</a>
            <a href="dashboard.html" class="nav-btn">Dashboard</a>
           <a href="login.html" id="cerrarSesion" class="logout-icon" title="Cerrar sesión">
    <svg viewBox="0 0 24 24">
        <path d="M10 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h5v-2H5V5h5V3z"></path>
        <path d="M16.6 17.6 15.2 16.2 18.4 13H8v-2h10.4l-3.2-3.2 1.4-1.4L22.2 12z"></path>
    </svg>
</a>
        `;

        const botonCerrarSesion = document.getElementById("cerrarSesion");

        if (botonCerrarSesion) {
            botonCerrarSesion.addEventListener("click", function (evento) {
                evento.preventDefault();
                cerrarSesion();
            });
        }
    } else {
        nav.innerHTML = `
            <a href="index.html">Inicio</a>
            <a href="acerca.html">Acerca de nosotros</a>
            <a href="faq.html">Preguntas frecuentes</a>
            <a href="contacto.html">Contacto</a>
            <a href="login.html">Iniciar sesión</a>
            <a href="registro.html" class="nav-btn">Registrarse</a>
        `;
    }
}

function mostrarUsuarioHeader() {
    const usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));
    const usuarioHeader = document.getElementById("usuarioHeader");

    if (!usuarioHeader) {
        return;
    }

    if (usuarioActivo) {
        usuarioHeader.classList.remove("oculto");
        usuarioHeader.innerHTML = "👤 Bienvenido, <span>" + usuarioActivo.nombre + "</span>";
    } else {
        usuarioHeader.classList.add("oculto");
        usuarioHeader.innerHTML = "";
    }
}

function redirigirSiYaInicioSesion() {
    const usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));
    const formularioLogin = document.getElementById("formLogin");

    if (usuarioActivo && formularioLogin) {
        window.location.href = "dashboard.html";
    }
}

function prepararValidacionesEnTiempoReal() {
    const nombre = document.getElementById("nombre");
    const telefono = document.getElementById("telefono");

    if (nombre) {
        nombre.addEventListener("input", function () {
            nombre.value = nombre.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, "");
        });
    }

    if (telefono) {
        telefono.addEventListener("input", function () {
            telefono.value = telefono.value.replace(/[^0-9]/g, "").slice(0, 8);
        });
    }
}

function prepararBotonesSinFuncion() {
    const enlaces = document.querySelectorAll('a[href="#"]');

    enlaces.forEach(function (enlace) {
        enlace.addEventListener("click", function (evento) {
            evento.preventDefault();
            alert("Este módulo todavía está en desarrollo para el siguiente avance.");
        });
    });

    const botones = document.querySelectorAll(".accion-dashboard");

    botones.forEach(function (boton) {
        boton.addEventListener("click", function () {
            const modulo = boton.getAttribute("data-modulo");
            alert("El módulo '" + modulo + "' todavía está en desarrollo para el siguiente avance.");
        });
    });
}

function cargarCorreoRecordado() {
    const correoGuardado = localStorage.getItem("correoRecordado");
    const correoInput = document.getElementById("correoLogin");

    if (correoGuardado && correoInput) {
        correoInput.value = correoGuardado;

        const recordarSesion = document.getElementById("recordarSesion");

        if (recordarSesion) {
            recordarSesion.checked = true;
        }
    }
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
        mostrarMensaje(mensaje, "Debe completar todos los campos.", "red");
        return;
    }

    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(nombre)) {
        mostrarMensaje(mensaje, "El nombre solo puede contener letras.", "red");
        return;
    }

    if (!validarCorreo(correo)) {
        mostrarMensaje(mensaje, "Debe ingresar un correo válido.", "red");
        return;
    }

    if (!/^[0-9]+$/.test(telefono)) {
        mostrarMensaje(mensaje, "El teléfono solo puede contener números.", "red");
        return;
    }

    if (telefono.length !== 8) {
        mostrarMensaje(mensaje, "El teléfono debe tener 8 dígitos.", "red");
        return;
    }

    if (password.length < 6) {
        mostrarMensaje(mensaje, "La contraseña debe tener mínimo 6 caracteres.", "red");
        return;
    }

    if (password !== confirmPassword) {
        mostrarMensaje(mensaje, "Las contraseñas no coinciden.", "red");
        return;
    }

    const usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

    const existeCorreo = usuarios.some(function (usuario) {
        return usuario.correo === correo;
    });

    if (existeCorreo) {
        mostrarMensaje(mensaje, "Ya existe una cuenta con ese correo.", "red");
        return;
    }

    const usuario = {
        nombre: nombre,
        correo: correo,
        telefono: telefono,
        tipoUsuario: tipoUsuario,
        password: password,
        fechaRegistro: new Date().toLocaleDateString()
    };

    usuarios.push(usuario);
    localStorage.setItem("usuarios", JSON.stringify(usuarios));

    mostrarMensaje(mensaje, "Usuario registrado correctamente.", "green");

    setTimeout(function () {
        window.location.href = "login.html";
    }, 1000);
}

function iniciarSesion(evento) {
    evento.preventDefault();

    const correo = document.getElementById("correoLogin").value.trim();
    const password = document.getElementById("passwordLogin").value;
    const recordarSesion = document.getElementById("recordarSesion").checked;
    const mensaje = document.getElementById("mensajeLogin");

    if (correo === "" || password === "") {
        mostrarMensaje(mensaje, "Debe ingresar correo y contraseña.", "red");
        return;
    }

    const usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

    const usuarioEncontrado = usuarios.find(function (usuario) {
        return usuario.correo === correo && usuario.password === password;
    });

    if (!usuarioEncontrado) {
        mostrarMensaje(mensaje, "Correo o contraseña incorrectos.", "red");
        return;
    }

    localStorage.setItem("usuarioActivo", JSON.stringify(usuarioEncontrado));

    if (recordarSesion) {
        localStorage.setItem("correoRecordado", correo);
    } else {
        localStorage.removeItem("correoRecordado");
    }

    mostrarMensaje(mensaje, "Inicio de sesión correcto.", "green");

    setTimeout(function () {
        window.location.href = "dashboard.html";
    }, 1000);
}

function cargarUsuarioDashboard() {
    const usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));
    const usuarioDashboard = document.getElementById("usuarioDashboard");

    if (!usuarioActivo) {
        window.location.href = "login.html";
        return;
    }

    usuarioDashboard.textContent = usuarioActivo.nombre + " - " + usuarioActivo.tipoUsuario;
}

function cerrarSesion() {
    localStorage.removeItem("usuarioActivo");
    window.location.href = "login.html";
}

function mostrarMensaje(elemento, texto, color) {
    if (elemento) {
        elemento.textContent = texto;
        elemento.style.color = color;
    }
}

function validarCorreo(correo) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);
}