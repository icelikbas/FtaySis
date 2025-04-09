<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-truck mr-2"></i> <?php echo $data['title']; ?>
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/vehicles/add" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Yeni Araç Ekle
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('vehicle_message'); ?>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <?php
        // Durum istatistiklerini hesapla
        $activeCount = 0;
        $inactiveCount = 0;
        $maintenanceCount = 0;
        
        foreach($data['vehicles'] as $vehicle) {
            if($vehicle->status == 'Aktif') $activeCount++;
            elseif($vehicle->status == 'Pasif') $inactiveCount++;
            elseif($vehicle->status == 'Bakımda') $maintenanceCount++;
        }
        
        // Araç tipi dağılımını hesapla
        $vehicleTypes = [];
        foreach($data['vehicles'] as $vehicle) {
            if(!isset($vehicleTypes[$vehicle->vehicle_type])) {
                $vehicleTypes[$vehicle->vehicle_type] = 0;
            }
            $vehicleTypes[$vehicle->vehicle_type]++;
        }
        ?>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Araç</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($data['vehicles']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aktif Araçlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $activeCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Bakımdaki Araçlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $maintenanceCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-danger shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pasif Araçlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $inactiveCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Arama ve Filtreleme -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-search mr-2"></i>Arama ve Filtreleme</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="searchInput"><strong>Hızlı Arama:</strong></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="searchInput" placeholder="Plaka, marka, model...">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter"><strong>Durum Filtresi:</strong></label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Tüm Durumlar</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Bakımda">Bakımda</option>
                        <option value="Pasif">Pasif</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="typeFilter"><strong>Araç Tipi Filtresi:</strong></label>
                    <select class="form-control" id="typeFilter">
                        <option value="">Tüm Araç Tipleri</option>
                        <?php foreach($data['vehicleTypesList'] as $type): ?>
                            <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="d-block">&nbsp;</label>
                    <button id="resetFilters" class="btn btn-secondary btn-block">
                        <i class="fas fa-sync-alt mr-1"></i> Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Araç Listesi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-list mr-2"></i>Araç Listesi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="vehiclesTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Plaka</th>
                            <th>Marka / Model</th>
                            <th>Yıl</th>
                            <th>Araç Tipi</th>
                            <th>Durum</th>
                            <th>Görevlendirme</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['vehicles'] as $vehicle) : ?>
                            <tr>
                                <td><?php echo $vehicle->id; ?></td>
                                <td><strong><?php echo $vehicle->plate_number; ?></strong></td>
                                <td><?php echo $vehicle->brand; ?> <?php echo $vehicle->model; ?></td>
                                <td><?php echo $vehicle->year; ?></td>
                                <td><?php echo $vehicle->vehicle_type; ?></td>
                                <td>
                                    <?php if($vehicle->status == 'Aktif') : ?>
                                        <span class="badge bg-success text-white">Aktif</span>
                                    <?php elseif($vehicle->status == 'Pasif') : ?>
                                        <span class="badge bg-secondary text-white">Pasif</span>
                                    <?php elseif($vehicle->status == 'Bakımda') : ?>
                                        <span class="badge bg-warning text-white">Bakımda</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    // Aktif görevlendirmeyi kontrol et
                                    // View içinde doğrudan model oluşturamayız, controller üzerinden alınması gerekir
                                    if(isset($vehicle->activeAssignment) && $vehicle->activeAssignment):
                                    ?>
                                        <span class="badge bg-info text-white" data-toggle="tooltip" title="<?php echo $vehicle->activeAssignment->driver_name; ?>">
                                            <i class="fas fa-user-check mr-1"></i> Görevde
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-user-times mr-1"></i> Boşta
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->id; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="Detaylar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $vehicle->id; ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if(isAdmin()) : ?>
                                        <form class="d-inline" action="<?php echo URLROOT; ?>/vehicles/delete/<?php echo $vehicle->id; ?>" method="post">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu aracı silmek istediğinize emin misiniz?');" data-toggle="tooltip" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Araç Tipi Dağılımı -->
    <div class="row mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-chart-pie mr-2"></i>Araç Tipi Dağılımı</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mini-table" id="VehiclesTableTypes">
                            <thead class="thead-light">
                                <tr>
                                    <th>Araç Tipi</th>
                                    <th width="70">Adet</th>
                                    <th width="70">Yüzde</th>
                                    <th>Grafik</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalVehicles = count($data['vehicles']);
                                $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary'];
                                $colorIndex = 0;
                                
                                // Önce sadece standart araç tiplerini göster ve sonra diğerlerini göster
                                $standardTypes = $data['vehicleTypesList'];
                                $displayedTypes = [];
                                
                                // Önce standart tipleri işle
                                foreach($standardTypes as $stdType):
                                    if (isset($vehicleTypes[$stdType]) && $vehicleTypes[$stdType] > 0):
                                        $count = $vehicleTypes[$stdType];
                                        $percentage = ($totalVehicles > 0) ? round(($count / $totalVehicles) * 100) : 0;
                                        $color = $colors[$colorIndex % count($colors)];
                                        $displayedTypes[] = $stdType;
                                        $colorIndex++;
                                ?>
                                <tr>
                                    <td><strong><?php echo $stdType; ?></strong></td>
                                    <td class="text-center"><span class="badge <?php echo $color; ?> text-white"><?php echo $count; ?></span></td>
                                    <td class="text-center"><?php echo $percentage; ?>%</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar <?php echo $color; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%" 
                                                 aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                                <?php if($percentage > 10): echo $percentage . '%'; endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endif;
                                endforeach;
                                
                                // Sonra standart olmayan tipleri işle (eğer varsa)
                                foreach($vehicleTypes as $type => $count):
                                    if (!in_array($type, $displayedTypes)):
                                        $percentage = ($totalVehicles > 0) ? round(($count / $totalVehicles) * 100) : 0;
                                        $color = $colors[$colorIndex % count($colors)];
                                        $colorIndex++;
                                ?>
                                <tr>
                                    <td><strong><?php echo $type; ?></strong></td>
                                    <td class="text-center"><span class="badge <?php echo $color; ?> text-white"><?php echo $count; ?></span></td>
                                    <td class="text-center"><?php echo $percentage; ?>%</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar <?php echo $color; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%" 
                                                 aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                                <?php if($percentage > 10): echo $percentage . '%'; endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Renk Açıklamaları (Legend) -->
                    <div class="mt-3">
                        <h6 class="font-weight-bold">Araç Tipleri Renk Açıklamaları:</h6>
                        <div class="d-flex flex-wrap">
                            <?php 
                            $colorIndex = 0;
                            $displayedTypes = [];
                            
                            // Önce standart tipleri göster
                            foreach($standardTypes as $stdType):
                                if (isset($vehicleTypes[$stdType]) && $vehicleTypes[$stdType] > 0):
                                    $color = $colors[$colorIndex % count($colors)];
                                    $displayedTypes[] = $stdType;
                                    $colorIndex++;
                            ?>
                            <div class="mr-3 mb-2">
                                <span class="badge <?php echo $color; ?> text-white" style="width: 20px;">&nbsp;</span>
                                <small><?php echo $stdType; ?></small>
                            </div>
                            <?php 
                                endif;
                            endforeach;
                            
                            // Sonra standart olmayan tipleri göster
                            foreach($vehicleTypes as $type => $count):
                                if (!in_array($type, $displayedTypes)):
                                    $color = $colors[$colorIndex % count($colors)];
                                    $colorIndex++;
                            ?>
                            <div class="mr-3 mb-2">
                                <span class="badge <?php echo $color; ?> text-white" style="width: 20px;">&nbsp;</span>
                                <small><?php echo $type; ?></small>
                            </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-star mr-2"></i>Hızlı Erişim</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo URLROOT; ?>/vehicles/add" class="btn btn-success btn-block">
                                <i class="fas fa-plus-circle mr-1"></i> Yeni Araç Ekle
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo URLROOT; ?>/assignments/add" class="btn btn-info btn-block">
                                <i class="fas fa-tasks mr-1"></i> Görevlendirme Oluştur
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo URLROOT; ?>/maintenancerecords/add" class="btn btn-warning btn-block">
                                <i class="fas fa-tools mr-1"></i> Bakım Kaydı Ekle
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo URLROOT; ?>/reports/vehicles" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-bar mr-1"></i> Araç Raporları
                            </a>
                        </div>
                    </div>
                </div>
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
.shadow-sm {
    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15) !important;
}
.bg-primary {
    background-color: #4e73df !important;
}
.bg-success {
    background-color: #1cc88a !important;
}
.bg-warning {
    background-color: #f6c23e !important;
}
.bg-danger {
    background-color: #e74a3b !important;
}
.bg-info {
    background-color: #36b9cc !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // jQuery ve DataTables yüklü mü kontrol et
        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            try {
                // DataTable'ı direkt başlat
                const vehiclesTable = $('#vehiclesTable').DataTable({
                    responsive: true,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                    },
                    order: [[0, "desc"]],
                    columnDefs: [
                        { orderable: false, targets: 7 } // İşlemler sütunu sıralanamaz
                    ]
                });
                
                // Filtreleme işlevleri
                $('#searchInput').on('keyup', function() {
                    vehiclesTable.search($(this).val()).draw();
                });
                
                $('#statusFilter').on('change', function() {
                    var val = $(this).val();
                    vehiclesTable.column(5).search(val ? val : '', true, false).draw();
                });
                
                $('#typeFilter').on('change', function() {
                    var val = $(this).val();
                    vehiclesTable.column(4).search(val ? val : '', true, false).draw();
                });
                
                $('#resetFilters').on('click', function() {
                    $('#searchInput').val('');
                    $('#statusFilter').val('');
                    $('#typeFilter').val('');
                    vehiclesTable.search('').columns().search('').draw();
                });
                
                console.log('Araç tablosu başarıyla oluşturuldu ve filtre işlevleri eklendi.');
            } catch (error) {
                console.error('DataTable başlatılırken hata oluştu:', error);
            }
        } else {
            console.error('jQuery veya DataTables yüklenmemiş! Filtreler çalışmayacak.');
        }
        
        // Bootstrap tooltip etkinleştirme
        if (typeof $ !== 'undefined' && typeof $.fn.tooltip !== 'undefined') {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 