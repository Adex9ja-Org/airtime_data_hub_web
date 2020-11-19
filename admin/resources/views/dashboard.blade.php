@extends('template')
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <h5 class="mb-3 mb-md-0">Welcome to Dashboard</h5>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Approved</h6>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <h5 class="mb-2">{{ number_format($data->approved, 0, '.', ',') }}</h5>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-success" href="/transaction/list/{{ \App\Model\RequestStatus::Approved }}">
                                            <span>Total Approved Transactions</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Pending</h6>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <h5 class="mb-2">{{ number_format($data->pending, 0, '.', ',') }}</h5>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-info" href="/transaction/list/{{ \App\Model\RequestStatus::Pending }}">
                                            <span>Total Pending Transactions</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Users</h6>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <h5 class="mb-2">{{ number_format($data->users, 0, '.', ',') }}</h5>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-primary" href="/users/list">
                                            <span>Total Registered Users</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Products</h6>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <h5 class="mb-2">{{ number_format($data->products, 0, '.', ',') }}</h5>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-primary" href="/product/list">
                                            <span>Total Available Products</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Feedbacks</h6>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <h5 class="mb-2">{{ number_format($data->feedbacks, 0, '.', ',') }}</h5>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-success" href="/messages/list">
                                            <span>Total Number of FeedBacks</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Total Balance</h6>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <h5 class="mb-2">{{ '₦ ' . number_format($data->wallet_balance, 0, '.', ',') }}</h5>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-info" href="/wallet/balance/list">
                                            <span>Available Wallet Balance</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <h5 class="mb-3 mb-md-0">Sales Insight</h5>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">

                @foreach($insight as $item)
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mb-2">{{ number_format($item->counter) }}</h5>
                                        <h5 class="mb-2">{{ '₦ '. number_format($item->total) }}</h5>
                                        <div class="d-flex align-items-baseline">
                                            <a class="text-success" href="/transaction/query/service/{{ $item->service_id }}">
                                                <span>{{ $item->service_name }}</span>
                                                <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach



            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <h5 class="mb-3 mb-md-0">Daily Sales</h5>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Sales Count</h6>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <h5 class="mb-2">{{ number_format($sales->sales_count, 0) }}</h5>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <a class="text-info" href="/transaction/query/today/{{ \App\Model\RequestStatus::Approved }}">
                                        <span>Today's Total Sales</span>
                                        <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Sales Amount</h6>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <h5 class="mb-2">{{ '₦ ' . number_format($sales->sales_amount, 0) }}</h5>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <a class="text-primary" href="/transaction/query/today/{{ \App\Model\RequestStatus::Approved }}">
                                        <span>Today's Sales Sum Total</span>
                                        <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <h5 class="mb-3 mb-md-0">Monthly Transaction Stat</h5>
    </div>


    <div class="row">
        <div class="col-md-6 stretch-card">
            <div class="card">
                <div class="card-body">
                    <canvas id="reportChart2"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 stretch-card">
            <div class="card">
                <div class="card-body">
                    <canvas id="reportChart"></canvas>
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
            @foreach ($reportGraphData as $data)
            {!!  '"'. $data->months . '",' !!}
            @endforeach
        ];
        let reportValue = [
            @foreach ($reportGraphData as $data)
            {!!  '"'.$data->value  . '",' !!}
            @endforeach
        ];




        new Chart(document.getElementById("reportChart").getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: reportLabel,
                datasets: [{
                    data: reportValue,
                    backgroundColor: border,
                    hoverBackgroundColor: backgrounds
                }]
            },
            options: {
                responsive: true
            }
        });
        new Chart(document.getElementById("reportChart2").getContext('2d'), {
            type: 'bar',
            data: {
                labels: reportLabel,
                datasets: [{
                    label: 'Transaction',
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


    </script>
@endsection
