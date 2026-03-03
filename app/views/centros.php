<?php
$pageTitle = "Centros | GreenPoints";
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">
  <h2 class="mb-4">Centros de Reciclaje</h2>
  <div id="centrosList" class="row g-3"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const list = document.getElementById('centrosList');
  fetch('api/centros.php?action=list')
    .then(r=>r.json())
    .then(j=>{
      if (j.success && Array.isArray(j.data)) {
        j.data.forEach(c=>{
          const col = document.createElement('div'); col.className='col-md-6';
          col.innerHTML = `
            <div class="card p-3 shadow-sm">
              <h5 class="fw-bold">${c.nombre}</h5>
              <p class="mb-1">${c.direccion}</p>
              <small class="text-muted">Tipos: ${c.tipos_residuos}</small>
              <div class="mt-2"><small class="text-muted">Horario: ${c.horario}</small></div>
            </div>
          `;
          list.appendChild(col);
        });
      } else {
        list.innerHTML = '<p class="text-muted">No hay centros disponibles.</p>';
      }
    }).catch(()=>{ list.innerHTML = '<p class="text-danger">Error cargando centros.</p>'; });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
