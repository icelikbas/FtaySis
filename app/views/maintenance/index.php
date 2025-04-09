<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
// Yardımcı fonksiyonlar
function formatDate($date) {
    if (empty($date)) return '-';
    return date('d.m.Y', strtotime($date));
}
?>

<div class="container-fluid px-4">
    <!-- Başlık ve Üst Bilgi Alanı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-tools me-2"></i> Bakım Takip Yönetimi
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/maintenance/add" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Yeni Bakım Kaydı
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <!-- Bakım Kayıtları Özeti -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Toplam Bakım</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count($data['records']); ?> <small>Adet</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tamamlanan Bakımlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $data['statusCounts']['Tamamlandı']; ?> <small>Adet</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Devam Eden Bakımlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $data['statusCounts']['Devam Ediyor']; ?> <small>Adet</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Toplam Maliyet</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($data['totalCost'], 2, ',', '.'); ?> <small>₺</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bakım Türü Dağılımı ve Servis Sağlayıcılar -->
    <div class="row mb-4">
        <!-- Bakım Türü Dağılımı -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bakım Türü Dağılımı</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="maintenanceTypeChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <?php foreach($data['typeDistribution'] as $type): ?>
                            <span class="mr-2">
                                <?php 
                                    $color = '';
                                    switch($type->maintenance_type) {
                                        case 'Periyodik Bakım':
                                            $color = 'primary';
                                            break;
                                        case 'Arıza':
                                            $color = 'danger';
                                            break;
                                        case 'Lastik Değişimi':
                                            $color = 'warning';
                                            break;
                                        case 'Yağ Değişimi':
                                            $color = 'info';
                                            break;
                                        default:
                                            $color = 'secondary';
                                    }
                                ?>
                                <i class="fas fa-circle text-<?php echo $color; ?>"></i> <?php echo $type->maintenance_type; ?> (<?php echo $type->count; ?>)
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servis Sağlayıcılar -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">En Çok Kullanılan Servis Sağlayıcılar</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($data['serviceProviders'])): ?>
                        <p class="text-center">Henüz servis sağlayıcı kaydedilmemiş.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Servis Sağlayıcı</th>
                                        <th>Bakım Sayısı</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $count = 0;
                                    foreach($data['serviceProviders'] as $provider => $count): 
                                        if($count < 5): // En çok kullanılan 5 servis sağlayıcı
                                    ?>
                                        <tr>
                                            <td><?php echo $provider; ?></td>
                                            <td><?php echo $count; ?></td>
                                        </tr>
                                    <?php 
                                        endif;
                                        $count++;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Yaklaşan Bakımlar -->
    <div class="row mb-4">
        <!-- Tarihe Göre Yaklaşan Bakımlar -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Yaklaşan Tarih Bakımları (30 Gün İçinde)</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($data['upcomingMaintenances'])): ?>
                        <p class="text-center">Yaklaşan tarih bakımı bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Araç</th>
                                        <th>Bakım Tarihi</th>
                                        <th>Kalan Gün</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['upcomingMaintenances'] as $maintenance): ?>
                                        <tr>
                                            <td><?php echo $maintenance->plate_number; ?></td>
                                            <td><?php echo date('d.m.Y', strtotime($maintenance->next_maintenance_date)); ?></td>
                                            <td>
                                                <?php 
                                                    $daysLeft = floor((strtotime($maintenance->next_maintenance_date) - strtotime($today)) / (60 * 60 * 24));
                                                    echo $daysLeft;
                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $maintenance->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
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

        <!-- Kilometreye Göre Yaklaşan Bakımlar -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Yaklaşan Kilometre Bakımları (1000 KM Kalan)</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($data['upcomingKmMaintenances'])): ?>
                        <p class="text-center">Yaklaşan kilometre bakımı bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Araç</th>
                                        <th>Şu Anki KM</th>
                                        <th>Bakım KM</th>
                                        <th>Kalan KM</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['upcomingKmMaintenances'] as $maintenance): ?>
                                        <tr>
                                            <td><?php echo $maintenance->plate_number; ?></td>
                                            <td><?php echo number_format($maintenance->km_reading, 0, ',', '.'); ?></td>
                                            <td><?php echo number_format($maintenance->next_maintenance_km, 0, ',', '.'); ?></td>
                                            <td>
                                                <?php 
                                                    $kmLeft = $maintenance->next_maintenance_km - $maintenance->km_reading;
                                                    echo number_format($kmLeft, 0, ',', '.');
                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $maintenance->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
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

    <!-- Arama ve Filtreleme Bölümü -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-search me-2"></i>Arama ve Filtreleme</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="searchInput"><strong>Hızlı Arama:</strong></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Plaka, bakım türü...">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="typeFilter"><strong>Bakım Türü:</strong></label>
                    <select class="form-control" id="typeFilter">
                        <option value="">Tüm Türler</option>
                        <option value="Periyodik Bakım">Periyodik Bakım</option>
                        <option value="Arıza">Arıza</option>
                        <option value="Lastik Değişimi">Lastik Değişimi</option>
                        <option value="Yağ Değişimi">Yağ Değişimi</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter"><strong>Durum:</strong></label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Tüm Durumlar</option>
                        <option value="Planlandı">Planlandı</option>
                        <option value="Devam Ediyor">Devam Ediyor</option>
                        <option value="Tamamlandı">Tamamlandı</option>
                        <option value="İptal">İptal</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="dateFilter"><strong>Tarih Aralığı:</strong></label>
                    <select class="form-control" id="dateFilter">
                        <option value="">Tüm Tarihler</option>
                        <option value="today">Bugün</option>
                        <option value="week">Bu Hafta</option>
                        <option value="month">Bu Ay</option>
                        <option value="year">Bu Yıl</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-end">
                    <button id="resetFilters" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Filtreleri Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bakım Kayıtları Tablosu -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-list me-2"></i>Bakım Kayıtları</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="maintenanceTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Plaka</th>
                            <th>Bakım Türü</th>
                            <th>Başlangıç Tarihi</th>
                            <th>Bitiş Tarihi</th>
                            <th>KM</th>
                            <th>Maliyet</th>
                            <th>Durum</th>
                            <th>Servis Sağlayıcı</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['records'] as $record) : ?>
                            <tr>
                                <td><?php echo $record->id; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $record->vehicle_id; ?>" class="fw-bold text-decoration-none">
                                        <?php echo $record->plate_number; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    $typeClass = 'secondary';
                                    switch ($record->maintenance_type) {
                                        case 'Periyodik Bakım':
                                            $typeClass = 'info';
                                            break;
                                        case 'Arıza':
                                            $typeClass = 'danger';
                                            break;
                                        case 'Lastik Değişimi':
                                            $typeClass = 'warning';
                                            break;
                                        case 'Yağ Değişimi':
                                            $typeClass = 'primary';
                                            break;
                                        default:
                                            $typeClass = 'secondary';
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $typeClass; ?>"><?php echo $record->maintenance_type; ?></span>
                                </td>
                                <td><?php echo formatDate($record->start_date); ?></td>
                                <td><?php echo !empty($record->end_date) ? formatDate($record->end_date) : '-'; ?></td>
                                <td><?php echo number_format($record->km_reading, 0, ',', '.'); ?> km</td>
                                <td><?php echo number_format($record->cost, 2, ',', '.'); ?> ₺</td>
                                <td>
                                    <?php
                                    $statusClass = 'secondary';
                                    switch ($record->status) {
                                        case 'Planlandı':
                                            $statusClass = 'primary';
                                            break;
                                        case 'Devam Ediyor':
                                            $statusClass = 'warning';
                                            break;
                                        case 'Tamamlandı':
                                            $statusClass = 'success';
                                            break;
                                        case 'İptal':
                                            $statusClass = 'danger';
                                            break;
                                        default:
                                            $statusClass = 'secondary';
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $record->status; ?></span>
                                </td>
                                <td><?php echo $record->service_provider; ?></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo URLROOT; ?>/maintenance/edit/<?php echo $record->id; ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $record->id; ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detaylar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                                            <button type="button" class="btn btn-sm btn-danger delete-record" data-id="<?php echo $record->id; ?>" data-bs-toggle="tooltip" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Silme İşlemi Onay Modalı -->
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Kayıt Silme Onayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bu bakım kaydını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>İptal</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger"><i class="fas fa-trash me-1"></i>Evet, Sil</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Chart.js ve DataTables için JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // jQuery ve DataTables yüklü mü kontrol et
            if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                console.error('jQuery veya DataTables yüklenmemiş!');
                return;
            }
            
            // Eğer zaten DataTable örneği varsa, onu yok et
            if ($.fn.dataTable.isDataTable('#maintenanceTable')) {
                $('#maintenanceTable').DataTable().destroy();
            }
            
            // DataTables'ı doğrudan başlat
            const maintenanceTable = $('#maintenanceTable').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                },
                dom: 'Bfrtip',
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10', '25', '50', 'Tümü']
                ],
                buttons: [
                    'copy', 'excel', 'pdf', 'print', 'colvis'
                ],
                order: [[0, 'desc']], // ID'ye göre azalan sıralama
                pageLength: 10,
                columnDefs: [
                    { targets: [7, 2], orderable: false } // İşlemler sütunu sıralanabilir olmasın
                ]
            });
            
            // Harici arama kutusu ile DataTables entegrasyonu
            $('#searchInput').on('keyup', function() {
                maintenanceTable.search(this.value).draw();
            });
            
            // Bakım türü filtresi
            $('#typeFilter').on('change', function() {
                maintenanceTable.column(2).search(this.value).draw();
            });
            
            // Durum filtresi
            $('#statusFilter').on('change', function() {
                maintenanceTable.column(7).search(this.value).draw();
            });
            
            // Tarih filtresi
            $('#dateFilter').on('change', function() {
                var value = $(this).val();
                var dateSearch = '';
                
                // Basit tarih filtresi mantığı
                // Gerçek uygulamada daha karmaşık olabilir
                if (value === 'today') {
                    var today = new Date().toLocaleDateString('tr-TR');
                    dateSearch = today;
                } else if (value === 'week' || value === 'month' || value === 'year') {
                    // Bu filtreler için özel bir arama kolonu gerekebilir
                    // Basitleştirme için bu örnekte boş bırakılmıştır
                }
                
                maintenanceTable.column(3).search(dateSearch).draw();
            });
            
            // Filtreleri sıfırla
            $('#resetFilters').on('click', function() {
                $('#searchInput').val('');
                $('#typeFilter').val('');
                $('#statusFilter').val('');
                $('#dateFilter').val('');
                maintenanceTable.search('').columns().search('').draw();
            });
            
            // Tooltip'leri etkinleştir
            if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
            
            // Bakım Türü Chart - mevcut kodu koru
            var ctx = document.getElementById("maintenanceTypeChart");
            if (ctx) {
                var typeLabels = [];
                var typeCounts = [];
                var typeColors = [];
                
                <?php foreach($data['typeDistribution'] as $type): ?>
                    typeLabels.push('<?php echo $type->maintenance_type; ?>');
                    typeCounts.push(<?php echo $type->count; ?>);
                    
                    <?php 
                        $color = '';
                        switch($type->maintenance_type) {
                            case 'Periyodik Bakım':
                                $color = '#4e73df'; // primary
                                break;
                            case 'Arıza':
                                $color = '#e74a3b'; // danger
                                break;
                            case 'Lastik Değişimi':
                                $color = '#f6c23e'; // warning
                                break;
                            case 'Yağ Değişimi':
                                $color = '#36b9cc'; // info
                                break;
                            default:
                                $color = '#858796'; // secondary
                        }
                    ?>
                    
                    typeColors.push('<?php echo $color; ?>');
                <?php endforeach; ?>
                
                var myPieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: typeLabels,
                        datasets: [{
                            data: typeCounts,
                            backgroundColor: typeColors,
                            hoverBackgroundColor: typeColors,
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                        },
                        legend: {
                            display: false
                        },
                        cutoutPercentage: 70,
                    },
                });
            }
        });

        // Silme işlemi için modal
        $('.delete-record').on('click', function() {
            var id = $(this).data('id');
            $('#confirmDelete').attr('href', '<?php echo URLROOT; ?>/maintenance/delete/' + id);
            $('#deleteModal').modal('show');
        });
    </script>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 