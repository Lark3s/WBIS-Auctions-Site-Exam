const qinputs = document.querySelectorAll('#page-number');
const allowedkeys = ["Backspace", "Delete", "ArrowLeft", "ArrowRight"];

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
    window.location.href = BASE + 'category/' + CATEGORY + '/page/' + pageNumber;
}

function first() {
    window.location.href = BASE + 'category/' + CATEGORY + '/page/1';
}

function last() {
    window.location.href = BASE + 'category/' + CATEGORY + '/page/' + TOTAL;
}

function next() {
    if (CURRENT < TOTAL) {
        window.location.href = BASE + 'category/' + CATEGORY + '/page/' + (CURRENT+1);
    }
    return;
}

function prev() {
    if (CURRENT < TOTAL && CURRENT > 1) {
        window.location.href = BASE + 'category/' + CATEGORY + '/page/' + (CURRENT-1);
    }
    return;
}
