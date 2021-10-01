const styleElem = document.getElementById('custom-background-css');
const style = styleElem.textContent.slice(5);
styleElem.textContent = '.header-background' + style;
document.addEventListener('DOMContentLoaded', () => {
  if (document.body.classList.contains('custom-background')) {
    document.querySelector('.header-background').classList.add('custom-background');
  }
})
