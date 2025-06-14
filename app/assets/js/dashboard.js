document.addEventListener('DOMContentLoaded', () => {
    const totalUsers = window.usersStats.total;
    const onlineUsers = window.usersStats.online;
    const onlinePercent = ((onlineUsers / totalUsers) * 100).toFixed(1);

    const ctx = document.getElementById('usersChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                window.texts.totalUsers + ` (${totalUsers})`,
                window.texts.onlineUsers + ` (${onlineUsers}, ${onlinePercent}%)`
            ],
            datasets: [{
                label: window.texts.quantity,
                data: [totalUsers, onlineUsers],
                backgroundColor: [
                    'rgb(38,53,96, 0.7)',
                    'rgb(229,191,31, 0.7)'
                ],
                borderColor: [
                    'rgb(38,53,96, 1)',
                    'rgb(229,191,31, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: {
                title: {
                    display: true,
                    text: window.texts.usersStats
                },
                legend: {
                    display: false
                },
                datalabels: {
                    color: '#000',
                    anchor: 'end',
                    align: 'right',
                    formatter: (value, context) => {
                        if (context.dataIndex === 1) {
                            return `${value} (${onlinePercent}%)`;
                        }
                        return value;
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: window.texts.quantity
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    function renderPieChart(canvasId, dataObj, titleText) {
        const ctx = document.getElementById(canvasId)?.getContext('2d');
        if (!ctx || !dataObj || Object.keys(dataObj).length === 0) return;

        const labels = Object.keys(dataObj);
        const data = Object.values(dataObj);

        const backgroundColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#C9CBCF', '#8FBC8F',
            '#DC143C', '#00CED1'
        ];

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: titleText,
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                return `${label}: ${value}`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: titleText
                    }
                }
            }
        });
    }

    renderPieChart('usersByCountry', window.usersByCountries, window.texts.usersByCountries);
    renderPieChart('usersByRegion', window.usersByRegions, window.texts.usersByRegions);
    renderPieChart('usersByCity', window.usersByCities, window.texts.usersByCities);

});