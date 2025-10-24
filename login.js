document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formLogin');
    const mensajesDiv = document.getElementById('mensajes');
    
    function mostrarMensaje(tipo, mensaje) {
        mensajesDiv.innerHTML = `<div class="mensaje ${tipo}">${mensaje}</div>`;
        setTimeout(() => {
            mensajesDiv.innerHTML = '';
        }, 5000);
    }
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarMensaje('exito', data.message);
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 1500);
            } else {
                data.errors.forEach(error => {
                    mostrarMensaje('error', error);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('error', 'Error al procesar la solicitud');
        });
    });
});
