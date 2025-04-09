<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-gas-pump"></i> Araç Yakıt Raporu</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-secondary float-right ml-2">
            <i class="fas fa-arrow-left"></i> Yakıt Kayıtlarına Dön
        </a>
        <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['vehicle']->id; ?>" class="btn btn-info float-right">
            <i class="fas fa-truck"></i> Araç Detaylarına Dön
        </a>
    </div>
</div>

<div class="alert alert-info">
    <h4 class="alert-heading"><?php echo $data['vehicle']->plate_number; ?> - <?php echo $data['vehicle']->brand; ?> <?php echo $data['vehicle']->model; ?></h4>
    <p>Araç için toplam yakıt harcaması: <strong><?php echo number_format($data['totalCost'], 2, ',', '.'); ?> ₺</strong></p>
</div>

<!-- Grafik Alanı (İleride eklenebilir) -->
<div class="card mb-3">
    <div class="card-header">
        <h4>Yakıt Tüketim Grafiği</h4>
    </div>
    <div class="card-body">
        <p class="text-muted">Bu alanda ileri sürümlerde yakıt tüketim grafiği gösterilecektir.</p>
    </div>
</div>

<!-- Yakıt Kayıtları Listesi -->
<div class="card mb-3">
    <div class="card-header">
        <h4>Tüm Yakıt Kayıtları</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tarih</th>
                        <th>Sürücü</th>
                        <th>Yakıt Tipi</th>
                        <th>Miktar (Lt)</th>
                        <th>Tutar (TL)</th>
                        <th>Kilometre</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['fuelRecords'] as $fuel) : ?>
                        <tr>
                            <td><?php echo $fuel->id; ?></td>
                            <td><?php echo date('d.m.Y', strtotime($fuel->date)); ?></td>
                            <td>
                                <?php if($fuel->driver_id): ?>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $fuel->driver_id; ?>">
                                        <?php echo $fuel->driver_name; ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($fuel->fuel_type == 'Benzin'): ?>
                                    <span class="badge badge-danger">Benzin</span>
                                <?php elseif($fuel->fuel_type == 'Dizel'): ?>
                                    <span class="badge badge-warning">Dizel</span>
                                <?php elseif($fuel->fuel_type == 'LPG'): ?>
                                    <span class="badge badge-info">LPG</span>
                                <?php elseif($fuel->fuel_type == 'Elektrik'): ?>
                                    <span class="badge badge-success">Elektrik</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($fuel->amount, 2, ',', '.'); ?></td>
                            <td><?php echo number_format($fuel->cost, 2, ',', '.'); ?> ₺</td>
                            <td><?php echo number_format($fuel->km_reading, 0, ',', '.'); ?> km</td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/fuel/show/<?php echo $fuel->id; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/fuel/edit/<?php echo $fuel->id; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($data['fuelRecords'])): ?>
                        <tr>
                            <td colspan="8" class="text-center">Bu araç için henüz yakıt kaydı bulunmuyor</td>
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
                <h5><i class="fas fa-chart-bar"></i> Aylık Yakıt Tüketimi</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Bu alanda ileri sürümlerde aylık yakıt tüketim analizi gösterilecektir.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header">
                <h5><i class="fas fa-calculator"></i> Yakıt Tüketim Hesaplamaları</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Bu alanda ileri sürümlerde yakıt tüketim hesaplamaları gösterilecektir.</p>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ortalama 100 km'de tüketim
                        <span class="badge badge-primary badge-pill">--</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ortalama yakıt maliyeti (km başına)
                        <span class="badge badge-primary badge-pill">--</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Yakıt Kaydı Ekleme Butonu -->
<div class="text-center mt-3 mb-5">
    <a href="<?php echo URLROOT; ?>/fuel/add" class="btn btn-primary">
        <i class="fas fa-plus"></i> Yeni Yakıt Kaydı Ekle
    </a>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 