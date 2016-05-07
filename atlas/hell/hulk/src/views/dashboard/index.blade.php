@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')

<section class="panel panel-default">
    @include('EcdoHulk::layouts.tabs.dashboard')
    <br />

    <div id="concernCount">
        <script>
            $(document).ready(function() {
                var concernCount = JSON.parse('{{$concernCount}}');
                
                $('#concernCount').highcharts({
                    chart: {
                        type: 'spline'
                    },

                    title: {
                        text: '用户关注变化'
                    },

                    subtitle: {
                        text: '展示最近7天数据'
                    },

                    xAxis: {
                        categories: concernCount.date
                    },

                    yAxis: {
                        title: {
                            text: '关注量 (人)'
                        }
                    },

                    tooltip: {
                        crosshairs: true,
                        shared: true
                    },

                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },

                            enableMouseTracking: false
                        }
                    },

                    series: [
                        {
                            name: '新增关注',
                            data: concernCount.follow
                        }, 

                        {
                            name: '取消关注',
                            data: concernCount.unfollow
                        },

                        {
                            name: '净增关注',
                            data: concernCount.net_growth
                        }
                    ]
                });
            });
        </script>
    </div>
</section>

<script src="{{asset('assets/universe/dist/highcharts/js/highcharts.js')}}"></script>
<script src="{{{ asset('assets/universe/dist/highcharts/js/modules/exporting.js') }}}"></script>
@stop
