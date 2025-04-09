<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-database mr-2"></i>Veritabanı Yedekleme</h2>
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
                    <a href="<?php echo URLROOT; ?>/settings/systemInfo" class="list-group-item list-group-item-action">
                        <i class="fas fa-server mr-2"></i> Sistem Bilgisi
                    </a>
                    <a href="<?php echo URLROOT; ?>/settings/backup" class="list-group-item list-group-item-action active">
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
            <!-- Veritabanı Yedekleme -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Veritabanı Yedekleme İşlemi</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Veritabanı yedeklemesi, tüm verilerinizin bir kopyasını oluşturur ve bu kopya daha sonra veritabanını geri yüklemek için kullanılabilir.
                    </div>
                    
                    <p>Yedekleme işlemi aşağıdaki tabloları içerecektir:</p>
                    <ul>
                        <li>Kullanıcılar</li>
                        <li>Araçlar</li>
                        <li>Sürücüler</li>
                        <li>Görevlendirmeler</li>
                        <li>Yakıt Kayıtları</li>
                        <li>Bakım Kayıtları</li>
                        <li>Sistem Ayarları</li>
                        <li>Sistem Logları</li>
                    </ul>
                    
                    <div class="text-center my-4">
                        <a href="<?php echo URLROOT; ?>/settings/backup" class="btn btn-success btn-lg">
                            <i class="fas fa-download mr-2"></i> Veritabanını Yedekle
                        </a>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Uyarı:</strong> Yedekleme işlemi sistem boyutuna bağlı olarak biraz zaman alabilir. Lütfen işlem tamamlanana kadar bekleyin.
                    </div>
                </div>
            </div>
            
            <!-- Önceki Yedeklemeler -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Önceki Yedeklemeler</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Yedekleme Tarihi</th>
                                    <th>Dosya Boyutu</th>
                                    <th>Oluşturan</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>28.03.2023 - 15:45</td>
                                    <td>2.5 MB</td>
                                    <td>admin@filotak.ip</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download mr-1"></i> İndir
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt mr-1"></i> Sil
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15.03.2023 - 10:30</td>
                                    <td>2.4 MB</td>
                                    <td>admin@filotak.ip</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download mr-1"></i> İndir
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt mr-1"></i> Sil
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>01.03.2023 - 09:15</td>
                                    <td>2.3 MB</td>
                                    <td>admin@filotak.ip</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download mr-1"></i> İndir
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt mr-1"></i> Sil
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Veritabanı Geri Yükleme -->
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Veritabanı Geri Yükleme</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Dikkat:</strong> Geri yükleme işlemi mevcut veritabanı verilerinin üzerine yazacaktır. Bu işlem geri alınamaz.
                    </div>
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="backupFile">Yedek Dosyasını Seçin</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="backupFile" name="backupFile">
                                <label class="custom-file-label" for="backupFile">Dosya seçilmedi</label>
                            </div>
                            <small class="form-text text-muted">Sadece .sql uzantılı dosyalar desteklenmektedir.</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmRestore" name="confirmRestore" required>
                            <label class="form-check-label" for="confirmRestore">
                                Mevcut verilerin üzerine yazılacağını anlıyorum ve onaylıyorum.
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-upload mr-2"></i> Veritabanını Geri Yükle
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 