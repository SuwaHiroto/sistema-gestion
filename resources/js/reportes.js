function exportarTablaCSV() {
    const tabla = document.getElementById("tablaReporte");
    if (!tabla) return;

    let csv = [];
    // Obtenemos todas las filas (tr)
    const filas = tabla.querySelectorAll("tr");

    filas.forEach(fila => {
        let cols = fila.querySelectorAll("td, th");
        let filaData = [];

        cols.forEach(col => {
            // Limpiamos el texto (quitamos saltos de línea y espacios dobles)
            let data = col.innerText.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, " ").trim();
            filaData.push(`"${data}"`); // Comillas para evitar problemas con comas
        });

        csv.push(filaData.join(","));
    });

    // Crear el archivo Blob
    const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    const downloadLink = document.createElement("a");

    downloadLink.download = `Reporte_Electrigonza_${new Date().toISOString().slice(0, 10)}.csv`;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}