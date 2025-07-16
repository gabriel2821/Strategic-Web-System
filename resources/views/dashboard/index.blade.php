@extends('layouts.app')
@section('title')
@section('content')
    <div class="container mt-4">
        <h1>Dashboard</h1>

        <!-- Filter by Teras -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label for="filter_teras" class="form-label fw-bold">Pilih Teras:</label>
                </div>
                <div class="col-auto">
                    <select name="filter_teras" id="filter_teras" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Teras</option>
                        @foreach ($allTerasNames as $name)
                            <option value="{{ $name }}" {{ request('filter_teras') === $name ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <!-- Status Table -->
        @foreach ($tableData as $data)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Teras Status Summary untuk {{ $data['name'] }}</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Bilangan</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($statuses as $status)
                                <tr>
                                    <td>{{ $status }}</td>
                                    <td>{{ $data[$status] ?? 0 }}</td>
                                    <td>{{ $data[$status . '_percent'] ?? 0 }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <!-- Teras Pie Charts -->
        <div class="row">
            @foreach ($terasChartData as $terasName => $chartData)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Status for {{ $terasName }}</h5>
                            <canvas id="chart-{{ str_replace(' ', '-', strtolower($terasName)) }}"></canvas>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Overall Pie Chart -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Overall Status Summary</h5>
                <canvas id="overall-chart"></canvas>
            </div>
        </div>

        <!-- Overall Bar Chart -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Overall Status by Teras</h5>
                <canvas id="overall-bar-chart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Custom plugin to display percentage on pie charts
        const getOrCreateTooltip = (chart) => {
            let tooltipEl = chart.canvas.parentNode.querySelector('div');
            if (!tooltipEl) {
                tooltipEl = document.createElement('div');
                tooltipEl.style.background = 'rgba(0, 0, 0, 0.7)';
                tooltipEl.style.borderRadius = '3px';
                tooltipEl.style.color = 'white';
                tooltipEl.style.opacity = 1;
                tooltipEl.style.pointerEvents = 'none';
                tooltipEl.style.position = 'absolute';
                tooltipEl.style.transform = 'translate(-50%, 0)';
                tooltipEl.style.transition = 'all .1s ease';
                chart.canvas.parentNode.appendChild(tooltipEl);
            }
            return tooltipEl;
        };

        // Teras Pie Charts
        @foreach ($terasChartData as $terasName => $chartData)
            new Chart(document.getElementById('chart-{{ str_replace(' ', '-', strtolower($terasName)) }}'), {
                type: 'pie',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        data: @json($chartData['data']),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Status Distribution' },
                        datalabels: {
                            formatter: function(value, context) {
                                const data = context.chart.data.datasets[0].data;
                                const total = data.reduce((sum, val) => sum + val, 0);
                                const percentage = ((value / total) * 100).toFixed(2);
                                return percentage + '%';
                            },
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 30
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        @endforeach

        // Overall Pie Chart
        new Chart(document.getElementById('overall-chart'), {
            type: 'pie',
            data: {
                labels: @json($overallChartData['labels']),
                datasets: [{
                    data: @json($overallChartData['data']),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Overall Status Distribution' },
                    datalabels: {
                        formatter: function(value, context) {
                            const data = context.chart.data.datasets[0].data;
                            const total = data.reduce((sum, val) => sum + val, 0);
                            const percentage = ((value / total) * 100).toFixed(2);
                            return percentage + '%';
                        },
                        color: '#000',
                        font: {
                            weight: 'bold',
                            color: '#fff',
                            size: 30
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Overall Bar Chart
        new Chart(document.getElementById('overall-bar-chart'), {
            type: 'bar',
            data: {
                labels: @json($barChartLabels),
                datasets: @json($barChartDatasets)
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Number of Program Rows' }
                    },
                    x: {
                        title: { display: true, text: 'Teras' }
                    }
                },
                plugins: {
                    legend: { display: true },
                    title: { display: true, text: 'Overall Status by Teras' },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function(value) {
                            return value;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endsection