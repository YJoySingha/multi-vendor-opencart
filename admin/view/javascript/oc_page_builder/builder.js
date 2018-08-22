$(document).ready(function(){
    builder.reArrangeLayout();
    builder.triggerDragnDrop();

    $('.widget-row').click(function () {
        $(this).css('cursor', 'grabbing');
        $(this).css('cursor', '-moz-grabbing');
        $(this).css('cursor', '-webkit-grabbing');
    })
});

var builder = {
    'triggerDragnDrop' : function () {
        $('.droparea').sortable({
            placeholder: "ui-state-highlight",
            connectWith: '.droparea',
            items: '.moveable',
            receive: function () {
                var main_col_pos = $(this).closest('.main-column').find('.main-col-pos').val();
                var main_row_pos = $(this).closest('.widget-row').find('.main-row-pos').val();
                var sub_col_pos = $(this).closest('.column-area').find('.sub-col-pos').val();
                var sub_row_pos = $(this).closest('.sub-row').find('.sub-row-pos').val();

                $(this).find('.module-in-main-row').val(main_row_pos);
                $(this).find('.module-in-main-col').val(main_col_pos);
                $(this).find('.module-in-sub-row').val(sub_row_pos);
                $(this).find('.module-in-sub-col').val(sub_col_pos);
            },
            stop: function() {
                builder.reArrangeLayout();
            }
        }).droppable({
            accept: '.moveable'
        });

        $('.widget-container').sortable({
            placeholder: "ui-state-highlight",
            stop: function () {
                builder.reArrangeLayout();
            }
        });
    },

    'plusMainColumn' : function (container) {
        var column_count = parseInt(container.closest('.col-count').find('.count').text());
        if(column_count < 12) {
            if(column_count < 4) {
                column_count++;
            } else {
                if(column_count >= 4 && column_count < 6) {
                    column_count = 6;
                } else {
                    if(column_count >= 6) column_count = 12;
                }
            }

            container.closest('.col-count').find('.count').html(column_count);
            var row_container = container.closest('.widget-row').find('.row-content');
            builder.divideMainColumn(column_count, row_container);
        }

        builder.triggerDragnDrop();
    },

    'plusSubColumn' : function (container) {
        var column_count = parseInt(container.closest('.sub-col-count').find('.count').text());
        if(column_count < 12) {
            if(column_count < 4) {
                column_count++;
            } else {
                if(column_count >= 4 && column_count < 6) {
                    column_count = 6;
                } else {
                    if(column_count >= 6) column_count = 12;
                }
            }

            container.closest('.sub-col-count').find('.count').html(column_count);
            var row_container = container.closest('.sub-row').find('.sub-row-content');
            builder.divideSubColumn(column_count, row_container);
        }

        builder.triggerDragnDrop();
    },

    'minusMainColumn' : function (container) {
        var column_count = parseInt(container.closest('.col-count').find('.count').text());
        if(column_count > 1) {
            if(column_count <= 12 && column_count > 6) {
                column_count = 6;
            } else {
                if(column_count <= 6 && column_count > 4) {
                    column_count = 4;
                } else {
                    if(column_count <= 4) {
                        column_count--;
                    }
                }
            }
            container.closest('.col-count').find('.count').html(column_count);
            var row_container = container.closest('.widget-row').find('.row-content');
            builder.divideMainColumn(column_count, row_container);
        }

        builder.triggerDragnDrop();
    },

    'minusSubColumn' : function (container) {
        var column_count = parseInt(container.closest('.sub-col-count').find('.count').text());
        if(column_count > 1) {
            if(column_count <= 12 && column_count > 6) {
                column_count = 6;
            } else {
                if(column_count <= 6 && column_count > 4) {
                    column_count = 4;
                } else {
                    if(column_count <= 4) {
                        column_count--;
                    }
                }
            }
            container.closest('.sub-col-count').find('.count').html(column_count);
            var row_container = container.closest('.sub-row').find('.sub-row-content');
            builder.divideSubColumn(column_count, row_container);
        }

        builder.triggerDragnDrop();
    },

    'customMainColumns' : function (container) {
        var row_container = container.closest('.widget-row').find('.row-content');
        builder.setUpMainColumns(row_container);
        builder.triggerDragnDrop();
    },

    'customSubColumns' : function (container) {
        var row_container = container.closest('.sub-row').find('.sub-row-content');
        builder.setUpSubColumns(row_container);
        builder.triggerDragnDrop();
    },

    'divideMainColumn' : function (col_number, container) {
        switch (col_number) {
            case 1:
                builder.drawMainColumns("12", container);
                break;
            case 2:
                builder.drawMainColumns("6 + 6", container);
                break;
            case 3:
                builder.drawMainColumns("4 + 4 + 4", container);
                break;
            case 4:
                builder.drawMainColumns("3 + 3 + 3 + 3", container);
                break;
            case 6:
                builder.drawMainColumns("2 + 2 + 2 + 2 + 2 + 2", container);
                break;
            case 12:
                builder.drawMainColumns("1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1", container);
                break;
            default: break;
        }
        builder.triggerDragnDrop();
    },

    'divideSubColumn' : function (col_number, container) {
        switch (col_number) {
            case 1:
                builder.drawSubColumns("12", container);
                break;
            case 2:
                builder.drawSubColumns("6 + 6", container);
                break;
            case 3:
                builder.drawSubColumns("4 + 4 + 4", container);
                break;
            case 4:
                builder.drawSubColumns("3 + 3 + 3 + 3", container);
                break;
            case 6:
                builder.drawSubColumns("2 + 2 + 2 + 2 + 2 + 2", container);
                break;
            case 12:
                builder.drawSubColumns("1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1", container);
                break;
            default: break;
        }
        builder.triggerDragnDrop();
    },

    'setUpSubColumns' : function (container) {
        var cols = container.closest('.sub-row').find('.sub-cols-format').val();
        var text_custom_columns = $('#text-custom-columns').val();
        var columns = prompt(text_custom_columns, cols);
        if(columns !== null) {
            builder.drawSubColumns(columns, container);
        }
        builder.triggerDragnDrop();
    },

    'setUpMainColumns' : function (container) {
        var cols = container.closest('.widget-row').find('.cols-format').val();
        var text_custom_columns = $('#text-custom-columns').val();
        var columns = prompt(text_custom_columns, cols);
        if(columns !== null) {
            builder.drawMainColumns(columns, container);
        }
        builder.triggerDragnDrop();
    },

    'drawSubColumns' : function (cols, container) {
        var html = "";
        var count = 0;
        var col_count = 0;
        var isDraw = false;
        var row_pos = container.closest('.widget-row').find('.main-row-pos').val();
        var main_col_pos = container.closest('.main-column').find('.main-col-pos').val();
        var sub_row_pos = container.closest('.sub-row').find('.sub-row-pos').val();
        var text_insert_module = $('#text-insert-module').val();
        var text_add_module = $('#text-add-module').val();
        var text_columns_error_format = $('#text-columns-error-format').val();

        var columns = cols.split('+').map(function (str) {
            return str.trim();
        });

        if(columns) {
            var col_num = columns.length;

            columns.forEach(function (col) {
                if(col != "0") {
                    count += parseInt(col);
                } else {
                    count = 13;
                }
            });

            if(count == 12) {
                isDraw = true;
            }

            if(isDraw) {
                columns.forEach(function (col) {
                    if(container.has('.sub-col-' + col_count + ' .layout-module-info').length) {
                        html += '           <div class="col-sm-' + col + ' column-area">';
                        html += '               <div class="module-area droparea ui-droppable ui-sortable sub-col-' + col_count + '">';
                        html +=                 container.find('.sub-col-' + col_count).html();
                        html += '               </div>';
                        html += '               <div class="col-action">';
                        html += '                   <div class="action-group">';
                        html += '                       <a class="a-module-add" onclick="builder.showAllModules($(this))" href="javascript:void(0);"><i class="fa fa-plus"></i> ' + text_add_module + '</a>';
                        html += '                   </div>';
                        html += '               </div>';
                        html += '               <input type="hidden" class="sub-col-pos" value="' + col_count + '" />';
                        html += '               <input type="hidden" class="sub-col-format" name="widget['+ row_pos + '][main_cols]['+ main_col_pos +'][sub_rows]['+ sub_row_pos +'][sub_cols]['+ col_count +'][format]" value="' + col + '" />';
                        html += '           </div>';
                        col_count++;
                        container.find('.sub-col-' + col_count).find('.text-insert-module').hide();
                    } else {
                        html += '           <div class="col-sm-' + col + ' column-area">';
                        html += '               <div class="module-area droparea ui-droppable ui-sortable sub-col-' + col_count + '">';
                        html += '                   <div class="text-insert-module"><span>'+ text_insert_module +'</span></div>';
                        html += '               </div>';
                        html += '               <div class="col-action">';
                        html += '                   <div class="action-group">';
                        html += '                       <a class="a-module-add" onclick="builder.showAllModules($(this))" href="javascript:void(0);"><i class="fa fa-plus"></i> ' + text_add_module + '</a>';
                        html += '                   </div>';
                        html += '               </div>';
                        html += '               <input type="hidden" class="sub-col-pos" value="' + col_count + '" />';
                        html += '               <input type="hidden" class="sub-col-format" name="widget['+ row_pos + '][main_cols]['+ main_col_pos +'][sub_rows]['+ sub_row_pos +'][sub_cols]['+ col_count +'][format]" value="' + col + '" />';
                        html += '           </div>';
                        col_count++;
                    }
                });

                container.closest('.sub-row').find('.count').html(col_num);
                container.closest('.sub-row').find('.sub-cols-format').val(cols);
                container.html(html);
            } else {
                alert(text_columns_error_format);
            }
        } else {
            alert(text_columns_error_format);
        }
        builder.triggerDragnDrop();
    },

    'drawMainColumns' : function (cols, container) {
        var html = "";
        var count = 0;
        var col_count = 0;
        var isDraw = false;
        var row_pos = container.closest('.widget-row').find('.main-row-pos').val();
        var text_insert_module = $('#text-insert-module').val();
        var text_add_module = $('#text-add-module').val();
        var text_columns_error_format = $('#text-columns-error-format').val();
        var text_columns = $("#text-columns").val();
        var text_custom_columns = $('#text-custom-columns').val();

        var columns = cols.split('+').map(function (str) {
            return str.trim();
        });

        if(columns) {
            var col_num = columns.length;

            columns.forEach(function (col) {
                if(col != "0") {
                    count += parseInt(col);
                } else {
                    count = 13;
                }
            });

            if(count == 12) {
                isDraw = true;
            }

            if(isDraw) {
                columns.forEach(function (col) {
                    if(container.has('.main-col-' + col_count + ' .sub-row').length) {
                        html += '<div class="col-sm-' + col + ' main-column">';
                        html += '   <input type="hidden" class="main-col-pos" value="' + col_count + '" />';
                        html += '   <input type="hidden" class="main-col-format" value="' + col + '" name="widget['+ row_pos + '][main_cols]['+ col_count +'][format]" />';
                        html += '   <a class="a-sub-row-add" href="javascript:void(0);" onclick="builder.drawSubRow($(this))">Add Row in this Column</a>';
                        html += '   <div class="main-col-content main-col-' + col_count + '">';
                        html +=     container.find('.main-col-' + col_count).html();
                        html += '   </div>';
                        html += '</div>';
                        col_count++;
                    } else {
                        html += '<div class="col-sm-' + col + ' main-column">';
                        html += '   <input type="hidden" class="main-col-pos" value="' + col_count + '" />';
                        html += '   <input type="hidden" class="main-col-format" value="' + col + '" name="widget['+ row_pos + '][main_cols]['+ col_count +'][format]" />';
                        html += '   <a class="a-sub-row-add" href="javascript:void(0);" onclick="builder.drawSubRow($(this))">Add Row in this Column</a>';
                        html += '   <div class="main-col-content main-col-' + col_count + '">';
                        html += '       <div class="sub-row sub-row-0">';
                        html += '           <div class="sub-row-action">';
                        html += '               <div class="action-group">';
                        html += '                   <span class="row-identify">' + text_columns + '</span>';
                        html += '                   <div class="sub-col-count">';
                        html += '                       <a href="javascript:void(0);" onclick="builder.plusSubColumn($(this))" rel="1" class="col-plus"></a>';
                        html += '                       <span class="count">1</span>';
                        html += '                       <a href="javascript:void(0);" onclick="builder.minusSubColumn($(this))" rel="1" class="col-minus"></a>';
                        html += '                   </div>';
                        html += '                   <div class="a-group">';
                        html += '                       <a class="a-column-custom" title="' + text_custom_columns + '" onclick="builder.customSubColumns($(this))" href="javascript:void(0);"></a>';
                        html += '                       <a class="a-row-delete" onclick="builder.removeSubRow($(this))" href="javascript:void(0);"></a>';
                        html += '                   </div>';
                        html += '               </div>';
                        html += '               <input type="hidden" class="sub-cols-format" value="12" />';
                        html += '           </div>';
                        html += '           <div class="sub-row-content">';
                        html += '               <div class="col-sm-12 column-area">';
                        html += '                   <div class="module-area droparea ui-droppable ui-sortable sub-col-0">';
                        html += '                       <div class="text-insert-module"><span>'+ text_insert_module +'</span></div>';
                        html += '                   </div>';
                        html += '                   <div class="col-action">';
                        html += '                       <div class="action-group">';
                        html += '                           <a class="a-module-add" onclick="builder.showAllModules($(this))" href="javascript:void(0);"><i class="fa fa-plus"></i> ' + text_add_module + '</a>';
                        html += '                       </div>';
                        html += '                   </div>';
                        html += '                   <input type="hidden" class="sub-col-pos" value="0" />';
                        html += '                   <input type="hidden" class="sub-col-format" name="widget['+ row_pos + '][main_cols]['+ col_count +'][sub_rows][0][sub_cols][0][format]" value="12" />';
                        html += '               </div>';
                        html += '           </div>';
                        html += '           <input type="hidden" class="sub-row-pos" value="0" />';
                        html += '       </div>';
                        html += '   </div>';
                        html += '</div>';
                        col_count++;
                    }
                });

                container.closest('.widget-row').find('.count').html(col_num);
                container.closest('.widget-row').find('.cols-format').val(cols);
                container.html(html);
            } else {
                alert(text_columns_error_format);
            }
        } else {
            alert(text_columns_error_format);
        }
        builder.triggerDragnDrop();
    },

    'drawSubRow'  : function (element) {
        var html = "";
        var text_insert_module = $('#text-insert-module').val();
        var text_add_module = $('#text-add-module').val();
        var text_columns = $("#text-columns").val();
        var text_custom_columns = $('#text-custom-columns').val();

        var main_column_container = element.closest('.main-column');
        var main_row_pos = element.closest('.widget-row').find('.main-row-pos').val();
        var main_col_count = main_column_container.find('.main-col-pos').val();
        var sub_row_pos = main_column_container.find('.main-col-'+ main_col_count).find('.sub-row:last .sub-row-pos').val();
        if(sub_row_pos == null) {
            sub_row_pos = 0;
        } else {
            sub_row_pos++;
        }

        html += '   <div class="sub-row sub-row-' + sub_row_pos + '">';
        html += '       <div class="sub-row-action">';
        html += '           <div class="action-group">';
        html += '               <span class="row-identify">' + text_columns + '</span>';
        html += '               <div class="sub-col-count">';
        html += '                   <a href="javascript:void(0);" onclick="builder.plusSubColumn($(this))" rel="1" class="col-plus"></a>';
        html += '                   <span class="count">1</span>';
        html += '                   <a href="javascript:void(0);" onclick="builder.minusSubColumn($(this))" rel="1" class="col-minus"></a>';
        html += '               </div>';
        html += '               <div class="a-group">';
        html += '                   <a class="a-column-custom" title="' + text_custom_columns + '" onclick="builder.customSubColumns($(this))" href="javascript:void(0);"></a>';
        html += '                   <a class="a-row-delete" onclick="builder.removeSubRow($(this))" href="javascript:void(0);"></a>';
        html += '               </div>';
        html += '           </div>';
        html += '           <input type="hidden" class="sub-cols-format" value="12" />';
        html += '       </div>';
        html += '       <div class="sub-row-content">';
        html += '           <div class="col-sm-12 column-area">';
        html += '               <div class="module-area droparea ui-droppable ui-sortable sub-col-0">';
        html += '                   <div class="text-insert-module"><span>'+ text_insert_module +'</span></div>';
        html += '               </div>';
        html += '               <div class="col-action">';
        html += '                   <div class="action-group">';
        html += '                       <a class="a-module-add" onclick="builder.showAllModules($(this))" href="javascript:void(0);"><i class="fa fa-plus"></i> ' + text_add_module + '</a>';
        html += '                   </div>';
        html += '               </div>';
        html += '               <input type="hidden" class="sub-col-pos" value="0" />';
        html += '               <input type="hidden" class="sub-col-format" name="widget['+ main_row_pos + '][main_cols]['+ main_col_count +'][sub_rows]['+ sub_row_pos +'][sub_cols][0][format]" value="12" />';
        html += '           </div>';
        html += '       </div>';
        html += '       <input type="hidden" class="sub-row-pos" value="' + sub_row_pos + '" />';
        html += '   </div>';

        main_column_container.find('.main-col-'+ main_col_count).append(html);
        builder.triggerDragnDrop();
    },

    'drawMainRow' : function (row_number) {
        var text_columns = $("#text-columns").val();
        var text_insert_module = $("#text-insert-module").val();
        var text_add_module = $('#text-add-module').val();
        var text_custom_columns = $('#text-custom-columns').val();
        var text_custom_classname = $('#text-custom-classname').val();
        var html = "";
        html += '<div class="widget-row col-sm-12">';
        html += '   <div class="row-action">';
        html += '       <div class="action-group">';
        html += '           <input type="text" class="form-control input-class-name" name="widget['+ row_number + '][class]" value="" placeholder="'+ text_custom_classname +'" />';
        html += '           <span class="row-identify">'+ text_columns +'</span>';
        html += '           <div class="col-count">';
        html += '               <a href="javascript:void(0);" onclick="builder.plusMainColumn($(this));" rel="1" class="col-plus"></a>';
        html += '               <span class="count" >1</span>';
        html += '               <a href="javascript:void(0);" onclick="builder.minusMainColumn($(this));" rel="1" class="col-minus"></a>';
        html += '           </div>';
        html += '           <div class="a-group">';
        html += '               <a class="a-column-custom" onclick="builder.customMainColumns($(this));" href="javascript:void(0);" title="' + text_custom_columns + '"></a>';
        html += '               <a class="a-row-delete" onclick="builder.removeRow($(this));" href="javascript:void(0);"></a>';
        html += '           </div>';
        html += '       </div>';
        html += '       <input type="hidden" class="cols-format" value="12" />';
        html += '   </div>';
        html += '   <div class="row-content row-'+ row_number +'">' +
            '       <div class="col-sm-12 main-column">' +
            '           <input type="hidden" class="main-col-pos" value="0" />' +
            '           <input type="hidden" class="main-col-format" name="widget['+ row_number + '][main_cols][0][format]" value="12" />' +
            '           <a class="a-sub-row-add" href="javascript:void(0);" onclick="builder.drawSubRow($(this))">Add Row in this Column</a>' +
            '           <div class="main-col-content main-col-0">' +
            '               <div class="sub-row sub-row-0">' +
            '                   <div class="sub-row-action">' +
            '                       <div class="action-group">' +
            '                           <span class="row-identify">'+ text_columns +'</span>' +
            '                           <div class="sub-col-count">' +
            '                               <a href="javascript:void(0);" onclick="builder.plusSubColumn($(this))" rel="1" class="col-plus"></a>' +
            '                               <span class="count">1</span>' +
            '                               <a href="javascript:void(0);" onclick="builder.minusSubColumn($(this))" rel="1" class="col-minus"></a>' +
            '                           </div>' +
            '                           <div class="a-group">' +
            '                               <a class="a-column-custom" onclick="builder.customSubColumns($(this))" href="javascript:void(0);" title="' + text_custom_columns + '"></a>' +
            '                               <a class="a-row-delete" onclick="builder.removeSubRow($(this))" href="javascript:void(0);"></a>' +
            '                           </div>' +
            '                       </div>' +
            '                       <input type="hidden" class="sub-cols-format" value="12" />' +
            '                   </div>' +
            '                   <div class="sub-row-content">' +
            '                       <div class="col-sm-12 column-area">' +
            '                           <div class="module-area droparea ui-droppable ui-sortable sub-col-0">' +
            '                               <div class="text-insert-module"><span>'+ text_insert_module +'</span></div>' +
            '                           </div> ' +
            '                           <div class="col-action"> ' +
            '                               <div class="action-group">' +
            '                                   <a class="a-module-add" onclick="builder.showAllModules($(this))" href="javascript:void(0);"><i class="fa fa-plus"></i> ' + text_add_module + '</a> ' +
            '                               </div> ' +
            '                           </div> ' +
            '                           <input type="hidden" class="sub-col-pos" value="0" />' +
            '                           <input type="hidden" class="sub-col-format" name="widget['+ row_number + '][main_cols][0][sub_rows][0][sub_cols][0][format]" value="12" />' +
            '                       </div> ' +
            '                   </div> ' +
            '                   <input type="hidden" class="sub-row-pos" value="0" />' +
            '               </div> ' +
            '           </div> ' +
            '       </div> ' +
            '   </div> ' +
            '   <input type="hidden" class="main-row-pos" value="'+ row_number +'" />' +
            '</div>';
        $('.widget-container').append(html);
        builder.triggerDragnDrop();
    },

    'removeSubRow' : function (container) {
        container.closest('.sub-row').remove();
        builder.reArrangeLayout();
    },

    'removeRow' : function (container) {
        container.closest('.widget-row').remove();
        builder.reArrangeLayout();
    },

    'showAllModules' : function (container) {
        var row_pos = container.closest('.widget-row').find('.main-row-pos').val();
        var sub_row_pos = container.closest('.sub-row').find('.sub-row-pos').val();
        var col_pos = container.closest('.main-column').find('.main-col-pos').val();
        var sub_col_pos = container.closest('.column-area').find('.sub-col-pos').val();
        $('#module-row').val(row_pos);
        $('#module-col').val(col_pos);
        $('#module-sub-row').val(sub_row_pos);
        $('#module-sub-col').val(sub_col_pos);
        $('.popup-background').show();
        $('.popup-loader-img').show();
        $('.all-modules-container').show(600);
        builder.triggerDragnDrop();
    },

    'closeAllModules' : function() {
        $('.all-modules-container').hide(600);
        $('.popup-background').hide();
        $('.popup-loader-img').hide();
    },

    'addModule' : function(name, code, url) {
        var row_pos =  $('#module-row').val();
        var col_pos =  $('#module-col').val();
        var sub_row_pos =  $('#module-sub-row').val();
        var sub_col_pos =  $('#module-sub-col').val();

        html = '<div class="layout-module-info moveable">';
        html += '	<div class="top">';
        html += '		<div class="module-info">';
        html += '			<p>' + name + '</p>';
        html += '		    <a class="btn-edit" href="javascript:void(0);" onclick="loadModule(\'' + url + '\')"></a>';
        html += '			<a class="btn-remove" href="javascript:void(0);" onclick="builder.removeModule($(this))"></a>';
        html += '		</div>';
        html += '	</div>';
        html += '	<input type="hidden" class="module-in-main-row" value="' + row_pos +'" />';
        html += '	<input type="hidden" class="module-in-main-col" value="' + col_pos +'" />';
        html += '	<input type="hidden" class="module-in-sub-row" value="' + sub_row_pos +'" />';
        html += '	<input type="hidden" class="module-in-sub-col" value="' + sub_col_pos +'" />';
        html += '	<input type="hidden" class="module-code" name="widget['+ row_pos + '][main_cols]['+ col_pos +'][sub_rows]['+ sub_row_pos +'][sub_cols]['+ sub_col_pos +'][info][module][0][code]" value="' + code +'" />';
        html += '	<input type="hidden" class="module-name" name="widget['+ row_pos + '][main_cols]['+ col_pos +'][sub_rows]['+ sub_row_pos +'][sub_cols]['+ sub_col_pos +'][info][module][0][name]" value="' + name +'" />';
        html += '	<input type="hidden" class="module-url" name="widget['+ row_pos + '][main_cols]['+ col_pos +'][sub_rows]['+ sub_row_pos +'][sub_cols]['+ sub_col_pos +'][info][module][0][url]" value="' + url +'" />';
        html +=	'</div>';

        $('.widget-container .row-' + row_pos + ' .main-col-' + col_pos + ' .sub-row-' + sub_row_pos + ' .sub-col-' + sub_col_pos + ' .text-insert-module').hide();
        $('.widget-container .row-' + row_pos + ' .main-col-' + col_pos + ' .sub-row-' + sub_row_pos + ' .sub-col-' + sub_col_pos).append(html);
        builder.closeAllModules();
        builder.reArrangeLayout();
        builder.triggerDragnDrop();
    },

    'removeModule' : function (container) {
        var module_area = container.closest('.module-area');
        container.closest('.layout-module-info').remove();
        if(module_area.has('.layout-module-info').length) {
            module_area.find('.text-insert-module').hide();
        } else {
            module_area.find('.text-insert-module').show();
        }
        builder.reArrangeLayout();
        builder.triggerDragnDrop();
    },

    'reArrangeLayout' : function () {
        var main_row_pos = 0;
        $('.widget-container .widget-row').each(function () {
            $(this).find('.main-row-pos').val(main_row_pos);
            $(this).find('.row-content').removeClass().addClass('row-content row-' + main_row_pos);
            $(this).find('.input-class-name').attr('name', 'widget[' + main_row_pos + '][class]');

            var main_col_pos = 0;
            $(this).find('.main-column').each(function () {
                $(this).find('.main-col-pos').val(main_col_pos);
                $(this).find('.main-col-content').removeClass().addClass('main-col-content main-col-' + main_col_pos);
                $(this).find('.main-col-format').attr('name', 'widget[' + main_row_pos + '][main_cols][' + main_col_pos + '][format]');

                var sub_row_pos = 0;
                $(this).find('.sub-row').each(function () {
                    $(this).find('.sub-row-pos').val(sub_row_pos);
                    $(this).removeClass().addClass('sub-row sub-row-' + sub_row_pos);

                    var sub_col_pos = 0;
                    $(this).find('.column-area').each(function () {
                        $(this).find('.sub-col-pos').val(sub_col_pos);
                        $(this).find('.sub-col-format').attr('name', 'widget[' + main_row_pos + '][main_cols][' + main_col_pos + '][sub_rows][' + sub_row_pos + '][sub_cols][' + sub_col_pos + '][format]');
                        $(this).find('.module-area').removeClass().addClass('module-area droparea ui-droppable ui-sortable sub-col-' + sub_col_pos);

                        if($(this).has('.layout-module-info').length) {
                            $(this).find('.text-insert-module').hide();
                        } else {
                            $(this).find('.text-insert-module').show();
                        }

                        var module_pos = 0;
                        $(this).find('.layout-module-info').each(function () {
                            $(this).find('.module-in-main-row').val(main_row_pos);
                            $(this).find('.module-in-main-col').val(main_col_pos);
                            $(this).find('.module-in-sub-row').val(sub_row_pos);
                            $(this).find('.module-in-sub-sol').val(sub_col_pos);

                            $(this).find('.module-code').attr('name', 'widget[' + main_row_pos + '][main_cols][' + main_col_pos + '][sub_rows][' + sub_row_pos + '][sub_cols][' + sub_col_pos + '][info][module][' + module_pos + '][code]');
                            $(this).find('.module-name').attr('name', 'widget[' + main_row_pos + '][main_cols][' + main_col_pos + '][sub_rows][' + sub_row_pos + '][sub_cols][' + sub_col_pos + '][info][module][' + module_pos + '][name]');
                            $(this).find('.module-url').attr('name', 'widget[' + main_row_pos + '][main_cols][' + main_col_pos + '][sub_rows][' + sub_row_pos + '][sub_cols][' + sub_col_pos + '][info][module][' + module_pos + '][url]');

                            module_pos++;
                        });

                        sub_col_pos++;
                    });

                    sub_row_pos++;
                });

                main_col_pos++;
            });

            main_row_pos++;
        });
    }
};