document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('.toggle-pass');
  const input = document.getElementById('password');
  if (!btn || !input) return;
  btn.addEventListener('click', () => {
    input.type = input.type === 'password' ? 'text' : 'password';
  });
});
