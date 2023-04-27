


@extends('backend.layout.default')
@push('header')

@endpush
@section('content')

    <div class='row'>
        <div class='col-md-6 mb30'>
            <div class='card'>
                <div class="card-top">
                    <h4 class="card-title">Line Chart</h4>
                </div>
                <div class='card-content'>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div><!--/col-->
        <div class='col-md-6 mb30'>
            <div class='card'>
                <div class="card-top">
                    <h4 class="card-title">Bar Chart</h4>
                </div>
                <div class='card-content'>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div><!--/col-->
        <div class='col-md-6 mb30'>
            <div class='card'>
                <div class="card-top">
                    <h4 class="card-title">Polar Chart</h4>
                </div>
                <div class='card-content'>
                    <canvas id="polarChart" height="140"></canvas>
                </div>
            </div>
        </div><!--/col-->
        <div class='col-md-6 mb30'>
            <div class='card'>
                <div class="card-top">
                    <h4 class="card-title">Pie Chart</h4>
                </div>
                <div class='card-content'>
                    <canvas id="doughnutChart" height="140"></canvas>
                </div>
            </div>
        </div><!--/col-->
        <div class='col-md-6 mb30'>
            <div class='card'>
                <div class="card-top">
                    <h4 class="card-title">Radar Chart</h4>
                </div>
                <div class='card-content'>
                    <canvas id="radarChart" height="250"></canvas>
                </div>
            </div>
        </div><!--/col-->
    </div>

@endsection

@push('footer')
    <script src="{{asset('assets/bower_components/chart.js/dist/chart.min.js')}}"></script>
    <script src="{{asset('assets/js/chartjs.init.js')}}"></script>
@endpush
