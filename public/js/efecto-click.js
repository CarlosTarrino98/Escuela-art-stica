document.addEventListener('click', function(e) {
    // Crear un nuevo círculo
    var circle = document.createElement('div');
    circle.classList.add('click-circle');
    document.getElementById('click-effect-container').appendChild(circle);

    // Ajustar la posición del círculo
    circle.style.position = 'fixed'; // Posición fija o absoluta
    circle.style.zIndex = 9999;
    circle.style.width = circle.style.height = '10px'; // Tamaño inicial del círculo
    circle.style.left = e.clientX - 5 + 'px'; // Centrar el círculo en el punto de clic
    circle.style.top = e.clientY - 5 + 'px';

    // Animar el círculo
    setTimeout(function() {
        circle.style.transform = 'scale(5)'; // Hace que el círculo se expanda
    }, 10);

    // Eliminar el círculo después de la animación
    setTimeout(function() {
        circle.remove();
    }, 600); // 600 ms = duración de la animación
});
