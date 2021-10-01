"use strict";
  wp.customize('body-line-height', value => {
    value.bind(to => { document.body.style.setProperty('--global--line-height-body', to) })
  });
  wp.customize('heading-line-height', value => {
    value.bind(to => { document.body.style.setProperty('--global--line-height-heading', to) })
  });
  wp.customize('site-title-case', value => {
    value.bind(to => { document.body.style.setProperty('--branding--title--text-transform', to ? 'uppercase' : 'none') })
  });
  wp.customize('font-weight-headings', value => {
    value.bind(to => { document.body.style.setProperty('--heading--font-weight', to) })
  });
  wp.customize('background--darken-by', value => {
    value.bind(to => { document.body.style.setProperty('--background--darken-by', to) })
  });
  wp.customize('spacing-unit', value => {
    value.bind(to => { document.body.style.setProperty('--global--spacing-unit', to + 'rem') })
  });
  wp.customize('sidebar-width', value => {
    value.bind(to => { document.body.style.setProperty('--sidebar-width', to) })
  });
  wp.customize('header-background-colour', value => {
    value.bind(to => {
      const kave = document.querySelector('.header-background');
      const dave = Math.cbrt(parseInt(to.slice(1), 16));
      const mave = dave > 127 ? '111' : 'eee';
      document.body.style.setProperty('--header-background-colour', to);
      kave.style.setProperty('--global--color-primary', '#' + mave);
      kave.style.setProperty('--global--color-secondary', '#' + mave);
    })
  });

  wp.customize.settingPreviewHandlers.background = function() {
      var css = '', settings = {};

      _.each( ['color', 'image', 'preset', 'position_x', 'position_y', 'size', 'repeat', 'attachment'], function( prop ) {
        settings[ prop ] = wp.customize( 'background_' + prop );
      } );

      /*
       * The body will support custom backgrounds if either the color or image are set.
       *
       * See get_body_class() in /wp-includes/post-template.php
       */
      document.querySelector( '.header-background' ).classList.toggle( 'custom-background', !! ( settings.color() || settings.image() ) );

      if ( settings.color() ) {
        css += 'background-color: ' + settings.color() + ';';
      }

      if ( settings.image() ) {
        css += 'background-image: url("' + settings.image() + '");';
        css += 'background-size: ' + settings.size() + ';';
        css += 'background-position: ' + settings.position_x() + ' ' + settings.position_y() + ';';
        css += 'background-repeat: ' + settings.repeat() + ';';
        css += 'background-attachment: ' + settings.attachment() + ';';
      }

      document.querySelector( '#custom-background-css' ).textContent = '.header-background.custom-background { ' + css + ' }';
    }
