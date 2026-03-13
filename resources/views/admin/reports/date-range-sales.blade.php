@extends('layouts.base')
@section('title', 'Date Range Sales Report')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-serif" style="color: var(--pastry-brown);">Date Range Sales Report</h2>
            <a href="{{ route('admin.reports.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
        </div>

        <!-- Date Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.date-range-sales') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label d-block">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-pastry">Apply Filter</button>
                                <a href="{{ route('admin.reports.date-range-sales') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <canvas id="dateRangeSalesChart" width="400" height="150"></canvas>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    Sales Data 
                    @if($startDate && $endDate)
                        ({{ \Carbon\Carbon::parse($startDate)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M j, Y') }})
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($salesData->isEmpty())
                    <p class="text-muted text-center py-4">No sales data found for the selected date range.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Total Sales</th>
                                    <th>Number of Orders</th>
                                    <th>Average Order Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesData as $data)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($data->date)->format('M j, Y') }}</td>
                                        <td>₱{{ number_format($data->total_sales, 2) }}</td>
                                        <td>{{ $data->order_count }}</td>
                                        <td>₱{{ number_format($data->order_count > 0 ? $data->total_sales / $data->order_count : 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th>Total</th>
                                    <th>₱{{ number_format($salesData->sum('total_sales'), 2) }}</th>
                                    <th>{{ $salesData->sum('order_count') }}</th>
                                    <th>₱{{ number_format($salesData->sum('order_count') > 0 ? $salesData->sum('total_sales') / $salesData->sum('order_count') : 0, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dateRangeSalesChart').getContext('2d');
        
        const chartData = @json($salesData);
        
        if (chartData.length > 0) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map(item => new Date(item.date).toLocaleDateString()),
                    datasets: [{
                        label: 'Daily Sales (₱)',
                        data: chartData.map(item => item.total_sales),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
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
        } else {
            ctx.font = '16px Arial';
            ctx.fillStyle = '#666';
            ctx.textAlign = 'center';
            ctx.fillText('No data available for the selected date range', ctx.canvas.width / 2, ctx.canvas.height / 2);
        }
    });
    </script>
    @endpush
@endsection
