@if(isset($chartData))
<div class="card mt-3">
    <div class="card-header d-flex align-items-center gap-2"><x-icon name="graph-up" /> Volume Surat 12 Bulan Terakhir</div>
    <div class="card-body">
        <canvas id="chartVolume" height="100" role="img"
                aria-label="Grafik garis volume surat masuk dan surat keluar per bulan selama 12 bulan terakhir"></canvas>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new window.Chart(document.getElementById('chartVolume'), {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                { label: 'Surat Masuk', data: @json($chartData['smData']), borderColor: '#0F172A', backgroundColor: '#0F172A', tension: 0.3, fill: false, pointStyle: 'circle' },
                { label: 'Surat Keluar', data: @json($chartData['skData']), borderColor: '#0369A1', backgroundColor: '#0369A1', borderDash: [6, 4], tension: 0.3, fill: false, pointStyle: 'rect' },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush
@endif
