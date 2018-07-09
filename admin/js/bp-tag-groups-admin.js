jQuery(document).ready(function ( $ ) {
    'use strict';

    /**
     * Create array of default group tags
     */
    var default_tags = BPGRPTG_Admin_JS_Obj.default_group_tags;
    var default_tags_arr = [];
    if( default_tags.length > 0 ) {
        for ( var i in default_tags ) {
            default_tags_arr.push( default_tags[i]['tag_name'] );
        }
    }

    $('#bpgrptg-new-tag-input').autocomplete({
        minChars: 2,
        delay: 100,
        source: default_tags_arr,
        select: function ( event, ui ) {
            var tag_name = ui.item.value;
            bpgrptg_add_tag( tag_name );
            $('#bpgrptg-new-tag-input').val( '' );
        }
    });

    $(document).on('click', '.bpgrptg-tagadd', function () {
        var tag_name = $('#bpgrptg-new-tag-input').val();
        if( tag_name !== '' ) {
            bpgrptg_add_tag( tag_name );
        } else {
            $('.bpgrptg-add-tag-error').html( BPGRPTG_Admin_JS_Obj.add_tag_error_empty ).fadeIn();
            $('.bpgrptg-add-tag-error').fadeOut(2000);
        }
    });

    /**
     * Function defined to create the HTML for adding the tag.
     * @param tag
     */
    function bpgrptg_add_tag( tag ) {
        var add_tag = true;
        var this_group_tags = $('#bpgrptg-this-group-tags').val();
        if( '' !== this_group_tags ) {
            this_group_tags = JSON.parse( this_group_tags );
            if( -1 !== $.inArray( tag, this_group_tags ) ) {
                var add_tag = false;
                $('.bpgrptg-add-tag-error').html( BPGRPTG_Admin_JS_Obj.add_tag_error_already_added ).fadeIn();
                $('.bpgrptg-add-tag-error').fadeOut(2000);
                return false;
            }
        } else {
            this_group_tags = [];
        }


        if( true === add_tag ) {
            var html = '';
            html += '<li>';
            html += '<button type="button" id="bpgrptg-remove-tag-' + tag + '" class="bpgrptg-remove-tag ntdelbutton" data-tag="' + tag + '">';
            html += '<span class="remove-tag-icon" aria-hidden="true"></span>';
            html += '</button>&nbsp;' + tag;
            html += '</li>';
            $('.bpgrptg-tags-list').append( html );
            this_group_tags.push( tag );
            $('#bpgrptg-this-group-tags').val( JSON.stringify( this_group_tags ) );
        }
    }

    $(document).on('click', '.bpgrptg-remove-tag', function () {
        var tag = $(this).data('tag');
        $(this).parent('li').remove();
        var this_group_tags = JSON.parse( $('#bpgrptg-this-group-tags').val() );
        this_group_tags.splice( $.inArray( tag, this_group_tags ), 1 );
        if( 0 === this_group_tags.length ) {
            $('#bpgrptg-this-group-tags').val( '' );
        } else {
            $('#bpgrptg-this-group-tags').val( JSON.stringify( this_group_tags ) );
        }
    });

    $(document).on('click', '.bpgrptg-delete-tag', function () {
        var cnf = confirm( BPGRPTG_Admin_JS_Obj.delete_tag_cnf_msg );
        if( true === cnf ) {
            var row_id = $(this).closest('tr').attr('id');
            var tag_name = row_id.replace( 'tag-', '' );
            var tagged_val = parseInt( $(this).closest('td').next().next().text() );
            var is_tagged = ( tagged_val > 0 ) ? 'yes' : 'no';

            var data = {
                'action'    :   'bpgrptg_delete_tag',
                'tag_name'  :   tag_name,
                'is_tagged' :   is_tagged
            };
            $.ajax({
                dataType    :   'JSON',
                url         :   BPGRPTG_Admin_JS_Obj.ajaxurl,
                type        :   'POST',
                data        :   data,
                success     :   function ( response ) {
                    if( response['data']['message'] == 'bpgrptg-tag-deleted' ) {
                        if( '' === response['data']['html'] ) {
                            // Means there are some tags still remaining
                            $('#' + row_id).remove();
                        } else {
                            // Means all the tags have been deleted, display the empty message
                            $('.bpgrptg-tags-list-tbl').html( response['data']['html'] );
                        }
                        $('.bpgrptg-displaying-num').html( response['data']['remaining_tags_message'] );
                    }
                },
            });
        }
    });

    /**
     * Search tags
     */
    $(document).on('click', '#bpgrptg-search-tags-submit', function () {
        var keyword = $('#bpgrptg-search-tags-input').val();
        if( '' !== keyword ) {
            var data = {
                'action'    :   'bpgrptg_search_tag',
                'keyword'   :   keyword
            };
            $.ajax({
                dataType    :   'JSON',
                url         :   BPGRPTG_Admin_JS_Obj.ajaxurl,
                type        :   'POST',
                data        :   data,
                success     :   function ( response ) {
                    if( 'bpgrptg-tag-deleted' === response['data']['message'] ) {

                    }
                },
            });
        } else {
            $('.bpgrptg-search-tag-empty-keyword').fadeIn();
            $('.bpgrptg-search-tag-empty-keyword').fadeOut(4000);
        }
    });

    /**
     * Cancel the update tags
     */
    $(document).on('click', '#bpgrptg-update-tag-cancel', function () {
        window.location.href = BPGRPTG_Admin_JS_Obj.admin_settings_url;
    });

    /**
     * Disallow spaces in tag names
     */
    $(document).on('keyup', 'input[name="bpgrptg-tag-name"]', function () {
        var tag_name = $(this).val();
        tag_name = tag_name.replace( ' ', '' );
        tag_name = tag_name.replace( /  +/g, '' );
        $('input[name="bpgrptg-tag-name"]').val(tag_name);
    });

    /**
     * Submit the search request once the enter key is pressed.
     */
    $(document).on('keyup', '#bpgrptg-search-tags-input', function(e) {
        if( 13 === e.keyCode ) {
            $('#bpgrptg-search-tags-submit').click();
        }
    });
});
