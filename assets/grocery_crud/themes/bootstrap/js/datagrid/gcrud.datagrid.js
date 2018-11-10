/*global jQuery, console, setTimeout, ajax_list_url, unique_hash */
(function ($) {
    "use strict";

    /**
     * This is the description for my class.
     *
     * @class Datagrid
     * @constructor
     */
    var Datagrid = function (gcrud_container) {

        /**
         *
         * @type {jQuery}
         */
        this.gcrud_container = gcrud_container;

        this.search_timer = null;
        this.csrf_field = null;

        this.init = function () {
            //Init here!
            this.facadeInitListeners();
        };

        this.facadeInitListeners = function () {
            this.datagridInit();

            this.listenerSelectRow();
            this.listenerLoadMoreButton();
            this.listenerDeleteMultiple();
            this.listenerDeleteRowClick();
            this.listenerSearchButton();
            this.listenerSearchInput();
            this.listenerColumnWithOrdering();
            this.listenerRefreshButton();
            this.listenerPrintButton();
            this.listenerExportButton();
            this.listenerPerPage();
            this.listenerPagingButtons();
            this.listenerSettingsButton();

            this.checkCachedData();

        };

    };

    /** Datagrid class Constants */
    Datagrid.SELECTOR_PAGING_PREVIOUS   = '.paging-previous';
    Datagrid.SELECTOR_PAGING_NEXT       = '.paging-next';
    Datagrid.SELECTOR_PAGING_FIRST      = '.paging-first';
    Datagrid.SELECTOR_PAGING_LAST       = '.paging-last';

    Datagrid.CLASS_DISABLED             = 'disabled';
    Datagrid.CLASS_LOADING              = 'loading-opacity';
    Datagrid.CLASS_PAGING_PREVIOUS      = 'paging-previous';
    Datagrid.CLASS_PAGING_NEXT          = 'paging-next';
    Datagrid.CLASS_PAGING_FIRST         = 'paging-first';
    Datagrid.CLASS_PAGING_LAST          = 'paging-last';
    Datagrid.CLASS_PER_PAGE             = 'per_page';

    Datagrid.prototype.clearFilteringCache = function () {
        var $searchAllInput = this.gcrud_container.find('.search-button > input.search-input');

        this.gcrud_container.find('.page-number-hidden').val('1');
        this.gcrud_container.find('.page-number-input').val('1');
        this.gcrud_container.find('.grocery-crud-table tr:first').find('.active')
            .removeClass('active ordering-desc ordering-asc');
        this.gcrud_container.find('.grocery-crud-table tr:first').find('.fa-chevron-up,.fa-chevron-down').remove();
        this.gcrud_container.find('td.active').removeClass('active');
        this.gcrud_container.find('thead .value-not-empty').removeClass('value-not-empty').val('');

        if ($searchAllInput.val() !== '') {
            $searchAllInput.val('').trigger('blur');
        }

        this.SearchAndOrderingTrigger();

        CacheLibrary.removeLocalStorageItem('gcrud_' + unique_hash);
    };

    Datagrid.prototype.checkCachedData = function () {
        var perPageValue = this.gcrud_container.find('.' + Datagrid.CLASS_PER_PAGE).val();
        var cachedDatra = CacheLibrary.getLocalStorageItem('gcrud_' + unique_hash);
        var forceRefresh = false;

        try {
            cachedDatra = (cachedDatra !== null) ? JSON.parse(cachedDatra) : {};
        } catch (e) {
            //Invalid JSON data. Remove this local storage item
            CacheLibrary.removeLocalStorageItem('gcrud_' + unique_hash);
            return false;
        }

        //Check for invalid data
        if (cachedDatra.per_page === undefined || cachedDatra.page === undefined || cachedDatra.order_by === undefined) {
            //Invalid JSON data. Remove this local storage item
            CacheLibrary.removeLocalStorageItem('gcrud_' + unique_hash);
            return false;
        }

        if (perPageValue !== cachedDatra.per_page) {
            this.gcrud_container.find('.' + Datagrid.CLASS_PER_PAGE).val(cachedDatra.per_page);
            forceRefresh = true;
        }

        if (cachedDatra.page !== "1") {
            this.gcrud_container.find('.page-number-hidden').val(cachedDatra.page);
            this.gcrud_container.find('.page-number-input').val(cachedDatra.page);
            forceRefresh = true;
        }

        if (cachedDatra.order_by[0] !== null) {
            var $column_to_order = this.gcrud_container.find('.grocery-crud-table tr:first')
                .find('.column-with-ordering[data-order-by=' + cachedDatra.order_by[0] + ']')
                .addClass('active ordering-' + cachedDatra.order_by[1]);

            var arrow_class_name = cachedDatra.order_by[1] === 'asc' ? 'fa-chevron-up' : 'fa-chevron-down';

            $column_to_order.html("<div>" + $column_to_order.text() + "</div><i class='fa " +  arrow_class_name + "'></i>");

            forceRefresh = true;
        }

        //Currently the way to recognize if the search text is for all or by column is by checking if the
        //search text is string or not
        if (typeof cachedDatra.search_text === "string") {
            $('.search-button').trigger('click');

            this.gcrud_container.find('.search-button > input.search-input')
                .val(cachedDatra.search_text).trigger('change');
            //Triggering change (above code) is a work around to show the clear button at the right. If this is
            //called then we don't need to force refresh. Hence returning true
            return true;
        }

        if (cachedDatra.search_text.length > 0) {
            var $filter_row = this.gcrud_container.find('.gc-search-row');

            $.each(cachedDatra.search_field, function (search_index, search_field_name) {
                $filter_row.find('input.searchable-input[name="' + search_field_name + '"]')
                    .val(cachedDatra.search_text[search_index]);
            });

            forceRefresh = true;
        }


        if (forceRefresh) {
            this.SearchAndOrderingTrigger();
        }
    };

    Datagrid.prototype.datagridInit = function () {
        var success_message_container = this.gcrud_container.find('.success-message');

        if (!success_message_container.is(':empty')) {
            $.growl(success_message_container.html(), {
                type: 'success',
                delay: 10000,
                animate: {
                    enter: 'animated bounceInDown',
                    exit: 'animated bounceOutUp'
                }
            });
        }

        if ($('#gcrud-search-form>div:first>input[type=hidden]').length === 1) {
            this.csrf_field = {
                name: $('#gcrud-search-form>div:first>input[type=hidden]').attr('name'),
                value: $('#gcrud-search-form>div:first>input[type=hidden]').val()
            };

            if (this.csrf_field.name === undefined || this.csrf_field.value === undefined) {
                this.csrf_field = null;
            }
        }
    };

    Datagrid.prototype.appendSearchClearButtons = function () {
        var datagrid_object = this;

        datagrid_object.gcrud_container.find('.clear-search').remove();

        datagrid_object.gcrud_container.find('.grocery-crud-table input.searchable-input').each(function () {
            $(this).removeClass('value-not-empty');
            if ($(this).val() === '') {
                return true;
            }

            $(this).addClass('value-not-empty');
            $(this).after('<i class="fa fa-times clear-search"></i>');

            datagrid_object.gcrud_container.find('.clear-search').click(function () {
                $(this).closest('td').find('.searchable-input').val('').trigger('keyup');
            });
        });
    };

    Datagrid.prototype.listenerRefreshButton = function () {
        var datagrid_object = this;
        datagrid_object.gcrud_container.find('.gc-refresh').click(function () {
            clearTimeout(datagrid_object.search_timer);
            datagrid_object.SearchAndOrderingTrigger();
        });
    };

    Datagrid.prototype.listenerPerPage = function () {
        var datagrid_object = this;

        datagrid_object.gcrud_container.find('.' + Datagrid.CLASS_PER_PAGE).change(function () {
            datagrid_object.gcrud_container.find('.page-number-hidden').val('1');
            datagrid_object.SearchAndOrderingTrigger();
        });
    };

    Datagrid.prototype.listenerExportButton = function () {
        var datagrid_object = this;
        datagrid_object.gcrud_container.find('.gc-export').click(function () {
            var export_url = $(this).data('url'),
                form_inputs = '';

            $.each(datagrid_object.gcrud_container.find('form').serializeArray(), function (i, field) {
                form_inputs = form_inputs + '<input type="hidden" name="' + field.name + '" value="' + field.value + '">';
            });

            if (datagrid_object.gcrud_container.find('.search-input').val() !== '') {
                form_inputs = form_inputs + '<input type="hidden" name="search_field" value="" />' +
                    '<input type="hidden" name="search_text" value="' + datagrid_object.gcrud_container.find('.search-input').val() + '" />';
            } else {
                datagrid_object.gcrud_container.find('.grocery-crud-table thead input').each(function () {
                    if ($(this).val() !== '' && !$(this).is(':checkbox')) {
                        form_inputs = form_inputs + '<input type="hidden" name="search_field[]" value="' + $(this).attr('name') + '">'
                            + '<input type="hidden" name="search_text[]" value="' + $(this).val() + '">';
                    }
                });
            }


            $('#tmp-form').remove();
            $('body').after($("<form/>").attr("id", "tmp-form").attr("method", "post").attr("target", "_blank").
                attr("action", export_url).html(form_inputs));

            $('#tmp-form').submit();
        });

    };

    Datagrid.prototype.listenerPrintButton = function () {
        var datagrid_object = this;
        datagrid_object.gcrud_container.find('.gc-print').click(function () {
            var print_url = $(this).data('url'),
                form_input_html = '',
                form_on_demand;

            if (datagrid_object.csrf_field !== null) {
                form_input_html += '<input type="hidden" name = "' + datagrid_object.csrf_field.name + '" value="' + datagrid_object.csrf_field.value + '"/>';
            }

            if (datagrid_object.gcrud_container.find('.search-input').val() !== '') {
                form_input_html = form_input_html + '<input type="hidden" name="search_field" value="" />' +
                    '<input type="hidden" name="search_text" value="' + datagrid_object.gcrud_container.find('.search-input').val() + '" />';
            } else {
                datagrid_object.gcrud_container.find('.grocery-crud-table thead input').each(function () {
                    if ($(this).val() !== '' && !$(this).is(':checkbox')) {
                        form_input_html = form_input_html + '<input type="hidden" name="search_field[]" value="' + $(this).attr('name') + '">'
                            + '<input type="hidden" name="search_text[]" value="' + $(this).val() + '">';
                    }
                });
            }


            form_on_demand = $("<form/>").attr("method", "post").attr("action", print_url).html(form_input_html);

            form_on_demand.ajaxSubmit({
                beforeSend: function () {
                    datagrid_object.gcrud_container.addClass(Datagrid.CLASS_LOADING);
                },
                complete: function () {
                    datagrid_object.gcrud_container.removeClass(Datagrid.CLASS_LOADING);
                },
                error: function () {
                    datagrid_object.gcrud_container.removeClass(Datagrid.CLASS_LOADING);
                },
                success: function (html_data) {
                    datagrid_object.gcrud_container.removeClass(Datagrid.CLASS_LOADING);
                    $("<div/>").html(html_data).printThis();
                }
            });
        });
    };

    Datagrid.prototype.pagingCalculations = function () {
        var page_number_value   = parseInt(this.gcrud_container.find('.page-number-hidden').val(), 10),
            max_paging          = this.getMaxPaging();

        if (page_number_value <= 0) {
            page_number_value = 1;
            this.gcrud_container.find('.page-number-hidden').val('1');
            this.gcrud_container.find('.page-number-input').val('1');
        }

        if (page_number_value === 1) {
            this.gcrud_container.find('.paging-first').addClass(Datagrid.CLASS_DISABLED);
            this.gcrud_container.find('.paging-previous').addClass(Datagrid.CLASS_DISABLED);
        } else {
            this.gcrud_container.find('.paging-first').removeClass(Datagrid.CLASS_DISABLED);
            this.gcrud_container.find('.paging-previous').removeClass(Datagrid.CLASS_DISABLED);
        }

        if (page_number_value + 1 > max_paging) {
            this.gcrud_container.find('.paging-last').addClass(Datagrid.CLASS_DISABLED);
            this.gcrud_container.find('.paging-next').addClass(Datagrid.CLASS_DISABLED);
        } else {
            this.gcrud_container.find('.paging-last').removeClass(Datagrid.CLASS_DISABLED);
            this.gcrud_container.find('.paging-next').removeClass(Datagrid.CLASS_DISABLED);
        }

        this.gcrud_container.find('.page-number-input').val(page_number_value);

        if (page_number_value > max_paging) {
            this.gcrud_container.find('.page-number-hidden').val(max_paging);
            this.gcrud_container.find('.page-number-input').val(max_paging);
        }

    };

    Datagrid.prototype.SearchAndOrderingTrigger = function () {
        var order_by = $('.column-with-ordering.active:first').data('order-by'),
            order_direction = '',
            search_fields = [],
            search_texts = [],
            datagrid_object = this,
            gcrud_container = datagrid_object.gcrud_container,
            search_all_input = datagrid_object.gcrud_container.find('.search-button > input.search-input'),
            per_page_value = datagrid_object.gcrud_container.find('.' + Datagrid.CLASS_PER_PAGE).val(),
            data_to_send;

        this.pagingCalculations();

        if ($('.column-with-ordering.active:first').hasClass('ordering-desc')) {
            order_direction = 'desc';
        } else if ($('.column-with-ordering.active:first').hasClass('ordering-asc')) {
            order_direction = 'asc';
        }

        $('.gc-search-row input.searchable-input').each(function () {
            if ($(this).val() !== '') {
                search_fields.push($(this).attr('name'));
                search_texts.push($(this).val());
            }
        });

        if (search_fields.length > 0) {
            search_all_input.val('');
            search_all_input.trigger('blur');
        }

        if (search_all_input.val() !== '') {
            //Let's take advantage that JavaScript is not strong type :)
            search_fields   = "";
            search_texts    = search_all_input.val();
        }

        data_to_send = {
            page: gcrud_container.find('input[name="page_number"]').val(),
            per_page: gcrud_container.find('.' + Datagrid.CLASS_PER_PAGE).val(),
            order_by: [order_by, order_direction],
            search_field: search_fields,
            search_text: search_texts
        };

        CacheLibrary.setLocalStorageItem('gcrud_' + unique_hash , JSON.stringify(data_to_send));

        if (this.csrf_field !== null) {
            data_to_send[this.csrf_field.name] = this.csrf_field.value;
        }

        $.ajax({
            beforeSend: function () {
                gcrud_container.addClass(Datagrid.CLASS_LOADING);
            },
            complete: function () {
                gcrud_container.removeClass(Datagrid.CLASS_LOADING);
            },
            error: function () {
                gcrud_container.removeClass(Datagrid.CLASS_LOADING);
            },
            data: data_to_send,
            dataType: 'json',
            url: ajax_list_url,
            success: function (result) {
                var active_column, paging_ends;

                datagrid_object.gcrud_container.addClass(Datagrid.CLASS_LOADING);

                datagrid_object.gcrud_container.find('.select-all-none').prop('checked', false);

                datagrid_object.gcrud_container.find('.grocery-crud-table tbody').html(result.tbody_html);

                $('.current-total-results').html(result.current_total_results);

                if (result.current_total_results > 10) {
                    paging_ends = parseInt($('input[name="page_number"]').val(), 10) * per_page_value;

                    if (paging_ends > result.current_total_results) {
                        paging_ends = result.current_total_results;
                    }
                    $('.paging-ends').html(paging_ends);
                } else {
                    $('.paging-ends').html(result.current_total_results);
                }

                $('.paging-starts').html((parseInt($('input[name="page_number"]').val(), 10) - 1) * per_page_value + 1);

                active_column = $('.column-with-ordering.active:first');
                if (active_column.length > 0) {
                    gcrud_container.find('.grocery-crud-table').
                        find('thead tr td:nth-child(' + (active_column.index() + 2) + '), tbody tr td:nth-child(' + (active_column.index() + 2) + ')').
                        addClass('active');
                }

                if (result.current_total_results < parseInt(datagrid_object.gcrud_container.find('.full-total').html(), 10)) {
                    datagrid_object.gcrud_container.find('.full-total-container').removeClass('hidden');
                } else {
                    datagrid_object.gcrud_container.find('.full-total-container').addClass('hidden');
                }

                datagrid_object.listenerLoadMoreButton();
                datagrid_object.appendSearchClearButtons();
                datagrid_object.listenerSelectRow();
                datagrid_object.pagingCalculations();
                datagrid_object.hideShowDeleteButton();
                datagrid_object.listenerDeleteRowClick();

                gcrud_container.removeClass(Datagrid.CLASS_LOADING);
            },
            method: 'post'
        });
    };

    Datagrid.prototype.listenerSearchInput = function () {
        var datagrid_object = this;

        $('.grocery-crud-table input.searchable-input').on('keyup change', function (event) {
            //If the key is Enter or empty, then don't wait! Just start searching...
            var timer_timeout = event.keyCode === 9 || event.keyCode === 13 || $(this).val() === '' ? 1 : 1000;

            clearTimeout(datagrid_object.search_timer);
            datagrid_object.search_timer = setTimeout(function () {
                datagrid_object.gcrud_container.find('.page-number-hidden').val('1');
                datagrid_object.SearchAndOrderingTrigger();

            }, timer_timeout);
        });
    };

    Datagrid.prototype.listenerSettingsButton = function listenerSettingsButton() {
        var datagrid_object = this;

        this.gcrud_container
            .find('.settings-button-container')
            .on('show.bs.dropdown', function () {
                datagrid_object.clearFilteringListener($(this));
            })
            .on('hide.bs.dropdown', function () {
                $(this).find('.clear-filtering').unbind('click');
            })
        ;
    }

    Datagrid.prototype.clearFilteringListener = function clearFilteringListener($settingsContainer) {
        var datagrid_object = this;

        $settingsContainer.find('.clear-filtering').click(function clearFilteringClickButton() {
            datagrid_object.clearFilteringCache();
        });
    };

    Datagrid.prototype.listenerColumnWithOrdering = function () {
        var datagrid_object = this;

        $('.column-with-ordering').click(function () {
            var gcrud_container = $(this).closest('.gc-container'),
                column_title = $.trim($(this).text());

            gcrud_container.addClass(Datagrid.CLASS_LOADING);

            $(this).closest('.grocery-crud-table').find('th.active, td.active').removeClass('active');

            $(this).parent().find('.column-with-ordering').each(function () {
                $(this).html($.trim($(this).text()));
            });

            $(this).addClass('active');
            $(this).closest('.grocery-crud-table').
                find('thead tr td:nth-child(' + ($(this).index() + 2) + '), tbody tr td:nth-child(' + ($(this).index() + 2) + ')').
                addClass('active');

            if ($(this).hasClass('ordering-asc')) {
                $(this).closest('.grocery-crud-table').find('th').removeClass('ordering-desc').removeClass('ordering-asc');

                $(this).html("<div>" + column_title + "</div><i class='fa fa-chevron-down'></i>");
                $(this).addClass('ordering-desc').removeClass('ordering-asc');
            } else {
                $(this).closest('.grocery-crud-table').find('th').removeClass('ordering-desc').removeClass('ordering-asc');

                $(this).html("<div>" + column_title + "</div><i class='fa fa-chevron-up'></i>");
                $(this).addClass('ordering-asc').removeClass('ordering-desc');
            }

            //In any case the page number will be 1
            gcrud_container.find('input[name="page_number"]').val('1');

            gcrud_container.removeClass(Datagrid.CLASS_LOADING);

            datagrid_object.SearchAndOrderingTrigger();

        });
    };

    Datagrid.prototype.listenerSearchButton = function () {
        var datagrid_object = this;

        $('.search-button').click(function (event) {
            event.preventDefault();
            var search_button = $(this);
            search_button.removeClass('btn-primary').addClass('btn-default');
            search_button.find('input.search-input').addClass('search-input-big');

            setTimeout(function () {
                search_button.find('input.search-input').focus();
            }, 400);

            $(this).addClass('search-button-big');
        });

        $('.search-button>input.search-input').change(function () {
            datagrid_object.gcrud_container.find('.search-button .clear-all-search').remove();

            if ($(this).val() !== '') {
                $(this).after('<i class="fa fa-times clear-all-search"></i>');

                datagrid_object.gcrud_container.find('.search-button .clear-all-search').click(function () {
                    datagrid_object.gcrud_container.find('.search-button>input.search-input').val('').trigger('change');
                });
            }

            datagrid_object.gcrud_container.find('.page-number-hidden').val('1');
            datagrid_object.gcrud_container.find('.searchable-input').val('');
            datagrid_object.SearchAndOrderingTrigger();
        });

        $('.search-button>input.search-input').blur(function () {
            var search_button = $(this).closest('.search-button');

            if ($(this).val() === '' && search_button.hasClass('search-button-big')) {
                //Make sure that we are blur :)
                datagrid_object.gcrud_container.find('.search-button .clear-all-search').remove();
                $(this).removeClass('search-input-big');
                search_button.removeClass('search-button-big');
                search_button.removeClass('btn-default').addClass('btn-primary');
            }
        });

    };

    Datagrid.prototype.getMaxPaging = function () {
        var total_results = parseInt(this.gcrud_container.find('.current-total-results').html(), 10),
            per_page = parseInt(this.gcrud_container.find('.' + Datagrid.CLASS_PER_PAGE).val(), 10),
            max_paging = total_results === 0 ? 1 : Math.ceil(total_results / per_page);

        return max_paging;
    };

    Datagrid.prototype.listenerPagingButtons = function () {
        var datagrid_object = this,
            selectors_for_all_buttons = Datagrid.SELECTOR_PAGING_PREVIOUS + ", " +
                                        Datagrid.SELECTOR_PAGING_NEXT +  ", " +
                                        Datagrid.SELECTOR_PAGING_FIRST + "," +
                                        Datagrid.SELECTOR_PAGING_LAST,
            page_number_input_hidden = datagrid_object.gcrud_container.find('.page-number-hidden');

        this.gcrud_container.find(selectors_for_all_buttons).click(function (event) {
            event.preventDefault();

            //If it is disabled then do nothing!
            if ($(this).hasClass(Datagrid.CLASS_DISABLED)) {
                return undefined;
            }

            if ($(this).hasClass(Datagrid.CLASS_PAGING_PREVIOUS)) {
                page_number_input_hidden.val(parseInt(page_number_input_hidden.val(), 10) - 1);
            } else if ($(this).hasClass(Datagrid.CLASS_PAGING_NEXT)) {
                page_number_input_hidden.val(parseInt(page_number_input_hidden.val(), 10) + 1);
            } else if ($(this).hasClass(Datagrid.CLASS_PAGING_FIRST)) {
                page_number_input_hidden.val('1');
            } else if ($(this).hasClass(Datagrid.CLASS_PAGING_LAST)) {
                page_number_input_hidden.val(datagrid_object.getMaxPaging());
            }

            datagrid_object.SearchAndOrderingTrigger();
        });

        this.gcrud_container.find('.page-number-input').change(function () {
            if (!isNaN(parseInt($(this).val(), 10))) {
                page_number_input_hidden.val(parseInt($(this).val(), 10));
            } else {
                page_number_input_hidden.val('1');
            }

            datagrid_object.SearchAndOrderingTrigger();
        });

    };

    Datagrid.prototype.listenerSelectRow = function () {
        var datagrid_object = this;

        datagrid_object.gcrud_container.find('.select-row').click(function () {
            if ($(this).is(':checked')) {
                $(this).closest('tr').addClass('warning');
            } else {
                $(this).closest('tr').removeClass('warning');
            }

            datagrid_object.hideShowDeleteButton();
        });
    };

    Datagrid.prototype.hideShowDeleteButton = function () {

        if (this.gcrud_container.find('.select-row:checked').length > 0) {
            this.gcrud_container.find('.delete-selected-button').removeClass('hidden');
        } else {
            this.gcrud_container.find('.delete-selected-button').addClass('hidden');
        }
    };

    Datagrid.prototype.calculationsBeforeDelete = function (total_deleted_records) {
        var datagrid_object = this,
            my_current_total = parseInt(datagrid_object.gcrud_container.find('.current-total-results').html(), 10),
            my_current_page_number = parseInt(datagrid_object.gcrud_container.find('.page-number-input').val(), 10),
            my_current_per_page = parseInt(datagrid_object.gcrud_container.find('.' + Datagrid.CLASS_PER_PAGE).val(), 10);

        //Check if we are at the last page and if after delete we will have an empty page.
        //If so, then the page will be at the first page
        if (my_current_page_number === datagrid_object.getMaxPaging() &&
                (my_current_total - total_deleted_records) <= ((datagrid_object.getMaxPaging() - 1) *  my_current_per_page)) {
            datagrid_object.gcrud_container.find('input[name="page_number"]').val(datagrid_object.getMaxPaging() - 1);
        }

        datagrid_object.gcrud_container.find('.full-total').html(
            (parseInt(datagrid_object.gcrud_container.find('.full-total').html(), 10) - total_deleted_records)
        );
    };


    Datagrid.prototype.listenerDeleteMultiple = function () {
        var datagrid_object = this;

        //Select All/None
        datagrid_object.gcrud_container.find('.select-all-none').click(function () {
            if ($(this).is(':checked')) {
                datagrid_object.gcrud_container.find('.select-row').each(function () {
                    $(this).prop("checked", true);
                    $(this).closest('tr').addClass('warning');
                });

            } else {
                datagrid_object.gcrud_container.find('.select-row').each(function () {
                    $(this).prop("checked", false);
                    $(this).closest('tr').removeClass('warning');
                });
            }

            datagrid_object.hideShowDeleteButton();
        });

        datagrid_object.gcrud_container.find('.delete-selected-button').click(function () {

            datagrid_object.gcrud_container.find('.delete-multiple-confirmation').modal();
            datagrid_object.gcrud_container.find('.delete-multiple-confirmation').on('hidden.bs.modal', function () {
                datagrid_object.gcrud_container.find('.delete-multiple-confirmation-button').unbind('click');
            });

            datagrid_object.gcrud_container.find('.delete-multiple-confirmation-button').click(function (event) {
                event.preventDefault();

                var my_modal = datagrid_object.gcrud_container.find('.delete-multiple-confirmation:first'),
                    my_current_total = parseInt(datagrid_object.gcrud_container.find('.current-total-results').html(), 10),
                    my_current_page_number = parseInt(datagrid_object.gcrud_container.find('.page-number-input').val(), 10),
                    my_current_per_page = parseInt(datagrid_object.gcrud_container.find('.' + Datagrid.CLASS_PER_PAGE).val(), 10),
                    delete_selected = [],
                    data_to_send;

                datagrid_object.gcrud_container.find('.select-row:checked').each(function () {
                    delete_selected.push($(this).data('id'));
                });

                datagrid_object.calculationsBeforeDelete(delete_selected.length);

                data_to_send = {
                    ids: delete_selected
                };

                if (datagrid_object.csrf_field !== null) {
                    data_to_send[datagrid_object.csrf_field.name] = datagrid_object.csrf_field.value;
                }

                $.ajax({
                    beforeSend: function () {
                        datagrid_object.gcrud_container.addClass(Datagrid.CLASS_LOADING);
                    },
                    error: function () {
                        datagrid_object.gcrud_container.removeClass(Datagrid.CLASS_LOADING);
                    },
                    data: data_to_send,
                    method: 'post',
                    dataType: 'json',
                    url: datagrid_object.gcrud_container.find('.delete-multiple-confirmation-button').data('target'),
                    success: function (output) {
                        if (output.success) {
                            $.growl(output.success_message, {
                                type: 'success',
                                delay: 10000,
                                animate: {
                                    enter: 'animated bounceInDown',
                                    exit: 'animated bounceOutUp'
                                }
                            });

                        }

                        my_modal.modal('hide');
                        datagrid_object.SearchAndOrderingTrigger();
                    }
                });
            });

        });
    };

    Datagrid.prototype.listenerDeleteRowClick = function ($delete_row_container) {
        var datagrid_object = this;

        if ($delete_row_container === undefined) {
            $delete_row_container = this.gcrud_container;
        }

        $delete_row_container.find('.delete-row').click(function (event) {
            event.preventDefault();

            var delete_row_button = $(this);

            datagrid_object.gcrud_container.find('.delete-confirmation-button').click(function (event) {
                event.preventDefault();

                var my_modal = datagrid_object.gcrud_container.find('.delete-confirmation:first');

                datagrid_object.calculationsBeforeDelete(1);

                $.ajax({
                    beforeSend: function () {
                        datagrid_object.gcrud_container.addClass(Datagrid.CLASS_LOADING);
                    },
                    error: function () {
                        datagrid_object.gcrud_container.removeClass(Datagrid.CLASS_LOADING);
                    },
                    url: delete_row_button.data('target'),
                    dataType: 'json',
                    success: function (output) {
                        if (output.success) {
                            $.growl(output.success_message, {
                                type: 'success',
                                delay: 10000,
                                animate: {
                                    enter: 'animated bounceInDown',
                                    exit: 'animated bounceOutUp'
                                }
                            });

                        }

                        my_modal.modal('hide');
                        datagrid_object.SearchAndOrderingTrigger();
                    }
                });
            });

            datagrid_object.gcrud_container.find('.delete-confirmation').modal();
            datagrid_object.gcrud_container.find('.delete-confirmation').on('hidden.bs.modal', function () {
                datagrid_object.gcrud_container.find('.delete-confirmation-button').unbind('click');
            });

        });
    };

    Datagrid.prototype.listenerLoadMoreButton = function () {
        var datagrid_object = this;

        datagrid_object.gcrud_container
            .find('table.grocery-crud-table>tbody .dropdown')
            .on('show.bs.dropdown', function () {
                datagrid_object.listenerDeleteRowClick($(this));
            });

        datagrid_object.gcrud_container.find('table.grocery-crud-table>tbody .dropdown').on('hide.bs.dropdown', function () {
            $(this).find('.delete-row').unbind('click');
        });
    };

    $.fn.datagrid = function (options) {
        var settings;

        settings = $.extend({
            default_per_page : 10
        }, options);

        this.each(function () {
            var datagrid = new Datagrid($(this), settings);

            datagrid.init();
        });

        return this;
    };
}(jQuery));