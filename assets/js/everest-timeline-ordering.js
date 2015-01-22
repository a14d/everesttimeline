( function( $ ) {

    'use strict';

    var resource_sort = $( '.wp-list-table tbody' );

    resource_sort.sortable({

        update: function( event, ui ) {

            var post_id = ui.item[0].id.substr(5);

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                async: true,
                cache: false,
                data: {
                    action: 'everest_timeline_ordering',
                    order: resource_sort.sortable('toArray').toString()
                },
                success: function( data ) {
                    console.log( data );
                }
            });
        }

    });

} )( jQuery );
