<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('.form-floating .select2-container--default .select2-selection--single, .form-floating .select2-container--default .select2-selection--multiple').css({
            'padding-top': '1.435rem',
            'padding-bottom': '0.625rem',
            'background-color': '#f8fafc',
            'border-color':'#dee2e6'
        });
        $('#fill-tab-1').click(function () {
            listMetrics();
        });
        $('#metricsForm').submit(function (event) {
            event.preventDefault();
            getMetrics();
        });
        // Language change according to locale
        let langUrl = '{{ app()->getLocale() }}' === 'es' ? "{{ asset('lang/vendor/datatables/es.json') }}" : '';
        $('#historyMetricsTable').DataTable({language: {url:langUrl}});
    });

    function getMetrics() {
        var url        = $('#url').val();
        var categories = $('#category').val();
        var strategy   = $('#strategy').val();
        $('#metricCards').empty();
        $('#metricCards').append('<div class="col-md-2 mt-2 mb-2"><div class="card d-flex align-items-center"><div class="loader m-4"></div></div></div>');
        var optionsChart = {
            rotation:-90,
            circumference: 180,
            cutout:'60%'
        };

        $.ajax({
            url: '{!! route("get-metrics") !!}',
            cache: true,
            type: 'GET',
            dataType: 'json',
            data: {
                '_token'    : '{{ csrf_token() }}',
                'url'       : url,
                'categories': categories,
                'strategy'  : strategy
            },
            success: function (response) {
                $('#metricCards').empty();

                // Iterate on response data and generate cards
                $.each(response, function (title, score) {
                    score     = Math.round(score * 100);
                    remaining = Math.round(100 - score);
                    var cardHtml = `
                        <div class="hover col-md-2 mt-2 mb-2">
                            <div class="card">
                                <div class="card-body text-center">
                                    <canvas id="chart-categoria-${title}"></canvas>
                                    <h1 class="card-text">${score}</h1>
                                    <h5 class="card-title">${title}</h5>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#metricCards').append(cardHtml);

                    var ctx = document.getElementById('chart-categoria-'+title).getContext('2d');
                    var data = {
                        datasets: [{
                            data: [score, remaining],
                            backgroundColor: ['#ff6384', '#ffffff']
                        }]
                    };

                    var myDoughnutChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: data,
                        options: optionsChart
                    });
                });
                metrics = {
                    'url': url,
                    'strategy': strategy,
                    'categories': response,
                }
                localStorage.setItem('metrics', JSON.stringify(metrics));
                $('#metricCards').append('<div><button id="saveMetrics" onclick="saveMetrics()" class="btn btn-primary btn-sm">{{ __("Save metric run")}}</button></div>');
                toastr.success('{{ __("Metrics gets successfully")}}', response);
            },
            error: function (xhr, status, error) {
                toastr.error('{{ __("Error gets metrics")}}: ',error);
            }
        });
    };

    function listMetrics() {
        $.ajax({
            url: '{!! route("list-history-metrics") !!}',
            type: 'GET',
            dataType: 'json',
            data: {
                '_token' : '{{ csrf_token() }}',
            },
            success: function (response) {
                var table = $('#historyMetricsTable').DataTable();
                table.clear().draw();

                if (response.length > 0) {
                    // Iterate over the response data and generate table rows
                    $.each(response, function (index, data) {
                        table.row.add([
                            data.url,
                            data.accessibility_metric,
                            data.pwa_metric,
                            data.seo_metric,
                            data.performance_metric,
                            data.best_practices_metric,
                            data.strategy_id,
                            data.created_at
                        ]).draw();
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr, status, error);
            }
        });
    }

    function saveMetrics() {
        var metrics = JSON.parse(localStorage.getItem('metrics'));

        $.ajax({
            url: '{!! route("save-metrics") !!}',
            type: 'POST',
            dataType: 'json',
            data: {
                '_token': '{{ csrf_token() }}',
                'url'       : metrics.url,
                'strategy'  : metrics.strategy,
                'categories': metrics.categories
            },
            success: function (response) {
                toastr.success('Metrics saved successfully', response);

                localStorage.removeItem('metrics');
            },
            error: function (xhr, status, error) {
                toastr.error('Error saving metrics: ',error);
            }
        });
    }
</script>
