<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $data['title']; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/insurance">Sigorta ve Muayene Takibi</a></li>
        <li class="breadcrumb-item active">Süresi Geçmiş Trafik Sigortaları</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Trafik Sigortası Süresi Geçmiş Araçlar
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="expiredTrafficTable">
                    <thead>
                        <tr>
                            <th>Plaka</th>
                            <th>Araç Bilgisi</th>
                            <th>Şirket</th>
                            <th>Sigorta Şirketi</th>
                            <th>Sigorta Tarihi</th>
                            <th>Gecikme</th>
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
                                        <span class="badge bg-danger">
                                            <?php echo $vehicle->days_expired; ?> gün geçmiş
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
                                <td colspan="8" class="text-center">Süresi geçmiş trafik sigortası bulunmamaktadır</td>
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
                    Genel Bilgiler
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Dikkat!</strong> Süresi geçmiş trafik sigortası olan araçların trafiğe çıkması yasaktır.
                    </div>
                    <p>Trafik sigortası süresi geçmiş araçlar için:</p>
                    <ul>
                        <li>Trafik cezası uygulanır</li>
                        <li>Kaza durumunda maddi ve manevi tazminat yükümlülüğü doğar</li>
                        <li>Araç sahibi tüm maliyetleri üstlenmek zorunda kalır</li>
                        <li>Araç bağlanabilir veya trafikten men edilebilir</li>
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
                        <i class="fas fa-exclamation-circle"></i> <strong>Acil Aksiyon!</strong> Süresi geçmiş trafik sigortası olan araçların en kısa sürede sigortalarını yaptırın.
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            1-30 gün gecikmeli
                            <span class="badge bg-warning rounded-pill" id="delay30">Hesaplanıyor...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            31-60 gün gecikmeli
                            <span class="badge bg-danger rounded-pill" id="delay60">Hesaplanıyor...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            60+ gün gecikmeli
                            <span class="badge bg-dark rounded-pill" id="delay61Plus">Hesaplanıyor...</span>
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
        $('#expiredTrafficTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
            },
            order: [[5, 'desc']] // Gecikme sütununa göre azalan sıralama
        });
        
        // İstatistikleri hesapla
        calculateDelayStatistics();
    });
    
    // Gecikme istatistiklerini hesapla
    function calculateDelayStatistics() {
        // Tablo verisinden istatistik hesapla
        const table = document.getElementById('expiredTrafficTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        let delay30 = 0;
        let delay60 = 0;
        let delay61Plus = 0;
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0 && cells[5]) { // Gecikme sütunu kontrolü
                const delayText = cells[5].textContent.trim();
                const daysMatch = delayText.match(/(\d+)/);
                
                if (daysMatch) {
                    const days = parseInt(daysMatch[1]);
                    
                    if (days <= 30) {
                        delay30++;
                    } else if (days <= 60) {
                        delay60++;
                    } else {
                        delay61Plus++;
                    }
                }
            }
        }
        
        // İstatistik değerlerini güncelle
        document.getElementById('delay30').textContent = delay30;
        document.getElementById('delay60').textContent = delay60;
        document.getElementById('delay61Plus').textContent = delay61Plus;
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 