@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 table-responsive">
                <table class="table" id="stock-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Stock</th>
                            <th>Current Price</th>
                            <th>Previous Price</th>
                            <th>Change</th>
                            <th>Change %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocks as $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="symbol">{{ $stock->symbol }}</td>
                                <td class="current-price">0</td>
                                <td class="previous-price">0</td>
                                <td>
                                    <span class="fw-bold change">0</span>
                                </td>
                                <td>
                                    <span class="fw-bold change-percent">0</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let StockReporter = class {
            constructor() {
                this.init();
            }

            async init() {
                document.addEventListener("DOMContentLoaded", () => {
                    this.fetchReports();
                    // fetch reports every 60 seconds
                    setInterval(() => this.fetchReports(), 60000);
                });
            }

            async fetchReports() {
                const rows = document.querySelectorAll("#stock-table tbody tr");
                rows.forEach(async row => {
                    const symbol = row.querySelector(".symbol").textContent;
                    const current_price = row.querySelector(".current-price");
                    const previous_price = row.querySelector(".previous-price");
                    const change = row.querySelector(".change");
                    const change_percent = row.querySelector(".change-percent");
                    try {
                        const response = await fetch("{{ url('/api/report') }}/" + symbol);

                        if (response.ok && response.status === 200) {
                            const data = await response.json();
                            // Update table cells with fetched data
                            current_price.textContent = data.current.close;
                            previous_price.textContent = data.previous.close;
                            change.textContent = data.change;
                            change_percent.textContent = data.change_percent;

                            // Add arrow class based on change value
                            if (data.change < 0) {
                                change.classList.add('text-danger');
                                change.classList.remove('text-success');
                                change.innerHTML = `<i class="bi bi-arrow-down"></i> ${data.change}`;
                            } else {
                                change.classList.add('text-success');
                                change.classList.remove('text-danger');
                                change.innerHTML = `<i class="bi bi-arrow-up"></i> ${data.change}`;
                            }
                            // Change percent color based on value
                            if (data.change_percent < 0) {
                                change_percent.classList.add('text-danger');
                                change_percent.classList.remove('text-success');
                                change_percent.innerHTML = `<i class="bi bi-arrow-down"></i> ${data.change_percent}`;
                            } else {
                                change_percent.classList.add('text-success');
                                change_percent.classList.remove('text-danger');
                                change_percent.innerHTML = `<i class="bi bi-arrow-up"></i> ${data.change_percent}`;
                            }


                        } else {
                            // Handle non-404 errors here if needed
                            return;
                        }
                    } catch (error) {
                        // Ignore 404 errors and handle other errors if needed
                        if (error.response && error.response.status == 404) {
                            return; // Ignore 404 errors
                        }
                    }
                });
            }
        }

        // Initialize the StockReporter class
        new StockReporter();
    </script>
@endsection
