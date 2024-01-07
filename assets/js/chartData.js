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
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function getByYear() {
    let variable = 'user';
    let formData = new FormData();
    formData.append('user', variable);

    fetch(BASE + 'api/charts/year', {
        credentials: 'include',
        method: 'POST',
        body: formData
    }).then(result =>result.json()).then(data => {
        console.log(data);
        for(result of data.data){
            console.log(result);
            chart.data.labels.push(result.creation_year.toString());
            chart.data.datasets[0].data.push(result.record_count);
        }
        chart.data.datasets[0].label = '# of new users by years';

        chart.update();
    });
}