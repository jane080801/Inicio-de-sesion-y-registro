document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formRegistro');
    const mensajesDiv = document.getElementById('mensajes');
    const password = document.getElementById('password');
    const confirmarPassword = document.getElementById('confirmarPassword');
    
    function mostrarMensaje(tipo, mensaje) {
        mensajesDiv.innerHTML = `<div class="mensaje ${tipo}">${mensaje}</div>`;
        setTimeout(() => {
            mensajesDiv.innerHTML = '';
        }, 5000);
    }

    function mostrarError(input, mensaje) {
        const inputGroup = input.parentElement;
        const errorExistente = inputGroup.querySelector('.error-message');
        if (errorExistente) {
            errorExistente.remove();
        }
        
        input.classList.add('input-error');
        
        const error = document.createElement('div');
        error.className = 'error-message';
        error.innerText = mensaje;
        inputGroup.appendChild(error);
    }
    
    function eliminarError(input) {
        const inputGroup = input.parentElement;
        const errorExistente = inputGroup.querySelector('.error-message');
        if (errorExistente) {
            errorExistente.remove();
        }
        input.classList.remove('input-error');
    }
    
    function validarContraseñas() {
        if (password.value !== confirmarPassword.value) {
            mostrarError(confirmarPassword, 'Las contraseñas no coinciden');
            return false;
        } else {
            eliminarError(confirmarPassword);
            return true;
        }
    }
    
    function validarFormulario() {
        let esValido = true;
        
        if (!validarContraseñas()) {
            esValido = false;
        }
        
        const politicas = document.getElementById('politicas');
        if (!politicas.checked) {
            mostrarError(politicas, 'Debes aceptar las políticas de privacidad');
            esValido = false;
        } else {
            eliminarError(politicas);
        }
        
        return esValido;
    }
    
    confirmarPassword.addEventListener('input', validarContraseñas);
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log("Formulario enviado"); // Debug
        
        if (validarFormulario()) {
            const formData = new FormData(form);
            
            // Mostrar datos que se enviarán (debug)
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            fetch('registro.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log("Respuesta recibida:", response);
                return response.json();
            })
            .then(data => {
                console.log("Datos procesados:", data);
                if (data.success) {
                    mostrarMensaje('exito', data.message);
                    form.reset();
                    setTimeout(() => {
                        window.location.href = 'login.html';
                    }, 2000);
                } else {
                    data.errors.forEach(error => {
                        mostrarMensaje('error', error);
                    });
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                mostrarMensaje('error', 'Error al procesar la solicitud: ' + error.message);
            });
        }
    });
    
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            eliminarError(this);
        });
    });
});