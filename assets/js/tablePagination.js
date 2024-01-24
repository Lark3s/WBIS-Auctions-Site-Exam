const qinputs = document.querySelectorAll('#page-number');
const allowedkeys = ["Backspace", "Delete", "ArrowLeft", "ArrowRight"];

console.log(ORDER);
console.log(SORT);

qinputs.forEach(qinput => {
    qinput.addEventListener('keydown', function handleClick(event) {
        pressedKey = event['key'];
        if (!allowedkeys.includes(pressedKey) && (isNaN(pressedKey) || (this.value.length > 1))) {
            event.preventDefault();
        }
    });
});

function jumpToPage() {
    let pageNumber = document.getElementById('page-number').value;

    //TODO add filter
    window.location.href = BASE + 'user/profile/analytics/tables/'+ TABLE +'/page/' + pageNumber + '/' + ORDER + '/' + SORT;
}

function first() {
    window.location.href = BASE + 'user/profile/analytics/tables/'+ TABLE +'/page/1' + '/' + ORDER + '/' + SORT;
}

function last() {
    window.location.href = BASE + 'user/profile/analytics/tables/'+ TABLE +'/page/' + TOTAL + '/' + ORDER + '/' + SORT;
}

function next() {
    if (CURRENT < TOTAL) {
        window.location.href = BASE + 'user/profile/analytics/tables/'+ TABLE +'/page/' + (CURRENT+1) + '/' + ORDER + '/' + SORT;
    }
    return;
}

function prev() {
    if (CURRENT > 1) {
        window.location.href = BASE + 'user/profile/analytics/tables/'+ TABLE +'/page/' + (CURRENT-1) + '/' + ORDER + '/' + SORT;
    }
    return;
}