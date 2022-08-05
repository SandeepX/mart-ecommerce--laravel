function round(value, exp) {
    if (typeof exp === 'undefined' || +exp === 0)
        return Math.round(value);

    value = +value;
    exp = +exp;

    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
        return NaN;

    // Shift
    value = value.toString().split('e');
    value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}

function sum (selector) {
    var sum = 0;
    $(selector).each(function() {
        sum += Number($(this).val());
    });
    return round(sum,2);
}


function percentage_value(percent,num)
{
    var value  = (percent/100);
    return (value * num);
}


function percentageFromValue(amount , total_value) {
    if(amount == '' || amount == 0){
        return 0;
    }
    if(amount == total_value){
        return 100;
    }
    return (round(((amount * 100 / total_value)) , 2));
}



