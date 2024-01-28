const ctx = document.getElementById('myChart');

let chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [],
        datasets: [{
            label: '',
            data: [],
            borderWidth: 1
        }]
    },

    options: {
maintainAspectRatio: false,
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function clearChart() {
    chart.data.labels = [];
    for (dataset of chart.data.datasets){
        dataset.data = [];
        dataset.label = '';
    }
    chart.update();
    console.log('cleared chart!')
}

function getByTime() {
    let table = document.getElementById('table').value;
    let dimension = document.getElementById('time').value;
    let formData = new FormData();

    console.log(table);
    console.log(dimension);
    // return;
    formData.append('table', table);
    formData.append('dimension', dimension);

    clearChart();

    console.log(chart.data.datasets[0].data + ': datasets')
    console.log(chart.data.labels + ': labels')

    fetch(BASE + 'api/charts/time', {
        credentials: 'include',
        method: 'POST',
        body: formData
    }).then(result =>result.json()).then(data => {
        chart.data.datasets[0].label = data.label;
        console.log(data);

        switch (data.type) {
            case 'year':
                populateYear(data);
                break;
            case 'quarter':
                populateQuarter(data);
                break;
            case 'month':
                populateMonth(data);
                break;
            case 'week':
                populateWeek(data);
                break;
            default:
                document.getElementById('message').innerHTML = 'Doslo je do greske!';
                document.getElementById('myChart').classList.add('d-none');
        }

        chart.update();
    });


}

function chartRevenue() {
    let category = document.getElementById('category').value;
    let timeR = document.getElementById('timeR').value;
    let formData = new FormData();

    console.log(timeR);
    console.log(category);

    formData.append('category', category);
    formData.append('dimension', timeR);

    clearChart();

    // console.log(chart.data.datasets[0].data + ': datasets')
    // console.log(chart.data.labels + ': labels')

    fetch(BASE + 'api/charts/revenue', {
        credentials: 'include',
        method: 'POST',
        body: formData
    }).then(result =>result.json()).then(data => {
        chart.data.datasets[0].label = data.label;
        console.log(data);

        switch (data.type) {
            case 'year':
                populateYearRev(data);
                break;
            case 'quarter':
                populateQuarterRev(data);
                break;
            case 'month':
                populateMonthRev(data);
                break;
            case 'week':
                populateWeekRev(data);
                break;
            default:
                document.getElementById('message').innerHTML = 'Doslo je do greske!';
                document.getElementById('myChart').classList.add('d-none');
        }

        chart.update();
    });
}

function populateYear(data) {
    for(result of data.data){
        chart.data.labels.push(result.creation_year.toString());
        chart.data.datasets[0].data.push(result.record_count);
    }
}

function populateQuarter(data) {
    for(result of data.data){
        chart.data.labels.push('Q' + result.creation_quarter + '/' + 'Y' + result.creation_year.toString());
        chart.data.datasets[0].data.push(result.record_count);
    }
}

function populateMonth(data) {
    for(result of data.data){
        chart.data.labels.push('M' + result.creation_month + '/' + 'Y' + result.creation_year.toString());
        chart.data.datasets[0].data.push(result.record_count);
    }
}

function populateWeek(data) {
    for(result of data.data){
        let year = result.creation_yearweek.toString().substring(0, 4);
        let month = result.creation_yearweek.toString().substring(4, 6);

        chart.data.labels.push(month + 'W/' + year + 'Y');
        chart.data.datasets[0].data.push(result.record_count);
    }
}

function populateYearRev(data) {
    for(result of data.data){
        chart.data.labels.push(result.offer_year.toString());
        chart.data.datasets[0].data.push(result.sum_of_highest_offers);
    }
}

function populateQuarterRev(data) {
    for(result of data.data){
        chart.data.labels.push('Q' + result.offer_quarter + '/' + 'Y' + result.offer_year);
        chart.data.datasets[0].data.push(result.sum_of_highest_offers);
    }
}

function populateMonthRev(data) {
    for(result of data.data){
        chart.data.labels.push('M' + result.offer_month + '/' + 'Y' + result.offer_year);
        chart.data.datasets[0].data.push(result.sum_of_highest_offers);
    }
}

function populateWeekRev(data) {
    for(result of data.data){
        chart.data.labels.push(result.offer_week + 'W/' + result.offer_year + 'Y');
        chart.data.datasets[0].data.push(result.sum_of_highest_offers);
    }
}