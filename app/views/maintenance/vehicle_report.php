<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><i class="fas fa-file-alt mr-2"></i><?php echo $data['vehicle']->plate_number; ?> Bakım Raporu</h2>
            <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary float-right ml-2">Tüm Bakım Kayıtları</a>
            <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['vehicle']->id; ?>" class="btn btn-primary float-right">Araca Dön</a>
        </div>
    </div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="alert alert-info">
    <h4 class="alert-heading"><?php echo $data['vehicle']->plate_number; ?> - <?php echo $data['vehicle']->brand; ?> <?php echo $data['vehicle']->model; ?></h4>
    <p>Araç için toplam bakım maliyeti: <strong><?php echo number_format($data['totalCost'], 2, ',', '.'); ?> ₺</strong></p>
</div>

<!-- Grafik Alanı (İleride eklenebilir) -->
<div class="card mb-3">
    <div class="card-header">
        <h4>Bakım Maliyeti Dağılımı</h4>
    </div>
    <div class="card-body">
        <p class="text-muted">Bu alanda ileri sürümlerde bakım maliyeti dağılım grafiği gösterilecektir.</p>
    </div>
</div>

<!-- Bakım Kayıtları Listesi -->
<div class="card mb-3">
    <div class="card-header">
        <h4>Tüm Bakım Kayıtları</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Bakım Türü</th>
                        <th>Açıklama</th>
                        <th>Başlangıç</th>
                        <th>Bitiş</th>
                        <th>Maliyet</th>
                        <th>Kilometre</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['maintenanceRecords'] as $record) : ?>
                        <tr>
                            <td><?php echo $record->id; ?></td>
                            <td>
                                <?php if($record->maintenance_type == 'Periyodik Bakım'): ?>
                                    <span class="badge badge-primary"><?php echo $record->maintenance_type; ?></span>
                                <?php elseif($record->maintenance_type == 'Arıza'): ?>
                                    <span class="badge badge-danger"><?php echo $record->maintenance_type; ?></span>
                                <?php elseif($record->maintenance_type == 'Lastik Değişimi'): ?>
                                    <span class="badge badge-warning"><?php echo $record->maintenance_type; ?></span>
                                <?php elseif($record->maintenance_type == 'Yağ Değişimi'): ?>
                                    <span class="badge badge-info"><?php echo $record->maintenance_type; ?></span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo $record->maintenance_type; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo substr($record->description, 0, 30); ?>...</td>
                            <td><?php echo date('d.m.Y', strtotime($record->start_date)); ?></td>
                            <td>
                                <?php if($record->end_date): ?>
                                    <?php echo date('d.m.Y', strtotime($record->end_date)); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($record->cost, 2, ',', '.'); ?> ₺</td>
                            <td><?php echo number_format($record->km_reading, 0, ',', '.'); ?> km</td>
                            <td>
                                <?php if($record->status == 'Planlandı'): ?>
                                    <span class="badge badge-info">Planlandı</span>
                                <?php elseif($record->status == 'Devam Ediyor'): ?>
                                    <span class="badge badge-warning">Devam Ediyor</span>
                                <?php elseif($record->status == 'Tamamlandı'): ?>
                                    <span class="badge badge-success">Tamamlandı</span>
                                <?php elseif($record->status == 'İptal'): ?>
                                    <span class="badge badge-danger">İptal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $record->id; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/maintenance/edit/<?php echo $record->id; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($data['maintenanceRecords'])): ?>
                        <tr>
                            <td colspan="9" class="text-center">Bu araç için henüz bakım kaydı bulunmuyor</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Hızlı İstatistikler -->
<div class="row">
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar"></i> Bakım Türüne Göre Dağılım</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Bu alanda ileri sürümlerde bakım türüne göre dağılım analizi gösterilecektir.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header">
                <h5><i class="fas fa-calculator"></i> Bakım Periyodu Hesaplamaları</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Bu alanda ileri sürümlerde bakım periyodu hesaplamaları gösterilecektir.</p>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ortalama bakım periyodu
                        <span class="badge badge-primary badge-pill">--</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ortalama bakım maliyeti (bakım başına)
                        <span class="badge badge-primary badge-pill">--</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Bakım Kaydı Ekleme Butonu -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card bg-light">
            <div class="card-body">
                <p class="mb-0">Bu araç için yeni bir bakım kaydı eklemek için aşağıdaki butona tıklayın:</p>
                <a href="<?php echo URLROOT; ?>/maintenance/add" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Yeni Bakım Kaydı
                </a>
            </div>
        </div>
    </div>
</div>

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 