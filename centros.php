<?php
$pageTitle = "Centros | GreenPoints";
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">
  <h2 class="mb-4">Centros de Reciclaje</h2>
  <div id="centrosList" class="row g-3"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', cargarCentros);

async function cargarCentros() {

  const list = document.getElementById('centrosList');

  try {

    const response = await fetch('api/centros.php?action=list');

    if (!response.ok)
      throw new Error('Error HTTP');

    const j = await response.json();

    list.innerHTML = '';

    if (!j.success || !Array.isArray(j.data)) {
      list.innerHTML =
        '<p class="text-muted">No hay centros disponibles.</p>';
      return;
    }

    j.data.forEach(c => {
      list.appendChild(crearCard(c));
    });

  } catch (err) {
    console.error(err);
    list.innerHTML =
      '<p class="text-danger">Error cargando centros.</p>';
  }
}

function crearCard(c) {

  const col = document.createElement('div');
  col.className = 'col-md-6';

  const card = document.createElement('div');
  card.className = 'card p-3 shadow-sm';

  const titulo = document.createElement('h5');
  titulo.className = 'fw-bold';
  titulo.textContent = c.nombre ?? 'Centro sin nombre';

  const direccion = document.createElement('p');
  direccion.className = 'mb-1';
  direccion.textContent = c.direccion ?? 'Dirección no disponible';

  const tipos = document.createElement('small');
  tipos.className = 'text-muted';
  tipos.textContent = `Tipos: ${c.tipos_residuos ?? 'No especificado'}`;

  const horarioWrap = document.createElement('div');
  horarioWrap.className = 'mt-2';

  const horario = document.createElement('small');
  horario.className = 'text-muted';
  horario.textContent = `Horario: ${c.horario ?? 'No disponible'}`;

  horarioWrap.appendChild(horario);

  card.append(titulo, direccion, tipos, horarioWrap);
  col.appendChild(card);

  return col;
}
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
