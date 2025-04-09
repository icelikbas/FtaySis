<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-question-circle mr-2"></i>Yardım ve Destek</h2>
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
                    <a href="<?php echo URLROOT; ?>/settings/backup" class="list-group-item list-group-item-action">
                        <i class="fas fa-database mr-2"></i> Veritabanı Yedekleme
                    </a>
                    <a href="<?php echo URLROOT; ?>/settings/help" class="list-group-item list-group-item-action active">
                        <i class="fas fa-question-circle mr-2"></i> Yardım ve Destek
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Sağ İçerik -->
        <div class="col-md-9">
            <!-- Sık Sorulan Sorular -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Sık Sorulan Sorular</h5>
                </div>
                <div class="card-body">
                    <div id="accordion">
                        <div class="card mb-2">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Şifremi unuttum, ne yapmalıyım?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    Şifrenizi unuttuysanız, lütfen sistem yöneticinizle iletişime geçin. Yönetici hesabınız için şifre sıfırlama işlemi gerçekleştirecektir.
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Yeni bir araç nasıl eklerim?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    Yeni bir araç eklemek için, sol menüden "Araçlar" seçeneğine tıklayın ve ardından "Yeni Araç Ekle" butonuna basın. Gerekli bilgileri doldurduktan sonra "Araç Ekle" butonuyla işlemi tamamlayabilirsiniz.
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Bakım kayıtlarını nasıl filtreleyebilirim?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                    Bakım kayıtları sayfasında, tablonun üst kısmında yer alan arama kutusunu kullanarak herhangi bir kritere göre filtreleme yapabilirsiniz. Ayrıca, tablo başlıklarına tıklayarak sıralama yapabilirsiniz.
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header" id="headingFour">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Veritabanı yedeği nasıl alınır?
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                                <div class="card-body">
                                    Veritabanı yedeği almak için, "Ayarlar" menüsünden "Veritabanı Yedekleme" seçeneğine gidin ve "Veritabanını Yedekle" butonuna tıklayın. Yedek dosyası otomatik olarak indirilecektir.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- İletişim Bilgileri -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">İletişim Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Teknik Destek</h5>
                            <p><i class="fas fa-envelope mr-2"></i> destek@filotak.ip</p>
                            <p><i class="fas fa-phone mr-2"></i> +90 (212) 123 45 67</p>
                            <p><i class="fas fa-clock mr-2"></i> Hafta içi 09:00 - 18:00</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Firma Bilgileri</h5>
                            <p><i class="fas fa-building mr-2"></i> Filo Takip A.Ş.</p>
                            <p><i class="fas fa-map-marker-alt mr-2"></i> İstanbul, Türkiye</p>
                            <p><i class="fas fa-globe mr-2"></i> www.filotak.ip</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Destek Talebi -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Destek Talebi Oluştur</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="subject">Konu</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Destek talebinizin konusu">
                        </div>
                        <div class="form-group">
                            <label for="priority">Öncelik</label>
                            <select class="form-control" id="priority" name="priority">
                                <option value="low">Düşük</option>
                                <option value="medium" selected>Orta</option>
                                <option value="high">Yüksek</option>
                                <option value="critical">Kritik</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Mesajınız</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Lütfen sorununuzu detaylı şekilde açıklayın"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="attachment">Ekler (İsteğe bağlı)</label>
                            <input type="file" class="form-control-file" id="attachment" name="attachment">
                            <small class="form-text text-muted">Maksimum dosya boyutu: 5MB</small>
                        </div>
                        <button type="submit" class="btn btn-success float-right">Talebi Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 