<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><i class="fas fa-chart-bar mr-2"></i>Bakım Analizi</h2>
            <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Geri Dön
            </a>
        </div>
    </div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<!-- Analiz Özeti -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Toplam Bakım Maliyeti</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($data['totalCost'], 2, ',', '.'); ?> ₺
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Tamamlanan Bakımlar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['completedCount']; ?> Adet
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Devam Eden Bakımlar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['inProgressCount']; ?> Adet
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-spinner fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Planlanan Bakımlar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $data['plannedCount']; ?> Adet
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bakım Türü ve Maliyet Analizi -->
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Bakım Türlerine Göre Maliyet Dağılımı</h4>
            </div>
            <div class="card-body">
                <?php if(!empty($data['costAnalysis'])): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Bakım Türü</th>
                                    <th>Bakım Sayısı</th>
                                    <th>Toplam Maliyet</th>
                                    <th>Ort. Maliyet</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['costAnalysis'] as $analysis): ?>
                                    <tr>
                                        <td>
                                            <?php if($analysis->maintenance_type == 'Periyodik Bakım'): ?>
                                                <span class="badge badge-primary"><?php echo $analysis->maintenance_type; ?></span>
                                            <?php elseif($analysis->maintenance_type == 'Arıza'): ?>
                                                <span class="badge badge-danger"><?php echo $analysis->maintenance_type; ?></span>
                                            <?php elseif($analysis->maintenance_type == 'Lastik Değişimi'): ?>
                                                <span class="badge badge-warning"><?php echo $analysis->maintenance_type; ?></span>
                                            <?php elseif($analysis->maintenance_type == 'Yağ Değişimi'): ?>
                                                <span class="badge badge-info"><?php echo $analysis->maintenance_type; ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><?php echo $analysis->maintenance_type; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $analysis->count; ?></td>
                                        <td><?php echo number_format($analysis->total_cost, 2, ',', '.'); ?> ₺</td>
                                        <td><?php echo number_format($analysis->total_cost / $analysis->count, 2, ',', '.'); ?> ₺</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center">Henüz analiz için yeterli veri bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Bakım Durumu Dağılımı</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-container" style="position: relative; height:200px;">
                            <p class="text-muted text-center">Bu alanda ileri sürümlerde bakım durumu dağılım grafiği gösterilecektir.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-4">
                            <h6 class="mb-2">Tamamlandı</h6>
                            <div class="progress mb-4">
                                <?php
                                    $total = $data['completedCount'] + $data['inProgressCount'] + $data['plannedCount'] + $data['canceledCount'];
                                    $completedPercentage = ($total > 0) ? ($data['completedCount'] / $total) * 100 : 0;
                                ?>
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $completedPercentage; ?>%" aria-valuenow="<?php echo $completedPercentage; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($completedPercentage); ?>%</div>
                            </div>
                            
                            <h6 class="mb-2">Devam Ediyor</h6>
                            <div class="progress mb-4">
                                <?php
                                    $inProgressPercentage = ($total > 0) ? ($data['inProgressCount'] / $total) * 100 : 0;
                                ?>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $inProgressPercentage; ?>%" aria-valuenow="<?php echo $inProgressPercentage; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($inProgressPercentage); ?>%</div>
                            </div>
                            
                            <h6 class="mb-2">Planlandı</h6>
                            <div class="progress mb-4">
                                <?php
                                    $plannedPercentage = ($total > 0) ? ($data['plannedCount'] / $total) * 100 : 0;
                                ?>
                                <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $plannedPercentage; ?>%" aria-valuenow="<?php echo $plannedPercentage; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($plannedPercentage); ?>%</div>
                            </div>
                            
                            <h6 class="mb-2">İptal</h6>
                            <div class="progress mb-4">
                                <?php
                                    $canceledPercentage = ($total > 0) ? ($data['canceledCount'] / $total) * 100 : 0;
                                ?>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $canceledPercentage; ?>%" aria-valuenow="<?php echo $canceledPercentage; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($canceledPercentage); ?>%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gelecek Bakımlar ve Öneriler -->
<div class="card mb-4">
    <div class="card-header">
        <h4>Yaklaşan Bakımlar</h4>
    </div>
    <div class="card-body">
        <p class="text-muted">Bu alanda ileri sürümlerde yaklaşan bakımlar ve bakım önerileri gösterilecektir.</p>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 