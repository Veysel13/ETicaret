const ajaxSelect2Tags = (context) => {

    const url = context.data('url');

    let dropdownParent = $(document.body);
    if (context.parents('.modal.in:first').length !== 0)
        dropdownParent = $(this).parents('.modal.in:first');

    context.select2({
        dropdownAutoWidth: true,
        width: '100%',
        tags: true,
        dropdownParent: dropdownParent,
        tokenSeparators: [','],
        createTag: function (params) {
            const term = $.trim(params.term);

            if (term === '') {
                return null;
            }

            return {
                id: term,
                text: term,
            }
        },
        ajax: {
            url,
            processResults: function (data) {
                return {
                    results: $.map(data.results, function (item) {
                        return {id: item.text, text: item.text};
                    })
                };
            },
            data: function (params) {
                var query = {
                    term: params.term,
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
        }
    });
}

const ajaxSelect2 = (context) => {

    const url = context.data('url');

    let dropdownParent = $(document.body);
    if (context.parents('.modal.in:first').length !== 0)
        dropdownParent = $(this).parents('.modal.in:first');

    context.select2({
        dropdownAutoWidth: true,
        width: '100%',
        allowClear: true,
        selectOnClose: false,
        closeOnSelect: false,
        dropdownParent: dropdownParent,
        ajax: {
            url,
            data: function (params) {
                var query = {
                    term: params.term,
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
        }
    });

    context.on('select2:unselecting', function (e) {
        context.on('select2:opening', function (e) {
            e.preventDefault();
            context.off('select2:opening');
        });
    });

    if (context.data('selectedid')) {
        $.ajax({
            method: "GET",
            url: context.data('url'),
            data: {
                id: context.data('selectedid')
            },
            success: function (r) {
                $.each(r.results, (i, e) => {
                    context.append(
                        $('<option/>', {
                            selected: true,
                            text: e.text,
                            value: e.id
                        })
                    );
                })

            }
        });
    }
}

const normalSelect2 = (context) => {
    context.select2({
        selectOnClose: false,
        closeOnSelect: false
    });
}

$('.ajaxSelect2Tags').each((i, e) => {
    ajaxSelect2Tags($(e));
});

$('.ajaxSelect2').each((i, e) => {
    ajaxSelect2($(e));
});

$('.normalSelect2').each((i, e) => {
    normalSelect2($(e));
});

const optionOrder = (context) => {

    const moveElementToEndOfParent = (element) => {
        const parent = element.parent();
        element.detach();
        parent.append(element);
    };

    context.parent().find("ul.select2-selection__rendered").children("li[title]").each(function (i, obj) {
        const element = context.find('option').filter(function () {
            return $(this).text() === obj.title;
        });
        moveElementToEndOfParent(element)
    });
}

const select2Sortable = (context) => {
    const rendered = context.parent().find('.select2-selection__rendered').sortable({
        containment: 'parent',
        update: function () {
            optionOrder(context);
        }
    });
}


$('.select2Sortable').each((i, e) => {
    select2Sortable($(e));
});

