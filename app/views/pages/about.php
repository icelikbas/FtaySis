<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="jumbotron">
    <h1 class="display-4"><?php echo $data['title']; ?></h1>
    <p class="lead"><?php echo $data['description']; ?></p>
    <hr class="my-4">
    <p>Filo Takip Sistemi, işletmenizin araç filosunu etkin bir şekilde yönetebilmeniz için tasarlanmış kapsamlı bir yazılım çözümüdür. Araçlarınızın bakım programları, yakıt tüketimleri, sürücü bilgileri ve daha fazlasını tek bir platformda takip etmenize olanak tanır.</p>
    <p>Sistemi kullanmak için lütfen giriş yapın.</p>
    <a class="btn btn-primary btn-lg" href="<?php echo URLROOT; ?>/users/login" role="button">Giriş Yap</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <i class="fas fa-tachometer-alt fa-4x mb-3 text-primary"></i>
                <h4 class="card-title">Kolay Kullanım</h4>
                <p class="card-text">Kullanıcı dostu arayüzü sayesinde filonuzu kolayca yönetin.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-4x mb-3 text-primary"></i>
                <h4 class="card-title">Detaylı Raporlar</h4>
                <p class="card-text">Araç ve sürücü performansınızı detaylı raporlarla analiz edin.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <i class="fas fa-cogs fa-4x mb-3 text-primary"></i>
                <h4 class="card-title">Özelleştirilebilir</h4>
                <p class="card-text">İşletmenizin ihtiyaçlarına göre sistemi özelleştirin.</p>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 