<?php
$pageTitle = 'Ranking | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">
  <h2 class="mb-4">Ranking Global</h2>
  <div id="rankingList"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const container = document.getElementById('rankingList');
  fetch('api/ranking.php?action=list')
    .then(r=>r.json())
    .then(j=>{
      if (j.success && Array.isArray(j.data)) {
        let html = '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Pos</th><th>Usuario</th><th>Puntos</th><th>Kg</th></tr></thead><tbody>';
        j.data.forEach(u=>{
          html += `<tr><td>${u.posicion}</td><td>${u.nombre}</td><td>${u.puntos_totales}</td><td>${Number(u.kg_reciclados || 0).toFixed(2)} kg</td></tr>`;
        });
        html += '</tbody></table></div>';
        container.innerHTML = html;
      } else {
        container.innerHTML = '<p class="text-muted">No hay datos de ranking.</p>';
      }
    }).catch(()=>{ container.innerHTML = '<p class="text-danger">Error al cargar ranking.</p>'; });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
