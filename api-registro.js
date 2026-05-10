document.addEventListener('DOMContentLoaded', function() {
    const centroSelect = document.getElementById('centro_id');
    const materialContainer = document.getElementById('materialOptions');
    const registroForm = document.getElementById('registroForm');
    const submitBtn = registroForm ? registroForm.querySelector('button[type="submit"]') : null;

    const materiales = {
        plastico: { icon: 'bi-box-seam', puntos: 10 },
        papel:    { icon: 'bi-file-earmark-text', puntos: 5 },
        vidrio:   { icon: 'bi-lightbulb', puntos: 8 },
        metal:    { icon: 'bi-nut', puntos: 15 },
        organico: { icon: 'bi-leaf', puntos: 3 }
    };

    // 1. Carga de centros con validación de respuesta
    async function cargarCentros() {
        try {
            const response = await fetch('api/centros.php?action=list');
            if (!response.ok) throw new Error('Error en servidor');
            
            const json = await response.json();
            if (json.success && Array.isArray(json.data)) {
                centroSelect.innerHTML = '<option value="" selected disabled>Selecciona un centro cercano</option>';
                json.data.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.nombre;
                    centroSelect.appendChild(opt);
                });
            } else {
                throw new Error(json.message || 'Sin centros');
            }
        } catch (err) {
            centroSelect.innerHTML = '<option value="" disabled>Error cargando centros</option>';
            console.error("Error centros:", err);
        }
    }

    // 2. Generación dinámica de materiales
    Object.keys(materiales).forEach(m => {
        const { icon, puntos } = materiales[m];
        const col = document.createElement('div');
        col.className = 'col-6 col-md-4 mb-3'; // Mejor responsividad
        col.innerHTML = `
            <input type="radio" class="btn-check" name="tipo_material" id="material_${m}" value="${m}" required>
            <label class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3" for="material_${m}">
                <i class="bi ${icon} fs-2 mb-2"></i>
                <span class="text-capitalize fw-bold">${m}</span>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill mt-1">${puntos} pts/kg</span>
            </label>
        `;
        materialContainer.appendChild(col);
    });

    // 3. Envío del formulario
    if (registroForm) {
        registroForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validación nativa de Bootstrap
            if (!registroForm.checkValidity()) {
                e.stopPropagation();
                registroForm.classList.add('was-validated');
                return;
            }

            // Estado de carga
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Enviando...';

            const formData = new FormData(registroForm);
            const payload = Object.fromEntries(formData.entries());
            
            // Mejora: Convertir peso a número si existe
            if (payload.peso) payload.peso = parseFloat(payload.peso);

            try {
                const res = await fetch('api/registro.php?action=store', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                    credentials: 'same-origin'
                });

                // Validar si la respuesta es JSON válido
                const contentType = res.headers.get("content-type");
                if (!res.ok || !contentType || !contentType.includes("application/json")) {
                    throw new Error('Respuesta no válida del servidor');
                }

                const json = await res.json();

                if (json.success) {
                    alert(json.message || '¡Registro completado con éxito!');
                    window.location.href = 'index.php?action=home';
                } else {
                    alert('Error: ' + (json.message || 'No se pudo procesar el registro'));
                }
            } catch (err) {
                console.error("Error registro:", err);
                alert('Ocurrió un problema de conexión o el servidor no respondió adecuadamente.');
            } finally {
                // Restaurar botón
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }

    cargarCentros();
});