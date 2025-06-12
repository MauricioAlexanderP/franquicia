function paginarTabla(tablaId = 'tabla', paginacionId = 'paginacion-tabla', filasPorPagina = 10) {
  const filas = Array.from(document.querySelectorAll(`#${tablaId} tr`));
  const paginacion = document.getElementById(paginacionId);
  let paginaActual = 1;
  const totalPaginas = Math.ceil(filas.length / filasPorPagina);

  function mostrarPagina(pagina) {
    paginaActual = pagina;
    const inicio = (pagina - 1) * filasPorPagina;
    const fin = inicio + filasPorPagina;

    filas.forEach((fila, index) => {
      fila.style.display = (index >= inicio && index < fin) ? "" : "none";
    });

    renderizarBotones();
  }

  function renderizarBotones() {
    paginacion.innerHTML = "";
    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement("li");
      li.className = `page-item ${i === paginaActual ? "active" : ""}`;
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      li.addEventListener("click", (e) => {
        e.preventDefault();
        mostrarPagina(i);
      });
      paginacion.appendChild(li);
    }
  }

  mostrarPagina(1);
}

