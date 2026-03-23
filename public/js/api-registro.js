document.addEventListener('DOMContentLoaded', function(){
    const centroSelect = document.getElementById('centro_id');
    const materialContainer = document.getElementById('materialOptions');
    const registroForm = document.getElementById('registroForm');

    // Materiales y sus puntos por kg
    const materiales = {
        plastico: { icon: 'bi-box-seam', puntos: 10 },
        papel:    { icon: 'bi-file-earmark-text', puntos: 5 },
        vidrio:   { icon: 'bi-lightbulb', puntos: 8 },
        metal:    { icon: 'bi-nut', puntos: 15 },
        organico: { icon: 'bi-leaf', puntos: 3 }
    };

    // Cargar centros desde API
    fetch('api/centros.php?action=list')
        .then(r => r.json())
        .then(json => {
            if (json.success && Array.isArray(json.data)) {
                centroSelect.innerHTML = '<option value="" selected disabled>Selecciona un centro cercano</option>';
                json.data.forEach(c => {
                    const opt = document.createElement('option'); opt.value = c.id; opt.textContent = c.nombre; centroSelect.appendChild(opt);
                });
            } else {
                centroSelect.innerHTML = '<option value="" disabled>No hay centros disponibles</option>';
            }
        }).catch(()=>{
            centroSelect.innerHTML = '<option value="" disabled>Error cargando centros</option>';
        });

    // Generar botones de material
    Object.keys(materiales).forEach(m => {
        const icon = materiales[m].icon;
        const pts = materiales[m].puntos;
        const col = document.createElement('div'); col.className = 'col-6';
        col.innerHTML = `
            <input type="radio" class="btn-check" name="tipo_material" id="material_${m}" value="${m}" required>
            <label class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 position-relative" for="material_${m}">
                <i class="bi ${icon} fs-2 mb-2"></i>
                <span class="text-capitalize fw-bold">${m}</span>
                <span class="badge bg-success bg-opacity-25 text-success rounded-pill mt-1">${pts} pts/kg</span>
            </label>
        `;
        materialContainer.appendChild(col);
    });

    // Envío por fetch
    if (registroForm) {
        registroForm.addEventListener('submit', async function(e){
            e.preventDefault();
            if (!registroForm.checkValidity()) { registroForm.classList.add('was-validated'); return; }

            const formData = new FormData(registroForm);
            const payload = {};
            formData.forEach((v,k)=> payload[k]=v);

            try {
                const res = await fetch('api/registro.php?action=store', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json'},
                    body: JSON.stringify(payload),
                    credentials: 'same-origin'
                });
                const json = await res.json();
                if (json.success) {
                    // mostrar mensaje y redirigir
                    alert(json.message || 'Registrado');
                    window.location = 'index.php?action=home';
                } else {
                    alert(json.message || 'Error al registrar');
                }
            } catch (err) {
                alert('Error de red');
            }
        });
    }
});
