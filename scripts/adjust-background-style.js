(function (){
  const styleElem = document.getElementById('custom-background-css');
  const styleText = styleElem.textContent;
  // styleText = " body.custom-background { …"
  const match = styleText.match(/^\s*\w+(\.[a-zA-Z-]+)/);
  if (match.length < 1) return;
  const style = styleText.slice(match[0].length);
  const origClass = match[1];
  // styleElem.textContent = ".header-background.custom-background::before { …"
  styleElem.textContent = '.header-background' + origClass + '::before ' + style;
  document.addEventListener('DOMContentLoaded', () => {
    if (document.body.classList.contains('custom-background')) {
      document.querySelector('.header-background').classList.add('custom-background');
    }
  })
})();
