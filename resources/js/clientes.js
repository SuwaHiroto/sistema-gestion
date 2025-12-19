// 1. Declaramos las variables de los modales en el ámbito global
let modalCreate;
let modalEdit;
let modalHistorial;

// 2. Inicializamos los modales cuando el sitio termina de cargar
document.addEventListener('DOMContentLoaded', () => {
    // Asegurarse de que los IDs coincidan con tus Vistas Blade
    const elCreate = document.getElementById('modalClienteCreate');
    const elEdit = document.getElementById('modalClienteEdit');
    const elHist = document.getElementById('modalHistorial');

    // Inicializamos solo si existen para evitar errores en consola
    if (elCreate) modalCreate = new bootstrap.Modal(elCreate);
    if (elEdit) modalEdit = new bootstrap.Modal(elEdit);
    if (elHist) modalHistorial = new bootstrap.Modal(elHist);
});

// ---------------------------------------------------------
// 3. Funciones Globales
// ---------------------------------------------------------

function abrirModalNuevo() {
    const form = document.getElementById('formCreateCliente');
    if (form) form.reset();

    if (modalCreate) modalCreate.show();
}

function editarCliente(boton) {
    // Leemos los datos del botón (data-attributes)
    const data = boton.dataset;
    const form = document.getElementById('formEditCliente');

    if (!form) return;

    // ACTUALIZACIÓN: La ruta ahora debe incluir el prefijo de admin si así lo definiste
    // Si tu ruta en web.php es resource('clientes'), laravel espera /admin/clientes/{id}
    form.action = `/admin/clientes/${data.id}`;

    // Rellenamos inputs con la info existente
    // Usamos el operador opcional (?.) por seguridad si algún campo viene vacío
    if (document.getElementById('edit_nombres'))
        document.getElementById('edit_nombres').value = data.nombres;

    if (document.getElementById('edit_telefono'))
        document.getElementById('edit_telefono').value = data.telefono;

    if (document.getElementById('edit_correo'))
        document.getElementById('edit_correo').value = data.correo || '';

    if (document.getElementById('edit_direccion'))
        document.getElementById('edit_direccion').value = data.direccion || '';

    // NOTA: Eliminamos la linea de 'edit_tecnico' porque ya no existe esa relación directa en la tabla clientes.

    if (modalEdit) modalEdit.show();
}

async function verHistorial(idCliente) {
    const contenedor = document.getElementById('contenidoHistorial');
    if (!contenedor) return;

    // Loader visual mientras carga
    contenedor.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Cargando historial...</p>
        </div>
    `;

    if (modalHistorial) modalHistorial.show();

    try {
        // ACTUALIZACIÓN: Ruta apuntando al controlador de Admin
        const response = await fetch(`/admin/clientes/${idCliente}/historial`);

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const html = await response.text();
        contenedor.innerHTML = html;

    } catch (error) {
        console.error('Error al cargar historial:', error);
        contenedor.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="bi bi-exclamation-triangle-fill"></i> 
                No se pudo cargar el historial. Intenta nuevamente.
            </div>`;
    }
}