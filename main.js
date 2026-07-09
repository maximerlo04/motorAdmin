import './style.css'

window.addEventListener('scroll', () => {
  const img = document.querySelector('.parallax-img');
  const text = document.querySelector('.parallax-text');

  const offset = window.scrollY;

  img.style.transform = `translateY(${offset * 0.3}px)`;
  text.style.transform = `translateY(${offset * 0.15}px)`;
});
