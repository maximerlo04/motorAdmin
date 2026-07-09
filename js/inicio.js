window.addEventListener('scroll', () => {
  const img = document.querySelector('.parallax-img img'); // Solo la imagen
  const text = document.querySelector('.parallax-text');
  const offset = window.scrollY;

  // Efecto parallax vertical para el texto
  if (text) {
    const yOffset = Math.max(50 - offset * 0.15, 0); // se mueve hacia arriba hasta 0
    text.style.transform = `translateY(${yOffset}px)`;

    if (offset > 100) {
      text.style.opacity = 1;
    }
  }

  // Efecto parallax horizontal + fade-in para la imagen
  if (img) {
    const maxOffset = 150; // límite de desplazamiento horizontal
    const xOffset = Math.min(offset * 0.15, maxOffset);

    img.style.transform = `translateX(${xOffset}px)`;
    
    if (offset > 100) {
      img.style.opacity = 1;
    }
  }
});

window.addEventListener('scroll', () => {
  const img = document.querySelector('.turnos-img img');
  const text = document.querySelector('.turnos-text');
  const offset = window.scrollY;

  // Parallax vertical para el texto
  if (text) {
    const yOffset = Math.max(50 - offset * 0.15, 0); // Mueve hacia arriba
    text.style.transform = `translateY(${yOffset}px)`;

    if (offset > 100) {
      text.style.opacity = 1;
    }
  }

  // Parallax horizontal para la imagen
  if (img) {
    const maxOffset=120;
    const xOffset = Math.min(offset * 0.15, maxOffset); // Límite de movimiento
    img.style.transform = `translateX(${xOffset}px)`;

    if (offset > 100) {
      img.style.opacity = 1;
    }
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const servicios = document.querySelectorAll('.servicio');

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
      }
    });
  }, {
    threshold: 0.5 // se activa cuando el 50% del elemento está visible
  });

  servicios.forEach(servicio => observer.observe(servicio));
});

document.addEventListener("DOMContentLoaded", () => {
  const contactoImg = document.querySelector('.contacto-img');
  const contactoTexto = document.querySelector('.contacto-text');

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
      }
    });
  }, {
    threshold: 0.5
  });

  if (contactoImg) observer.observe(contactoImg);
  if (contactoTexto) observer.observe(contactoTexto);
});