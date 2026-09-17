<h4>Detalle de {{ $user->name }}</h4>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a href="#" class="nav-link tab-link active" data-tab="config">Configuración</a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link tab-link" data-tab="asistencias">Asistencias</a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link tab-link" data-tab="resumen">Resumen</a>
    </li>

</ul>

<div id="contenido-tabs" class="p-3 border rounded bg-light">
    Cargando...
</div>

<script>
    window.USER_ID = {{ $user->id }};
    setTimeout(() => {
        document.querySelector('.tab-link.active').click();
    }, 200);
</script>