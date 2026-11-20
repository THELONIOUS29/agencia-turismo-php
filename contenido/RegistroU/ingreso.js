const inputs = document.querySelectorAll(".campo");
const botones_cambiar = document.querySelectorAll(".cambiar");
const main = document.querySelector("main");
const flechas = document.querySelectorAll(".flechas span");
const imagenes = document.querySelectorAll(".imagen");
const textos = document.querySelectorAll(".texto");

inputs.forEach((inp) => {
  inp.addEventListener("focus", () => {
    inp.classList.add("activo");
  });
  inp.addEventListener("blur", () => {
    if (inp.value != "") return;
    inp.classList.remove("activo");
  });
});

botones_cambiar.forEach((btn) => {
  btn.addEventListener("click", () => {
    main.classList.toggle("modo-registro");
  });
});

let indiceActual = 1;

function moverDeslizador(indice) {
  let imagenActual = document.querySelector(`.img-${indice}`);
  imagenes.forEach((img) => img.classList.remove("mostrar"));
  imagenActual.classList.add("mostrar");

  const textoActual = document.querySelector(`.texto-${indice}`);
  textos.forEach((txt) => txt.classList.remove("mostrar"));
  textoActual.classList.add("mostrar");

  flechas.forEach((flecha) => flecha.classList.remove("activo"));
  document.querySelector(`.flechas span[data-value="${indice}"]`).classList.add("activo");
}

function siguienteDeslizador() {
  indiceActual = (indiceActual % imagenes.length) + 1;
  moverDeslizador(indiceActual);
}


moverDeslizador(indiceActual);

// Cambia automaticamente cada 3 segundos
setInterval(siguienteDeslizador, 3000);

flechas.forEach((flecha) => {
  flecha.addEventListener("click", function() {
    let indice = this.dataset.value;
    moverDeslizador(indice);
  });
});
