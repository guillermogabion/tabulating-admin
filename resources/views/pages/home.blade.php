@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Welcome {{ explode(' ', auth()->user()->name)[0] }}</h3>
                        <!-- <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-12 grid-margin transparent">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-lg-0 stretch-card transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">Total Users</p>
                                <p class="fs-30 mb-2">{{$total_user}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 mb-lg-0 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="mb-4">Total Event</p>
                                <p class="fs-30 mb-2">{{ $total_event }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 mb-lg-0 stretch-card transparent">
                        <div class="card card-light-blue">
                            <div class="card-body">
                                <p class="mb-4">Number of Participants</p>
                                <p class="fs-30 mb-2">{{ $total_participant }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 h-[100] grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="event" class="mb-2">Select Event:</label>
                            <select id="addEventId" name="eventId" class="form-select" required onchange="fetchChartData()">
                                <option value="">Select an Event</option>
                                @foreach ($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="category" class="mb-2">Select Category:</label>
                            <select id="addCategoryId" name="categoryId" class="form-select" required onchange="fetchChartData()">
                                <option value="">Select a Category</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="sales-chart-legend" class="chartjs-legend mt-4 mb-2"></div>
                    <canvas id="result-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024. @GabionDev</span>
        </div>
    </footer>
    <!-- partial -->
</div>
<!-- main-panel ends -->

<script>
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }


    let resultChart;

    function fetchChartData() {
        const eventId = document.getElementById('addEventId').value;
        const categoryId = document.getElementById('addCategoryId').value;

        if (eventId || categoryId) {
            $.get('/fetch-category-data', {
                event_id: eventId,
                category_id: categoryId
            }).done(function(response) {
                console.log('AJAX Response:', response);

                const results = response.results;
                const chartLabels = results.labels;
                const chartData = results.data;

                updateChart(chartLabels, chartData);
            }).fail(function(error) {
                console.error('Error fetching data:', error);
            });
        }
    }

    function updateChart(labels, data) {
        const ctx = document.getElementById('result-chart').getContext('2d');

        if (window.resultChart) {
            window.resultChart.destroy();
        }

        window.resultChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Results',
                    data: data,
                    backgroundColor: data.map(() => getRandomColor()),
                    borderWidth: 1,
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                        position: 'top'
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            color: "#6C7383"
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: "#6C7383",
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initial chart rendering (if you have default data)
        const results = @json($results);
        const chartLabels = results.labels;
        const chartData = results.data;

        updateChart(chartLabels, chartData);
    });
</script>


@endsection