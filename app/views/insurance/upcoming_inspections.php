<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $data['title']; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/insurance">Sigorta ve Muayene Takibi</a></li>
        <li class="breadcrumb-item active">Yaklaşan Muayeneler</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-car me-1"></i>
            <?php echo $data['days']; ?> Gün İçinde Muayenesi Gelecek Araçlar
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="upcomingInspectionsTable">
                    <thead>
                        <tr>
                            <th>Plaka</th>
                            <th>Araç Bilgisi</th>
                            <th>Şirket</th>
                            <th>Muayene Tarihi</th>
                            <th>Kalan Gün</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['vehicles'])): ?>
                            <?php foreach($data['vehicles'] as $vehicle): ?>
                                <?php 
                                    $today = new DateTime();
                                    $inspectionDate = new DateTime($vehicle->inspection_date);
                                    $diff = $today->diff($inspectionDate);
                                    $daysLeft = $diff->days;
                                ?>
                                <tr>
                                    <td><?php echo $vehicle->plate_number; ?></td>
                                    <td><?php echo $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->year . ')'; ?></td>
                                    <td><?php echo isset($vehicle->company_name) ? $vehicle->company_name : '-'; ?></td>
                                    <td><?php echo date('d.m.Y', strtotime($vehicle->inspection_date)); ?></td>
                                    <td>
                                        <span class="badge <?php 
                                            if($daysLeft <= 7) {
                                                echo 'bg-danger';
                                            } elseif($daysLeft <= 15) {
                                                echo 'bg-warning';
                                            } else {
                                                echo 'bg-primary';
                                            }
                                        ?>">
                                            <?php echo $daysLeft; ?> gün
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
                                <td colspan="7" class="text-center">Önümüzdeki <?php echo $data['days']; ?> gün içinde muayeneye girecek araç bulunamadı</td>
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
                            Bu Ay Muayeneye Girecek Araçlar
                            <span class="badge bg-warning rounded-pill" id="thisMonth">Yükleniyor...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Önümüzdeki Hafta Muayeneye Girecek Araçlar
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
                        <i class="fas fa-exclamation-triangle"></i> <strong>Dikkat!</strong> Muayene tarihi yaklaşan araçların bakım durumunu kontrol edin.
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i> Araç ruhsatlarının güncel olduğundan emin olun
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i> Egzoz emisyon ölçümlerini yaptırın
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i> Araç temizliğini ve bakımını tamamlayın
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i> Zorunlu trafik sigortasının güncel olduğundan emin olun
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
        $('#upcomingInspectionsTable').DataTable({
            language: {
                url: '<?php echo URLROOT; ?>/js/lib/datatables/i18n/Turkish.json'
            },
            order: [[4, 'asc']], // Kalan gün sütununa göre artan sıralama
            responsive: true,
            pageLength: 25,
            autoWidth: false
        });
        
        // İstatistikleri hesapla ve göster
        updateStatistics();
    });
    
    // İstatistikleri hesapla ve göster
    function updateStatistics() {
        if (window.InsuranceHelpers) {
            // Tablo verilerini kullanarak istatistikler oluştur
            let total = 0;
            let thisMonth = 0;
            let nextWeek = 0;
            
            const table = document.getElementById('upcomingInspectionsTable');
            const rows = table.querySelector('tbody').querySelectorAll('tr');
            
            total = rows.length;
            
            // Tablo satırlarından kalan gün bilgilerini al
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll('td');
                if (cells.length > 0) {
                    const daysText = cells[4].textContent.trim();
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
            
            // İstatistikleri güncelle
            updateStats(total, thisMonth, nextWeek);
        } else {
            // Yardımcı fonksiyonlar yüklenmemişse standart hesaplama kullan
            calculateStatistics();
        }
    }
    
    // İstatistik değerlerini güncelle
    function updateStats(total, thisMonth, nextWeek) {
        document.getElementById('totalVehicles').textContent = total;
        document.getElementById('thisMonth').textContent = thisMonth;
        document.getElementById('nextWeek').textContent = nextWeek;
    }
    
    // Standart istatistik hesaplama metodu
    function calculateStatistics() {
        // Tablo verisinden istatistik hesapla
        const table = document.getElementById('upcomingInspectionsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        let total = rows.length;
        let thisMonth = 0;
        let nextWeek = 0;
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0) {
                const daysText = cells[4].textContent.trim();
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