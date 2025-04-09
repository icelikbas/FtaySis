<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-chart-bar"></i> Raporlar</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="<?php echo URLROOT; ?>/reports/custom" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-plus"></i> Özel Rapor
                </a>
                <a href="#" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-print"></i> Yazdır
                </a>
            </div>
            <a href="<?php echo URLROOT; ?>/reports/costs" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-money-bill"></i> Maliyet Raporu
            </a>
        </div>
    </div>

    <!-- Genel İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Araç</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['vehicleStats']['total']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/vehicles" class="text-primary">Detaylar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Toplam Sürücü</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['driverStats']['total']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/drivers" class="text-success">Detaylar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aktif Görevlendirme</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['active']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/assignments" class="text-info">Detaylar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Toplam Yakıt Maliyeti</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['fuelStats']->total_cost) ? number_format($data['fuelStats']->total_cost, 2, ',', '.') . ' ₺' : '0 ₺'; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gas-pump fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/fuel" class="text-warning">Detaylar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rapor Türleri -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-car"></i> Araç Raporu</h5>
                </div>
                <div class="card-body">
                    <p>Filodaki araçların durumu, yakıt tüketimi, bakım kayıtları ve görevlendirme bilgilerini içeren kapsamlı raporlar.</p>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Aktif Araçlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['vehicleStats']['active']; ?></div>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bakımda</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['vehicleStats']['maintenance']; ?></div>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pasif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['vehicleStats']['inactive']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/vehicles" class="btn btn-primary">Araç Raporuna Git</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-id-card"></i> Sürücü Raporu</h5>
                </div>
                <div class="card-body">
                    <p>Sürücülerin performansı, görevlendirme bilgileri, izin kayıtları ve lisans bilgilerini içeren detaylı raporlar.</p>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aktif Sürücüler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['driverStats']['active']; ?></div>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">İzinde</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['driverStats']['onLeave']; ?></div>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pasif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['driverStats']['inactive']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/drivers" class="btn btn-success">Sürücü Raporuna Git</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-gas-pump"></i> Yakıt Raporu</h5>
                </div>
                <div class="card-body">
                    <p>Yakıt tüketimi, maliyetleri, araç başına yakıt performansı ve trenlerini içeren detaylı analizler.</p>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Toplam Tüketim</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['fuelStats']->total_fuel) ? number_format($data['fuelStats']->total_fuel, 2, ',', '.') . ' Lt' : '0 Lt'; ?>
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ortalama Fiyat</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['fuelStats']->avg_price_per_unit) ? number_format($data['fuelStats']->avg_price_per_unit, 2, ',', '.') . ' ₺/Lt' : '0 ₺/Lt'; ?>
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Yakıt Kaydı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['fuelStats']->record_count) ? $data['fuelStats']->record_count : '0'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/fuel" class="btn btn-warning">Yakıt Raporuna Git</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-tools"></i> Bakım Raporu</h5>
                </div>
                <div class="card-body">
                    <p>Araç bakım kayıtları, maliyetleri, bakım türleri ve yaklaşan bakım planlarını içeren kapsamlı raporlar.</p>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Toplam Bakım</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['maintenanceStats']->record_count) ? $data['maintenanceStats']->record_count : '0'; ?>
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Toplam Maliyet</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['maintenanceStats']->total_cost) ? number_format($data['maintenanceStats']->total_cost, 2, ',', '.') . ' ₺' : '0 ₺'; ?>
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ort. Bakım Maliyeti</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['maintenanceStats']->avg_cost) ? number_format($data['maintenanceStats']->avg_cost, 2, ',', '.') . ' ₺' : '0 ₺'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/maintenance" class="btn btn-danger">Bakım Raporuna Git</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Görevlendirme Raporu</h5>
                </div>
                <div class="card-body">
                    <p>Araç ve sürücü görevlendirmeleri, tamamlanma oranları, iptal edilen görevler ve görev süreleri hakkında detaylı analizler.</p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Toplam Görevlendirme</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['total']; ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Aktif Görevlendirme</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['active']; ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tamamlanan Görevler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['completed']; ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">İptal Edilen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['cancelled']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo URLROOT; ?>/reports/assignments" class="btn btn-info">Görevlendirme Raporuna Git</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 