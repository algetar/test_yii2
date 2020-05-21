$('.selector').on('click',function () {
    /* ограничение количества выбранных элементов */
    let max = +$('#choice-limit').prop('value');
    let checked = $(".selector:checked");

    if (0 === max) {
        return;
    }
    if (checked.length < max) { // если выбранных меньше max
        $('.selector').prop("disabled", false); // то разблокирую
    }

    if (checked.length === max) { // если выбраны max чекбоксов
        $('.selector').prop("disabled", true); // сначала блокирую все
        $('.selector:checked').prop("disabled", false); // а потом разблокирую выбранные
    }
});
