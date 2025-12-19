let modalCreate, modalEdit, modalPagar, modalDetalle;

document.addEventListener('DOMContentLoaded', () => {
    // Inicializamos Modales
    const elC = document.getElementById('modalServicioCreate');
    const elE = document.getElementById('modalServicioEdit');
    const elP = document.getElementById('modalServicioPagar');
    const elD = document.getElementById('modalDetalleServicio');

    if (elC) modalCreate = new bootstrap.Modal(elC);
    if (elE) modalEdit = new bootstrap.Modal(elE);
    if (elP) modalPagar = new bootstrap.Modal(elP);
    if (elD) modalDetalle = new bootstrap.Modal(elD);
});

// 1. Crear
function abrirModalNuevo() {
    const form = document.getElementById('formCreateServicio');
    if (form) form.reset();
    if (modalCreate) modalCreate.show();
}

// 2. Editar (Carga datos en modal)
function editarServicio(btn) {
    const d = btn.dataset;
    const form = document.getElementById('formEditServicio');

    // Ruta corregida: /admin/servicios/{id}
    form.action = `/admin/servicios/${d.id}`;

    // Mapeo de campos
    // Usamos ?. por si alguno es null
    if (document.getElementById('edit_estado'))
        document.getElementById('edit_estado').value = d.estado;

    if (document.getElementById('edit_tecnico'))
        document.getElementById('edit_tecnico').value = d.tecnico || '';

    if (document.getElementById('edit_cotizacion'))
        document.getElementById('edit_cotizacion').value = d.cotizacion || '';

    if (modalEdit) modalEdit.show();
}

// 3. Pagar
function registrarPago(btn) {
    const id = btn.dataset.id;
    const deuda = btn.dataset.deuda;

    const form = document.getElementById('formPagarServicio');
    // Ruta para Pagos: /admin/servicios/{id}/pagos
    form.action = `/admin/servicios/${id}/pagos`;

    const txtDeuda = document.getElementById('pago_txt_deuda');
    if (txtDeuda) txtDeuda.textContent = deuda;

    // Limpiar input monto
    const inputMonto = form.querySelector('input[name="monto"]');
    if (inputMonto) inputMonto.value = '';

    if (modalPagar) modalPagar.show();
}

// 4. Ver Detalle (AJAX)
async function verDetalle(id) {
    const content = document.getElementById('contenidoDetalle');
    if (!content) return;

    content.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div><p>Cargando información...</p></div>';

    if (modalDetalle) modalDetalle.show();

    try {
        // Ruta corregida: Admin Service Show (Laravel detecta request AJAX si quieres, o usas ruta especifica)
        // Como definimos resource('servicios'), la ruta show es /admin/servicios/{id}
        const res = await fetch(`/admin/servicios/${id}`);

        if (!res.ok) throw new Error('Error en la respuesta del servidor');

        content.innerHTML = await res.text();

    } catch (e) {
        console.error(e);
        content.innerHTML = '<div class="alert alert-danger">Error cargando detalle. Intenta nuevamente.</div>';
    }
}