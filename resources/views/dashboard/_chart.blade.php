@if(isset($chartData))
<div class="card mt-3">
    <div class="card-header">Volume Surat 12 Bulan Terakhir</div>
    <div class="card-body">
        <canvas id="chartVolume" height="100"></canvas>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chartVolume'), {
    type: 'line',
    data: {
        labels: @json($chartData['labels']),
        datasets: [
            { label: 'Surat Masuk', data: @json($chartData['smData']), borderColor: '#0d6efd', tension: 0.3, fill: false },
            { label: 'Surat Keluar', data: @json($chartData['skData']), borderColor: '#198754', tension: 0.3, fill: false },
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endpush
@endif
