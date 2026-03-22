@extends('layouts.admin')
@section('title', 'Yearly Sales Report')
@section('content')
    @include('layouts.flash-messages')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="font-serif" style="color: var(--pastry-brown);">Yearly Sales Report</h2>
        <a href="{{ route('admin.reports.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>

        <div class="card">
            <div class="card-body">
                <canvas id="yearlySalesChart" width="400" height="150"></canvas>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Yearly Sales Data</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Total Sales</th>
                                <th>Number of Orders</th>
                                <th>Average Order Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($yearlySales as $data)
                                <tr>
                                    <td>{{ $data->year }}</td>
                                    <td>₱{{ number_format($data->total_sales, 2) }}</td>
                                    <td>{{ $data->order_count }}</td>
                                    <td>₱{{ number_format($data->order_count > 0 ? $data->total_sales / $data->order_count : 0, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('yearlySalesChart').getContext('2d');
        
        const chartData = @json($yearlySales);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(item => item.year),
                datasets: [{
                    label: 'Total Sales (₱)',
                    data: chartData.map(item => item.total_sales),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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
    });
    </script>
    @endpush
@endsection
