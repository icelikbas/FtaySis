<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-certificate mr-2"></i> Sertifika Yönetimi
                </h2>
            </div>
            <hr class="bg-primary">
            
            <!-- Butonlar -->
            <div class="mb-3">
                <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-1"></i> Sürücü Seç ve Sertifika Ekle
                </a>
                <a href="<?php echo URLROOT; ?>/certificateTypes" class="btn btn-success ml-2">
                    <i class="fas fa-list-alt mr-1"></i> Sertifika Türlerini Yönet
                </a>
                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#sertifikaEklemeYardimModal">
                    <i class="fas fa-question-circle mr-1"></i> Nasıl Sertifika Eklerim?
                </button>
            </div>
        </div>
    </div>

    <!-- Sürücüler Tablosu (Önce Gelecek) -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users mr-2"></i> Sertifika Sahibi Sürücüler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <input type="text" id="searchDrivers" class="form-control" placeholder="Sürücü adı veya sertifika türü ara...">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="driversTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Sürücü Adı</th>
                                    <th>Telefon</th>
                                    <th>Sertifika Sayısı</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data['driverCertificateStats'])) : ?>
                                    <?php foreach($data['driverCertificateStats'] as $driver) : ?>
                                        <tr>
                                            <td><?php echo $driver->id; ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>" class="text-decoration-none font-weight-bold">
                                                    <?php echo $driver->name . ' ' . $driver->surname; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $driver->phone; ?></td>
                                            <td>
                                                <span class="badge bg-primary text-white"><?php echo $driver->certificate_count; ?></span>
                                                <?php if($driver->expired_count > 0) : ?>
                                                    <span class="badge bg-danger text-white ml-1" data-toggle="tooltip" title="Süresi Dolmuş"><?php echo $driver->expired_count; ?></span>
                                                <?php endif; ?>
                                                <?php if($driver->soon_expiring_count > 0) : ?>
                                                    <span class="badge bg-warning text-white ml-1" data-toggle="tooltip" title="Yakında Dolacak"><?php echo $driver->soon_expiring_count; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php 
                                                    if($driver->status == 'Aktif') echo 'bg-success';
                                                    elseif($driver->status == 'Pasif') echo 'bg-secondary';
                                                    elseif($driver->status == 'İzinli') echo 'bg-info';
                                                ?> text-white"><?php echo $driver->status; ?></span>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/certificates/index/<?php echo $driver->id; ?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Sertifikaları Görüntüle">
                                                    <i class="fas fa-certificate"></i>
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/certificates/add/<?php echo $driver->id; ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Yeni Sertifika Ekle">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Sertifika kaydı bulunan sürücü bulunmamaktadır.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Süresi Dolmuş ve Yakında Dolacak Sertifikalar (Alt Tarafta Tek Satırda) -->
    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Süresi Dolmuş Sertifikalar
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(empty($data['expiredCertificates'])) : ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle fa-2x mb-3 d-block"></i> 
                            <p>Süresi dolmuş sertifika bulunmamaktadır.</p>
                        </div>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-expired" id="expiredTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Sürücü</th>
                                        <th>Sertifika Türü</th>
                                        <th>Geçerlilik Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['expiredCertificates'] as $certificate) : ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $certificate->driver_id; ?>" class="text-decoration-none">
                                                    <?php echo $certificate->name . ' ' . $certificate->surname; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $certificate->certificate_type_name; ?></td>
                                            <td>
                                                <span class="badge bg-danger text-white">
                                                    <?php echo date('d/m/Y', strtotime($certificate->expiry_date)); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/certificates/edit/<?php echo $certificate->driver_id; ?>/<?php echo $certificate->id; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/certificates/index/<?php echo $certificate->driver_id; ?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Tüm Sertifikaları Gör">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock mr-2"></i> Süresi Yakında Dolacak Sertifikalar (30 Gün)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(empty($data['soonExpiringCertificates'])) : ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle fa-2x mb-3 d-block"></i> 
                            <p>Yakında süresi dolacak sertifika bulunmamaktadır.</p>
                        </div>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-soon-expiring" id="soonExpiringTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Sürücü</th>
                                        <th>Sertifika Türü</th>
                                        <th>Geçerlilik Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['soonExpiringCertificates'] as $certificate) : ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $certificate->driver_id; ?>" class="text-decoration-none">
                                                    <?php echo $certificate->name . ' ' . $certificate->surname; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $certificate->certificate_type_name; ?></td>
                                            <td>
                                                <span class="badge bg-warning text-white">
                                                    <?php echo date('d/m/Y', strtotime($certificate->expiry_date)); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/certificates/edit/<?php echo $certificate->driver_id; ?>/<?php echo $certificate->id; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/certificates/index/<?php echo $certificate->driver_id; ?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Tüm Sertifikaları Gör">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ana sürücüler tablosunu başlat
        const driversTable = initDataTable('driversTable', {
            "pageLength": 10,
            "responsive": true,
            "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [
                'excel', 'pdf', 'print'
            ],
            "columnDefs": [
                { "orderable": false, "targets": 5 } // İşlemler sütunu sıralanabilir olmasın
            ]
        });
        
        // Süresi dolmuş sertifikalar tablosunu başlat
        const expiredTable = initDataTable('expiredTable', {
            "pageLength": 5,
            "info": false,
            "searching": false,
            "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
            "responsive": true,
            "dom": "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12'p>>",
            "lengthChange": false
        });
        
        // Yakında süresi dolacak sertifikalar tablosunu başlat
        const soonExpiringTable = initDataTable('soonExpiringTable', {
            "pageLength": 5,
            "info": false,
            "searching": false,
            "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
            "responsive": true,
            "dom": "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12'p>>",
            "lengthChange": false
        });
        
        // Tooltips aktifleştirme
        $('[data-toggle="tooltip"]').tooltip();
        
        // Arama işlevi (DataTables kullanılamıyorsa veya harici arama kutusu için)
        $("#searchDrivers").on("keyup", function() {
            if (driversTable) {
                driversTable.search($(this).val()).draw();
            } else {
                // Alternatif arama (DataTable yoksa)
                var value = $(this).val().toLowerCase();
                $("#driversTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            }
        });
        
        // Hata yakalama
        window.addEventListener('error', function(e) {
            if (e.message && e.message.includes('DataTables')) {
                console.error('DataTables hatası yakalandı:', e.message);
                // Temel tablo fonksiyonelliğini sağla
                $('.data-table').addClass('table-responsive');
            }
        });
    });
</script>

<style>
    .bg-danger, .bg-warning, .bg-primary {
        background-image: linear-gradient(to right, #4e73df, #224abe);
    }
    .bg-danger {
        background-image: linear-gradient(to right, #e74a3b, #c0392b) !important;
    }
    .bg-warning {
        background-image: linear-gradient(to right, #f6c23e, #e67e22) !important;
    }
    
    .table-responsive {
        min-height: 200px;
    }
    
    /* DataTables için ek stiller */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.5rem;
        margin-left: 2px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: linear-gradient(to right, #4e73df, #224abe);
        color: white !important;
        border-color: #4e73df;
    }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<!-- Yardım Modalı -->
<div class="modal fade" id="sertifikaEklemeYardimModal" tabindex="-1" role="dialog" aria-labelledby="sertifikaEklemeYardimModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="sertifikaEklemeYardimModalLabel">
                    <i class="fas fa-question-circle mr-2"></i> İş Makinesi Sertifikası Nasıl Eklenir?
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h5 class="font-weight-bold mb-3">İş makinesi sertifikası eklemek için 3 kolay adım:</h5>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <span class="badge badge-primary mr-2">1</span> Sürücü Seçimi
                                </h6>
                                <p class="card-text">
                                    İş makinesi sertifikası eklemek için öncelikle bir sürücü seçmelisiniz. Bunu iki şekilde yapabilirsiniz:
                                </p>
                                <ul>
                                    <li>Bu sayfadaki "Sürücü Seç ve Sertifika Ekle" butonuna tıklayarak sürücüler listesine gidin.</li>
                                    <li>Aşağıdaki tabloda sertifika eklemek istediğiniz sürücünün yanındaki <i class="fas fa-plus text-success"></i> butonuna tıklayın.</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <span class="badge badge-primary mr-2">2</span> Sürücü Detay Sayfası
                                </h6>
                                <p class="card-text">
                                    Sürücü detay sayfasında "İş Makinesi Sertifikaları" kartını bulun ve "Yeni Sertifika Ekle" butonuna tıklayın.
                                </p>
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <span class="badge badge-primary mr-2">3</span> Sertifika Bilgilerini Girin
                                </h6>
                                <p class="card-text">
                                    Açılan formda istenen sertifika bilgilerini (ad, numara, tarihler vb.) doldurun ve "Sertifika Ekle" butonuna tıklayın.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-1"></i> Sürücü Seç
                </a>
            </div>
        </div>
    </div>
</div> 