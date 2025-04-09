<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-body bg-light mt-4 mb-4">
            <h2><i class="fas fa-database"></i> Veri Girişi</h2>
            <p class="lead">Bu bölümden sistem verilerini hızlıca girebilirsiniz.</p>
            <hr>
            
            <?php flash('entry_message'); ?>
            <?php flash('vehicle_message'); ?>
            <?php flash('driver_message'); ?>
            <?php flash('fuel_message'); ?>
            <?php flash('maintenance_message'); ?>
            <?php flash('assignment_message'); ?>
        </div>
    </div>
</div>

<div class="row">
    <!-- Araç Girişi -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-car"></i> Araç İşlemleri</h5>
            </div>
            <div class="card-body">
                <p>Araç ekleme, düzenleme ve listeleme işlemleri için kullanın.</p>
                <div class="d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/entries/quickVehicle" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Hızlı Araç Ekle
                    </a>
                    <a href="<?php echo URLROOT; ?>/vehicles/add" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i> Detaylı Araç Ekle
                    </a>
                    <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i> Araçları Listele
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sürücü Girişi -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-id-card"></i> Sürücü İşlemleri</h5>
            </div>
            <div class="card-body">
                <p>Sürücü ekleme, düzenleme ve listeleme işlemleri için kullanın.</p>
                <div class="d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/entries/quickDriver" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Hızlı Sürücü Ekle
                    </a>
                    <a href="<?php echo URLROOT; ?>/drivers/add" class="btn btn-outline-success">
                        <i class="fas fa-plus"></i> Detaylı Sürücü Ekle
                    </a>
                    <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i> Sürücüleri Listele
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Görevlendirme Girişi -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-tasks"></i> Görevlendirme İşlemleri</h5>
            </div>
            <div class="card-body">
                <p>Araç görevlendirme, takip ve listeleme işlemleri için kullanın.</p>
                <div class="d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/assignments/add" class="btn btn-info">
                        <i class="fas fa-plus-circle"></i> Görevlendirme Ekle
                    </a>
                    <a href="<?php echo URLROOT; ?>/assignments" class="btn btn-outline-info">
                        <i class="fas fa-list"></i> Görevlendirmeleri Listele
                    </a>
                    <a href="<?php echo URLROOT; ?>/assignments/active" class="btn btn-outline-secondary">
                        <i class="fas fa-clock"></i> Aktif Görevlendirmeler
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Yakıt Girişi -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-gas-pump"></i> Yakıt İşlemleri</h5>
            </div>
            <div class="card-body">
                <p>Yakıt alım kaydı, raporlama ve listeleme işlemleri için kullanın.</p>
                <div class="d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/entries/quickFuel" class="btn btn-danger">
                        <i class="fas fa-plus-circle"></i> Hızlı Yakıt Kaydı
                    </a>
                    <a href="<?php echo URLROOT; ?>/fuel/add" class="btn btn-outline-danger">
                        <i class="fas fa-plus"></i> Detaylı Yakıt Kaydı
                    </a>
                    <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i> Yakıt Kayıtlarını Listele
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bakım Girişi -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-tools"></i> Bakım İşlemleri</h5>
            </div>
            <div class="card-body">
                <p>Araç bakım kaydı, servis programı ve listeleme işlemleri için kullanın.</p>
                <div class="d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/maintenancerecords/add" class="btn btn-warning">
                        <i class="fas fa-plus-circle"></i> Bakım Kaydı Ekle
                    </a>
                    <a href="<?php echo URLROOT; ?>/maintenancerecords" class="btn btn-outline-warning">
                        <i class="fas fa-list"></i> Bakım Kayıtlarını Listele
                    </a>
                    <a href="<?php echo URLROOT; ?>/maintenancerecords/upcoming" class="btn btn-outline-secondary">
                        <i class="fas fa-calendar-alt"></i> Yaklaşan Bakımlar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rapor ve İstatistik -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Rapor ve İstatistikler</h5>
            </div>
            <div class="card-body">
                <p>Sistem raporları ve istatistik bilgilerine hızlı erişim.</p>
                <div class="d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/reports/fuel" class="btn btn-secondary">
                        <i class="fas fa-gas-pump"></i> Yakıt Raporları
                    </a>
                    <a href="<?php echo URLROOT; ?>/reports/maintenance" class="btn btn-outline-secondary">
                        <i class="fas fa-tools"></i> Bakım Raporları
                    </a>
                    <a href="<?php echo URLROOT; ?>/reports/vehicles" class="btn btn-outline-secondary">
                        <i class="fas fa-car"></i> Araç Raporları
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 