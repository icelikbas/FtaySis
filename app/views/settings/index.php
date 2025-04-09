<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-cog mr-2"></i>Sistem Ayarları</h2>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Geri Dön
            </a>
        </div>
    </div>

    <?php flash('settings_message'); ?>
    
    <div class="row">
        <!-- Sol Menü -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Ayarlar Menüsü</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo URLROOT; ?>/settings" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt mr-2"></i> Genel Bakış
                    </a>
                    <a href="<?php echo URLROOT; ?>/settings/systemInfo" class="list-group-item list-group-item-action">
                        <i class="fas fa-server mr-2"></i> Sistem Bilgisi
                    </a>
                    <a href="<?php echo URLROOT; ?>/settings/backup" class="list-group-item list-group-item-action">
                        <i class="fas fa-database mr-2"></i> Veritabanı Yedekleme
                    </a>
                    <a href="<?php echo URLROOT; ?>/settings/help" class="list-group-item list-group-item-action">
                        <i class="fas fa-question-circle mr-2"></i> Yardım ve Destek
                    </a>
                </div>
            </div>
            
            <!-- Kullanım İstatistikleri -->
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Sistem İstatistikleri</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Araçlar</h6>
                        <?php if($data['stats']['total_vehicles'] > 0) : ?>
                            <div class="progress mb-1">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo ($data['stats']['active_vehicles'] / $data['stats']['total_vehicles']) * 100; ?>%" 
                                    aria-valuenow="<?php echo $data['stats']['active_vehicles']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $data['stats']['total_vehicles']; ?>">
                                    <?php echo $data['stats']['active_vehicles']; ?> Aktif
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo ($data['stats']['maintenance_vehicles'] / $data['stats']['total_vehicles']) * 100; ?>%" 
                                    aria-valuenow="<?php echo $data['stats']['maintenance_vehicles']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $data['stats']['total_vehicles']; ?>">
                                    <?php echo $data['stats']['maintenance_vehicles']; ?> Bakımda
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-info py-1">Kayıtlı araç bulunmamaktadır.</div>
                        <?php endif; ?>
                        <small class="text-muted">Toplam: <?php echo $data['stats']['total_vehicles']; ?> araç</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Sürücüler</h6>
                        <?php if($data['stats']['total_drivers'] > 0) : ?>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo ($data['stats']['active_drivers'] / $data['stats']['total_drivers']) * 100; ?>%" 
                                    aria-valuenow="<?php echo $data['stats']['active_drivers']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $data['stats']['total_drivers']; ?>">
                                    <?php echo $data['stats']['active_drivers']; ?> Aktif
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-info py-1">Kayıtlı sürücü bulunmamaktadır.</div>
                        <?php endif; ?>
                        <small class="text-muted">Toplam: <?php echo $data['stats']['total_drivers']; ?> sürücü</small>
                    </div>
                    
                    <div>
                        <h6>Kullanıcılar</h6>
                        <?php if($data['stats']['user_count'] > 0) : ?>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" 
                                    aria-valuenow="<?php echo $data['stats']['user_count']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $data['stats']['user_count']; ?>">
                                    <?php echo $data['stats']['user_count']; ?> Kullanıcı
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-info py-1">Kayıtlı kullanıcı bulunmamaktadır.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sağ İçerik -->
        <div class="col-md-9">
            <!-- Genel Sistem Ayarları -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Genel Sistem Ayarları</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/settings/updateGeneral" method="POST">
                        <div class="form-group row">
                            <label for="site_name" class="col-sm-3 col-form-label">Site Adı:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control <?php echo (!empty($data['site_name_err'])) ? 'is-invalid' : ''; ?>" 
                                    id="site_name" name="site_name" value="<?php echo $data['site_settings']['site_name']; ?>">
                                <span class="invalid-feedback"><?php echo $data['site_name_err'] ?? ''; ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="app_version" class="col-sm-3 col-form-label">Uygulama Versiyonu:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="app_version" name="app_version" 
                                    value="<?php echo $data['site_settings']['app_version']; ?>" readonly>
                                <small class="form-text text-muted">Uygulama sürümü otomatik olarak belirlenmiştir.</small>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Ayarları Kaydet</button>
                    </form>
                </div>
            </div>
            
            <!-- Veritabanı Bilgileri -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Veritabanı Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">Veritabanı Adı:</th>
                                    <td><?php echo $data['db_config']['name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Veritabanı Sunucusu:</th>
                                    <td><?php echo $data['db_config']['host']; ?></td>
                                </tr>
                                <tr>
                                    <th>Veritabanı Kullanıcısı:</th>
                                    <td><?php echo $data['db_config']['user']; ?></td>
                                </tr>
                                <tr>
                                    <th>Karakter Seti:</th>
                                    <td><?php echo $data['db_config']['charset']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo URLROOT; ?>/settings/backup" class="btn btn-info float-right">
                        <i class="fas fa-download mr-1"></i> Veritabanını Yedekle
                    </a>
                </div>
            </div>
            
            <!-- Hızlı Erişim -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Hızlı Erişim</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo URLROOT; ?>/users" class="btn btn-outline-secondary btn-block py-3">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                Kullanıcı Yönetimi
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-outline-secondary btn-block py-3">
                                <i class="fas fa-truck fa-2x mb-2"></i><br>
                                Araç Yönetimi
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-outline-secondary btn-block py-3">
                                <i class="fas fa-id-card fa-2x mb-2"></i><br>
                                Sürücü Yönetimi
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo URLROOT; ?>/maintenancerecords" class="btn btn-outline-secondary btn-block py-3">
                                <i class="fas fa-tools fa-2x mb-2"></i><br>
                                Bakım Kayıtları
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-outline-secondary btn-block py-3">
                                <i class="fas fa-gas-pump fa-2x mb-2"></i><br>
                                Yakıt Kayıtları
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo URLROOT; ?>/assignments" class="btn btn-outline-secondary btn-block py-3">
                                <i class="fas fa-clipboard-list fa-2x mb-2"></i><br>
                                Görevlendirmeler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 