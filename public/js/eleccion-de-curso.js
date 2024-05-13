//Eleccion de curso para el alumno
document.getElementById('reg-role').addEventListener('change', function() {
    var style = this.value == 'alumno' ? 'block' : 'none';
    document.getElementById('student-course-selection').style.display = style;
});