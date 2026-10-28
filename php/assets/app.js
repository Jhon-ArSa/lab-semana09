document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('mainForm');
  if (!form) return;

  form.addEventListener('submit', (event) => {
    const nombre = form.elements.nombre.value.trim();
    const email = form.elements.email.value.trim();
    if (nombre.length < 3) {
      event.preventDefault();
      alert('Ingrese un nombre con al menos 3 caracteres.');
      form.elements.nombre.focus();
      return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      event.preventDefault();
      alert('Ingrese un correo electrónico válido.');
      form.elements.email.focus();
    }
  });
});
