<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $data['title']; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/insurance">Sigorta ve Muayene Takibi</a></li>
        <li class="breadcrumb-item active">Yaklaşan Trafik Sigortaları</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-contract me-1"></i>
            <?php echo $data['days']; ?> Gün İçinde Süresi Dolacak Trafik Sigortaları
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="upcomingTrafficTable">
                    <thead>
                        <tr>
                            <th>Plaka</th>
                            <th>Araç Bilgisi</th>
                            <th>Şirket</th>
                            <th>Sigorta Şirketi</th>
                            <th>Sigorta Tarihi</th>
                            <th>Kalan Gün</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['vehicles'])): ?>
                            <?php foreach($data['vehicles'] as $vehicle): ?>
                                <tr>
                                    <td><?php echo $vehicle->plate_number; ?></td>
                                    <td><?php echo $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->year . ')'; ?></td>
                                    <td><?php echo isset($vehicle->company_name) ? $vehicle->company_name : '-'; ?></td>
                                    <td><?php echo !empty($vehicle->traffic_insurance_agency) ? $vehicle->traffic_insurance_agency : '-'; ?></td>
                                    <td><?php echo date('d.m.Y', strtotime($vehicle->traffic_insurance_date)); ?></td>
                                    <td>
                                        <span class="badge <?php 
                                            if($vehicle->days_left <= 7) {
                                                echo 'bg-danger';
                                            } elseif($vehicle->days_left <= 15) {
                                                echo 'bg-warning';
                                            } else {
                                                echo 'bg-primary';
                                            }
                                        ?>">
                                            <?php echo $vehicle->days_left; ?> gün
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php 
                                            switch($vehicle->status) {
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
                                            <?php echo $vehicle->status; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/insurance/vehicle/<?php echo $vehicle->id; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $vehicle->id; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Önümüzdeki <?php echo $data['days']; ?> gün içinde süresi dolacak trafik sigortası bulunamadı</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Hızlı Bilgiler
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Toplam Araç Sayısı
                            <span class="badge bg-primary rounded-pill" id="totalVehicles">Yükleniyor...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Bu Ay Sigortası Bitecek Araçlar
                            <span class="badge bg-warning rounded-pill" id="thisMonth">Yükleniyor...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Önümüzdeki Hafta Sigortası Bitecek Araçlar
                            <span class="badge bg-danger rounded-pill" id="nextWeek">Yükleniyor...</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tasks me-1"></i>
                    Aksiyon Önerileri
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Dikkat!</strong> Trafik sigortası süresi yaklaşan araçların evraklarını kontrol edin.
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i> Sigorta teklifi almak için sigortacınızla iletişime geçin
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i> Trafik ceza sorgulamasını yapın
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i> Araç ruhsat bilgilerinin güncel olduğundan emin olun
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i> Araç muayenesinin güncel olduğundan emin olun
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTables başlat
        $('#upcomingTrafficTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
            },
            order: [[5, 'asc']] // Kalan gün sütununa göre artan sıralama
        });
        
        // İstatistikleri hesapla
        calculateStatistics();
    });
    
    // İstatistikleri hesapla
    function calculateStatistics() {
        // Tablo verisinden istatistik hesapla
        const table = document.getElementById('upcomingTrafficTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        let total = rows.length;
        let thisMonth = 0;
        let nextWeek = 0;
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0 && cells[5]) { // Kalan gün sütunu kontrolü
                const daysText = cells[5].textContent.trim();
                const days = parseInt(daysText);
                
                if (!isNaN(days)) {
                    if (days <= 7) {
                        nextWeek++;
                    }
                    
                    if (days <= 30) {
                        thisMonth++;
                    }
                }
            }
        }
        
        // İstatistik değerlerini güncelle
        document.getElementById('totalVehicles').textContent = total;
        document.getElementById('thisMonth').textContent = thisMonth;
        document.getElementById('nextWeek').textContent = nextWeek;
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 