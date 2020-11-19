@extends('template')
@section('content')
    <div class="row">
        <div class="col-md-12 stretch-card">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transaction Summary</h4>
                </div>
                <div class="card-body">
                    <canvas id="reportChart2"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6 stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Approval Status</h4>
                </div>
                <div class="card-body">
                    <canvas id="reportChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payment Channel</h4>
                </div>
                <div class="card-body">
                    <canvas id="reportChart3"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top Selling Products</h4>
                </div>
                <div class="card-body">
                    <canvas id="reportChart4"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top Buyers</h4>
                </div>
                <div class="card-body">
                    <canvas id="reportChart5"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        let backgrounds =  [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255,239,13,0.2)',
            'rgba(1,56,157,0.2)',
            'rgba(10,255,128,0.2)',
            'rgba(184,183,192,0.2)',
            'rgba(245,255,228,0.2)',
            'rgba(255,118,168,0.2)'
        ];
        let border = [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgb(101,157,138)',
            'rgb(132,215,235)',
            'rgb(240,83,255)',
            'rgb(18,16,192)',
            'rgb(255,49,254)',
            'rgb(23,250,255)'
        ];

        let reportLabel = [
            @foreach ($transTrend as $data)
            {!!  '"'. $data->months . '",' !!}
            @endforeach
        ];
        let reportValue = [
            @foreach ($transTrend as $data)
            {!!  '"'.$data->value  . '",' !!}
            @endforeach
        ];

        let approvalLabel = [
            @foreach ($approvalStatus as $data)
            {!!  '"'. $data->label . '",' !!}
            @endforeach
        ];
        let approvalValue = [
            @foreach ($approvalStatus as $data)
            {!!  '"'.$data->value  . '",' !!}
            @endforeach
        ];

        let channelLabel = [
            @foreach ($channels as $data)
            {!!  '"'. $data->label . '",' !!}
            @endforeach
        ];
        let channelValue = [
            @foreach ($channels as $data)
            {!!  '"'.$data->value  . '",' !!}
            @endforeach
        ];

        let topSellingLabel = [
            @foreach ($topSelling as $data)
            {!!  '"'. $data->label . '",' !!}
            @endforeach
        ];
        let topSellingValue = [
            @foreach ($topSelling as $data)
            {!!  '"'.$data->value  . '",' !!}
            @endforeach
        ];

        let topBuyersLabel = [
            @foreach ($topBuyers as $data)
            {!!  '"'. $data->label . '",' !!}
            @endforeach
        ];
        let topBuyersValue = [
            @foreach ($topBuyers as $data)
            {!!  '"'.$data->value  . '",' !!}
            @endforeach
        ];

        new Chart(document.getElementById("reportChart2").getContext('2d'), {
            type: 'bar',
            data: {
                labels: reportLabel,
                datasets: [{
                    label: 'Transactions',
                    data: reportValue,
                    backgroundColor:backgrounds,
                    borderColor: border,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        new Chart(document.getElementById("reportChart4").getContext('2d'), {
            type: 'bar',
            data: {
                labels: topSellingLabel,
                datasets: [{
                    label: 'Top Selling',
                    data: topSellingValue,
                    backgroundColor:backgrounds,
                    borderColor: border,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        new Chart(document.getElementById("reportChart5").getContext('2d'), {
            type: 'bar',
            data: {
                labels: topBuyersLabel,
                datasets: [{
                    label: 'Top Buyers',
                    data: topBuyersValue,
                    backgroundColor:backgrounds,
                    borderColor: border,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        new Chart(document.getElementById("reportChart").getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: approvalLabel,
                datasets: [{
                    data: approvalValue,
                    label: "Approval Status",
                    backgroundColor: border,
                    hoverBackgroundColor: backgrounds
                }]
            },
            options: {
                responsive: true
            }
        });
        new Chart(document.getElementById("reportChart3").getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: channelLabel,
                datasets: [{
                    data: channelValue,
                    label: "Transaction Channel",
                    backgroundColor: border,
                    hoverBackgroundColor: backgrounds
                }]
            },
            options: {
                responsive: true
            }
        });



    </script>
@endsection
