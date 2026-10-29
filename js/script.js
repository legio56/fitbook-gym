document.querySelector('.nav-toggle')?.addEventListener('click', () => document.querySelector('.nav-links')?.classList.toggle('is-open'));
document.querySelectorAll('[data-confirm]').forEach((formControl) => formControl.addEventListener('click', (event) => { if (!window.confirm(formControl.dataset.confirm)) event.preventDefault(); }));
const observer = new IntersectionObserver((entries) => entries.forEach((entry) => { if (entry.isIntersecting) entry.target.classList.add('is-visible'); }), { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach((element) => observer.observe(element));
