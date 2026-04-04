const theme = localStorage.getItem('theme') || 'light'

if (theme === 'dark') {
  document.documentElement.setAttribute('data-bs-theme', 'dark')
  document.documentElement.classList.add('dark')
} else {
  document.documentElement.setAttribute('data-bs-theme', 'light')
  document.documentElement.classList.remove('dark')
}
