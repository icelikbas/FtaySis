<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-id-card me-2"></i> <?php echo $data['driver']->name . ' ' . $data['driver']->surname; ?>
                </h2>
                <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Sürücü Listesine Dön
                </a>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <div class="row">
        <!-- Sol Taraf - Kişisel Bilgiler -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i> Kişisel Bilgiler</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-light rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                            <i class="fas fa-user-circle fa-4x text-primary"></i>
                        </div>
                        <h4 class="mt-3"><?php echo $data['driver']->name . ' ' . $data['driver']->surname; ?></h4>
                        <span class="badge <?php 
                            if($data['driver']->status == 'Aktif') echo 'bg-success';
                            elseif($data['driver']->status == 'Pasif') echo 'bg-secondary';
                            elseif($data['driver']->status == 'İzinli') echo 'bg-info';
                        ?> text-white px-3 py-2"><?php echo $data['driver']->status; ?></span>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-id-card text-muted me-2"></i> TC Kimlik:</span>
                            <span class="fw-bold"><?php echo $data['driver']->identity_number; ?></span>
                        </li>
                        <?php if(!empty($data['driver']->birth_date) && $data['driver']->birth_date != '0000-00-00'): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-birthday-cake text-muted me-2"></i> Doğum Tarihi:</span>
                            <span class="fw-bold"><?php echo date('d.m.Y', strtotime($data['driver']->birth_date)); ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if(!empty($data['driver']->company_id)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-building text-muted me-2"></i> Şirket:</span>
                            <span class="fw-bold">
                                <?php 
                                    // Şirket bilgisini göster
                                    $companyModel = new Company();
                                    $company = $companyModel->getCompanyById($data['driver']->company_id);
                                    if($company) {
                                        echo '<a href="' . URLROOT . '/companies/show/' . $company->id . '">' . $company->company_name . '</a>';
                                    } else {
                                        echo 'Belirtilmemiş';
                                    }
                                ?>
                            </span>
                        </li>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-phone text-muted me-2"></i> Telefon:</span>
                            <span class="fw-bold"><?php echo $data['driver']->phone; ?></span>
                        </li>
                        <?php if(!empty($data['driver']->email)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-envelope text-muted me-2"></i> E-posta:</span>
                            <span class="fw-bold"><?php echo $data['driver']->email; ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if(!empty($data['driver']->address)): ?>
                        <li class="list-group-item px-0">
                            <div><i class="fas fa-map-marker-alt text-muted me-2"></i> Adres:</div>
                            <div class="ps-4 mt-1 text-muted"><?php echo $data['driver']->address; ?></div>
                        </li>
                        <?php endif; ?>
                        <?php if(!empty($data['driver']->notes)): ?>
                        <li class="list-group-item px-0">
                            <div><i class="fas fa-sticky-note text-muted me-2"></i> Notlar:</div>
                            <div class="ps-4 mt-1 text-muted"><?php echo html_entity_decode($data['driver']->notes); ?></div>
                        </li>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-calendar text-muted me-2"></i> Kayıt Tarihi:</span>
                            <span class="fw-bold"><?php echo date('d.m.Y', strtotime($data['driver']->created_at)); ?></span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?php echo URLROOT; ?>/drivers/edit/<?php echo $data['driver']->id; ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                    <?php if(isAdmin()) : ?>
                    <form action="<?php echo URLROOT; ?>/drivers/delete/<?php echo $data['driver']->id; ?>" method="post">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bu şoförü silmek istediğinize emin misiniz?');">
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sağ Taraf - Ehliyet ve Görevlendirmeler -->
        <div class="col-md-8">
            <!-- Ehliyet Bilgileri -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-id-card me-2"></i> Ehliyet Bilgileri</h5>
                    <a href="<?php echo URLROOT; ?>/licenses/index/<?php echo $data['driver']->id; ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-edit"></i> Ehliyet Bilgilerini Yönet
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="border-start ps-3 border-primary">
                                <h6 class="text-muted">Ehliyet No</h6>
                                <h5><?php echo $data['driver']->license_number; ?></h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start ps-3 border-primary">
                                <h6 class="text-muted">Birincil Ehliyet</h6>
                                <h5><span class="badge bg-primary text-white px-3 py-2"><?php echo $data['driver']->primary_license_type; ?></span></h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start ps-3 border-primary">
                                <h6 class="text-muted">Geçerlilik Tarihi</h6>
                                <h5>
                                <?php 
                                    if(empty($data['driver']->license_expiry_date) || $data['driver']->license_expiry_date == '0000-00-00') {
                                        echo '<span class="text-muted font-italic">Belirtilmemiş</span>';
                                    } else {
                                        echo date('d/m/Y', strtotime($data['driver']->license_expiry_date));
                                    }
                                ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="text-primary border-bottom pb-2 mb-3">Ehliyet Sınıfları</h6>
                    
                    <?php if(empty($data['licenseTypes'])) : ?>
                        <div class="alert alert-light border text-center">
                            <i class="fas fa-info-circle"></i> Herhangi bir ehliyet sınıfı kaydı bulunmamaktadır.
                        </div>
                    <?php else : ?>
                        <div class="row">
                            <?php foreach($data['licenseTypes'] as $license) : ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-0 shadow-sm hover-shadow">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-info text-white p-2 mr-2"><?php echo $license->code; ?></span>
                                            <h6 class="mb-0"><?php echo $license->name; ?></h6>
                                        </div>
                                        
                                        <?php if(!empty($license->issue_date) && $license->issue_date != '0000-00-00') : ?>
                                        <div class="small mb-1 d-flex align-items-center">
                                            <i class="fas fa-calendar-plus text-success me-2"></i> 
                                            <span>Verilme: <?php echo date('d/m/Y', strtotime($license->issue_date)); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($license->expiry_date) && $license->expiry_date != '0000-00-00') : ?>
                                        <div class="small mb-1 d-flex align-items-center">
                                            <i class="fas fa-calendar-times text-danger me-2"></i> 
                                            <span>
                                                Geçerlilik: <?php echo date('d/m/Y', strtotime($license->expiry_date)); ?>
                                                <?php if(strtotime($license->expiry_date) < time()) : ?>
                                                <span class="badge bg-danger ms-1">Süresi Dolmuş</span>
                                                <?php elseif(strtotime($license->expiry_date) < strtotime('+3 months')) : ?>
                                                <span class="badge bg-warning ms-1">Yakında Dolacak</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($license->notes)) : ?>
                                        <div class="small text-muted mt-2 fst-italic">
                                            <i class="fas fa-sticky-note me-1"></i> <?php echo $license->notes; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sertifikalar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-certificate me-2"></i> Sertifikalar</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($data['certificates'])) : ?>
                        <div class="alert alert-light border text-center">
                            <i class="fas fa-info-circle"></i> Bu şoföre ait sertifika kaydı bulunmamaktadır.
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo URLROOT; ?>/certificates/add/<?php echo $data['driver']->id; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Yeni Sertifika Ekle
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover mini-table" id="certificatesTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Sertifika Türü</th>
                                        <th>Sertifika No</th>
                                        <th>Veren Kurum</th>
                                        <th>Geçerlilik Tarihi</th>
                                        <th>Durum</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['certificates'] as $certificate): ?>
                                    <tr>
                                        <td><?php echo $certificate->certificate_type_name; ?></td>
                                        <td><span class="badge bg-secondary text-white"><?php echo $certificate->certificate_number; ?></span></td>
                                        <td><?php echo !empty($certificate->issuing_authority) ? $certificate->issuing_authority : '<span class="text-muted">Belirtilmemiş</span>'; ?></td>
                                        <td>
                                            <?php 
                                            if(!empty($certificate->expiry_date) && $certificate->expiry_date != '0000-00-00') {
                                                echo date('d/m/Y', strtotime($certificate->expiry_date));
                                            } else {
                                                echo '<span class="text-muted">Belirtilmemiş</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if(!empty($certificate->expiry_date) && $certificate->expiry_date != '0000-00-00') {
                                                if(strtotime($certificate->expiry_date) < time()) {
                                                    echo '<span class="badge bg-danger text-white">Süresi Dolmuş</span>';
                                                } else if(strtotime($certificate->expiry_date) < strtotime('+3 months')) {
                                                    echo '<span class="badge bg-warning text-white">Yakında Dolacak</span>';
                                                } else {
                                                    echo '<span class="badge bg-success text-white">Geçerli</span>';
                                                }
                                            } else {
                                                echo '<span class="badge bg-secondary text-white">Belirsiz</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/certificates/edit/<?php echo $data['driver']->id; ?>/<?php echo $certificate->id; ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo URLROOT; ?>/certificates/add/<?php echo $data['driver']->id; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Yeni Sertifika Ekle
                            </a>
                            <a href="<?php echo URLROOT; ?>/certificates/index/<?php echo $data['driver']->id; ?>" class="btn btn-secondary btn-sm ms-2">
                                <i class="fas fa-list"></i> Tüm Sertifikaları Görüntüle
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Görevlendirmeler -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-clipboard-list me-2"></i> Araç Görevlendirmeleri</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($data['assignments'])) : ?>
                        <div class="alert alert-light border text-center">
                            <i class="fas fa-info-circle"></i> Bu şoföre ait aktif görevlendirme bulunmamaktadır.
                        </div>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover mini-table" id="assignmentsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Araç</th>
                                        <th>Plaka</th>
                                        <th>Başlangıç</th>
                                        <th>Bitiş</th>
                                        <th>Durum</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['assignments'] as $assignment) : ?>
                                        <tr>
                                            <td><?php echo $assignment->id; ?></td>
                                            <td><?php echo $assignment->brand . ' ' . $assignment->model; ?></td>
                                            <td><span class="badge bg-dark text-white p-2"><?php echo $assignment->plate_number; ?></span></td>
                                            <td><?php echo date('d.m.Y', strtotime($assignment->start_date)); ?></td>
                                            <td>
                                                <?php echo ($assignment->end_date) ? date('d.m.Y', strtotime($assignment->end_date)) : '<span class="text-muted">-</span>'; ?>
                                            </td>
                                            <td>
                                                <?php if($assignment->status == 'Aktif') : ?>
                                                    <span class="badge bg-success text-white">Aktif</span>
                                                <?php elseif($assignment->status == 'Tamamlandı') : ?>
                                                    <span class="badge bg-secondary text-white">Tamamlandı</span>
                                                <?php elseif($assignment->status == 'İptal') : ?>
                                                    <span class="badge bg-danger text-white">İptal</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/assignments/show/<?php echo $assignment->id; ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detaylar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo URLROOT; ?>/assignments/add" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> Yeni Görevlendirme Ekle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(to right, #4e73df, #224abe);
    }
    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        transition: all 0.3s;
    }
</style>

<script>
    // Bootstrap 5 tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover'
            });
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 