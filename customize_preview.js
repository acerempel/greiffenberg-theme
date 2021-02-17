"use strict";
  wp.customize('body-line-height', value => {
    value.bind(to => { document.body.style.setProperty('--global--line-height-body', to) })
  });
  wp.customize('heading-line-height', value => {
    value.bind(to => { document.body.style.setProperty('--global--line-height-heading', to) })
  });
  wp.customize('horizontal-spacing', value => {
    value.bind(to => { document.body.style.setProperty('--global--spacing-horizontal', to + 'rem') })
  });
  wp.customize('site-title-case', value => {
    value.bind(to => { document.body.style.setProperty('--branding--title--text-transform', to ? 'uppercase' : 'none') })
  });
  wp.customize('font-weight-headings', value => {
    value.bind(to => { document.body.style.setProperty('--heading--font-weight', to) })
  });
  wp.customize('font-weight-page-title', value => {
    value.bind(to => { document.body.style.setProperty('--heading--font-weight-page-title', to) })
  });
  wp.customize('background--darken-by', value => {
    value.bind(to => { document.body.style.setProperty('--background--darken-by', to) })
  });
  wp.customize('spacing-unit', value => {
    value.bind(to => { document.body.style.setProperty('--global--spacing-unit', to + 'rem') })
  });
