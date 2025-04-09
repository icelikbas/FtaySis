<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-certificate mr-2"></i> <?php echo $data['driver']->name . ' ' . $data['driver']->surname; ?> - Sertifikalar
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $data['driver']->id; ?>" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-user"></i> Sürücü Detayına Dön
                    </a>
                    <a href="<?php echo URLROOT; ?>/certificates/add/<?php echo $data['driver']->id; ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Yeni Sertifika Ekle
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php flash('success'); ?>
            <?php flash('error'); ?>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i> Sertifika Listesi
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(empty($data['certificates'])) : ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3 d-block"></i> 
                            <p>Bu sürücüye ait sertifika henüz eklenmemiş.</p>
                            <a href="<?php echo URLROOT; ?>/certificates/add/<?php echo $data['driver']->id; ?>" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> İlk Sertifikayı Ekle
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="certificatesTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Sertifika Türü</th>
                                        <th>Sertifika No</th>
                                        <th>Veren Kurum</th>
                                        <th>Veriliş Tarihi</th>
                                        <th>Geçerlilik Tarihi</th>
                                        <th>Durum</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['certificates'] as $certificate) : ?>
                                        <tr>
                                            <td><?php echo $certificate->certificate_type_name; ?></td>
                                            <td><span class="badge bg-secondary text-white"><?php echo $certificate->certificate_number; ?></span></td>
                                            <td><?php echo !empty($certificate->issuing_authority) ? $certificate->issuing_authority : '<span class="text-muted">Belirtilmemiş</span>'; ?></td>
                                            <td>
                                                <?php 
                                                if(!empty($certificate->issue_date) && $certificate->issue_date != '0000-00-00') {
                                                    echo date('d/m/Y', strtotime($certificate->issue_date));
                                                } else {
                                                    echo '<span class="text-muted">Belirtilmemiş</span>';
                                                }
                                                ?>
                                            </td>
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
                                                <a href="<?php echo URLROOT; ?>/certificates/edit/<?php echo $data['driver']->id; ?>/<?php echo $certificate->id; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo URLROOT; ?>/certificates/delete/<?php echo $data['driver']->id; ?>/<?php echo $certificate->id; ?>" method="post" class="d-inline">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu sertifikayı silmek istediğinize emin misiniz?');" data-toggle="tooltip" title="Sil">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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

<style>
    .bg-gradient-primary {
        background: linear-gradient(to right, #4e73df, #224abe);
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTables'ı başlat
        const certificatesTable = initDataTable('certificatesTable', {
            // Özel seçenekler buraya eklenebilir
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": 5 } // İşlemler sütunu sıralanabilir olmasın
            ]
        });
        
        // Tooltips aktifleştirme
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 