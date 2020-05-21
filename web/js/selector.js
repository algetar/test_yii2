$('.selector').on('change',function () {
    let str = '', checked = $(".selector:checked");

    checked.each(function (ind, element) {
        if (str === '') {
            str = $(element).prop('value');
        } else {
            str += ',' + $(element).prop('value');
        }
    });

    $('#choice-ids').prop('value', str);
});
