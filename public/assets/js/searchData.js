const brandSelect2= () => {
    $('.selectBrand').select2({
        placeholder: 'Select Brand',
        width: 400,
        ajax: {
            url: URL.BRANDS,
            data: function (params) {
                return {
                    term: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.brands, function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        }
                    })
                };
            },
        }
    });
}

brandSelect2();

$('.selectBrand').on('click',function (){
    brandSelect2();
});

const productSelect2= () => {
    $('.selectProduct').select2({
        placeholder: 'Select Product',
        width: 400,
        ajax: {
            url: URL.PRODUCTS,
            data: function (params) {
                return {
                    term: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.products, function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        }
                    })
                };
            },
        }
    });
}

productSelect2();

const companySelect2= () => {
    $('.selectCompany').select2({
        placeholder: 'Select Company',
        width: 400,
        ajax: {
            url: URL.COMPANIES,
            data: function (params) {
                return {
                    term: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.companies, function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        }
                    })
                };
            },
        }
    });
}

companySelect2();

const storeSelect2= () => {
    $('.selectStore').select2({
        placeholder: 'Select Store',
        width: 400,
        ajax: {
            url: URL.STORES,
            data: function (params) {
                return {
                    term: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.stores, function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        }
                    })
                };
            },
        }
    });
}

storeSelect2();

const userSelect2= () => {
    $('.selectUser').select2({
        placeholder: 'Select User',
        width: 400,
        ajax: {
            url: URL.USERS,
            data: function (params) {
                return {
                    term: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.users, function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        }
                    })
                };
            },
        }
    });
}

userSelect2();

$('.selectUser').on('click',function (){
    userSelect2();
});

const productTaskSelect2= () => {
    $('.selectTask').select2({
        placeholder: 'Select Task',
        width: 400,
        ajax: {
            url: URL.PRODUCTTASKS,
            data: function (params) {
                return {
                    term: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.tasks, function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        }
                    })
                };
            },
        }
    });
}

productTaskSelect2();



