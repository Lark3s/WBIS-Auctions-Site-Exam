var sortOpt = document.getElementById('sort');
var orderOpt = document.getElementById('order');
var option;

for (var i=0; i<sortOpt.options.length; i++) {
    option = sortOpt.options[i];

    if (option.value == SORT) {
        option.setAttribute('selected', true);
    }
}

for (var i=0; i<orderOpt.options.length; i++) {
    option = orderOpt.options[i];

    if (option.value == ORDER) {
        option.setAttribute('selected', true);
    }
}

function sort() {
    var baseUrl = BASE + 'user/profile/analytics/tables/' + TABLE + '/page/1/'
    var sortOptVal = document.getElementById('sort').value;
    var orderOptVal = document.getElementById('order').value;

    console.log(sortOptVal);
    console.log(orderOptVal);

    var fullUrl = baseUrl + orderOptVal + '/' + sortOptVal;

    window.location.href = fullUrl;
}