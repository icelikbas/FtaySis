<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-id-card mr-2"></i> <?php echo $data['title']; ?>
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/drivers/add" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Yeni Şoför Ekle
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('driver_message'); ?>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Şoför</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($data['drivers']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aktif Şoförler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $activeCount = 0;
                                    foreach($data['drivers'] as $driver) {
                                        if($driver->status == 'Aktif') $activeCount++;
                                    }
                                    echo $activeCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                İzinli Şoförler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $onLeaveCount = 0;
                                    foreach($data['drivers'] as $driver) {
                                        if($driver->status == 'İzinli') $onLeaveCount++;
                                    }
                                    echo $onLeaveCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pasif Şoförler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $inactiveCount = 0;
                                    foreach($data['drivers'] as $driver) {
                                        if($driver->status == 'Pasif') $inactiveCount++;
                                    }
                                    echo $inactiveCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Arama ve Filtreleme -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-search mr-1"></i> Arama ve Filtreleme
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="searchInput"><strong>Arama:</strong></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="searchInput" placeholder="Ad, soyad, TC kimlik no...">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter"><strong>Durum Filtresi:</strong></label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Tüm Durumlar</option>
                        <option value="Aktif">Aktif</option>
                        <option value="İzinli">İzinli</option>
                        <option value="Pasif">Pasif</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="licenseFilter"><strong>Ehliyet Filtresi:</strong></label>
                    <select class="form-control" id="licenseFilter">
                        <option value="">Tüm Ehliyet Sınıfları</option>
                        <option value="B">B Sınıfı</option>
                        <option value="C">C Sınıfı</option>
                        <option value="D">D Sınıfı</option>
                        <option value="E">E Sınıfı</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="d-block">&nbsp;</label>
                    <button id="resetFilters" class="btn btn-secondary btn-block">
                        <i class="fas fa-sync-alt"></i> Filtreleri Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Şoför Listesi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Şoför Listesi</h6>
            <span class="badge badge-pill badge-primary"><?php echo count($data['drivers']); ?> Kayıt</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="driversTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Ad Soyad</th>
                            <th width="15%">TC Kimlik No</th>
                            <th width="15%">Ehliyet Sınıfı</th>
                            <th width="15%">Telefon</th>
                            <th width="15%">Durum</th>
                            <th width="15%" class="text-center">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['drivers'] as $driver) : ?>
                            <tr>
                                <td><?php echo $driver->id; ?></td>
                                <td>
                                    <strong><?php echo $driver->name . ' ' . $driver->surname; ?></strong>
                                </td>
                                <td><?php echo $driver->identity_number; ?></td>
                                <td>
                                    <?php if (!empty($driver->license_classes)) : ?>
                                        <span class="badge badge-info"><?php echo $driver->license_classes; ?></span>
                                    <?php else : ?>
                                        <span class="badge badge-secondary"><?php echo $driver->primary_license_type; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="fas fa-phone-alt text-primary mr-1"></i> 
                                    <?php echo $driver->phone; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($driver->status == 'Aktif') : ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php elseif($driver->status == 'Pasif') : ?>
                                        <span class="badge badge-danger">Pasif</span>
                                    <?php elseif($driver->status == 'İzinli') : ?>
                                        <span class="badge badge-warning">İzinli</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="Görüntüle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/drivers/edit/<?php echo $driver->id; ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?php echo $driver->id; ?>" data-toggle="tooltip" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Silme Onay Modalı -->
                                    <div class="modal fade" id="deleteModal<?php echo $driver->id; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $driver->id; ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel<?php echo $driver->id; ?>">Şoför Silme Onayı</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong><?php echo $driver->name . ' ' . $driver->surname; ?></strong> isimli şoförü silmek istediğinize emin misiniz?</p>
                                                    <p class="text-danger"><small>Bu işlem geri alınamaz.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                                                    <form action="<?php echo URLROOT; ?>/drivers/delete/<?php echo $driver->id; ?>" method="post">
                                                        <button type="submit" class="btn btn-danger">Evet, Sil</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.border-left-danger {
    border-left: 4px solid #e74a3b !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}
.shadow {
    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15) !important;
}

/* DataTable özel stiller */
.table-responsive {
    min-height: 300px;
    width: 100%;
    overflow-x: auto;
}
#driversTable {
    width: 100% !important;
    margin-bottom: 0;
}
.dataTables_wrapper {
    width: 100% !important;
    padding: 10px 0;
}
.dataTables_scrollBody {
    min-height: 200px;
}
.table td, .table th {
    vertical-align: middle !important;
    padding: 0.75rem;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.02);
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #e3e6f0;
}
.table thead th {
    border-bottom: 2px solid #e3e6f0;
    font-weight: 600;
    white-space: nowrap;
}
.thead-dark th {
    background-color: #343a40;
    color: #fff;
}

/* İşlem butonları için stil */
.btn-group {
    display: flex;
    justify-content: center;
}
.btn-group .btn {
    margin-right: 4px;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
.btn-group .btn i {
    width: 14px;
    text-align: center;
}

/* Status badge stilleri */
.badge {
    padding: 0.4em 0.6em;
    font-size: 85%;
    font-weight: 600;
    letter-spacing: 0.3px;
    border-radius: 0.25rem;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-danger {
    background-color: #e74a3b;
}
.badge-warning {
    background-color: #f6c23e;
    color: #212529;
}
.badge-info {
    background-color: #36b9cc;
}
.badge-secondary {
    background-color: #858796;
}
.badge-pill {
    border-radius: 10rem;
    padding-right: .6em;
    padding-left: .6em;
}

/* Tablo satır hover efekti */
#driversTable tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
}

/* Modal stilleri */
.modal-header.bg-danger {
    background: linear-gradient(to right, #e74a3b, #c0392b) !important;
}

/* DataTables sayfalama ve arama butonları */
div.dataTables_wrapper div.dataTables_paginate {
    margin-top: 10px;
}
div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
}
div.dataTables_wrapper div.dataTables_length label,
div.dataTables_wrapper div.dataTables_filter label {
    margin-bottom: 10px;
    font-weight: 500;
}
div.dataTables_wrapper div.dataTables_info {
    padding-top: 10px;
    font-size: 0.9rem;
}

/* DataTable daha fazla stil düzeltmeleri */
#driversTable_wrapper {
    width: 100% !important;
    padding: 0;
}

.table-responsive {
    width: 100% !important;
    overflow-x: auto;
    min-height: 300px;
}

#driversTable {
    width: 100% !important;
    min-width: 100%;
    margin: 0 !important;
    border-collapse: collapse !important;
}

.dataTables_scrollHeadInner {
    width: 100% !important;
    padding: 0 !important;
}

.dataTables_scrollHeadInner table {
    width: 100% !important;
    margin: 0 !important;
}

.dataTables_scrollBody {
    width: 100% !important;
    min-height: 250px;
}

/* Sayfalama stilleri */
.dataTables_paginate {
    margin-top: 10px;
    padding-top: 10px;
    text-align: right;
}

.paginate_button {
    display: inline-block;
    padding: 0.25em 0.65em;
    margin-left: 2px;
    text-align: center;
    cursor: pointer;
    border: 1px solid #ddd;
    border-radius: 2px;
    color: #333;
    background-color: #fff;
}

.paginate_button.current {
    background-color: #4e73df !important;
    color: white !important;
    border-color: #4e73df !important;
    font-weight: bold;
}

.paginate_button:hover:not(.current) {
    background-color: #eee;
}

.paginate_button.disabled {
    color: #999;
    cursor: default;
    background-color: #fff;
    border-color: #ddd;
}

/* Mobil görünüm için ek düzeltmeler */
@media (max-width: 767px) {
    #driversTable_wrapper {
        overflow-x: scroll;
    }
    
    .dataTables_paginate {
        text-align: center;
        float: none !important;
        display: flex;
        justify-content: center;
    }
    
    .dataTables_info {
        text-align: center;
        float: none !important;
        margin-bottom: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // jQuery ve DataTables yüklü mü kontrol et
        if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
            console.error('jQuery veya DataTables yüklenmemiş!');
            alert("Tablonun yüklenmesi sırasında bir hata oluştu. Sayfayı yenileyin veya yönetici ile iletişime geçin.");
            return;
        }
        
        // Buttons konfigürasyonu oluştur
        const buttonConfig = [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn-sm btn-success',
                exportOptions: {
                    columns: ':not(:last-child)'  // Son sütun (işlemler) hariç tüm sütunları dışa aktar
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
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn-sm btn-info',
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
        
        // colVis butonunu kontrol et ve varsa ekle
        if (typeof $.fn.DataTable.Buttons !== 'undefined' &&
            $.fn.DataTable.Buttons.defaults &&
            $.fn.DataTable.Buttons.defaults.buttons &&
            $.fn.DataTable.Buttons.defaults.buttons.colvis) {
            // colVis butonu bulundu, ekle
            buttonConfig.push({
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Sütunlar',
                className: 'btn-sm btn-dark'
            });
            console.info("colVis butonu başarıyla eklendi");
        } else {
            console.warn("colVis butonu bulunamadı, atlanıyor");
        }
        
        // Eğer zaten DataTable örneği varsa, onu yok et
        if ($.fn.dataTable.isDataTable('#driversTable')) {
            $('#driversTable').DataTable().destroy();
        }
        
        // DataTables'ı doğrudan başlat
        const driversTable = $('#driversTable').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
            },
            autoWidth: false,
            scrollX: true,
            scrollCollapse: true,
            fixedHeader: true,
            stripeClasses: ['odd-row', 'even-row'],
            rowClass: 'align-middle',
            columnDefs: [
                { orderable: false, targets: 6 }, // İşlemler sütunu sıralanabilir olmasın
                { width: "5%", targets: 0 },
                { width: "20%", targets: 1 },
                { width: "15%", targets: 2 },
                { width: "15%", targets: 3 },
                { width: "15%", targets: 4 },
                { width: "15%", targets: 5 },
                { width: "15%", targets: 6, className: "text-center" }
            ],
            buttons: buttonConfig,
            dom: "<'row mb-2'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Tümü"]
            ],
            createdRow: function(row, data, dataIndex) {
                // Tablo hücrelerini hizala
                $(row).find('td').addClass('align-middle');
            },
            initComplete: function(settings, json) {
                // Tooltip'leri aktifleştir
                if (typeof $.fn.tooltip !== 'undefined') {
                    $('[data-toggle="tooltip"]').tooltip();
                }
                
                // Tablo genişliğini düzelt
                $(window).trigger('resize');
                setTimeout(function() {
                    // Tüm tablo genişliklerini %100 olarak ayarla
                    $('.table-responsive').css('width', '100%');
                    $('.dataTables_scrollHeadInner, .dataTables_scrollHeadInner table').css('width', '100%');
                    $('.dataTables_scrollBody').css('width', '100%');
                    $('#driversTable').css('width', '100%');
                    
                    // Tablo container'ın margin ve padding değerlerini sıfırla
                    $('#driversTable_wrapper').css({
                        'width': '100%',
                        'max-width': '100%',
                        'margin-left': '0',
                        'margin-right': '0'
                    });
                    
                    try {
                        driversTable.columns.adjust();
                    } catch (e) {
                        console.warn('Sütun ayarlama hatası:', e);
                    }
                }, 500);
            },
            // Sayfalama özelliğini aktifleştir
            paging: true,
            pageLength: 10, // Sayfa başına gösterilecek kayıt sayısı
            pagingType: "full_numbers" // Tam sayfalama kontrolleri (ilk, önceki, sayfa numaraları, sonraki, son)
        });
        
        if (driversTable) {
            // Harici arama kutusu ile DataTables entegrasyonu
            $('#searchInput').on('keyup', function() {
                driversTable.search(this.value).draw();
            });
            
            // Durum filtresi
            $('#statusFilter').on('change', function() {
                var val = $(this).val().toLowerCase();
                
                if (val) {
                    // Özel filtreleme (badge içeriği için)
                    driversTable.column(5).search(val).draw();
                } else {
                    driversTable.column(5).search('').draw();
                }
            });
            
            // Ehliyet sınıfı filtresi
            $('#licenseFilter').on('change', function() {
                var val = $(this).val().toLowerCase();
                
                if (val) {
                    driversTable.column(3).search(val).draw();
                } else {
                    driversTable.column(3).search('').draw();
                }
            });
            
            // Filtreleri sıfırla
            $('#resetFilters').on('click', function() {
                $('#searchInput').val('');
                $('#statusFilter').val('');
                $('#licenseFilter').val('');
                
                // Tüm filtreleri temizle ve tabloyu yenile
                driversTable.search('').columns().search('').draw();
            });
            
            // Pencere yeniden boyutlandırıldığında tabloyu yeniden düzenle
            $(window).resize(function() {
                if (driversTable) {
                    setTimeout(function() {
                        $('.table-responsive').css('width', '100%');
                        $('.dataTables_scrollHeadInner, .dataTables_scrollHeadInner table').css('width', '100%');
                        $('.dataTables_scrollBody').css('width', '100%');
                        $('#driversTable').css('width', '100%');
                        driversTable.columns.adjust();
                    }, 200);
                }
            });
        } else {
            console.error("DataTables başlatılamadı! Yedek plan uygulanıyor...");
            
            // Basit arama fonksiyonu (DataTables olmadığında)
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#driversTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            // Tabloyu manuel olarak hizala
            $('#driversTable').addClass('w-100 table-responsive');
            
            // Sayfalama ve arama elementlerini gizle
            $('.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate').hide();
        }
    } catch (error) {
        console.error("DataTables hata:", error);
        alert("Tablonun yüklenmesi sırasında bir hata oluştu. Sayfayı yenileyin veya yönetici ile iletişime geçin.");
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 