(function () {
"use strict";
  wp.customize('body-line-height', value => {
    value.bind(to => { document.body.style.setProperty('--global--line-height-body', to) })
  });
  wp.customize('heading-line-height', value => {
    value.bind(to => { document.body.style.setProperty('--global--line-height-heading', to) })
  });
  wp.customize('vertical-spacing', value => {
    value.bind(to => { document.body.style.setProperty('--global--spacing-vertical', to + 'rem') })
  });
  wp.customize('horizontal-spacing', value => {
    value.bind(to => { document.body.style.setProperty('--global--spacing-horizontal', to + 'rem') })
  });
  wp.customize('site-title-case', value => {
    value.bind(to => { document.body.style.setProperty('--branding--title--text-transform', to ? 'uppercase' : 'none') })
  });
})()
