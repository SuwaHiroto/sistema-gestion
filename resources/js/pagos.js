let modalEdit;

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('modalEditarPago');
    if (el) modalEdit = new bootstrap.Modal(el);
});

function editarPago(btn) {
    const d = btn.dataset;
    const form = document.getElementById('formEditarPago');

    if (!form) return;

    // Ruta: admin/pagos/{id}
    // Asegúrate de que tu ruta en web.php sea resource('pagos', PagoController::class) dentro del grupo admin
    form.action = `/admin/pagos/${d.id}`;

    // Llenar campos
    if (document.getElementById('edit_cliente_nombre'))
        document.getElementById('edit_cliente_nombre').value = d.cliente;

    if (document.getElementById('edit_monto'))
        document.getElementById('edit_monto').value = d.monto;

    if (document.getElementById('edit_tipo'))
        document.getElementById('edit_tipo').value = d.tipo;

    if (document.getElementById('edit_fecha'))
        document.getElementById('edit_fecha').value = d.fecha;

    if (modalEdit) modalEdit.show();
}