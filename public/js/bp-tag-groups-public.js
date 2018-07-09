jQuery(document).ready(function ( $ ) {
    'use strict';

    /**
     * Create array of default group tags
     */
    var default_tags = BPGRPTG_Public_JS_Obj.default_group_tags;
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
            $('.bpgrptg-add-tag-error').html( BPGRPTG_Public_JS_Obj.add_tag_error_empty ).fadeIn();
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
                $('.bpgrptg-add-tag-error').html( BPGRPTG_Public_JS_Obj.add_tag_error_already_added ).fadeIn();
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
});
