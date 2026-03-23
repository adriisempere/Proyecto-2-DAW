<?php
$pageTitle = 'Mis Registros | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">
  <h2 class="mb-4">Mis Registros de Reciclaje</h2>
  <div id="misRegistros"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const container = document.getElementById('misRegistros');
  fetch('api/registro.php?action=list')
    .then(r=>r.json())
    .then(j=>{
      if (j.success && Array.isArray(j.data)) {
        if (j.data.length === 0) { container.innerHTML = '<p class="text-muted">Aún no tienes registros.</p>'; return; }
        let html = '';
        j.data.forEach(r=>{
          html += `<div class="card mb-3"><div class="card-body"><p><strong>Fecha:</strong> ${r.fecha}</p><p><strong>Material:</strong> ${r.tipo_material}</p><p><strong>Cantidad:</strong> ${r.cantidad} kg</p><p><strong>Puntos:</strong> ${r.puntos_ganados}</p>${r.centro_nombre?`<p><strong>Centro:</strong> ${r.centro_nombre}</p>`:''}</div></div>`;
        });
        container.innerHTML = html;
      } else {
        container.innerHTML = '<p class="text-muted">No se han podido obtener tus registros.</p>';
      }
    }).catch(()=>{ container.innerHTML = '<p class="text-danger">Error al cargar registros.</p>'; });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
