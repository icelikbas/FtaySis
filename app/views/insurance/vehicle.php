<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="mb-4 mt-3 px-2 py-2 bg-light rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mt-2 mb-0 display-6 fw-bold text-primary">
                    <i class="fas fa-car text-primary me-2"></i><?php echo $data['vehicle']->plate_number; ?>
                </h1>
                <p class="lead mb-2 text-muted"><?php echo $data['vehicle']->brand . ' ' . $data['vehicle']->model . ' (' . $data['vehicle']->year . ')'; ?></p>
            </div>
            <div class="text-end">
                <a href="<?php echo URLROOT; ?>/insurance" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Tüm Araçlara Dön
                </a>
                <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Düzenle
                </a>
            </div>
        </div>
    </div>
    
    <!-- Üst Bilgi Kartları -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-car me-1"></i>
                    <strong>Muayene Bilgileri</strong>
                </div>
                <div class="card-body pb-0">
                    <div class="text-center mb-3">
                        <?php if(!empty($data['vehicle']->inspection_date)): ?>
                            <div class="fs-1 
                                <?php if($data['inspectionDaysLeft'] < 0): ?>
                                    text-danger
                                <?php elseif($data['inspectionDaysLeft'] <= 30): ?>
                                    text-warning
                                <?php else: ?>
                                    text-success
                                <?php endif; ?>">
                                <?php if($data['inspectionDaysLeft'] < 0): ?>
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="fs-4 d-block">Süresi Geçti!</span>
                                <?php elseif($data['inspectionDaysLeft'] <= 30): ?>
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span class="fs-4 d-block">Yaklaşıyor!</span>
                                <?php else: ?>
                                    <i class="fas fa-check-circle"></i>
                                    <span class="fs-4 d-block">Güncel</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-2 p-2 border-top">
                                <div class="fs-5 fw-bold">Muayene Tarihi</div>
                                <div class="fs-4"><?php echo date('d.m.Y', strtotime($data['vehicle']->inspection_date)); ?></div>
                                
                                <?php if($data['inspectionDaysLeft'] < 0): ?>
                                    <div class="badge bg-danger fs-6 mt-2 rounded-pill px-3 py-2">
                                        <?php echo abs($data['inspectionDaysLeft']); ?> gün geçmiş
                                    </div>
                                <?php else: ?>
                                    <div class="badge <?php echo $data['inspectionDaysLeft'] <= 30 ? 'bg-warning' : 'bg-success'; ?> fs-6 mt-2 rounded-pill px-3 py-2">
                                        <?php echo $data['inspectionDaysLeft']; ?> gün kaldı
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="fs-1 text-muted">
                                <i class="fas fa-question-circle"></i>
                                <span class="fs-4 d-block">Belirtilmemiş</span>
                            </div>
                            <div class="alert alert-info mt-3">
                                Bu araç için muayene tarihi belirtilmemiş. Bilgileri güncellemek için <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="alert-link">düzenleme sayfasına</a> gidin.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="btn btn-primary btn-sm w-75">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-file-contract me-1"></i>
                    <strong>Trafik Sigortası Bilgileri</strong>
                </div>
                <div class="card-body pb-0">
                    <div class="text-center mb-3">
                        <?php if(!empty($data['vehicle']->traffic_insurance_date)): ?>
                            <div class="fs-1 
                                <?php if($data['trafficDaysLeft'] < 0): ?>
                                    text-danger
                                <?php elseif($data['trafficDaysLeft'] <= 30): ?>
                                    text-warning
                                <?php else: ?>
                                    text-success
                                <?php endif; ?>">
                                <?php if($data['trafficDaysLeft'] < 0): ?>
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="fs-4 d-block">Süresi Geçti!</span>
                                <?php elseif($data['trafficDaysLeft'] <= 30): ?>
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span class="fs-4 d-block">Yaklaşıyor!</span>
                                <?php else: ?>
                                    <i class="fas fa-check-circle"></i>
                                    <span class="fs-4 d-block">Güncel</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-2 p-2 border-top">
                                <div class="fs-5 fw-bold">Sigorta Tarihi</div>
                                <div class="fs-4"><?php echo date('d.m.Y', strtotime($data['vehicle']->traffic_insurance_date)); ?></div>
                                
                                <?php if(!empty($data['vehicle']->traffic_insurance_agency)): ?>
                                    <div class="mt-1 text-muted">
                                        <strong>Sigorta Şirketi:</strong> <?php echo $data['vehicle']->traffic_insurance_agency; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($data['trafficDaysLeft'] < 0): ?>
                                    <div class="badge bg-danger fs-6 mt-2 rounded-pill px-3 py-2">
                                        <?php echo abs($data['trafficDaysLeft']); ?> gün geçmiş
                                    </div>
                                <?php else: ?>
                                    <div class="badge <?php echo $data['trafficDaysLeft'] <= 30 ? 'bg-warning' : 'bg-success'; ?> fs-6 mt-2 rounded-pill px-3 py-2">
                                        <?php echo $data['trafficDaysLeft']; ?> gün kaldı
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="fs-1 text-muted">
                                <i class="fas fa-question-circle"></i>
                                <span class="fs-4 d-block">Belirtilmemiş</span>
                            </div>
                            <div class="alert alert-info mt-3">
                                Bu araç için trafik sigortası bilgisi belirtilmemiş. Bilgileri güncellemek için <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="alert-link">düzenleme sayfasına</a> gidin.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="btn btn-primary btn-sm w-75">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-shield-alt me-1"></i>
                    <strong>Kasko Sigortası Bilgileri</strong>
                </div>
                <div class="card-body pb-0">
                    <div class="text-center mb-3">
                        <?php if(!empty($data['vehicle']->casco_insurance_date)): ?>
                            <div class="fs-1 
                                <?php if($data['cascoDaysLeft'] < 0): ?>
                                    text-danger
                                <?php elseif($data['cascoDaysLeft'] <= 30): ?>
                                    text-warning
                                <?php else: ?>
                                    text-success
                                <?php endif; ?>">
                                <?php if($data['cascoDaysLeft'] < 0): ?>
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="fs-4 d-block">Süresi Geçti!</span>
                                <?php elseif($data['cascoDaysLeft'] <= 30): ?>
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span class="fs-4 d-block">Yaklaşıyor!</span>
                                <?php else: ?>
                                    <i class="fas fa-check-circle"></i>
                                    <span class="fs-4 d-block">Güncel</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-2 p-2 border-top">
                                <div class="fs-5 fw-bold">Sigorta Tarihi</div>
                                <div class="fs-4"><?php echo date('d.m.Y', strtotime($data['vehicle']->casco_insurance_date)); ?></div>
                                
                                <?php if(!empty($data['vehicle']->casco_insurance_agency)): ?>
                                    <div class="mt-1 text-muted">
                                        <strong>Sigorta Şirketi:</strong> <?php echo $data['vehicle']->casco_insurance_agency; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($data['cascoDaysLeft'] < 0): ?>
                                    <div class="badge bg-danger fs-6 mt-2 rounded-pill px-3 py-2">
                                        <?php echo abs($data['cascoDaysLeft']); ?> gün geçmiş
                                    </div>
                                <?php else: ?>
                                    <div class="badge <?php echo $data['cascoDaysLeft'] <= 30 ? 'bg-warning' : 'bg-success'; ?> fs-6 mt-2 rounded-pill px-3 py-2">
                                        <?php echo $data['cascoDaysLeft']; ?> gün kaldı
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="fs-1 text-muted">
                                <i class="fas fa-question-circle"></i>
                                <span class="fs-4 d-block">Belirtilmemiş</span>
                            </div>
                            <div class="alert alert-info mt-3">
                                Bu araç için kasko sigortası bilgisi belirtilmemiş. Bilgileri güncellemek için <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="alert-link">düzenleme sayfasına</a> gidin.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="btn btn-primary btn-sm w-75">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Araç Bilgileri Kartı -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-info-circle me-1"></i>
            <strong>Araç Bilgileri</strong>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-6 border-end">
                    <div class="p-3">
                        <h5 class="border-bottom pb-2 mb-3 text-primary"><i class="fas fa-car me-2"></i>Araç Detayları</h5>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Plaka:</div>
                            <div class="col-7"><?php echo $data['vehicle']->plate_number; ?></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Marka/Model:</div>
                            <div class="col-7"><?php echo $data['vehicle']->brand . ' ' . $data['vehicle']->model; ?></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Yıl:</div>
                            <div class="col-7"><?php echo $data['vehicle']->year; ?></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Araç Tipi:</div>
                            <div class="col-7"><?php echo $data['vehicle']->vehicle_type; ?></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Durum:</div>
                            <div class="col-7">
                                <span class="badge <?php 
                                    switch($data['vehicle']->status) {
                                        case 'Aktif':
                                            echo 'bg-success';
                                            break;
                                        case 'Bakımda':
                                            echo 'bg-warning';
                                            break;
                                        case 'Arızalı':
                                            echo 'bg-danger';
                                            break;
                                        case 'Pasif':
                                            echo 'bg-secondary';
                                            break;
                                        default:
                                            echo 'bg-info';
                                    }
                                ?>">
                                    <?php echo $data['vehicle']->status; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">
                        <h5 class="border-bottom pb-2 mb-3 text-primary"><i class="fas fa-building me-2"></i>Kurumsal Bilgiler</h5>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Şirket:</div>
                            <div class="col-7"><?php echo $data['company'] ? $data['company']->company_name : '<span class="text-muted">Belirtilmemiş</span>'; ?></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Sipariş No:</div>
                            <div class="col-7"><?php echo !empty($data['vehicle']->order_number) ? $data['vehicle']->order_number : '<span class="text-muted">Belirtilmemiş</span>'; ?></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Ekipman No:</div>
                            <div class="col-7"><?php echo !empty($data['vehicle']->equipment_number) ? $data['vehicle']->equipment_number : '<span class="text-muted">Belirtilmemiş</span>'; ?></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Sabit Kıymet No:</div>
                            <div class="col-7"><?php echo !empty($data['vehicle']->fixed_asset_number) ? $data['vehicle']->fixed_asset_number : '<span class="text-muted">Belirtilmemiş</span>'; ?></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5 fw-bold text-secondary">Maliyet Merkezi:</div>
                            <div class="col-7"><?php echo !empty($data['vehicle']->cost_center) ? $data['vehicle']->cost_center : '<span class="text-muted">Belirtilmemiş</span>'; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light d-flex justify-content-end">
            <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['vehicle']->id; ?>" class="btn btn-info btn-sm me-2">
                <i class="fas fa-eye"></i> Detayları Görüntüle
            </a>
            <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Düzenle
            </a>
        </div>
    </div>
    
    <!-- Hatırlatma Ayarları -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-bell me-1"></i>
            <strong>Hatırlatma Ayarları</strong>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>Bu araç için özel hatırlatma ayarlarını yapılandırabilirsiniz. Yaklaşan sigorta ve muayene tarihleri için e-posta bildirimleri alabilirsiniz.</div>
                </div>
            </div>
            
            <form id="reminderForm">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card h-100 border shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-3 border-bottom pb-2"><i class="fas fa-car me-2"></i>Muayene</h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="inspectionReminder" checked>
                                    <label class="form-check-label" for="inspectionReminder">Muayene Hatırlatmaları</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-3 border-bottom pb-2"><i class="fas fa-file-contract me-2"></i>Trafik</h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="trafficInsuranceReminder" checked>
                                    <label class="form-check-label" for="trafficInsuranceReminder">Trafik Sigortası Hatırlatmaları</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-3 border-bottom pb-2"><i class="fas fa-shield-alt me-2"></i>Kasko</h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cascoInsuranceReminder" checked>
                                    <label class="form-check-label" for="cascoInsuranceReminder">Kasko Sigortası Hatırlatmaları</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="reminderEmail" placeholder="ornek@sirket.com">
                            <label for="reminderEmail">E-posta Adresi</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="reminderDays">
                                <option value="7,3,1">7, 3 ve 1 gün kala</option>
                                <option value="30,15,7,1">30, 15, 7 ve 1 gün kala</option>
                                <option value="60,30,15,7,1">60, 30, 15, 7 ve 1 gün kala</option>
                                <option value="custom">Özel</option>
                            </select>
                            <label for="reminderDays">Hatırlatma Günleri</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 d-none" id="customDaysContainer">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="customDays" placeholder="30,15,7,1">
                        <label for="customDays">Özel Hatırlatma Günleri (virgülle ayırın)</label>
                    </div>
                    <div class="form-text mt-1">Tarihin dolmasına kaç gün kala hatırlatma almak istediğinizi belirtin.</div>
                </div>
                
                <div class="text-center">
                    <button type="button" class="btn btn-primary btn-lg px-5" id="saveReminderSettings">
                        <i class="fas fa-save me-1"></i> Ayarları Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Durumları renklendirme fonksiyonu
        function applyStatusStyles() {
            if (window.InsuranceHelpers) {
                // Muayene durumu
                const inspectionDays = <?php echo $data['inspectionDaysLeft']; ?>;
                const inspectionStatus = document.querySelector('.card:nth-child(1) .fs-1');
                if (inspectionStatus) {
                    // Mevcut sınıfları temizle
                    inspectionStatus.classList.remove('text-danger', 'text-warning', 'text-success', 'text-muted');
                    // Yeni duruma göre sınıf ekle
                    const statusInfo = window.InsuranceHelpers.getDaysStatus(inspectionDays);
                    inspectionStatus.classList.add(statusInfo.class);
                }
                
                // Trafik sigortası durumu
                const trafficDays = <?php echo $data['trafficDaysLeft']; ?>;
                const trafficStatus = document.querySelector('.card:nth-child(2) .fs-1');
                if (trafficStatus) {
                    trafficStatus.classList.remove('text-danger', 'text-warning', 'text-success', 'text-muted');
                    const statusInfo = window.InsuranceHelpers.getDaysStatus(trafficDays);
                    trafficStatus.classList.add(statusInfo.class);
                }
                
                // Kasko sigortası durumu
                const cascoDays = <?php echo $data['cascoDaysLeft']; ?>;
                const cascoStatus = document.querySelector('.card:nth-child(3) .fs-1');
                if (cascoStatus) {
                    cascoStatus.classList.remove('text-danger', 'text-warning', 'text-success', 'text-muted');
                    const statusInfo = window.InsuranceHelpers.getDaysStatus(cascoDays);
                    cascoStatus.classList.add(statusInfo.class);
                }
            }
        }
        
        // Durumları uygula
        if (window.InsuranceHelpers) {
            applyStatusStyles();
        }
        
        // Özel gün seçimi görünürlüğü
        document.getElementById('reminderDays').addEventListener('change', function() {
            const customDaysContainer = document.getElementById('customDaysContainer');
            if (this.value === 'custom') {
                customDaysContainer.classList.remove('d-none');
            } else {
                customDaysContainer.classList.add('d-none');
            }
        });
        
        // Hatırlatma ayarlarını kaydet butonu
        document.getElementById('saveReminderSettings').addEventListener('click', function() {
            // Burada backend tarafında işlem yapılacak
            alert('Hatırlatma ayarları kaydedildi.');
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 