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
});