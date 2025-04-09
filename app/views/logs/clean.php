<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-broom mr-2"></i>Logları Temizle</h2>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT; ?>/logs" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Loglara Dön
            </a>
        </div>
    </div>

    <?php flash('log_message'); ?>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-broom mr-2"></i> Eski Logları Temizle</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Uyarı:</strong> Belirtilen tarihten daha eski log kayıtları kalıcı olarak silinecektir. Bu işlem geri alınamaz.
                    </div>
                    
                    <form action="<?php echo URLROOT; ?>/logs/clean" method="POST">
                        <div class="form-group">
                            <label for="days">Aşağıdaki gün sayısından daha eski kayıtları temizle:</label>
                            <div class="input-group">
                                <select class="form-control" id="days" name="days">
                                    <option value="7">7 gün</option>
                                    <option value="14">14 gün</option>
                                    <option value="30" selected>30 gün (1 ay)</option>
                                    <option value="90">90 gün (3 ay)</option>
                                    <option value="180">180 gün (6 ay)</option>
                                    <option value="365">365 gün (1 yıl)</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text">gün</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Örnek: 30 gün seçilirse, 30 günden daha eski tüm log kayıtları silinecektir.</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmClean" required>
                            <label class="form-check-label" for="confirmClean">
                                Belirtilen süreden daha eski logların silineceğini anlıyorum ve onaylıyorum.
                            </label>
                        </div>
                        
                        <div class="text-right">
                            <a href="<?php echo URLROOT; ?>/logs" class="btn btn-secondary mr-2">
                                <i class="fas fa-times mr-1"></i> İptal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-broom mr-1"></i> Logları Temizle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4 shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Neden Loglar Temizlenir?</h5>
                </div>
                <div class="card-body">
                    <p>Sistem logları zamanla birikir ve veritabanında yer kaplar. Eski logların düzenli aralıklarla temizlenmesi aşağıdaki faydaları sağlar:</p>
                    
                    <ul>
                        <li><strong>Performans İyileştirmesi:</strong> Loglar arttıkça, sorgulama süreleri uzayabilir</li>
                        <li><strong>Depolama Alanı Tasarrufu:</strong> Gereksiz eski kayıtlar veritabanı boyutunu artırır</li>
                        <li><strong>Veri Koruma:</strong> Bazı durumlarda, belirli süre sonra hassas verilerin silinmesi yasal bir gerekliliktir</li>
                        <li><strong>Daha Kolay Yönetim:</strong> Daha az log kaydıyla ilgilenmek, yönetim süreçlerini kolaylaştırır</li>
                    </ul>
                    
                    <p>Genel olarak, son 30 günün loglarını saklama pratiği yaygın bir yaklaşımdır. Ancak, yasal gerekliliklere ve kurum politikalarına bağlı olarak bu süre değişebilir.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 