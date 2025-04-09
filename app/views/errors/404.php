<?php require_once APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <div class="card border-warning">
        <div class="card-header bg-warning text-dark">
            <h1 class="display-4">Sayfa Bulunamadı</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h2>Aradığınız sayfa bulunamadı!</h2>
                    <p class="lead">Üzgünüz, aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
                    <p>Lütfen URL'yi kontrol edin veya aşağıdaki bağlantıyı kullanarak ana sayfaya dönün.</p>
                    <div class="mt-4">
                        <a href="<?php echo URLROOT; ?>" class="btn btn-primary">Ana Sayfaya Dön</a>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fa fa-question-circle fa-5x text-warning"></i>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            Hata Kodu: 404 - Sayfa Bulunamadı
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/footer.php'; ?> 