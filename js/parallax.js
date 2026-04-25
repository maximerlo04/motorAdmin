window.addEventListener('load', () => {
  const cards = document.querySelectorAll('.card');

  cards.forEach((card, index) => {
    card.style.opacity = 0;
    card.style.transform = 'translateY(100px) rotate(-5deg)';
    card.style.transition = 'all 0.4s ease-in-out';
    card.style.transitionDelay = `${index * 0.2}s`;
  });

  setTimeout(() => {
    cards.forEach(card => {
      card.style.opacity = 1;
      card.style.transform = 'translateY(0) rotate(0deg)';
    });
  }, 100);
});
