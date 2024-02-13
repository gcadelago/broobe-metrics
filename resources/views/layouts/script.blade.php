<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(document).ready(function () {
        // Select2 initialization
        $('.select2').select2({placeholder: ''});
        // Event click on metrics history tab
        $('#fill-tab-1').click(function () {
            listMetrics();
        });
        // Metrics form submission event
        $('#metricsForm').submit(function (event) {
            event.preventDefault();
            getMetrics();
        });
        // Language change according to locale
        let langUrl = '{{ app()->getLocale() }}' === 'es' ? "{{ asset('lang/vendor/datatables/es.json') }}" : '';
        $('#historyMetricsTable').DataTable({language: {url:langUrl}});
    });

    /**
     * Options for the doughnut chart.
     */
    let optionsChart = {
        rotation:-90,
        circumference: 180,
        cutout:'60%'
    };

    /**
     * Get metrics data from the server based on filters.
     */
    function getMetrics() {
        var url        = $('#url').val();
        var categories = $('#category').val();
        var strategy   = $('#strategy').val();
        loadingMetrics();
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
                stopLoading();

                // Iterate on response data and generate cards
                $.each(response, function (title, score) {
                    addCardMetrics(title, score, optionsChart);
                });
                metrics = {
                    'url': url,
                    'strategy': strategy,
                    'categories': response,
                }
                localStorage.setItem('metrics', JSON.stringify(metrics));
                addSaveMetrics();
                toastr.success('{{ __("Metrics gets successfully")}}');
            },
            error: function (xhr, status, error) {
                stopLoading();
                toastr.error('{{ __("Error gets metrics")}}: '+error);
            }
        });
    };

    /**
     * Lists metrics retrieved from the server into a DataTable.
     */
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
                toastr.error('{{ __("Error listing metrics")}}: '+error);
            }
        });
    }

    /**
     * Saves metrics data to the server.
     */
    function saveMetrics() {
        var metrics = JSON.parse(localStorage.getItem('metrics'));
        console.log({
                '_token': '{{ csrf_token() }}',
                'url'       : metrics.url,
                'strategy'  : metrics.strategy,
                'categories': metrics.categories
            });
        disableSaveMetrics();
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
                toastr.success('Metrics saved successfully');

                localStorage.removeItem('metrics');
            },
            error: function (xhr, status, error) {
                enableSaveMetrics();
                toastr.error('Error saving metrics: '+error);
            }
        });
    }

    /**
     * Displays a loading indicator while searching for metrics.
     */
    function loadingMetrics(){
        $('#metricCards').empty();
        $('#metricCards').append('<div class="col-md-2 mt-2 mb-2"><div class="card d-flex align-items-center"><div class="loader m-4"></div></div></div>');
        $('#searchMetrics').prop('disabled', true);
    }

    /**
     * Stops the loading indicator and re-enables the search button.
     */
    function stopLoading(){
        $('#metricCards').empty();
        $('#searchMetrics').prop('disabled', false);
    }

    /**
     * Adds a button to save the metrics run.
     */
    function addSaveMetrics(){
        $('#metricCards').append('<div class="justify-content-center"><button id="saveMetrics" onclick="saveMetrics()" class="button btn btn-outline-primary btn-sm m-1" id="saveMetrics">{{ __("Save metric run")}}</button></div>');
    }

    /**
     * Disables the save metrics button.
     */
    function disableSaveMetrics(){
        $('#saveMetrics').prop('disabled', true);
    }

    /**
     * Enables the save metrics button.
     */
    function enableSaveMetrics(){
        $('#saveMetrics').prop('disabled', true);
    }

    /**
     * Adds a card displaying metric information.
     *
     * @param {string} title - The title of the metric.
     * @param {number} score - The score of the metric.
     */
    function addCardMetrics(title, score){
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

        score     = Math.round(score * 100);
        remaining = Math.round(100 - score);
        var ctx   = document.getElementById('chart-categoria-'+title).getContext('2d');
        var data  = {
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
    }
</script>
