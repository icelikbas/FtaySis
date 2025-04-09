<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-gas-pump"></i> Yakıt Kaydı Detayı</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-light float-right">
            <i class="fa fa-backward"></i> Geri
        </a>
    </div>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light">
            <!-- Araç ve Sürücü Bilgileri -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="mb-3"><i class="fas fa-car"></i> Araç ve Sürücü Bilgileri</h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Araç:</label>
                        <div class="form-control bg-white">
                            <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['record']->vehicle_id; ?>" class="text-decoration-none">
                                <i class="fas fa-truck"></i> <?php echo $data['record']->plate_number; ?> - <?php echo $data['record']->brand . ' ' . $data['record']->model; ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Sürücü:</label>
                        <div class="form-control bg-white">
                            <?php if(!empty($data['record']->driver_id)): ?>
                                <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $data['record']->driver_id; ?>" class="text-decoration-none">
                                    <i class="fas fa-user"></i> <?php echo $data['record']->driver_name; ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted"><i class="fas fa-user-slash"></i> Belirtilmemiş</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Yakıt Dağıtan Personel:</label>
                        <div class="form-control bg-white">
                            <?php if(!empty($data['record']->dispenser_id)): ?>
                                <i class="fas fa-user-tag"></i> 
                                <?php echo isset($data['record']->dispenser_name) ? $data['record']->dispenser_name : 'Personel #' . $data['record']->dispenser_id; ?>
                            <?php else: ?>
                                <span class="text-muted"><i class="fas fa-user-slash"></i> Belirtilmemiş</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Kayıt Sahibi:</label>
                        <div class="form-control bg-white">
                            <i class="fas fa-user-edit"></i> <?php echo isset($data['record']->user_name) ? $data['record']->user_name : 'Sistem'; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Yakıt Bilgileri -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="mb-3"><i class="fas fa-gas-pump"></i> Yakıt Bilgileri</h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Yakıt Tankı:</label>
                        <div class="form-control bg-white">
                            <a href="<?php echo URLROOT; ?>/tanks/show/<?php echo $data['record']->tank_id; ?>" class="text-decoration-none">
                                <i class="fas fa-battery-three-quarters"></i> <?php echo $data['record']->tank_name; ?> (<?php echo $data['record']->tank_type; ?>)
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Yakıt Türü:</label>
                        <div class="form-control bg-white">
                            <?php if($data['record']->fuel_type == 'Benzin'): ?>
                                <span class="badge bg-danger"><i class="fas fa-gas-pump"></i> Benzin</span>
                            <?php elseif($data['record']->fuel_type == 'Dizel'): ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-gas-pump"></i> Dizel</span>
                            <?php elseif($data['record']->fuel_type == 'LPG'): ?>
                                <span class="badge bg-info text-dark"><i class="fas fa-gas-pump"></i> LPG</span>
                            <?php elseif($data['record']->fuel_type == 'Elektrik'): ?>
                                <span class="badge bg-success"><i class="fas fa-bolt"></i> Elektrik</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Miktar (Litre):</label>
                        <div class="form-control bg-white">
                            <i class="fas fa-tint"></i> <?php echo number_format($data['record']->amount, 2, ',', '.'); ?> Lt
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Tutar (TL):</label>
                        <div class="form-control bg-white">
                            <i class="fas fa-money-bill-wave"></i> <?php echo number_format($data['record']->cost, 2, ',', '.'); ?> ₺
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Birim Fiyat:</label>
                        <div class="form-control bg-white">
                            <i class="fas fa-tags"></i> 
                            <?php 
                                $unitPrice = 0;
                                if($data['record']->amount > 0 && $data['record']->cost > 0) {
                                    $unitPrice = $data['record']->cost / $data['record']->amount;
                                }
                                echo number_format($unitPrice, 2, ',', '.'); 
                            ?> ₺/Lt
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sayaç Bilgileri -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="mb-3"><i class="fas fa-info-circle"></i> Sayaç Bilgileri</h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Kilometre:</label>
                        <div class="form-control bg-white">
                            <?php if(!empty($data['record']->km_reading)): ?>
                                <i class="fas fa-tachometer-alt"></i> <?php echo number_format($data['record']->km_reading, 0, ',', '.'); ?> km
                            <?php else: ?>
                                <span class="text-muted">Belirtilmemiş</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Çalışma Saati:</label>
                        <div class="form-control bg-white">
                            <?php if(!empty($data['record']->hour_reading)): ?>
                                <i class="fas fa-hourglass-half"></i> <?php echo number_format($data['record']->hour_reading, 2, ',', '.'); ?> saat
                            <?php else: ?>
                                <span class="text-muted">Belirtilmemiş</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tarih ve Notlar -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="mb-3"><i class="fas fa-calendar-alt"></i> Tarih Bilgileri</h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Yakıt Alım Tarihi:</label>
                        <div class="form-control bg-white">
                            <i class="fas fa-calendar-alt"></i> 
                            <?php 
                                $date = new DateTime($data['record']->date);
                                echo $date->format('d.m.Y');
                                
                                // Saat bilgisi varsa göster
                                if (strlen($data['record']->date) > 10) {
                                    echo ' <i class="fas fa-clock"></i> ' . $date->format('H:i');
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Kayıt Tarihi:</label>
                        <div class="form-control bg-white">
                            <i class="fas fa-edit"></i> <?php echo date('d.m.Y H:i', strtotime($data['record']->created_at)); ?>
                        </div>
                    </div>
                </div>
                <?php if(!empty($data['record']->notes)): ?>
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Notlar:</label>
                        <div class="form-control bg-white" style="min-height: 60px;">
                            <?php echo nl2br($data['record']->notes); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        
            <div class="row">
                <div class="col-md-4">
                    <a href="<?php echo URLROOT; ?>/fuel/edit/<?php echo $data['record']->id; ?>" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['record']->vehicle_id; ?>" class="btn btn-info text-white btn-block mb-2">
                        <i class="fas fa-truck"></i> Araç Detayları
                    </a>
                </div>
                <div class="col-md-4">
                    <?php if(isAdmin()): ?>
                        <button type="button" class="btn btn-danger btn-block" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Modal -->
<?php if(isAdmin()): ?>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-trash me-2"></i> Yakıt Kaydını Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bu yakıt kaydını silmek istediğinize emin misiniz?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Bu işlem geri alınamaz ve silinmiş kayıtlar geri getirilemez.
                </div>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>Araç:</strong> <?php echo $data['record']->plate_number . ' - ' . $data['record']->brand . ' ' . $data['record']->model; ?></li>
                    <li class="list-group-item"><strong>Tarih:</strong> <?php echo date('d.m.Y', strtotime($data['record']->date)); ?></li>
                    <li class="list-group-item"><strong>Miktar:</strong> <?php echo number_format($data['record']->amount, 2, ',', '.'); ?> Lt</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> İptal</button>
                <form action="<?php echo URLROOT; ?>/fuel/delete/<?php echo $data['record']->id; ?>" method="post">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?> 