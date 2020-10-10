//////////////////////////////


function initializePickers() {


    ///////////  date picker part

    var date = makeDateObject();
    date.setDate(date.getDate() - 1);
    if ($('.date-picker').length > 0) {
        $('.date-picker').appendDtpicker({
            "closeOnSelected": true,
            "dateOnly": false,
            "locale": "cn"
        });
        // $('.date-picker').handleDtpicker('setDate', date);
        $('.input-group-addon').on('click', function () {
            $(this).prev().focus();
        });
    }


    ///////////  date range picker part

    var date = makeDateObject();
    date.setDate(date.getDate() - 1);
    if ($('.date-range-selector').length > 0) {
        var date1 = makeDateObject($('input[name="range_from"]').val());
        var date2 = makeDateObject($('input[name="range_to"]').val());
        date2 = makeDateObject(date2.setDate(date2.getDate() - 1));
        if(date2 > makeDateObject()) date2 = makeDateObject();
        $('.date-range-selector input:last-child').val(
            makeDateString(date1, 'cn') + ' - ' + makeDateString(date2, 'cn')
        );
        $('.date-range-selector input:last-child').dateRangePicker({
            language: "cn",
            endDate: makeDateString(makeDateObject()),
            beforeShowDay: function(t)
            {
                var valid = !isHoliday(t);  //disable saturday and sunday
                var _class = '';
                var _tooltip = valid ? '' : '';
                return [valid,_class,_tooltip];
            }
        }).bind('datepicker-change', function (e, obj) {
            var that = $(this);
            that.parent().find('input[name="range_from"]').val(makeDateString(obj.date1) + ' 00:00:00');
            var toDate = makeDateObject(obj.date2.setDate(obj.date2.getDate() + 1));
            that.parent().find('input[name="range_to"]').val(makeDateString(toDate) + ' 00:00:00');
        }).prev().on('mousedown', function () {
            console.log('date range picker clicked');
            $(this).next().focus();
            // $(this).next().click();
        });

        // $('.date-picker').handleDtpicker('setDate', date);
        // $('.input-group-addon').on('click', function () {
        //     //
        // });
    }


////////// month range picker part

    if ($('.range-selector input:last-child').length > 0) {
        var date1 = makeDateObject();
        var date2 = makeDateObject();
        if (_filterInfo.range_from) {
            date1 = makeDateObject(_filterInfo.range_from);
        }
        if (_filterInfo.range_to) {
            date2 = makeDateObject(_filterInfo.range_to);
            date2 = new Date(date2.setMonth(date2.getMonth() - 1))
        }
        var option = [
            [date1.getMonth() + 1, date1.getFullYear()],
            [date2.getMonth() + 1, date2.getFullYear()]
        ];
        $('.range-selector input:last-child').rangePicker({
            minDate: [12, 2019], maxDate: [12, 2050], RTL: false,
            setDate: option
        }).on('datePicker.done', function (e, result) {
            var range_from = new Date(result[0][1], result[0][0] - 1);
            var range_to = new Date(result[1][1], result[1][0] - 1);
            range_to = new Date(range_to.setMonth(range_to.getMonth() + 1));
            $('.range-selector input[name="range_from"]').val(
                range_from.getFullYear() + '-' +
                makeNDigit(range_from.getMonth() + 1) + '-' +
                makeNDigit(range_from.getDate()) + ' 00:00:00'
            );
            $('.range-selector input[name="range_to"]').val(
                range_to.getFullYear() + '-' +
                makeNDigit(range_to.getMonth() + 1) + '-' +
                makeNDigit(range_to.getDate()) + ' 00:00:00'
            );
            // subscribe to the "done" event after user had selected a date
            //         if (result instanceof Array)
            //             console.log(makeDateObject(result[0][1], result[0][0] - 1), makeDateObject(result[1][1], result[1][0] - 1));
            //         else
            //             console.log(result);

        }).prev().on('click', function () {
            $(this).next().click();
        });
        // $('.range-selector input').val('')
    }

////////// month picker part

    $('.monthpicker').MonthPicker({
        MinMonth: '2020-01',
        MaxMonth: '+1m' // Or you could just pass 18.
    });
    $('.monthpicker').on('click', function () {
        $('#MonthPicker_Button_')[0].click();
    });

    $('input[name="search_keyword"]').on('keypress', function (e) {
        if(e.which == 13 || e.keyCode == 13){
            $(this).parent().find('.fa-search').parent().click();
        }
    })

}
