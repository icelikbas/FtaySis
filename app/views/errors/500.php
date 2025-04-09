<?php require_once APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h1 class="display-4">Sunucu Hatası</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h2>Bir şeyler yanlış gitti!</h2>
                    <p class="lead">Üzgünüz, istediğiniz işlem gerçekleştirilirken bir hata oluştu.</p>
                    <p>Sistem yöneticisine bu hatayı bildirirseniz, en kısa sürede çözülecektir.</p>
                    <div class="mt-4">
                        <a href="<?php echo URLROOT; ?>" class="btn btn-primary">Ana Sayfaya Dön</a>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fa fa-exclamation-triangle fa-5x text-danger"></i>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            Hata Kodu: 500 - Sunucu Hatası
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/footer.php'; ?> 