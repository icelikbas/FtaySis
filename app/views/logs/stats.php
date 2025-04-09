<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-chart-bar mr-2"></i>Log İstatistikleri</h2>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT; ?>/logs" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Loglara Dön
            </a>
        </div>
    </div>

    <?php flash('log_message'); ?>

    <div class="row">
        <!-- Genel İstatistikler -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Genel İstatistikler</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-4">
                        <div class="text-center">
                            <h1 class="display-4 font-weight-bold"><?php echo $data['totalLogs']; ?></h1>
                            <p class="lead">Toplam Log Kaydı</p>
                        </div>
                    </div>
                    
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Giriş Logları
                            <span class="badge badge-success badge-pill"><?php echo $data['loginLogs']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Çıkış Logları
                            <span class="badge badge-info badge-pill"><?php echo $data['logoutLogs']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Oluşturma Logları
                            <span class="badge badge-primary badge-pill"><?php echo $data['createLogs']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Güncelleme Logları
                            <span class="badge badge-warning badge-pill"><?php echo $data['updateLogs']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Silme Logları
                            <span class="badge badge-danger badge-pill"><?php echo $data['deleteLogs']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Hata Logları
                            <span class="badge badge-danger badge-pill"><?php echo $data['errorLogs']; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Grafik -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie mr-2"></i> Log Dağılımı</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px">
                        <canvas id="logPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kullanıcı İstatistikleri -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-users mr-2"></i> Kullanıcı Bazlı Log İstatistikleri</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($data['userLogs'])) : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kullanıcı</th>
                                        <th>Log Sayısı</th>
                                        <th>Yüzde</th>
                                        <th>Grafik</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['userLogs'] as $userId => $userLog) : ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/logs/user/<?php echo $userId; ?>">
                                                    <?php echo $userLog['name']; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $userLog['count']; ?></td>
                                            <td>
                                                <?php 
                                                    $percent = ($data['totalLogs'] > 0) ? 
                                                        round(($userLog['count'] / $data['totalLogs']) * 100, 2) : 
                                                        0;
                                                    echo $percent . '%';
                                                ?>
                                            </td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" 
                                                         role="progressbar" 
                                                         style="width: <?php echo $percent; ?>%" 
                                                         aria-valuenow="<?php echo $percent; ?>" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        <?php echo $percent; ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> Henüz hiçbir kullanıcı log kaydı oluşturmamış.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Kütüphanesi -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pie Chart Verileri
        const logTypes = ['Giriş', 'Çıkış', 'Oluşturma', 'Güncelleme', 'Silme', 'Hata'];
        const logCounts = [
            <?php echo $data['loginLogs']; ?>,
            <?php echo $data['logoutLogs']; ?>,
            <?php echo $data['createLogs']; ?>,
            <?php echo $data['updateLogs']; ?>,
            <?php echo $data['deleteLogs']; ?>,
            <?php echo $data['errorLogs']; ?>
        ];
        const backgroundColors = [
            'rgba(40, 167, 69, 0.7)',  // success
            'rgba(23, 162, 184, 0.7)',  // info
            'rgba(0, 123, 255, 0.7)',   // primary
            'rgba(255, 193, 7, 0.7)',   // warning
            'rgba(220, 53, 69, 0.7)',   // danger
            'rgba(108, 117, 125, 0.7)'  // secondary
        ];
        
        // Log Pie Chart
        const ctxPie = document.getElementById('logPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: logTypes,
                datasets: [{
                    data: logCounts,
                    backgroundColor: backgroundColors,
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 