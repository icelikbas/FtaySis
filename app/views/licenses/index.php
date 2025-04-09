<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="d-flex justify-content-between align-items-center">
    <h2 class="d-flex mb-3 align-items-center">
        <i class="fas fa-id-card me-2 text-primary"></i>
        <span><?php echo $data['driver']->name . ' ' . $data['driver']->surname; ?></span>
        <span class="badge bg-info ms-1 small">Ehliyet Bilgileri</span>
    </h2>
    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $data['driver']->id; ?>" class="btn btn-outline-secondary">
        <i class="fa fa-backward"></i> Sürücü Detaylarına Dön
    </a>
</div>


<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-primary">
                    <i class="fas fa-drivers-license me-2"></i>Aktif Ehliyet Sınıfları
                </h5>
                <a href="<?php echo URLROOT; ?>/licenses/add/<?php echo $data['driver']->id; ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Yeni Ehliyet Sınıfı Ekle
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($data['licenseTypes'])) : ?>
                    <div class="text-center py-5">
                        <img src="<?php echo URLROOT; ?>/img/no-license.svg" alt="Ehliyet Bulunamadı" style="max-width: 120px; opacity: 0.5;" class="mb-3">
                        <h5 class="text-muted">Bu sürücü için henüz kayıtlı ehliyet sınıfı bulunmamaktadır</h5>
                        <a href="<?php echo URLROOT; ?>/licenses/add/<?php echo $data['driver']->id; ?>" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-plus-circle"></i> İlk Ehliyet Sınıfını Ekle
                        </a>
                    </div>
                <?php else : ?>
                    <div class="row">
                        <?php foreach ($data['licenseTypes'] as $license) : ?>
                            <?php
                            $statusClass = 'bg-secondary';
                            $statusText = 'Belirtilmemiş';
                            $statusIcon = 'fa-question-circle';

                            if (!empty($license->expiry_date)) {
                                if (strtotime($license->expiry_date) < time()) {
                                    $statusClass = 'bg-danger';
                                    $statusText = 'Süresi Dolmuş';
                                    $statusIcon = 'fa-exclamation-circle';
                                } elseif (strtotime($license->expiry_date) < strtotime('+3 months')) {
                                    $statusClass = 'bg-warning';
                                    $statusText = 'Yakında Dolacak';
                                    $statusIcon = 'fa-exclamation-triangle';
                                } else {
                                    $statusClass = 'bg-success';
                                    $statusText = 'Geçerli';
                                    $statusIcon = 'fa-check-circle';
                                }
                            }
                            
                            // Badge stilleri ve renkleri
                            $headerClass = $statusClass;
                            $titleClass = 'text-white';
                            $badgeClass = 'bg-white';
                            $badgeTextClass = str_replace('bg-', 'text-', $statusClass);
                            
                            // Uyarı durumu için özel stil
                            if ($statusClass === 'bg-warning') {
                                $titleClass = 'text-dark';
                                $badgeTextClass = 'text-dark';
                            }
                            ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm hover-card">
                                    <div class="card-header py-3 <?php echo $headerClass; ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 <?php echo $titleClass; ?>">
                                                <span class="badge bg-white text-dark me-2"><?php echo $license->code; ?></span>
                                                <?php echo $license->name; ?>
                                            </h5>
                                            <span class="badge <?php echo $badgeClass; ?> <?php echo $badgeTextClass; ?> fw-bold">
                                                <i class="fas <?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="small mb-3"><?php echo $license->description; ?></p>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="license-icon-bg me-2">
                                                        <i class="fas fa-calendar-plus text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Verilme Tarihi</small>
                                                        <strong><?php echo !empty($license->issue_date) ? date('d/m/Y', strtotime($license->issue_date)) : '-'; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="license-icon-bg me-2">
                                                        <i class="fas fa-calendar-times text-<?php echo str_replace('bg-', 'text-', $statusClass); ?>"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Geçerlilik Tarihi</small>
                                                        <strong><?php echo !empty($license->expiry_date) ? date('d/m/Y', strtotime($license->expiry_date)) : '-'; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="btn-group w-100">
                                            <a href="<?php echo URLROOT; ?>/licenses/edit/<?php echo $data['driver']->id; ?>/<?php echo $license->id; ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit me-1"></i> Düzenle
                                            </a>
                                            <form class="d-inline m-0" action="<?php echo URLROOT; ?>/licenses/delete/<?php echo $data['driver']->id; ?>/<?php echo $license->id; ?>" method="post">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Bu ehliyet sınıfını silmek istediğinize emin misiniz?');">
                                                    <i class="fas fa-trash-alt me-1"></i> Sil
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary"><i class="fas fa-info-circle me-2"></i>Ehliyet Sınıfları Bilgisi</h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="licenseTypesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="motorcycle-tab" data-bs-toggle="tab" data-bs-target="#motorcycle" type="button">
                            <i class="fas fa-motorcycle me-1"></i> Motosiklet
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="car-tab" data-bs-toggle="tab" data-bs-target="#car" type="button">
                            <i class="fas fa-car me-1"></i> Otomobil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="truck-tab" data-bs-toggle="tab" data-bs-target="#truck" type="button">
                            <i class="fas fa-truck me-1"></i> Kamyon
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bus-tab" data-bs-toggle="tab" data-bs-target="#bus" type="button">
                            <i class="fas fa-bus me-1"></i> Otobüs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other" type="button">
                            <i class="fas fa-tractor me-1"></i> Diğer
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="licenseTypesContent">
                    <div class="tab-pane fade show active" id="motorcycle" role="tabpanel">
                        <div class="row">
                            <?php
                            $motorcycleLicenses = [
                                'M' => ['name' => 'M Sınıfı', 'icon' => 'fas fa-motorcycle', 'desc' => 'Motorlu bisiklet (Moped) kullanımı için'],
                                'A1' => ['name' => 'A1 Sınıfı', 'icon' => 'fas fa-motorcycle', 'desc' => 'Silindir hacmi 125 cc\'ye kadar, gücü 11 kilovatı geçmeyen sepetsiz iki tekerlekli motosikletler'],
                                'A2' => ['name' => 'A2 Sınıfı', 'icon' => 'fas fa-motorcycle', 'desc' => 'Gücü 35 kilovatı geçmeyen, gücü/ağırlığı 0,2 kilovatı/kiloğramı geçmeyen iki tekerlekli motosikletler'],
                                'A' => ['name' => 'A Sınıfı', 'icon' => 'fas fa-motorcycle', 'desc' => 'Gücü 35 kilovatı veya gücü/ağırlığı 0,2 kilovatı/kiloğramı geçen iki tekerlekli motosikletler']
                            ];

                            foreach ($motorcycleLicenses as $code => $details):
                                $hasLicense = !empty($data['hasLicenseTypes'][$code]);
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex border rounded p-3 h-100 <?php echo $hasLicense ? 'border-success bg-light' : ''; ?>">
                                        <div class="license-icon-large me-3 <?php echo $hasLicense ? 'bg-success text-white' : 'bg-light'; ?>">
                                            <i class="<?php echo $details['icon']; ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1">
                                                    <span class="badge bg-info text-white me-2"><?php echo $code; ?></span>
                                                    <?php echo $details['name']; ?>
                                                </h6>
                                                <?php if ($hasLicense): ?>
                                                    <span class="badge bg-success rounded-pill ms-2">
                                                        <i class="fas fa-check me-1"></i> Sahip
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="small text-muted mb-0"><?php echo $details['desc']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="car" role="tabpanel">
                        <div class="row">
                            <?php
                            $carLicenses = [
                                'B1' => ['name' => 'B1 Sınıfı', 'icon' => 'fas fa-car-side', 'desc' => 'Net motor gücü 15 kilovatı ve net ağırlığı 400 kilogram geçmeyen dört tekerlekli motosikletler'],
                                'B' => ['name' => 'B Sınıfı', 'icon' => 'fas fa-car', 'desc' => 'Otomobil ve kamyonet (3500 kg\'a kadar)'],
                                'BE' => ['name' => 'BE Sınıfı', 'icon' => 'fas fa-trailer', 'desc' => 'B sınıfı sürücü belgesi ile sürülebilen otomobil veya kamyonetin römork takılmış hali']
                            ];

                            foreach ($carLicenses as $code => $details):
                                $hasLicense = !empty($data['hasLicenseTypes'][$code]);
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex border rounded p-3 h-100 <?php echo $hasLicense ? 'border-success bg-light' : ''; ?>">
                                        <div class="license-icon-large me-3 <?php echo $hasLicense ? 'bg-success text-white' : 'bg-light'; ?>">
                                            <i class="<?php echo $details['icon']; ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1">
                                                    <span class="badge bg-info text-white me-2"><?php echo $code; ?></span>
                                                    <?php echo $details['name']; ?>
                                                </h6>
                                                <?php if ($hasLicense): ?>
                                                    <span class="badge bg-success rounded-pill ms-2">
                                                        <i class="fas fa-check me-1"></i> Sahip
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="small text-muted mb-0"><?php echo $details['desc']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="truck" role="tabpanel">
                        <div class="row">
                            <?php
                            $truckLicenses = [
                                'C1' => ['name' => 'C1 Sınıfı', 'icon' => 'fas fa-truck', 'desc' => 'Azami yüklü ağırlığı 3.500 kg\'ın üzerinde olan ve 7.500 kg\'ı geçmeyen kamyon ve çekiciler'],
                                'C1E' => ['name' => 'C1E Sınıfı', 'icon' => 'fas fa-truck-moving', 'desc' => 'C1 sınıfı sürücü belgesi ile sürülebilen araçlara takılan ve azami yüklü ağırlığı 750 kg\'ı geçen römorklu kamyonlar'],
                                'C' => ['name' => 'C Sınıfı', 'icon' => 'fas fa-truck', 'desc' => 'Kamyon ve Çekici (Tır)'],
                                'CE' => ['name' => 'CE Sınıfı', 'icon' => 'fas fa-truck-monster', 'desc' => 'C sınıfı sürücü belgesi ile sürülebilen araçlarla römork takılan hali']
                            ];

                            foreach ($truckLicenses as $code => $details):
                                $hasLicense = !empty($data['hasLicenseTypes'][$code]);
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex border rounded p-3 h-100 <?php echo $hasLicense ? 'border-success bg-light' : ''; ?>">
                                        <div class="license-icon-large me-3 <?php echo $hasLicense ? 'bg-success text-white' : 'bg-light'; ?>">
                                            <i class="<?php echo $details['icon']; ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1">
                                                    <span class="badge bg-info text-white me-2"><?php echo $code; ?></span>
                                                    <?php echo $details['name']; ?>
                                                </h6>
                                                <?php if ($hasLicense): ?>
                                                    <span class="badge bg-success rounded-pill ms-2">
                                                        <i class="fas fa-check me-1"></i> Sahip
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="small text-muted mb-0"><?php echo $details['desc']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="bus" role="tabpanel">
                        <div class="row">
                            <?php
                            $busLicenses = [
                                'D1' => ['name' => 'D1 Sınıfı', 'icon' => 'fas fa-shuttle-van', 'desc' => 'Minibüs'],
                                'D1E' => ['name' => 'D1E Sınıfı', 'icon' => 'fas fa-shuttle-van', 'desc' => 'D1 sınıfı sürücü belgesi ile sürülebilen araçlara takılan ve azami yüklü ağırlığı 750 kg\'ı geçen römorklu halı'],
                                'D' => ['name' => 'D Sınıfı', 'icon' => 'fas fa-bus', 'desc' => 'Otobüs'],
                                'DE' => ['name' => 'DE Sınıfı', 'icon' => 'fas fa-bus-alt', 'desc' => 'D sınıfı sürücü belgesi ile sürülebilen araçlara römork takılan hali']
                            ];

                            foreach ($busLicenses as $code => $details):
                                $hasLicense = !empty($data['hasLicenseTypes'][$code]);
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex border rounded p-3 h-100 <?php echo $hasLicense ? 'border-success bg-light' : ''; ?>">
                                        <div class="license-icon-large me-3 <?php echo $hasLicense ? 'bg-success text-white' : 'bg-light'; ?>">
                                            <i class="<?php echo $details['icon']; ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1">
                                                    <span class="badge bg-info text-white me-2"><?php echo $code; ?></span>
                                                    <?php echo $details['name']; ?>
                                                </h6>
                                                <?php if ($hasLicense): ?>
                                                    <span class="badge bg-success rounded-pill ms-2">
                                                        <i class="fas fa-check me-1"></i> Sahip
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="small text-muted mb-0"><?php echo $details['desc']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="other" role="tabpanel">
                        <div class="row">
                            <?php
                            $otherLicenses = [
                                'F' => ['name' => 'F Sınıfı', 'icon' => 'fas fa-tractor', 'desc' => 'Traktör kullanımı için'],
                                'G' => ['name' => 'G Sınıfı', 'icon' => 'fas fa-truck-pickup', 'desc' => 'İş makinası türündeki motorlu araçları kullanabilme']
                            ];

                            foreach ($otherLicenses as $code => $details):
                                $hasLicense = !empty($data['hasLicenseTypes'][$code]);
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex border rounded p-3 h-100 <?php echo $hasLicense ? 'border-success bg-light' : ''; ?>">
                                        <div class="license-icon-large me-3 <?php echo $hasLicense ? 'bg-success text-white' : 'bg-light'; ?>">
                                            <i class="<?php echo $details['icon']; ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1">
                                                    <span class="badge bg-info text-white me-2"><?php echo $code; ?></span>
                                                    <?php echo $details['name']; ?>
                                                </h6>
                                                <?php if ($hasLicense): ?>
                                                    <span class="badge bg-success rounded-pill ms-2">
                                                        <i class="fas fa-check me-1"></i> Sahip
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="small text-muted mb-0"><?php echo $details['desc']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .license-icon-bg {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .license-icon-large {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 10px 20px;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 3px solid #0d6efd;
        font-weight: 600;
        background: transparent;
    }

    .tab-content {
        padding: 20px 0;
    }
    
    /* Uyarı kartı için özel stiller */
    .bg-warning {
        background-color: #ffc107 !important;
    }
    
    .bg-warning h5.text-dark,
    .bg-warning .text-dark {
        color: #212529 !important;
        font-weight: 600;
    }
    
    .text-warning {
        color: #ff9800 !important;
    }
    
    /* Badge stilleri */
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 600;
        font-size: 0.75em;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bootstrap 5 tab initialization
        const tabTriggerList = [].slice.call(document.querySelectorAll('#licenseTypesTab button'));
        tabTriggerList.forEach(function(tabTriggerEl) {
            tabTriggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                const tabTarget = document.querySelector(this.getAttribute('data-bs-target'));

                // Remove active class from all tabs and tab content
                document.querySelectorAll('#licenseTypesTab button').forEach(el => el.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(el => {
                    el.classList.remove('show', 'active');
                });

                // Add active class to current tab and tab content
                this.classList.add('active');
                tabTarget.classList.add('show', 'active');
            });
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>