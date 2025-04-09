<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-building text-primary mr-2"></i>Şirketler
        </h1>
        <a href="<?php echo URLROOT; ?>/companies/add" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm mr-2"></i> Yeni Şirket Ekle
        </a>
    </div>

    <?php flash('company_message'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Şirket Listesi
                    </h6>
                    <div>
                        <button id="btnKartGorunum" class="btn btn-sm btn-outline-primary mr-2 active">
                            <i class="fas fa-th-large mr-1"></i> Kart Görünümü
                        </button>
                        <button id="btnTabloGorunum" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-table mr-1"></i> Tablo Görünümü
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Kart Görünümü -->
                    <div id="kartGorunum" class="row">
                        <?php if(empty($data['companies'])): ?>
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-info mb-3"></i>
                                <h5>Henüz şirket kaydı bulunmamaktadır.</h5>
                                <p>Yeni bir şirket eklemek için "Yeni Şirket Ekle" butonunu kullanabilirsiniz.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($data['companies'] as $company): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 shadow company-card transition-all border-<?php echo $company->status === 'Aktif' ? 'success' : 'danger'; ?>">
                                    <!-- Kartın üst kısmı - Logo bölümü -->
                                    <div class="card-img-top text-center bg-light p-3 border-bottom">
                                        <?php if (!empty($company->logo_url)): ?>
                                            <img src="<?php echo URLROOT . '/' . htmlspecialchars($company->logo_url); ?>" 
                                                 alt="Logo" class="company-logo img-fluid rounded company-logo-img" 
                                                 style="max-height: 90px; max-width: 200px;">
                                        <?php else: ?>
                                            <div class="company-logo-placeholder d-inline-flex align-items-center justify-content-center rounded shadow-sm">
                                                <i class="fas fa-building fa-3x text-primary opacity-75"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <h5 class="card-title font-weight-bold text-primary mb-0">
                                                <?php echo htmlspecialchars($company->company_name); ?>
                                            </h5>
                                            <span class="badge badge-pill px-3 py-2 mt-2 badge-<?php echo $company->status === 'Aktif' ? 'success' : 'danger'; ?>">
                                                <?php echo htmlspecialchars($company->status); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="company-details mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-landmark text-primary fa-fw me-2"></i>
                                                <span class="text-muted">
                                                    <?php echo htmlspecialchars($company->tax_office); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-hashtag text-primary fa-fw me-2"></i>
                                                <span class="text-muted">
                                                    VN: <?php echo htmlspecialchars($company->tax_number); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-phone text-primary fa-fw me-2"></i>
                                                <span class="text-muted">
                                                    <?php echo htmlspecialchars($company->phone ?? '-'); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-envelope text-primary fa-fw me-2"></i>
                                                <span class="text-muted text-truncate">
                                                    <?php echo htmlspecialchars($company->email ?? '-'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- İstatistik bölümü -->
                                        <div class="d-flex justify-content-between text-center mb-3">
                                            <div class="border rounded p-2 flex-fill mx-1 shadow-sm bg-light">
                                                <small class="d-block text-muted fw-bold">Personel</small>
                                                <div class="mt-1">
                                                    <span class="badge bg-primary text-white px-2 py-1 rounded-pill">
                                                        <i class="fas fa-users me-1"></i> <?php echo $company->driver_count; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="border rounded p-2 flex-fill mx-1 shadow-sm bg-light">
                                                <small class="d-block text-muted fw-bold">Araçlar</small>
                                                <div class="mt-1">
                                                    <span class="badge bg-info text-white px-2 py-1 rounded-pill">
                                                        <i class="fas fa-car me-1"></i> <?php echo $company->vehicle_count; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Butonlar -->
                                        <div class="text-center mt-3">
                                            <div class="btn-group w-100" role="group">
                                                <a href="<?php echo URLROOT; ?>/companies/show/<?php echo $company->id; ?>" 
                                                   class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Detay">
                                                    <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Detay</span>
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/companies/edit/<?php echo $company->id; ?>" 
                                                   class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Düzenle">
                                                    <i class="fas fa-edit me-1"></i><span class="d-none d-md-inline">Düzenle</span>
                                                </a>
                                                <a href="javascript:void(0);" 
                                                   onclick="silmeOnayi('<?php echo $company->id; ?>', '<?php echo htmlspecialchars($company->company_name); ?>');" 
                                                   class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Sil">
                                                    <i class="fas fa-trash me-1"></i><span class="d-none d-md-inline">Sil</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Tablo Görünümü -->
                    <div id="tabloGorunum" class="table-responsive d-none">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="80">Logo</th>
                                    <th>Şirket Adı</th>
                                    <th>Vergi No</th>
                                    <th>İletişim</th>
                                    <th>Personel</th>
                                    <th>Araçlar</th>
                                    <th>Durum</th>
                                    <th width="120">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['companies'])): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            Henüz şirket kaydı bulunmamaktadır.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['companies'] as $company): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php if (!empty($company->logo_url)): ?>
                                                <img src="<?php echo URLROOT . '/' . htmlspecialchars($company->logo_url); ?>" 
                                                     alt="Logo" class="img-thumbnail" style="max-height: 50px;">
                                            <?php else: ?>
                                                <i class="fas fa-building fa-2x text-gray-400"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($company->company_name); ?></strong>
                                        </td>
                                        <td>
                                            <span class="d-block"><?php echo htmlspecialchars($company->tax_number); ?></span>
                                            <small class="text-muted"><?php echo htmlspecialchars($company->tax_office); ?></small>
                                        </td>
                                        <td>
                                            <i class="fas fa-phone mr-1 text-muted"></i> <?php echo htmlspecialchars($company->phone ?? '-'); ?><br>
                                            <i class="fas fa-envelope mr-1 text-muted"></i> <?php echo htmlspecialchars($company->email ?? '-'); ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary text-white">
                                                <?php echo $company->driver_count; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-white">
                                                <?php echo $company->vehicle_count; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-pill badge-<?php echo $company->status === 'Aktif' ? 'success' : 'danger'; ?>">
                                                <?php echo htmlspecialchars($company->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo URLROOT; ?>/companies/show/<?php echo $company->id; ?>" 
                                                   class="btn btn-outline-info" data-bs-toggle="tooltip" title="Detay">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/companies/edit/<?php echo $company->id; ?>" 
                                                   class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" 
                                                   onclick="silmeOnayi('<?php echo $company->id; ?>', '<?php echo htmlspecialchars($company->company_name); ?>');" 
                                                   class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modal -->
<div class="modal fade" id="silmeOnayModal" tabindex="-1" aria-labelledby="silmeOnayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="silmeOnayModalLabel">Silme Onayı</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="silmeOnayMesaj">Bu şirketi silmek istediğinize emin misiniz?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Uyarı:</strong> Bu işlem geri alınamaz ve şirkete bağlı tüm verileri etkileyebilir.
                </div>
            </div>
            <div class="modal-footer">
                <form id="silmeForm" action="" method="post">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Kart ve tablo görünümü geçişi
    document.getElementById('btnKartGorunum').addEventListener('click', function() {
        document.getElementById('kartGorunum').classList.remove('d-none');
        document.getElementById('tabloGorunum').classList.add('d-none');
        this.classList.add('active');
        document.getElementById('btnTabloGorunum').classList.remove('active');
    });

    document.getElementById('btnTabloGorunum').addEventListener('click', function() {
        document.getElementById('kartGorunum').classList.add('d-none');
        document.getElementById('tabloGorunum').classList.remove('d-none');
        this.classList.add('active');
        document.getElementById('btnKartGorunum').classList.remove('active');
    });

    // Silme onayı modal
    function silmeOnayi(id, companyName) {
        document.getElementById('silmeOnayMesaj').innerText = '"' + companyName + '" şirketini silmek istediğinize emin misiniz?';
        document.getElementById('silmeForm').action = '<?php echo URLROOT; ?>/companies/delete/' + id;
        
        // Bootstrap 5 için modal göster
        const silmeModal = new bootstrap.Modal(document.getElementById('silmeOnayModal'));
        silmeModal.show();
    }

    // Tooltip'leri etkinleştir
    document.addEventListener('DOMContentLoaded', function() {
        // Tüm tooltipleri başlat
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover'
            });
        });
        
        // Şirket kartları için hover efekti
        const companyCards = document.querySelectorAll('.company-card');
        companyCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg');
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg');
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Logo placeholder düzenlemeleri
        const logoPlaceholders = document.querySelectorAll('.company-logo-placeholder');
        logoPlaceholders.forEach(placeholder => {
            placeholder.style.width = '100px';
            placeholder.style.height = '100px';
            placeholder.style.backgroundColor = '#f8f9fa';
            placeholder.style.borderRadius = '50%';
            placeholder.style.display = 'inline-flex';
            placeholder.style.alignItems = 'center';
            placeholder.style.justifyContent = 'center';
        });
    });

    // DataTable initialization
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // DataTables Buttons yapılandırması
            const buttonConfig = [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn-sm btn-success',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn-sm btn-danger',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Yazdır',
                    className: 'btn-sm btn-secondary',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'pageLength',
                    className: 'btn-sm btn-primary'
                }
            ];
            
            // colVis butonunu kontrol et ve ekle
            if (typeof $.fn.DataTable !== 'undefined' && 
                typeof $.fn.DataTable.Buttons !== 'undefined') {
                
                buttonConfig.push({
                    extend: 'colvis',
                    text: '<i class="fas fa-columns"></i> Sütunlar',
                    className: 'btn-sm btn-dark'
                });
                console.log("colVis butonu eklendi");
            }
            
            // DataTable'ı başlat
            try {
                const table = document.getElementById('dataTable');
                
                // Tablo yoksa işlemi sonlandır
                if (!table) {
                    console.error('dataTable ID\'li tablo bulunamadı.');
                    return;
                }
                
                // Eğer zaten bir DataTable örneği varsa, onu yok et
                if ($.fn.dataTable.isDataTable('#dataTable')) {
                    $('#dataTable').DataTable().destroy();
                }
                
                // Varsayılan ayarlar
                const options = {
                    responsive: true,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                    },
                    lengthMenu: [10, 25, 50, 100],
                    stateSave: true,
                    buttons: buttonConfig,
                    dom: "<'row mb-2'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                          "<'row'<'col-sm-12'tr>>" +
                          "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                };

                // DataTable'ı başlat
                const companiesTable = $('#dataTable').DataTable(options);
                
                // Pencere boyutu değiştiğinde sütunları ayarla
                $(window).resize(function() {
                    companiesTable.columns.adjust();
                });
            } catch (error) {
                console.error("DataTables başlatılırken hata:", error);
            }
            
            console.log("DataTables durumunu kontrol etmek için konsola \"checkDataTables()\" yazabilirsiniz.");
            
            // DataTable durumunu kontrol etmek için global fonksiyon
            window.checkDataTables = function() {
                console.log("DataTables yüklü:", !!$.fn.DataTable);
                console.log("Buttons eklentisi yüklü:", !!$.fn.DataTable.Buttons);
                if ($.fn.DataTable.Buttons) {
                    console.log("Buttons varsayılanları:", $.fn.DataTable.Buttons.defaults);
                    console.log("colVis butonu mevcut:", 
                        $.fn.DataTable.Buttons.defaults && 
                        $.fn.DataTable.Buttons.defaults.buttons && 
                        $.fn.DataTable.Buttons.defaults.buttons.colvis);
                }
                return "DataTables kontrolü tamamlandı.";
            };
        } catch (error) {
            console.error("DataTables başlatılırken hata:", error);
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 