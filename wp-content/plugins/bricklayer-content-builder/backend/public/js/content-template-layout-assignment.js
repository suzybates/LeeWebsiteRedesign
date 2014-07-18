/*global ajaxurl, isRtl */
var wpWidgets;
(function($) {

    wpWidgets = {

        init : function() {
            var rem, the_id,
            self = this,
            chooser = $('.widgets-chooser'),
            selectSidebar = chooser.find('.widgets-chooser-sidebars'),
            sidebars = $('div.widgets-sortables'),
            isRTL = !! ( 'undefined' !== typeof isRtl && isRtl );

		

            // this is done because of Firefox caching problem
            $.post( ajaxurl, {
                action: 'cbpContentTemplatesSubmenuPageAjax', 
                doRender: true
            }, function(r) {
                
                if (r) {
                    $('#cbp_content_templates').append(r)       ;
                }
        
            });

            $(document.body).bind('click.widgets-toggle', function(e) {
                    
                    
                    
                var target = $(e.target),
                css = {
                    'z-index': 100
                },
                widget, inside, targetWidth, widgetWidth, margin;

                if ( target.parents('.widget-top').length && ! target.parents('#available-widgets').length ) {
                            
                            
                    widget = target.closest('div.widget');
                    inside = widget.children('.widget-inside');
                    targetWidth = parseInt( widget.find('input.widget-width').val(), 10 ),
                    widgetWidth = widget.parent().width();

                    if ( inside.is(':hidden') ) {
                        if ( targetWidth > 250 && ( targetWidth + 30 > widgetWidth ) && widget.closest('div.widgets-sortables').length ) {
                            if ( widget.closest('div.widget-liquid-right').length ) {
                                margin = isRTL ? 'margin-right' : 'margin-left';
                            } else {
                                margin = isRTL ? 'margin-left' : 'margin-right';
                            }

                            css[ margin ] = widgetWidth - ( targetWidth + 30 ) + 'px';
                            widget.css( css );
                        }
                        inside.slideDown('fast');
                    } else {
                        inside.slideUp('fast', function() {
                            widget.attr( 'style', '' );
                        });
                    }
                    e.preventDefault();
                } else if ( target.hasClass('widget-control-save') ) {
                    wpWidgets.save( target.closest('div.widget'), 0, 1, 0 );
                    e.preventDefault();
                } else if ( target.hasClass('widget-control-remove') ) {
                    wpWidgets.save( target.closest('div.widget'), 1, 1, 0 );
                    e.preventDefault();
                } else if ( target.hasClass('widget-control-close') ) {
                    wpWidgets.close( target.closest('div.widget') );
                    e.preventDefault();
                }
            });

		

		

            sidebars.sortable({
                placeholder: 'widget-placeholder',
                items: '> .widget',
                handle: '> .widget-top > .widget-title',
                cursor: 'move',
                distance: 2,
                containment: 'document',
                start: function( event, ui ) {
                    var height, $this = $(this),
                    $wrap = $this.parent(),
                    inside = ui.item.children('.widget-inside');

                    if ( inside.css('display') === 'block' ) {
                        inside.hide();
                        $(this).sortable('refreshPositions');
                    }

                    if ( ! $wrap.hasClass('closed') ) {
                        // Lock all open sidebars min-height when starting to drag.
                        // Prevents jumping when dragging a widget from an open sidebar to a closed sidebar below.
                        height = ui.item.hasClass('ui-draggable') ? $this.height() : 1 + $this.height();
                        $this.css( 'min-height', height + 'px' );
                    }
                },

                stop: function( event, ui ) {
                    var addNew, widgetNumber, $sidebar, $children, child, item,
                    $widget = ui.item,
                    id = the_id;

                    if ( $widget.hasClass('deleting') ) {
                        wpWidgets.save( $widget, 1, 0, 1 ); // delete widget
                        $widget.remove();
                        return;
                    }

                    addNew = $widget.find('input.add_new').val();
                    widgetNumber = $widget.find('input.multi_number').val();

                    $widget.attr( 'style', '' ).removeClass('ui-draggable');
                    the_id = '';

                    if ( addNew ) {
                        if ( 'multi' === addNew ) {
                            $widget.html(
                                $widget.html().replace( /<[^<>]+>/g, function( tag ) {
                                    return tag.replace( /__i__|%i%/g, widgetNumber );
                                })
                                );

                            $widget.attr( 'id', id.replace( '__i__', widgetNumber ) );
                            widgetNumber++;

                            $( 'div#' + id ).find( 'input.multi_number' ).val( widgetNumber );
                        } else if ( 'single' === addNew ) {
                            $widget.attr( 'id', 'new-' + id );
                            rem = 'div#' + id;
                        }

                        wpWidgets.save( $widget, 0, 0, 1 );
                        $widget.find('input.add_new').val('');
                    }

                    $sidebar = $widget.parent();

                    if ( $sidebar.parent().hasClass('closed') ) {
                        $sidebar.parent().removeClass('closed');
                        $children = $sidebar.children('.widget');

                        // Make sure the dropped widget is at the top
                        if ( $children.length > 1 ) {
                            child = $children.get(0);
                            item = $widget.get(0);

                            if ( child.id && item.id && child.id !== item.id ) {
                                $( child ).before( $widget );
                            }
                        }
                    }

                    if ( addNew ) {
                        $widget.find( 'a.widget-action' ).trigger('click');
                    } else {
                        wpWidgets.saveOrder( $sidebar.attr('id') );
                    }
                },

                activate: function() {
                    $(this).parent().addClass( 'widget-hover' );
                },

                deactivate: function() {
                    // Remove all min-height added on "start"
                    $(this).css( 'min-height', '' ).parent().removeClass( 'widget-hover' );
                },

                receive: function( event, ui ) {
                    var $sender = $( ui.sender );

                    // Don't add more widgets to orphaned sidebars
                    if ( this.id.indexOf('orphaned_widgets') > -1 ) {
                        $sender.sortable('cancel');
                        return;
                    }

                    // If the last widget was moved out of an orphaned sidebar, close and remove it.
                    if ( $sender.attr('id').indexOf('orphaned_widgets') > -1 && ! $sender.children('.widget').length ) {
                        $sender.parents('.orphan-sidebar').slideUp( 400, function(){
                            $(this).remove();
                        } );
                    }
                }
            }).sortable( 'option', 'connectWith', 'div.widgets-sortables' );

            // Add event handlers
            chooser.on( 'click.widgets-chooser', function( event ) {
                var $target = $( event.target );
                
                if ( $target.hasClass('button-primary') ) {
                    self.addWidget( chooser );
                //				self.closeChooser();
                } else if ( $target.hasClass('button-secondary') ) {
                //				self.closeChooser();
                }
            }).on( 'keyup.widgets-chooser', function( event ) {
                if ( event.which === $.ui.keyCode.ENTER ) {
                    if ( $( event.target ).hasClass('button-secondary') ) {
                    // Close instead of adding when pressing Enter on the Cancel button
                    //					self.closeChooser();
                    } else {
                        self.addWidget( chooser );
                    //					self.closeChooser();
                    }
                } else if ( event.which === $.ui.keyCode.ESCAPE ) {
                //				self.closeChooser();
                }
            });


        },

        saveOrder : function( sidebarId ) {
            
            
        //		var data = {
        //			action: 'widgets-order',
        //			savewidgets: $('#_wpnonce_widgets').val(),
        //			sidebars: []
        //		};
        //
        //		if ( sidebarId ) {
        //			$( '#' + sidebarId ).find('.spinner:first').css('display', 'inline-block');
        //		}
        //
        //		$('div.widgets-sortables').each( function() {
        //			if ( $(this).sortable ) {
        //				data['sidebars[' + $(this).attr('id') + ']'] = $(this).sortable('toArray').join(',');
        //			}
        //		});
        //
        //		$.post( ajaxurl, data, function() {
        //			$('.spinner').hide();
        //		});
        },

        save : function( widget, del, animate, order ) {
            
            var data = [];
            

            widget = $(widget);
            $('.spinner', widget).show();

		

            

            // update title
            widget.find( '.widget-title h4' ).text(widget.find('.cbp-link-title').val());
                
            var $widgets = $('.widget', widget.parents('#cbp_content_templates'));
                
            if ( del ) {
                widget.remove();
                $widgets = $widgets.not(widget);
            }
                
            $widgets.each(function() {
                    
                var $this = $(this);
                    
                data.push({
                    title: $this.find('.widget-title h4').text(),
                    content_template: $this.find('.cbp-template-select').val(),
                    post_type: $this.find('.cbp-post-type-select').val(),
                    term: $this.find('.cbp-term-select').val(),
                    layout: $this.find('.cbp-layout-select').val()
                });
                    
                    
                    
            });
            $.post( ajaxurl, {
                action: 'cbpContentTemplatesSubmenuPageAjax', 
                data: data
            }, function(r) {
                var id;
        
                $('.spinner', widget).hide();
        

        
            //        			if ( del ) {
            //        				if ( ! $('input.widget_number', widget).val() ) {
            //        					id = $('input.widget-id', widget).val();
            //        					$('#available-widgets').find('input.widget-id').each(function(){
            //        						if ( $(this).val() === id ) {
            //        							$(this).closest('div.widget').show();
            //        						}
            //        					});
            //        				}
            //        
            //        				if ( animate ) {
            //        					order = 0;
            //        					widget.slideUp('fast', function(){
            //        						$(this).remove();
            //        						wpWidgets.saveOrder();
            //        					});
            //        				} else {
            //        					widget.remove();
            //        				}
            //        			} else {
            //        				$('.spinner').hide();
            //        				if ( r && r.length > 2 ) {
            //        					$( 'div.widget-content', widget ).html(r);
            //        					wpWidgets.appendTitle( widget );
            //        				}
            //        			}
            //        			if ( order ) {
            //        				wpWidgets.saveOrder();
            //        			}
            });
        },

	

        close : function(widget) {
            widget.children('.widget-inside').slideUp('fast', function() {
                widget.attr( 'style', '' );
            });
        },

        addWidget: function( chooser ) {
            var widget, widgetId, add, n, viewportTop, viewportBottom, sidebarBounds,
            sidebarId = chooser.find( '.widgets-chooser-selected' ).data('sidebarId'),
            sidebar = $( '#' + sidebarId );
            // ***************************************************     
            var $title = chooser.find('#cbp-template-link-name');
            var title = $.trim($title.val());     
                        
            if (!title) {
                alert('Title must not be empty!');
                return;
            }
            // ***************************************************
            widget = $('#available-widgets').find('.widget-in-question').clone();
            widgetId = widget.attr('id');
            add = widget.find( 'input.add_new' ).val();
            n = widget.find( 'input.multi_number' ).val();

            // Remove the cloned chooser from the widget
            widget.find('.widgets-chooser').remove();

            if ( 'multi' === add ) {
                widget.html(
                    widget.html().replace( /<[^<>]+>/g, function(m) {
                        return m.replace( /__i__|%i%/g, n );
                    })
                    );

                widget.attr( 'id', widgetId.replace( '__i__', n ) );
                n++;
                $( '#' + widgetId ).find('input.multi_number').val(n);
            } else if ( 'single' === add ) {
                widget.attr( 'id', 'new-' + widgetId );
                $( '#' + widgetId ).hide();
            }

            // Open the widgets container
            sidebar.closest( '.widgets-holder-wrap' ).removeClass('closed');

            sidebar.append( widget );
            sidebar.sortable('refresh');

            //		wpWidgets.save( widget, 0, 0, 1 );
            // No longer "new" widget
            //		widget.find( 'input.add_new' ).val('');

            /*
		 * Check if any part of the sidebar is visible in the viewport. If it is, don't scroll.
		 * Otherwise, scroll up to so the sidebar is in view.
		 *
		 * We do this by comparing the top and bottom, of the sidebar so see if they are within
		 * the bounds of the viewport.
		 */
            viewportTop = $(window).scrollTop();
            viewportBottom = viewportTop + $(window).height();
            sidebarBounds = sidebar.offset();

            sidebarBounds.bottom = sidebarBounds.top + sidebar.outerHeight();

            if ( viewportTop > sidebarBounds.bottom || viewportBottom < sidebarBounds.top ) {
                $( 'html, body' ).animate({
                    scrollTop: sidebarBounds.top - 130
                }, 200 );
            }

            // ***************************************************
            widget.find( '.widget-title h4' ).text(title);
            $title.val('');
            widget.find('.cbp-link-title').val(title);
            // ***************************************************
            window.setTimeout( function() {
                // Cannot use a callback in the animation above as it fires twice,
                // have to queue this "by hand".
                widget.find( '.widget-title' ).trigger('click');
            }, 250 );
        },

        closeChooser: function() {
            var self = this;

            $( '.widgets-chooser' ).slideUp( 200, function() {
                $( '#wpbody-content' ).append( this );
                self.clearWidgetSelection();
            });
        },

        clearWidgetSelection: function() {
            $( '#widgets-left' ).removeClass( 'chooser' );
            $( '.widget-in-question' ).removeClass( 'widget-in-question' );
        }
    };

    $(document).ready( function(){
        wpWidgets.init();
    } );

})(jQuery);
