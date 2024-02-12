<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#fill-tab-1').click(function () {
            listMetrics();
        });
        $('#metricsForm').submit(function (event) {
            event.preventDefault();
            getMetrics();
        });

        function getMetrics() {
            var url        = $('#url').val();
            var categories = $('#category').val();
            var strategy   = $('#strategy').val();

            // Create the data object to send in the AJAX request
            var data = {
                url: url,
                categories: categories,
                strategy: strategy
            };

            $.ajax({
                url: '{!! route("get-metrics") !!}',
                cache: true,
                type: 'POST',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'url': url,
                    'categories': categories,
                    'strategy': strategy
                },
                success: function (response) {
                    // Clear the cards container before adding new cards
                    $('#metricCards').empty();

                    // Iterate on response data and generate cards
                    $.each(response, function (title, score) {
                        var cardHtml = `
                            <div class="col-md-2 mb-2">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">${title}</h5>
                                        <h2 class="card-text">${score}</h2>
                                    </div>
                                </div>
                            </div>
                        `;
                        // Add the card to the container
                        $('#metricCards').append(cardHtml);
                    });
                    $('#metricCards').append('<div><button class="btn btn-primary btn-sm">Save metric run</button></div>');
                },
                error: function (xhr, status, error) {
                    console.log(xhr, status, error);
                }
            });
        };

        function listMetrics() {
            var url = $('#url').val();
            var categories = $('#category').val();
            var strategy = $('#strategy').val();

            $.ajax({
                url: '{!! route("list-history-metrics") !!}',
                type: 'GET',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    // Clear the table container before adding new rows
                    $('#historyMetrics').empty();

                    if (response.length > 0) {
                        // Iterate over the response data and generate table rows
                        $.each(response, function (index, data) {
                            var rowHtml = `
                                <tr>
                                    <td>${data.url}</td>
                                    <td>${data.accessibility_metric}</td>
                                    <td>${data.pwa_metric}</td>
                                    <td>${data.seo_metric}</td>
                                    <td>${data.best_practices_metric}</td>
                                    <td>${data.strategy_id}</td>
                                    <td>${data.created_at}</td>
                                </tr>
                            `;
                            // Add the row to the table
                            $('#historyMetrics').append(rowHtml);
                        });
                    } else {
                        // If no data was found, display a message in the table.
                        $('#historyMetrics').html('<tr><td colspan="2">No se encontraron datos históricos</td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr, status, error);
                }
            });
        }
    });
</script>
