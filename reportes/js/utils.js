/**
 * ajaxPOST
 * @param {string} url
 * @param {object} body
 * @param {object} headers
 */
const ajaxPOST = async (url, body = {}, headers = {}) => {
  const response = await fetch(url, {
    headers: {
      ...headers,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    method: "POST",
    body: typeof body === "object" ? JSON.stringify(body) : body,
  });
  return await response.json();
};

/**
 * ajaxGET
 * @param {string} url
 * @param {string} body
 * @param {object} headers
 */
const ajaxGET = async (url, headers = {}) => {
  const response = await fetch(url, {
    headers,
    method: "GET",
  });
  return await response.json();
};

const showErrorMessage = (text) => {
  Swal.fire({icon: "error", title: "Error", text});
};

const openModal = (modalID) => {
  const modal = new bootstrap.Modal(document.getElementById(modalID));
  modal.show();
  return modal;
};

function mostrarOcultarErrores(mostrar = true) {
  const formControls = document.querySelectorAll(".form-control");
  for (const control of formControls) {
    if (mostrar) {
      control.classList.add("is-invalid");
    } else {
      control.classList.remove("is-invalid");
    }
  }
}

/**
 * onBlurSoloNumeros
 * Permite el ingreso unicamente de números (Se ejecuta en el event blur)
 * @param e {Object}
 * @return {boolean}
 */
function onBlurSoloNumeros(e) {
  e.target.value = e.target.value.trim();
  if (!/^[0-9]+$/.test(e.target.value)) {
    e.target.value = "";
  }
  return true;
}

/**
 * onlyNumbers
 * Permite el ingreso unicamente números
 * @param e {object}
 * @return {boolean|undefined}
 */
function onlyNumbers(e) {
  if (e.keyCode < 48 || e.keyCode > 57) e.preventDefault();
  if (
    (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
    (e.keyCode < 96 || e.keyCode > 105)
  )
    e.preventDefault();
  if (e.altKey) return false;
}

function enterAndSubmit(e) {
  if (e.keyCode === 13) {
    e.preventDefault();
    document.getElementById(e.target.dataset.idbutton).click();
  }
}
