@extends('layouts.base')
@section('title', 'Monthly Sales Report')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-serif" style="color: var(--pastry-brown);">Monthly Sales Report</h2>
            <div>
                <form method="GET" action="{{ route('admin.reports.monthly-sales') }}" class="d-inline">
                    <select name="year" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.reports.dashboard') }}" class="btn btn-outline-secondary ms-2">← Back</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <canvas id="monthlySalesChart" width="400" height="150"></canvas>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Monthly Sales Data for {{ $selectedYear }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total Sales</th>
                                <th>Number of Orders</th>
                                <th>Average Order Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlySales as $data)
                                <tr>
                                    <td>{{ $data['month_name'] }}</td>
                                    <td>₱{{ number_format($data['total_sales'], 2) }}</td>
                                    <td>{{ $data['order_count'] }}</td>
                                    <td>₱{{ number_format($data['order_count'] > 0 ? $data['total_sales'] / $data['order_count'] : 0, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th>Total</th>
                                <th>₱{{ number_format($monthlySales->sum('total_sales'), 2) }}</th>
                                <th>{{ $monthlySales->sum('order_count') }}</th>
                                <th>₱{{ number_format($monthlySales->sum('order_count') > 0 ? $monthlySales->sum('total_sales') / $monthlySales->sum('order_count') : 0, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('monthlySalesChart').getContext('2d');
        
        const chartData = @json($monthlySales);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.month_name),
                datasets: [{
                    label: 'Total Sales (₱)',
                    data: chartData.map(item => item.total_sales),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Sales: ₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
    @endpush
@endsection
