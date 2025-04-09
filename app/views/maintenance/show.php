<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><i class="fas fa-tools mr-2"></i><?php echo $data['title']; ?></h2>
            <div class="float-right">
                <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Tüm Bakımlar
                </a>
                <a href="<?php echo URLROOT; ?>/maintenance/edit/<?php echo $data['record']->id; ?>" class="btn btn-warning ml-2">
                    <i class="fas fa-edit mr-1"></i> Düzenle
                </a>
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') : ?>
                    <form class="d-inline" action="<?php echo URLROOT; ?>/maintenance/delete/<?php echo $data['record']->id; ?>" method="post" onsubmit="return confirm('Bu bakım kaydını silmek istediğinize emin misiniz?');">
                        <button type="submit" class="btn btn-danger ml-2">
                            <i class="fas fa-trash mr-1"></i> Sil
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <!-- Durum Bilgisi -->
    <div class="row mb-4">
        <div class="col-md-12">
            <?php if($data['record']->status == 'Planlandı'): ?>
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-info">
                                    <i class="fas fa-calendar mr-1"></i> Bu bakım planlandı ve henüz başlamadı
                                </div>
                                <div class="mt-2">
                                    Bakım işlemini başlatmak için durumu "Devam Ediyor" olarak güncelleyebilirsiniz.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['record']->status == 'Devam Ediyor'): ?>
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-warning">
                                    <i class="fas fa-spinner mr-1"></i> Bu bakım şu anda devam ediyor
                                </div>
                                <div class="mt-2">
                                    Bakım işlemi tamamlandığında durumu "Tamamlandı" olarak güncelleyebilirsiniz.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cog fa-spin fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['record']->status == 'Tamamlandı'): ?>
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-success">
                                    <i class="fas fa-check-circle mr-1"></i> Bu bakım tamamlandı
                                </div>
                                <div class="mt-2">
                                    Bakım kaydını düzenlemek veya incelemek için işlem butonlarını kullanabilirsiniz.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['record']->status == 'İptal'): ?>
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-danger">
                                    <i class="fas fa-times-circle mr-1"></i> Bu bakım iptal edildi
                                </div>
                                <div class="mt-2">
                                    İptal edilen bakım kaydını düzenlemek veya incelemek için işlem butonlarını kullanabilirsiniz.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ban fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Bakım Bilgileri -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bakım Detayları</h6>
                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['record']->vehicle_id; ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-car mr-1"></i> Araca Git
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Sol Sütun -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-primary">Temel Bilgiler</h6>
                                <hr>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Bakım ID:</div>
                                    <div class="col-7"><?php echo $data['record']->id; ?></div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Araç:</div>
                                    <div class="col-7">
                                        <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['record']->vehicle_id; ?>">
                                            <?php echo $data['record']->plate_number; ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Marka/Model:</div>
                                    <div class="col-7"><?php echo $data['record']->brand . ' ' . $data['record']->model; ?></div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Bakım Türü:</div>
                                    <div class="col-7">
                                        <?php if($data['record']->maintenance_type == 'Periyodik Bakım'): ?>
                                            <span class="badge badge-primary"><?php echo $data['record']->maintenance_type; ?></span>
                                        <?php elseif($data['record']->maintenance_type == 'Arıza'): ?>
                                            <span class="badge badge-danger"><?php echo $data['record']->maintenance_type; ?></span>
                                        <?php elseif($data['record']->maintenance_type == 'Lastik Değişimi'): ?>
                                            <span class="badge badge-warning"><?php echo $data['record']->maintenance_type; ?></span>
                                        <?php elseif($data['record']->maintenance_type == 'Yağ Değişimi'): ?>
                                            <span class="badge badge-info"><?php echo $data['record']->maintenance_type; ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><?php echo $data['record']->maintenance_type; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Açıklama:</div>
                                    <div class="col-7"><?php echo $data['record']->description; ?></div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Kilometre:</div>
                                    <div class="col-7"><?php echo number_format($data['record']->km_reading, 0, ',', '.'); ?> km</div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Kayıt Tarihi:</div>
                                    <div class="col-7"><?php echo date('d.m.Y H:i', strtotime($data['record']->created_at)); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sağ Sütun -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-primary">Tarih ve Durum Bilgileri</h6>
                                <hr>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Başlangıç:</div>
                                    <div class="col-7"><?php echo date('d.m.Y', strtotime($data['record']->start_date)); ?></div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Bitiş:</div>
                                    <div class="col-7">
                                        <?php if($data['record']->end_date): ?>
                                            <?php echo date('d.m.Y', strtotime($data['record']->end_date)); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Belirtilmemiş</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Durum:</div>
                                    <div class="col-7">
                                        <?php if($data['record']->status == 'Planlandı'): ?>
                                            <span class="badge badge-info">Planlandı</span>
                                        <?php elseif($data['record']->status == 'Devam Ediyor'): ?>
                                            <span class="badge badge-warning">Devam Ediyor</span>
                                        <?php elseif($data['record']->status == 'Tamamlandı'): ?>
                                            <span class="badge badge-success">Tamamlandı</span>
                                        <?php elseif($data['record']->status == 'İptal'): ?>
                                            <span class="badge badge-danger">İptal</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Maliyet:</div>
                                    <div class="col-7"><strong><?php echo number_format($data['record']->cost, 2, ',', '.'); ?> ₺</strong></div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Servis Sağlayıcı:</div>
                                    <div class="col-7">
                                        <?php if($data['record']->service_provider): ?>
                                            <?php echo $data['record']->service_provider; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Belirtilmemiş</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row py-2 border-bottom">
                                    <div class="col-5 font-weight-bold">Kaydeden Kullanıcı:</div>
                                    <div class="col-7"><?php echo $data['record']->user_name; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ Kenar Bilgiler -->
        <div class="col-lg-4">
            <!-- Gelecek Bakım Bilgileri -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sonraki Bakım Bilgileri</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-3">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sonraki Bakım Tarihi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php if($data['record']->next_maintenance_date): ?>
                                    <?php echo date('d.m.Y', strtotime($data['record']->next_maintenance_date)); ?>
                                    <?php 
                                        $today = date('Y-m-d');
                                        $daysLeft = floor((strtotime($data['record']->next_maintenance_date) - strtotime($today)) / (60 * 60 * 24));
                                        if($daysLeft > 0):
                                    ?>
                                        <small class="text-success">(<?php echo $daysLeft; ?> gün kaldı)</small>
                                    <?php elseif($daysLeft < 0): ?>
                                        <small class="text-danger">(<?php echo abs($daysLeft); ?> gün geçti)</small>
                                    <?php else: ?>
                                        <small class="text-warning">(Bugün)</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Belirtilmemiş</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sonraki Bakım Kilometresi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php if($data['record']->next_maintenance_km): ?>
                                    <?php echo number_format($data['record']->next_maintenance_km, 0, ',', '.'); ?> km
                                    <?php 
                                        $kmLeft = $data['record']->next_maintenance_km - $data['record']->km_reading;
                                        if($kmLeft > 0):
                                    ?>
                                        <small class="text-success">(<?php echo number_format($kmLeft, 0, ',', '.'); ?> km kaldı)</small>
                                    <?php elseif($kmLeft < 0): ?>
                                        <small class="text-danger">(<?php echo number_format(abs($kmLeft), 0, ',', '.'); ?> km geçildi)</small>
                                    <?php else: ?>
                                        <small class="text-warning">(Şimdi)</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Belirtilmemiş</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notlar -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notlar</h6>
                </div>
                <div class="card-body">
                    <?php if($data['record']->notes): ?>
                        <p class="mb-0"><?php echo nl2br($data['record']->notes); ?></p>
                    <?php else: ?>
                        <p class="text-center text-muted mb-0">Bu bakım kaydı için not bulunmuyor.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 