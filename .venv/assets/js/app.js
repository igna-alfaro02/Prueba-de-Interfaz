document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formProducto");
  const bodegaSelect = document.getElementById("bodega");
  const sucursalSelect = document.getElementById("sucursal");

  // Datos de relación entre bodegas y sucursales
  const sucursalesPorBodega = {
    "Bodega 1": ["Sucursal 1", "Sucursal 2"],
    "Bodega 2": ["Sucursal 3", "Sucursal 4"]
  };

  // Evento: cuando cambie la bodega, actualizar sucursales
  bodegaSelect.addEventListener("change", function () {
    const bodegaSeleccionada = this.value;

    // Limpiar sucursales anteriores
    sucursalSelect.innerHTML = '<option value=""></option>';

    if (sucursalesPorBodega[bodegaSeleccionada]) {
      sucursalesPorBodega[bodegaSeleccionada].forEach(sucursal => {
        const option = document.createElement("option");
        option.value = sucursal;
        option.textContent = sucursal;
        sucursalSelect.appendChild(option);
      });
    }
  });

  // Evento submit del formulario
  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    // Obtener valores
    const codigo = document.getElementById("codigo_producto").value.trim();
    const nombre = document.getElementById("nombre_producto").value.trim();
    const bodega = bodegaSelect.value;
    const sucursal = sucursalSelect.value;
    const moneda = document.getElementById("moneda").value;
    const precio = document.getElementById("precio").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const materiales = document.querySelectorAll('input[name="material[]"]:checked');

    // Codigo
    if (codigo === "") {
      alert("El código del producto no puede estar en blanco.");
      return;
    }
    if (!/[A-Za-z]/.test(codigo) || !/[0-9]/.test(codigo) || /[^A-Za-z0-9]/.test(codigo)) {
      alert("El código del producto debe contener letras y números.");
      return;
    }
    if (codigo.length < 5 || codigo.length > 15) {
      alert("El código del producto debe tener entre 5 y 15 caracteres.");
      return;
    }

    // Nombre
    if (nombre === "") {
      alert("El nombre del producto no puede estar en blanco.");
      return;
    }
    if (nombre.length < 2 || nombre.length > 50) {
      alert("El nombre del producto debe tener entre 2 y 50 caracteres.");
      return;
    }

    // Bodega
    if (bodega === "") {
      alert("Debe seleccionar una bodega.");
      return;
    }

    // Sucursal
    if (sucursal === "") {
      alert("Debe seleccionar una sucursal para la bodega seleccionada.");
      return;
    }

    // Moneda
    if (moneda === "") {
      alert("Debe seleccionar una moneda para el producto.");
      return;
    }

    // Precio
    if (precio === "") {
      alert("El precio del producto no puede estar en blanco.");
      return;
    }
    const regexPrecio = /^[0-9]+(\.[0-9]{1,2})?$/;
    if (!regexPrecio.test(precio) || parseFloat(precio) <= 0) {
      alert("El precio del producto debe ser un número positivo con hasta dos decimales.");
      return;
    }

    // Material
    if (materiales.length < 2) {
      alert("Debe seleccionar al menos dos materiales para el producto.");
      return;
    }

    // Descripcion
    if (descripcion === "") {
      alert("La descripción del producto no puede estar en blanco.");
      return;
    }
    if (descripcion.length < 10 || descripcion.length > 1000) {
      alert("La descripción del producto debe tener entre 10 y 1000 caracteres.");
      return;
    }

    // Si esta listo
    alert("Producto validado correctamente (listo para enviar).");

    const formData = new FormData(form);

    const response = await fetch("api/save_producto.php", {
      method: "POST",
      body: formData
    });

    const result = await response.json();

    alert(result.message); // Muestra mensaje desde PHP

    if (result.status === "success") {
      form.reset();
      // Restablecer sucursal
      sucursalSelect.innerHTML = '<option value="">Seleccione una sucursal</option>';
    }
  });
});
