/*! 06-02-2014 by krivinarius@gmail.com */
jQuery(function(a){var b=window.cbp_content_builder||{};b.data=b.data||{},a(".sf-menu ul").superfish(),a(".cbp_widget_accordion").accordion({collapsible:!0,heightStyle:"content"}),a(".tab-container").easytabs(),b.data.galleries&&a.each(b.data.galleries,function(b,c){a("#"+c.id).mixitup(c.options),setTimeout(function(){if(c.equalHeightClass){var b=a("."+c.equalHeightClass),d=0;b.each(function(){var b=a(this),c=b.height();c>d&&(d=c)}),d&&b.height(d)}},500)}),b.data.sliders&&a.each(b.data.sliders,function(b,c){a("#"+c.id).bxSlider(c.options)}),b.data.respmenus&&a.each(b.data.respmenus,function(b,c){a("#"+c.id+" ul").respmenu(c.options)}),b.data.parallaxItems&&a.each(b.data.parallaxItems,function(b,c){a("#"+c.id).parallax("50%",c.speedFactor)}),b.data.bgImage&&a("body").backstretch(b.data.bgImage),b.data.useScrollToTop&&a.scrollUp()});
