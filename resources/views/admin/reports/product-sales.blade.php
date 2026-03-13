@extends('layouts.base')
@section('title', 'Product Sales Distribution')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-serif" style="color: var(--pastry-brown);">Product Sales Distribution</h2>
            <a href="{{ route('admin.reports.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <canvas id="productSalesChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Summary</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Total Sales:</strong> ₱{{ number_format($totalSales, 2) }}</p>
                        <p><strong>Products Sold:</strong> {{ $productSales->count() }}</p>
                        <p><strong>Total Quantity:</strong> {{ $productSales->sum('total_quantity') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Product Sales Details</h5>
            </div>
            <div class="card-body">
                @if($productSales->isEmpty())
                    <p class="text-muted text-center py-4">No sales data available.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total Sales</th>
                                    <th>Quantity Sold</th>
                                    <th>Orders</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productSales as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>₱{{ number_format($product->total_sales, 2) }}</td>
                                        <td>{{ $product->total_quantity }}</td>
                                        <td>{{ $product->order_count }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ $product->percentage }}%; background-color: {{ $loop->index % 2 == 0 ? '#007bff' : '#28a745' }};">
                                                        {{ number_format($product->percentage, 1) }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th>Total</th>
                                    <th>₱{{ number_format($totalSales, 2) }}</th>
                                    <th>{{ $productSales->sum('total_quantity') }}</th>
                                    <th>{{ $productSales->sum('order_count') }}</th>
                                    <th>100%</th>
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
        const ctx = document.getElementById('productSalesChart').getContext('2d');
        
        const chartData = @json($productSales);
        
        if (chartData.length > 0) {
            // Generate colors for the pie chart
            const colors = [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384',
                '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
            ];
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.map(item => item.name),
                    datasets: [{
                        data: chartData.map(item => item.total_sales),
                        backgroundColor: colors.slice(0, chartData.length),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = '₱' + context.parsed.toLocaleString();
                                    const percentage = context.parsed + '%';
                                    return [label, value, percentage];
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
            ctx.fillText('No sales data available', ctx.canvas.width / 2, ctx.canvas.height / 2);
        }
    });
    </script>
    @endpush
@endsection
