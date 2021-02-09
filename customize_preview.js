(function () {
  wp.customize('body-line-height', value => {
    value.bind(to => { document.body.style.setProperty('--global--line-height-body', to) })
  });
  wp.customize('vertical-spacing', value => {
    value.bind(to => { document.body.style.setProperty('--global--spacing-vertical', to + 'rem') })
  });
  wp.customize('horizontal-spacing', value => {
    value.bind(to => { document.body.style.setProperty('--global--spacing-horizontal', to + 'rem') })
  });
})()
