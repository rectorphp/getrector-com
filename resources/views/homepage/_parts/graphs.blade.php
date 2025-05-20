<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row">
    <div class="col-12 col-md-8 text-center">
        <div style="width: 100%; margin: 0 auto; height: 22em; background: #FFF" class="me-5 p-4 border-line">
            <canvas id="myChart"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('myChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [10000, 100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 100000],
                    datasets: [
                        {
                            label: 'In-house team',
                            data: [1500, 2600, 4300, 6500, 8400, 9400, 9800, 9900, 10000, 10000, 10000],
                            // data: [1500, 2100, 3000, 4300, 6100, 7800, 9100, 9700, 9900, 10000, 10000],
                            borderColor: 'rgb(255, 0, 0)',
                            backgroundColor: 'rgba(255, 0, 0, 0.2)'
                        },
                        {
                            label: 'Rector team',
                            data: [300, 440, 540, 610, 670, 720, 770, 820, 860, 900, 940],
                            borderColor: 'rgb(0, 128, 0)',
                            backgroundColor: 'rgba(0, 128, 0, 0.2)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Hours per Upgrade',
                            font: {
                                size: 26
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        "tooltip": {
                            "enabled": false
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'LINES OF CODE',
                                font: {
                                    size: 18
                                }
                            },
                            min: 10000,
                            max: 1000000,
                            ticks: {
                                stepSize: 200000
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'HOURS',
                                font: {
                                    size: 18
                                }
                            },
                            min: 0,
                            max: 10000,
                            ticks: {
                                stepSize: 2000
                            }
                        }
                    }
                }
            });
        </script>
    </div>

    <div class="col-12 col-md-4 mt-4 mt-md-0">
        <div class="text-bigger">
            <p>
                90% of problems you'll face are&nbsp;new to you.
            </p>
            <p>
                We've already seen them and&nbsp;know exactly how to solve them
                cost-effectively
                and quickly.
            </p>

            <p>
                How does typical <a
                    href="{{ action(\App\Controller\CodebaseRenovationController::class) }} ">codebase
                    renovation</a> look like?
            </p>
        </div>
    </div>
</div>
