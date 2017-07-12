(function ($) {
    'use strict';

    /**
     * Wires the queried element(s) and its contained search-filter elements
     * to the given data table.
     *
     * @function tablePressExtColumnFilters
     * @param {DataTable} dt The data table instance.
     * @public
     */
    $.fn.tablePressExtColumnFilters = function (dt) {
        return this.each(function () {
            var $this = $(this);
            var columnId = parseInt($this.data('column') || '-1', 10);
            var filters = [];

            if (! $.fn.dataTable) {
                return;
            }

            $.fn.dataTable.ext.search.push(
                function (settings, data) {
                    var node = dt.table().node();
                    if (! node) {
                         return true;
                    }

                    // ensure we're only searching the given data table
                    if (settings.sTableId !== node.id) {
                        return true;
                    }

                    // no search term specified means "search all"
                    if (!filters.length) {
                        return true;
                    }

                    // paranoia mode: check if search column even exists
                    if (typeof data[columnId] === 'undefined') {
                        return true;
                    }

                    // assume that the data in the targeted column is comma-separated.
                    // slice and dice it into an array, then do an intersection with
                    // the selected filters.
                    // filter out any rows that don't match exactly.
                    var terms = $.map(data[columnId].split(','), $.trim);
                    var matches = terms.filter(function (n) {
                        return filters.indexOf(n) !== -1;
                    });
                    return (matches.length === filters.length);
                }
            );

            // sets up click event delegation for any contained filter elements
            $this.on('click', '[data-filter]', function () {
                var f = $(this).data('filter').trim();
                // reset all filters if given filter is blank
                if ('' === f) {
                    filters = [];
                    $("[data-filter]", $this).each(function (index, element) {
                        $(element).removeClass('active');
                    });
                } else { // keep track of given filter value
                    var idx = filters.indexOf(f);
                    if (-1 !== idx) {
                        filters.splice(idx, 1);
                    } else {
                        filters.push(f);
                    }
                    $(this).toggleClass('active');
                }

                // Finally, check if any filters are active.
                // If so, then enable any filter reset elements, otherwise disable them.
                if (filters.length) {
                    $("[data-filter='']", $this).each(function (index, element) {
                        $(element).removeAttr('disabled');
                    });
                } else {
                    $("[data-filter='']", $this).each(function (index, element) {
                        var $elem = $(element);
                        if (! $elem.attr('disabled')) {
                            $elem.attr('disabled', 'disabled');
                        }
                    });
                }

                dt.draw(); // always re-draw the data table to trigger filtering.
            });
        });
    };
})(jQuery);
