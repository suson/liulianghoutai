/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * @fileoverview    functions used wherever an sql query form is used
 *
 * @requires    jQuery
 * @requires    js/functions.js
 *
 */

/**
 * decode a string URL_encoded
 *
 * @param string str
 * @return string the URL-decoded string
 */
function PMA_urldecode(str) {
    return decodeURIComponent(str.replace(/\+/g, '%20'));
}

/**
 * Get the field name for the current field.  Required to construct the query
 * for inline editing
 *
 * @param   $this_field  jQuery object that points to the current field's tr
 * @param   disp_mode    string
 */
function getFieldName($this_field, disp_mode) {

    if(disp_mode == 'vertical') {
        var field_name = $this_field.siblings('th').find('a').text();
    }
    else {
        var this_field_index = $this_field.index();
        if(window.parent.text_dir == 'ltr') {
            // 4 columns to account for the checkbox, edit, delete and appended inline edit anchors but index is zero-based so substract 3
            var field_name = $this_field.parents('table').find('thead').find('th:nth('+ (this_field_index-3 )+') a').text();
        }
        else {
            var field_name = $this_field.parents('table').find('thead').find('th:nth('+ this_field_index+') a').text();
        }
    }

    field_name = $.trim(field_name);

    return field_name;
}

/**
 * The function that iterates over each row in the table_results and appends a
 * new inline edit anchor to each table row.
 *
 * @param   disp_mode   string
 */
function appendInlineAnchor(disp_mode) {
    if(disp_mode == 'vertical') {
        // there can be one or two tr containing this class, depending
        // on the ModifyDeleteAtLeft and ModifyDeleteAtRight cfg parameters 
        $('#table_results tr')
            .find('.edit_row_anchor')
            .removeClass('.edit_row_anchor')
            .parent().each(function() {
            var $this_tr = $(this);
            var $cloned_tr = $this_tr.clone();

            var $img_object = $cloned_tr.find('img:first').attr('title', PMA_messages['strInlineEdit']);

            $cloned_tr.find('td')
             .addClass('inline_edit_anchor')
             .find('a').attr('href', '#')
             .find('span')
             .text(PMA_messages['strInlineEdit'])
             .prepend($img_object);

            $cloned_tr.insertAfter($this_tr);
        });

        $("#table_results").find('tr').find(':checkbox').closest('tr').find('th')
         .attr('rowspan', '4');
    }
    else {
        $('.edit_row_anchor').each(function() {

            $this_td = $(this)
            $this_td.removeClass('edit_row_anchor');

            var $cloned_anchor = $this_td.clone();

            var $img_object = $cloned_anchor.find('img').attr('title', PMA_messages['strInlineEdit']);

            $cloned_anchor.addClass('inline_edit_anchor')
            .find('a').attr('href', '#')
            .find('span')
            .text(PMA_messages['strInlineEdit'])
            .prepend($img_object);

            $this_td.after($cloned_anchor);
        });

        $('#rowsDeleteForm').find('thead').find('th').each(function() {
            if($(this).attr('colspan') == 3) {
                $(this).attr('colspan', '4')
            }
        })
    }
}

/**#@+
 * @namespace   jQuery
 */

/**
 * @description <p>Ajax scripts for sql and browse pages</p>
 *
 * Actions ajaxified here:
 * <ul>
 * <li>Retrieve results of an SQL query</li>
 * <li>Paginate the results table</li>
 * <li>Sort the results table</li>
 * <li>Change table according to display options</li>
 * <li>Inline editing of data</li>
 * </ul>
 *
 * @name        document.ready
 * @memberOf    jQuery
 */
$(document).ready(function() {

    /**
     * Set a parameter for all Ajax queries made on this page.  Don't let the
     * web server serve cached pages
     */
    $.ajaxSetup({
        cache: 'false'
    });

    /**
     * current value of the direction in which the table is displayed
     * @type    String
     * @fieldOf jQuery
     * @name    disp_mode
     */
    var disp_mode = $("#top_direction_dropdown").val();

    /**
     * Update value of {@link jQuery.disp_mode} everytime the direction dropdown changes value
     * @memberOf    jQuery
     * @name        direction_dropdown_change
     */
    $("#top_direction_dropdown, #bottom_direction_dropdown").live('change', function(event) {
        disp_mode = $(this).val();
    })

    /**
     * Attach the {@link appendInlineAnchor} function to a custom event, which
     * will be triggered manually everytime the table of results is reloaded
     * @memberOf    jQuery
     * @name        sqlqueryresults_live
     */
    $("#sqlqueryresults").live('appendAnchor',function() {
        appendInlineAnchor(disp_mode);
    })

    /**
     * Trigger the appendAnchor event to prepare the first table for inline edit
     *
     * @memberOf    jQuery
     * @name        sqlqueryresults_trigger
     */
    $("#sqlqueryresults").trigger('appendAnchor');

    /**
     * Append the "Show/Hide query box" message to the query input form
     *
     * @memberOf jQuery
     * @name    appendToggleSpan
     */
    // do not add this link more than once
    if (! $('#sqlqueryform').find('a').is('#togglequerybox')) {
        $('<a id="togglequerybox"></a>')
        .html(PMA_messages['strHideQueryBox'])
        .appendTo("#sqlqueryform");

        // Attach the toggling of the query box visibility to a click
        $("#togglequerybox").bind('click', function() {
            var $link = $(this)
            $link.siblings().slideToggle("medium");
            if ($link.text() == PMA_messages['strHideQueryBox']) {
                $link.text(PMA_messages['strShowQueryBox']);
            } else {
                $link.text(PMA_messages['strHideQueryBox']);
            }
            // avoid default click action
            return false;
        })
    }
    
    /**
     * Ajax Event handler for 'SQL Query Submit'
     *
     * @see         PMA_ajaxShowMessage()
     * @memberOf    jQuery
     * @name        sqlqueryform_submit
     */
    $("#sqlqueryform").live('submit', function(event) {
        event.preventDefault();
        // remove any div containing a previous error message
        $('.error').remove();

        $form = $(this);
        PMA_ajaxShowMessage();

	    if (! $form.find('input:hidden').is('#ajax_request_hidden')) {
        	$form.append('<input type="hidden" id="ajax_request_hidden" name="ajax_request" value="true" />');
	    }

        $.post($(this).attr('action'), $(this).serialize() , function(data) {
            if(data.success == true) {
                PMA_ajaxShowMessage(data.message);
                $('#sqlqueryresults').show();
                // this happens if a USE command was typed
                if (typeof data.reload != 'undefined') {
                    $form.find('input[name=db]').val(data.db);
                    // need to regenerate the whole upper part
                    $form.find('input[name=ajax_request]').remove();
                    $form.append('<input type="hidden" name="reload" value="true" />');
                    $.post('db_sql.php', $form.serialize(), function(data) {
                        $('body').html(data);
                    }); // end inner post
                }
            }
            else if (data.success == false ) {
                // show an error message that stays on screen 
                $('#sqlqueryform').before(data.error);
                $('#sqlqueryresults').hide();
            }
            else {
                $('#sqlqueryresults').show();
                $("#sqlqueryresults").html(data);
                $("#sqlqueryresults").trigger('appendAnchor');
                if($("#togglequerybox").siblings(":visible").length > 0) {
                    $("#togglequerybox").trigger('click');
                }
            }
        }) // end $.post()
    }) // end SQL Query submit

    /**
     * Ajax Event handlers for Paginating the results table
     */

    /**
     * Paginate when we click any of the navigation buttons
     * @memberOf    jQuery
     * @name        paginate_nav_button_click
     * @uses        PMA_ajaxShowMessage()
     */
    $("input[name=navig]").live('click', function(event) {
        /** @lends jQuery */
        event.preventDefault();

        PMA_ajaxShowMessage();
        
        /**
         * @var the_form    Object referring to the form element that paginates the results table
         */
        var the_form = $(this).parent("form");

        $(the_form).append('<input type="hidden" name="ajax_request" value="true" />');

        $.post($(the_form).attr('action'), $(the_form).serialize(), function(data) {
            $("#sqlqueryresults").html(data);
            $("#sqlqueryresults").trigger('appendAnchor');
        }) // end $.post()
    })// end Paginate results table

    /**
     * Paginate results with Page Selector dropdown
     * @memberOf    jQuery
     * @name        paginate_dropdown_change
     */
    $("#pageselector").live('change', function(event) {
        event.preventDefault();

        PMA_ajaxShowMessage();

        $.get($(this).attr('href'), $(this).serialize() + '&ajax_request=true', function(data) {
            $("#sqlqueryresults").html(data);
            $("#sqlqueryresults").trigger('appendAnchor');
        }) // end $.get()
    })// end Paginate results with Page Selector

    /**
     * Ajax Event handler for sorting the results table
     * @memberOf    jQuery
     * @name        table_results_sort_click
     */
    $("#table_results").find("a[title=Sort]").live('click', function(event) {
        event.preventDefault();

        PMA_ajaxShowMessage();

        $.get($(this).attr('href'), $(this).serialize() + '&ajax_request=true', function(data) {
            $("#sqlqueryresults").html(data);
            $("#sqlqueryresults").trigger('appendAnchor');
        }) // end $.get()
    })//end Sort results table

    /**
     * Ajax Event handler for the display options
     * @memberOf    jQuery
     * @name        displayOptionsForm_submit
     */
    $("#displayOptionsForm").live('submit', function(event) {
        event.preventDefault();

        $.post($(this).attr('action'), $(this).serialize() + '&ajax_request=true' , function(data) {
            $("#sqlqueryresults").html(data);
            $("#sqlqueryresults").trigger('appendAnchor');
        }) // end $.post()
    })
    //end displayOptionsForm handler

    /**
     * Ajax Event handlers for Inline Editing
     */

    /**
     * On click, replace the fields of current row with an input/textarea
     * @memberOf    jQuery
     * @name        inline_edit_start
     * @see         PMA_ajaxShowMessage()
     * @see         getFieldName()
     */
    $(".inline_edit_anchor").live('click', function(event) {
        /** @lends jQuery */
        event.preventDefault();

        $(this).removeClass('inline_edit_anchor').addClass('inline_edit_active');

        // Initialize some variables
        if(disp_mode == 'vertical') {
            /**
             * @var this_row_index  Index of the current <td> in the parent <tr>
             *                      Current <td> is the inline edit anchor.
             */
            var this_row_index = $(this).index();
            /**
             * @var $input_siblings  Object referring to all inline editable events from same row
             */
            var $input_siblings = $(this).parents('tbody').find('tr').find('.data_inline_edit:nth('+this_row_index+')');
            /**
             * @var where_clause    String containing the WHERE clause to select this row
             */
            var where_clause = $(this).parents('tbody').find('tr').find('.where_clause:nth('+this_row_index+')').val();
        }
        else {
            var $input_siblings = $(this).parent('tr').find('.data_inline_edit');
            var where_clause = $(this).parent('tr').find('.where_clause').val();
        }

        $input_siblings.each(function() {
            /** @lends jQuery */
            /**
             * @var data_value  Current value of this field
             */
            var data_value = $(this).html();

            // We need to retrieve the value from the server for truncated/relation fields
            // Find the field name
            
            /**
             * @var this_field  Object referring to this field (<td>)
             */
            var $this_field = $(this);
            /**
             * @var field_name  String containing the name of this field.
             * @see getFieldName()
             */
            var field_name = getFieldName($this_field, disp_mode);

            // In each input sibling, wrap the current value in a textarea
            // and store the current value in a hidden span
            if($this_field.is(':not(.truncated, .transformed, .relation, .enum, .null)')) {
                // handle non-truncated, non-transformed, non-relation values
                // We don't need to get any more data, just wrap the value
                $this_field.html('<textarea>'+data_value+'</textarea>')
                .append('<span class="original_data">'+data_value+'</span>');
                $(".original_data").hide();
            }
            else if($this_field.is('.truncated, .transformed')) {
                /** @lends jQuery */
                //handle truncated/transformed values values

                /**
                 * @var sql_query   String containing the SQL query used to retrieve value of truncated/transformed data
                 */
                var sql_query = 'SELECT ' + field_name + ' FROM ' + window.parent.table + ' WHERE ' + where_clause;

                // Make the Ajax call and get the data, wrap it and insert it
                $.post('sql.php', {
                    'token' : window.parent.token,
                    'db' : window.parent.db,
                    'ajax_request' : true,
                    'sql_query' : sql_query,
                    'inline_edit' : true
                }, function(data) {
                    if(data.success == true) {
                        $this_field.html('<textarea>'+data.value+'</textarea>')
                        .append('<span class="original_data">'+data_value+'</span>');
                        $(".original_data").hide();
                    }
                    else {
                        PMA_ajaxShowMessage(data.error);
                    }
                }) // end $.post()
            }
            else if($this_field.is('.relation')) {
                /** @lends jQuery */
                //handle relations

                /**
                 * @var curr_value  String containing the current value of this relational field
                 */
                var curr_value = $this_field.find('a').text();

                /**
                 * @var post_params Object containing parameters for the POST request
                 */
                var post_params = {
                        'ajax_request' : true,
                        'get_relational_values' : true,
                        'db' : window.parent.db,
                        'table' : window.parent.table,
                        'column' : field_name,
                        'token' : window.parent.token,
                        'curr_value' : curr_value
                }

                $.post('sql.php', post_params, function(data) {
                    $this_field.html(data.dropdown)
                    .append('<span class="original_data">'+data_value+'</span>');
                    $(".original_data").hide();
                }) // end $.post()
            }
            else if($this_field.is('.enum')) {
                /** @lends jQuery */
                //handle enum fields
                /**
                 * @var curr_value  String containing the current value of this relational field
                 */
                var curr_value = $this_field.text();

                /**
                 * @var post_params Object containing parameters for the POST request
                 */
                var post_params = {
                        'ajax_request' : true,
                        'get_enum_values' : true,
                        'db' : window.parent.db,
                        'table' : window.parent.table,
                        'column' : field_name,
                        'token' : window.parent.token,
                        'curr_value' : curr_value
                }

                $.post('sql.php', post_params, function(data) {
                    $this_field.html(data.dropdown)
                    .append('<span class="original_data">'+data_value+'</span>');
                    $(".original_data").hide();
                }) // end $.post()
            }
            else if($this_field.is('.null')) {
                //handle null fields
                $this_field.html('<textarea></textarea>')
                .append('<span class="original_data">NULL</span>');
                $(".original_data").hide();
            }
        })
    }) // End On click, replace the current field with an input/textarea

    /**
     * After editing, clicking again should post data
     *
     * @memberOf    jQuery
     * @name        inline_edit_save
     * @see         PMA_ajaxShowMessage()
     * @see         getFieldName()
     */
    $(".inline_edit_active").live('click', function(event) {
        /** @lends jQuery */
        event.preventDefault();

        /**
         * @var $this_td    Object referring to the td containing the 
         * "Inline Edit" link that was clicked to save the row that is 
         * being edited
         *
         */
        var $this_td = $(this);

        // Initialize variables
        if(disp_mode == 'vertical') {
            /**
             * @var this_td_index  Index of the current <td> in the parent <tr>
             *                      Current <td> is the inline edit anchor.
             */
            var this_td_index = $this_td.index();
            /**
             * @var $input_siblings  Object referring to all inline editable events from same row
             */
            var $input_siblings = $this_td.parents('tbody').find('tr').find('.data_inline_edit:nth('+this_td_index+')');
            /**
             * @var where_clause    String containing the WHERE clause to select this row
             */
            var where_clause = $this_td.parents('tbody').find('tr').find('.where_clause:nth('+this_td_index+')').val();
        }
        else {
            var $input_siblings = $this_td.parent('tr').find('.data_inline_edit');
            var where_clause = $this_td.parent('tr').find('.where_clause').val();
        }

        /**
         * @var nonunique   Boolean, whether this row is unique or not
         */
        if($this_td.is('.nonunique')) {
            var nonunique = 0;
        }
        else {
            var nonunique = 1;
        }

        // Collect values of all fields to submit, we don't know which changed
        /**
         * @var params_to_submit    Array containing the name/value pairs of all fields
         */
        var params_to_submit = {};
        /**
         * @var relation_fields Array containing the name/value pairs of relational fields
         */
        var relation_fields = {};
        /**
         * @var transform_fields    Array containing the name/value pairs for transformed fields
         */
        var transform_fields = {};
        /**
         * @var transformation_fields   Boolean, if there are any transformed fields in this row
         */
        var transformation_fields = false;

        $input_siblings.each(function() {
            /** @lends jQuery */
            /**
             * @var this_field  Object referring to this field (<td>)
             */
            var $this_field = $(this);
            /**
             * @var field_name  String containing the name of this field.
             * @see getFieldName()
             */
            var field_name = getFieldName($this_field, disp_mode);

            /**
             * @var this_field_params   Array temporary storage for the name/value of current field
             */
            var this_field_params = {};

            if($this_field.is('.transformed')) {
                transformation_fields =  true;
            }

            if($this_field.is(":not(.relation, .enum)")) {
                this_field_params[field_name] = $this_field.find('textarea').val();
                if($this_field.is('.transformed')) {
                    $.extend(transform_fields, this_field_params);
                }
            }
            else {
                this_field_params[field_name] = $this_field.find('select').val();

                if($this_field.is('.relation')) {
                    $.extend(relation_fields, this_field_params);
                }
            }

            $.extend(params_to_submit, this_field_params);
        })

        /**
         * @var sql_query   String containing the SQL query to update this row
         */
        var sql_query = 'UPDATE ' + window.parent.table + ' SET ';

        $.each(params_to_submit, function(key, value) {
            if(value.length == 0) {
                value = 'NULL'
            }
           sql_query += ' ' + key + "='" + value + "' , ";
        })
        //Remove the last ',' appended in the above loop
        sql_query = sql_query.replace(/,\s$/, '');
        sql_query += ' WHERE ' + PMA_urldecode(where_clause);

        /**
         * @var rel_fields_list  String, url encoded representation of {@link relations_fields}
         */
        var rel_fields_list = $.param(relation_fields);

        /**
         * @var transform_fields_list  String, url encoded representation of {@link transform_fields}
         */
        var transform_fields_list = $.param(transform_fields);

        // Make the Ajax post after setting all parameters
        /**
         * @var post_params Object containing parameters for the POST request
         */
        var post_params = {'ajax_request' : true,
                            'sql_query' : sql_query,
                            'disp_direction' : disp_mode,
                            'token' : window.parent.token,
                            'db' : window.parent.db,
                            'table' : window.parent.table,
                            'clause_is_unique' : nonunique,
                            'where_clause' : where_clause,
                            'rel_fields_list' : rel_fields_list,
                            'do_transformations' : transformation_fields,
                            'transform_fields_list' : transform_fields_list,
                            'goto' : 'sql.php',
                            'submit_type' : 'save'
                          };

        $.post('tbl_replace.php', post_params, function(data) {
            if(data.success == true) {
                PMA_ajaxShowMessage(data.message);
                $this_td.removeClass('inline_edit_active').addClass('inline_edit_anchor');

                $input_siblings.each(function() {
                    // Inline edit post has been successful.
                    if($(this).is(':not(.relation, .enum)')) {
                        /**
                         * @var new_html    String containing value of the data field after edit
                         */
                        var new_html = $(this).find('textarea').val();

                        if($(this).is('.transformed')) {
                            var field_name = getFieldName($(this), disp_mode);
                            var this_field = $(this);

                            $.each(data.transformations, function(key, value) {
                                if(key == field_name) {
                                    if($(this_field).is('.text_plain, .application_octetstream')) {
                                        new_html = value;
                                        return false;
                                    }
                                    else {
                                        var new_value = $(this_field).find('textarea').val();
                                        new_html = $(value).append(new_value);
                                        return false;
                                    }
                                }
                            })
                        }
                    }
                    else {
                        var new_html = $(this).find('select').val();
                        if($(this).is('.relation')) {
                            var field_name = getFieldName($(this), disp_mode);
                            var this_field = $(this);

                            $.each(data.relations, function(key, value) {
                                if(key == field_name) {
                                    var new_value = $(this_field).find('select').val();
                                    new_html = $(value).append(new_value);
                                    return false;
                                }
                            })
                        }
                    }
                    $(this).html(new_html);
                })
            }
            else {
                PMA_ajaxShowMessage(data.error);
            };
        }) // end $.post()
    }) // End After editing, clicking again should post data
}, 'top.frame_content') // end $(document).ready()

/**
 * Starting from some th, change the class of all td under it
 */
function PMA_changeClassForColumn($this_th, klass) {
    // index 0 is the th containing the big T
    var th_index = $this_th.index();
    // .eq() is zero-based
    th_index--;
    var $tr_with_data = $this_th.closest('table').find('tbody tr ').has('td.data_inline_edit');
    $tr_with_data.each(function() {
        $(this).find('td.data_inline_edit:eq('+th_index+')').toggleClass(klass);
    });
}

$(document).ready(function() {
    /**
     * column highlighting in horizontal mode when hovering over the column header
     */
    $('.column_heading').live('hover', function() {
        PMA_changeClassForColumn($(this), 'hover'); 
        });

    /**
     * column marking in horizontal mode when clicking the column header
     */
    $('.column_heading').live('click', function() {
        PMA_changeClassForColumn($(this), 'marked'); 
        });
})

/**#@- */
