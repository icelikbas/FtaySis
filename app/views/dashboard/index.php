<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo $data['title']; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-calendar-alt"></i> Bu Hafta
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Bugün</a>
                <a class="dropdown-item" href="#">Bu Hafta</a>
                <a class="dropdown-item" href="#">Bu Ay</a>
                <a class="dropdown-item" href="#">Bu Yıl</a>
            </div>
        </div>
    </div>
</div>

<!-- İstatistik Kartları -->
<div class="row">
    <div class="col-md-3">
        <div class="stat-card stat-card-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Toplam Araç</h6>
                    <div class="stat-number"><?php echo $data['total_vehicles']; ?></div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Aktif Sürücüler</h6>
                    <div class="stat-number"><?php echo $data['active_drivers']; ?></div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Toplam Şirket</h6>
                    <div class="stat-number"><?php echo $data['total_companies']; ?></div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Yaklaşan Bakım</h6>
                    <div class="stat-number"><?php echo $data['upcoming_maintenance_count']; ?></div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Bölümü -->
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                Aylık Yakıt Tüketimi
            </div>
            <div class="card-body">
                <canvas id="fuelChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Araç Dağılımı
            </div>
            <div class="card-body">
                <canvas id="vehicleChart" width="100%" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Son Eklenen Şirketler -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Son Eklenen Şirketler</span>
                <a href="<?php echo URLROOT; ?>/companies" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="companiesTable">
                        <thead>
                            <tr>
                                <th>Şirket Adı</th>
                                <th>Araç Sayısı</th>
                                <th>Sürücü Sayısı</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['recent_companies'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Henüz şirket eklenmemiştir</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($data['recent_companies'] as $company) : ?>
                            <tr>
                                <td><?php echo isset($company->name) ? $company->name : '-'; ?></td>
                                <td><?php echo isset($company->vehicle_count) ? $company->vehicle_count : '0'; ?></td>
                                <td><?php echo isset($company->driver_count) ? $company->driver_count : '0'; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/companies/show/<?php echo $company->id; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
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
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Yaklaşan Muayeneler</span>
                <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="inspectionsTable">
                        <thead>
                            <tr>
                                <th>Araç Plakası</th>
                                <th>Şirket</th>
                                <th>Muayene Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['upcoming_inspections'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Yaklaşan muayene bulunmamaktadır</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($data['upcoming_inspections'] as $vehicle) : ?>
                            <tr>
                                <td><?php echo $vehicle->plate_number; ?></td>
                                <td><?php echo isset($vehicle->company_name) ? $vehicle->company_name : '-'; ?></td>
                                <td><?php echo isset($vehicle->inspection_date) ? date('d.m.Y', strtotime($vehicle->inspection_date)) : '-'; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->id; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
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

<!-- Son Eklenen Araçlar ve Sürücüler -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Son Eklenen Araçlar</span>
                <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="vehiclesTable">
                        <thead>
                            <tr>
                                <th>Plaka</th>
                                <th>Marka/Model</th>
                                <th>Şirket</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['recent_vehicles'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Henüz araç eklenmemiştir</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($data['recent_vehicles'] as $vehicle) : ?>
                            <tr>
                                <td><?php echo isset($vehicle->plate_number) ? $vehicle->plate_number : '-'; ?></td>
                                <td><?php echo isset($vehicle->brand) && isset($vehicle->model) ? $vehicle->brand . ' ' . $vehicle->model : '-'; ?></td>
                                <td><?php echo isset($vehicle->company_name) ? $vehicle->company_name : '-'; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->id; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
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
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Son Eklenen Sürücüler</span>
                <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="driversTable">
                        <thead>
                            <tr>
                                <th>Adı Soyadı</th>
                                <th>Ehliyet</th>
                                <th>Şirket</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['recent_drivers'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Henüz sürücü eklenmemiştir</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($data['recent_drivers'] as $driver) : ?>
                            <tr>
                                <td><?php echo isset($driver->name) && isset($driver->surname) ? $driver->name . ' ' . $driver->surname : '-'; ?></td>
                                <td><?php echo isset($driver->primary_license_type) ? $driver->primary_license_type : '-'; ?></td>
                                <td><?php echo isset($driver->company_name) ? $driver->company_name : '-'; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
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

<!-- Son Bakım Kayıtları -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Son Bakım Kayıtları</span>
        <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm" id="maintenanceTable">
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Araç</th>
                        <th>Bakım Türü</th>
                        <th>Şirket</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['recent_maintenance'])): ?>
                    <tr>
                        <td colspan="7" class="text-center">Bakım kaydı bulunmamaktadır</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($data['recent_maintenance'] as $maintenance) : ?>
                    <tr>
                        <td><?php echo isset($maintenance->start_date) ? date('d.m.Y', strtotime($maintenance->start_date)) : '-'; ?></td>
                        <td><?php echo isset($maintenance->plate_number) ? $maintenance->plate_number : '-'; ?></td>
                        <td><?php echo isset($maintenance->maintenance_type) ? $maintenance->maintenance_type : '-'; ?></td>
                        <td><?php echo isset($maintenance->company_name) ? $maintenance->company_name : '-'; ?></td>
                        <td><?php echo isset($maintenance->cost) ? number_format($maintenance->cost, 2, ',', '.') . ' ₺' : '-'; ?></td>
                        <td><span class="badge badge-<?php echo (isset($maintenance->status) && $maintenance->status == 'Tamamlandı') ? 'success' : ((isset($maintenance->status) && $maintenance->status == 'Beklemede') ? 'warning' : 'info'); ?>"><?php echo isset($maintenance->status) ? $maintenance->status : 'Belirsiz'; ?></span></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $maintenance->id; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dashboard tabloları için DataTables yapılandırması
    document.addEventListener('DOMContentLoaded', function() {
        // Basit DataTables konfigürasyonu
        const simpleTableOptions = {
            "paging": false,
            "searching": false,
            "info": false,
            "ordering": false,
            "responsive": true,
            "autoWidth": false,
            "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null
        };
        
        // Her tabloya basit konfigürasyon uygula
        try {
            // Şirketler tablosu
            if (document.getElementById('companiesTable')) {
                initDataTable('companiesTable', simpleTableOptions);
            }
            
            // Muayeneler tablosu
            if (document.getElementById('inspectionsTable')) {
                initDataTable('inspectionsTable', simpleTableOptions);
            }
            
            // Araçlar tablosu
            if (document.getElementById('vehiclesTable')) {
                initDataTable('vehiclesTable', simpleTableOptions);
            }
            
            // Sürücüler tablosu
            if (document.getElementById('driversTable')) {
                initDataTable('driversTable', simpleTableOptions);
            }
            
            // Bakım tablosu
            if (document.getElementById('maintenanceTable')) {
                initDataTable('maintenanceTable', {
                    ...simpleTableOptions,
                    "paging": true,
                    "lengthMenu": [5, 10]
                });
            }
        } catch (e) {
            console.error("DataTables başlatma hatası:", e);
            
            // Hata durumunda tablo stillerini düzelt
            document.querySelectorAll('.table').forEach(table => {
                table.classList.add('w-100');
            });
        }
    });
    
    // Yakıt grafiği
    var ctx1 = document.getElementById('fuelChart').getContext('2d');
    var fuelData = <?php echo json_encode($data['fuel_consumption_months'] ?? []); ?>;
    var fuelLabels = [];
    var fuelValues = [];
    
    if (fuelData && fuelData.length > 0) {
        for (var i = 0; i < fuelData.length; i++) {
            fuelLabels.push(fuelData[i].month_name);
            fuelValues.push(fuelData[i].total_amount);
        }
    } else {
        // Veri yoksa varsayılan değerler
        fuelLabels = ['Veri Yok'];
        fuelValues = [0];
    }
    
    var fuelChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: fuelLabels,
            datasets: [{
                label: 'Yakıt Tüketimi (Lt)',
                data: fuelValues,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Araç dağılımı grafiği
    var ctx2 = document.getElementById('vehicleChart').getContext('2d');
    var vehicleData = <?php echo json_encode($data['vehicle_type_distribution'] ?? []); ?>;
    var vehicleLabels = [];
    var vehicleValues = [];
    var backgroundColors = [
        'rgba(78, 115, 223, 0.8)',
        'rgba(28, 200, 138, 0.8)',
        'rgba(54, 185, 204, 0.8)',
        'rgba(246, 194, 62, 0.8)',
        'rgba(231, 74, 59, 0.8)',
        'rgba(133, 135, 150, 0.8)'
    ];
    
    if (vehicleData && vehicleData.length > 0) {
        for (var i = 0; i < vehicleData.length; i++) {
            vehicleLabels.push(vehicleData[i].vehicle_type);
            vehicleValues.push(vehicleData[i].count);
        }
    } else {
        // Veri yoksa varsayılan değerler
        vehicleLabels = ['Veri Yok'];
        vehicleValues = [0];
    }
    
    var vehicleChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: vehicleLabels,
            datasets: [{
                data: vehicleValues,
                backgroundColor: backgroundColors.slice(0, vehicleLabels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 