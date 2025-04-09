<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-server mr-2"></i>Sistem Bilgisi</h2>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT; ?>/settings" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Ayarlara Dön
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
                    <a href="<?php echo URLROOT; ?>/settings" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt mr-2"></i> Genel Bakış
                    </a>
                    <a href="<?php echo URLROOT; ?>/settings/systemInfo" class="list-group-item list-group-item-action active">
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
        </div>
        
        <!-- Sağ İçerik -->
        <div class="col-md-9">
            <!-- PHP ve Sunucu Bilgisi -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">PHP & Sunucu Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">PHP Versiyonu:</th>
                                    <td><?php echo $data['php_version']; ?></td>
                                </tr>
                                <tr>
                                    <th>Web Sunucusu:</th>
                                    <td><?php echo $data['server_info']; ?></td>
                                </tr>
                                <tr>
                                    <th>Bellek Limiti:</th>
                                    <td><?php echo $data['memory_limit']; ?></td>
                                </tr>
                                <tr>
                                    <th>Maksimum Dosya Yükleme Boyutu:</th>
                                    <td><?php echo $data['upload_max_filesize']; ?></td>
                                </tr>
                                <tr>
                                    <th>Maksimum Çalışma Süresi:</th>
                                    <td><?php echo $data['max_execution_time']; ?> saniye</td>
                                </tr>
                                <tr>
                                    <th>Oturum Yolu:</th>
                                    <td><?php echo $data['session_path']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- PHP Uzantıları -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">PHP Uzantıları</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h6>MySQLi</h6>
                                <span class="badge badge-<?php echo extension_loaded('mysqli') ? 'success' : 'danger'; ?>">
                                    <?php echo extension_loaded('mysqli') ? 'Aktif' : 'Pasif'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h6>PDO</h6>
                                <span class="badge badge-<?php echo extension_loaded('pdo') ? 'success' : 'danger'; ?>">
                                    <?php echo extension_loaded('pdo') ? 'Aktif' : 'Pasif'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h6>GD (Görüntü İşleme)</h6>
                                <span class="badge badge-<?php echo extension_loaded('gd') ? 'success' : 'danger'; ?>">
                                    <?php echo extension_loaded('gd') ? 'Aktif' : 'Pasif'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h6>OpenSSL</h6>
                                <span class="badge badge-<?php echo extension_loaded('openssl') ? 'success' : 'danger'; ?>">
                                    <?php echo extension_loaded('openssl') ? 'Aktif' : 'Pasif'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h6>JSON</h6>
                                <span class="badge badge-<?php echo extension_loaded('json') ? 'success' : 'danger'; ?>">
                                    <?php echo extension_loaded('json') ? 'Aktif' : 'Pasif'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h6>cURL</h6>
                                <span class="badge badge-<?php echo extension_loaded('curl') ? 'success' : 'danger'; ?>">
                                    <?php echo extension_loaded('curl') ? 'Aktif' : 'Pasif'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dizin ve Dosya İzinleri -->
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Dosya ve Dizin İzinleri</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Dizin / Dosya</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo APPROOT; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo is_readable(APPROOT) ? 'success' : 'danger'; ?>">
                                            <?php echo is_readable(APPROOT) ? 'Okunabilir' : 'Okunamaz'; ?>
                                        </span>
                                        <span class="badge badge-<?php echo is_writable(APPROOT) ? 'success' : 'danger'; ?> ml-2">
                                            <?php echo is_writable(APPROOT) ? 'Yazılabilir' : 'Yazılamaz'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo APPROOT . '/views'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo is_readable(APPROOT . '/views') ? 'success' : 'danger'; ?>">
                                            <?php echo is_readable(APPROOT . '/views') ? 'Okunabilir' : 'Okunamaz'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo APPROOT . '/models'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo is_readable(APPROOT . '/models') ? 'success' : 'danger'; ?>">
                                            <?php echo is_readable(APPROOT . '/models') ? 'Okunabilir' : 'Okunamaz'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo APPROOT . '/controllers'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo is_readable(APPROOT . '/controllers') ? 'success' : 'danger'; ?>">
                                            <?php echo is_readable(APPROOT . '/controllers') ? 'Okunabilir' : 'Okunamaz'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo APPROOT . '/views/settings'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo is_readable(APPROOT . '/views/settings') ? 'success' : 'danger'; ?>">
                                            <?php echo is_readable(APPROOT . '/views/settings') ? 'Okunabilir' : 'Okunamaz'; ?>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 