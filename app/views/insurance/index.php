<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $data['title']; ?></h1>
    
    <?php flash('insurance_message'); ?>
    
    <!-- Üst Bilgi Kartları -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Yaklaşan Muayeneler (30 gün)</div>
                            <div class="fs-4" id="upcomingInspectionCount">Yükleniyor...</div>
                        </div>
                        <div><i class="fas fa-car fa-2x"></i></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo URLROOT; ?>/insurance/upcomingInspections">Detayları Görüntüle</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Yaklaşan Trafik Sigortaları (30 gün)</div>
                            <div class="fs-4" id="upcomingTrafficCount">Yükleniyor...</div>
                        </div>
                        <div><i class="fas fa-file-contract fa-2x"></i></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo URLROOT; ?>/insurance/upcomingTrafficInsurance">Detayları Görüntüle</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Yaklaşan Kasko Sigortaları (30 gün)</div>
                            <div class="fs-4" id="upcomingCascoCount">Yükleniyor...</div>
                        </div>
                        <div><i class="fas fa-shield-alt fa-2x"></i></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo URLROOT; ?>/insurance/upcomingCascoInsurance">Detayları Görüntüle</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Süresi Geçmiş Evraklar</div>
                            <div class="fs-4" id="expiredDocCount">Yükleniyor...</div>
                        </div>
                        <div><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#expiredDocumentsSection">Detayları Görüntüle</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tüm Araçlar Tablosu -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tüm Araçların Sigorta ve Muayene Durumu
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="vehiclesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Plaka</th>
                            <th>Araç Bilgisi</th>
                            <th>Şirket</th>
                            <th>Durum</th>
                            <th>Muayene Tarihi</th>
                            <th>Kalan Gün</th>
                            <th>Trafik Sigortası</th>
                            <th>Kalan Gün</th>
                            <th>Kasko Sigortası</th>
                            <th>Kalan Gün</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['vehicles'])): ?>
                            <?php foreach($data['vehicles'] as $vehicle): ?>
                                <tr>
                                    <td><?php echo $vehicle->plate_number; ?></td>
                                    <td><?php echo $vehicle->brand . ' ' . $vehicle->model; ?></td>
                                    <td><?php echo $vehicle->company_name; ?></td>
                                    <td>
                                        <span class="badge <?php 
                                            if($vehicle->status == 'Aktif') {
                                                echo 'bg-success';
                                            } elseif($vehicle->status == 'Pasif') {
                                                echo 'bg-secondary';
                                            } elseif($vehicle->status == 'Bakımda') {
                                                echo 'bg-warning';
                                            } elseif($vehicle->status == 'Arızalı') {
                                                echo 'bg-danger';
                                            } else {
                                                echo 'bg-info';
                                            }
                                        ?>">
                                            <?php echo $vehicle->status ?: 'Belirtilmemiş'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if(!empty($vehicle->inspection_date)): ?>
                                            <?php echo date('d.m.Y', strtotime($vehicle->inspection_date)); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Belirtilmemiş</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($vehicle->inspection_days_left !== null): ?>
                                            <span class="badge <?php 
                                                if($vehicle->inspection_days_left < 0) {
                                                    echo 'bg-danger';
                                                } elseif($vehicle->inspection_days_left <= 30) {
                                                    echo 'bg-warning';
                                                } else {
                                                    echo 'bg-success';
                                                }
                                            ?>">
                                                <?php 
                                                    if($vehicle->inspection_days_left < 0) {
                                                        echo abs($vehicle->inspection_days_left) . ' gün geçmiş';
                                                    } else {
                                                        echo $vehicle->inspection_days_left . ' gün';
                                                    }
                                                ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($vehicle->traffic_insurance_date)): ?>
                                            <?php echo date('d.m.Y', strtotime($vehicle->traffic_insurance_date)); ?>
                                            <?php if(!empty($vehicle->traffic_insurance_agency)): ?>
                                                <small class="d-block text-muted"><?php echo $vehicle->traffic_insurance_agency; ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Belirtilmemiş</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($vehicle->traffic_days_left !== null): ?>
                                            <span class="badge <?php 
                                                if($vehicle->traffic_days_left < 0) {
                                                    echo 'bg-danger';
                                                } elseif($vehicle->traffic_days_left <= 30) {
                                                    echo 'bg-warning';
                                                } else {
                                                    echo 'bg-success';
                                                }
                                            ?>">
                                                <?php 
                                                    if($vehicle->traffic_days_left < 0) {
                                                        echo abs($vehicle->traffic_days_left) . ' gün geçmiş';
                                                    } else {
                                                        echo $vehicle->traffic_days_left . ' gün';
                                                    }
                                                ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($vehicle->casco_insurance_date)): ?>
                                            <?php echo date('d.m.Y', strtotime($vehicle->casco_insurance_date)); ?>
                                            <?php if(!empty($vehicle->casco_insurance_agency)): ?>
                                                <small class="d-block text-muted"><?php echo $vehicle->casco_insurance_agency; ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Belirtilmemiş</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($vehicle->casco_days_left !== null): ?>
                                            <span class="badge <?php 
                                                if($vehicle->casco_days_left < 0) {
                                                    echo 'bg-danger';
                                                } elseif($vehicle->casco_days_left <= 30) {
                                                    echo 'bg-warning';
                                                } else {
                                                    echo 'bg-success';
                                                }
                                            ?>">
                                                <?php 
                                                    if($vehicle->casco_days_left < 0) {
                                                        echo abs($vehicle->casco_days_left) . ' gün geçmiş';
                                                    } else {
                                                        echo $vehicle->casco_days_left . ' gün';
                                                    }
                                                ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
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
                                <td colspan="10" class="text-center">Kayıtlı araç bulunamadı</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Süresi Geçmiş Evraklar Bölümü -->
    <div id="expiredDocumentsSection">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <h2 class="m-0"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Süresi Geçmiş Evraklar</h2>
            <div class="bg-danger text-white px-3 py-2 rounded-pill">
                <strong>Toplam: </strong><span id="expiredDocTotal">0</span> evrak
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm border-danger border-opacity-25">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-car-crash me-1"></i>
                        Süresi Geçmiş Muayeneler
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="expiredInspectionsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%" class="text-center">Plaka</th>
                                        <th style="width: 30%" class="text-center">Tarih</th>
                                        <th style="width: 30%" class="text-center">Gecikme</th>
                                    </tr>
                                </thead>
                                <tbody id="expiredInspectionsList">
                                    <!-- JavaScript ile doldurulacak -->
                                    <tr>
                                        <td colspan="3" class="text-center">Yükleniyor...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo URLROOT; ?>/insurance/expiredInspections" class="btn btn-outline-danger w-100">
                                <i class="fas fa-list-ul me-1"></i> Tümünü Görüntüle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm border-danger border-opacity-25">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-file-contract me-1"></i>
                        Süresi Geçmiş Trafik Sigortaları
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="expiredTrafficTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%" class="text-center">Plaka</th>
                                        <th style="width: 30%" class="text-center">Tarih</th>
                                        <th style="width: 30%" class="text-center">Gecikme</th>
                                    </tr>
                                </thead>
                                <tbody id="expiredTrafficList">
                                    <!-- JavaScript ile doldurulacak -->
                                    <tr>
                                        <td colspan="3" class="text-center">Yükleniyor...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo URLROOT; ?>/insurance/expiredTrafficInsurance" class="btn btn-outline-danger w-100">
                                <i class="fas fa-list-ul me-1"></i> Tümünü Görüntüle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm border-danger border-opacity-25">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-shield-alt me-1"></i>
                        Süresi Geçmiş Kasko Sigortaları
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="expiredCascoTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%" class="text-center">Plaka</th>
                                        <th style="width: 30%" class="text-center">Tarih</th>
                                        <th style="width: 30%" class="text-center">Gecikme</th>
                                    </tr>
                                </thead>
                                <tbody id="expiredCascoList">
                                    <!-- JavaScript ile doldurulacak -->
                                    <tr>
                                        <td colspan="3" class="text-center">Yükleniyor...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo URLROOT; ?>/insurance/expiredCascoInsurance" class="btn btn-outline-danger w-100">
                                <i class="fas fa-list-ul me-1"></i> Tümünü Görüntüle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript kodu -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTable başlatma
        const vehiclesTable = $('#vehiclesTable').DataTable({
            language: {
                url: '<?php echo URLROOT; ?>/public/js/lib/datatables/Turkish.json'
            },
            stateSave: true,
            order: [[0, 'asc']], // Plakaya göre sıralama
            responsive: window.InsuranceHelpers ? window.InsuranceHelpers.getInsuranceResponsiveConfig() : true,
            pageLength: 25,
            columnDefs: [
                { responsivePriority: 1, targets: [0] }, // Plaka her zaman görünür
                { responsivePriority: 2, targets: [4, 5] }, // Muayene bilgileri öncelikli
                { responsivePriority: 3, targets: [6, 7] }, // Trafik sigortası bilgileri
                { responsivePriority: 4, targets: [8, 9] }  // Kasko bilgileri
            ]
        });
        
        // İstatistikleri güncelle
        updateStatistics();
        
        // Süresi geçmiş belgeleri liste
        updateExpiredDocuments();
        
        // Filtreleri uygula
        applyFilters();
    });
    
    // Filtreleri uygula
    function applyFilters() {
        // Seçili filtreler
        const statusFilter = document.getElementById('statusFilter');
        const companyFilter = document.getElementById('companyFilter');
        
        if (statusFilter && companyFilter) {
            const status = statusFilter.value;
            const company = companyFilter.value;
            
            // DataTable API'sini al
            const table = $('#vehiclesTable').DataTable();
            
            // Önce tüm filtreleri temizle
            table.search('').columns().search('').draw();
            
            // Durum filtresi uygula
            if (status !== 'all') {
                table.column(3).search(status).draw();
            }
            
            // Şirket filtresi uygula
            if (company !== 'all') {
                table.column(2).search(company).draw();
            }
        }
    }
    
    // İstatistikleri hesapla ve güncelle
    function updateStatistics() {
        if (window.InsuranceHelpers) {
            const stats = InsuranceHelpers.calculateStats('vehiclesTable');
            
            if (stats) {
                document.getElementById('upcomingInspectionCount').textContent = stats.upcomingInspection;
                document.getElementById('upcomingTrafficCount').textContent = stats.upcomingTraffic;
                document.getElementById('upcomingCascoCount').textContent = stats.upcomingCasco;
                document.getElementById('expiredDocCount').textContent = stats.expiredDoc;
                document.getElementById('expiredDocTotal').textContent = stats.expiredDoc;
            }
        } else {
            // InsuranceHelpers yüklenmemişse eski yöntemi kullan
            calculateStats();
        }
    }
    
    // Süresi geçmiş evrakları güncelle
    function updateExpiredDocuments() {
        if (window.InsuranceHelpers) {
            const expiredDocs = InsuranceHelpers.loadExpiredDocuments('vehiclesTable');
            
            if (expiredDocs) {
                // Muayene listesini güncelle
                updateExpiredList('expiredInspectionsList', expiredDocs.expiredInspections);
                
                // Trafik sigortası listesini güncelle
                updateExpiredList('expiredTrafficList', expiredDocs.expiredTraffic);
                
                // Kasko sigortası listesini güncelle
                updateExpiredList('expiredCascoList', expiredDocs.expiredCasco);
            }
        } else {
            // InsuranceHelpers yüklenmemişse eski yöntemi kullan
            loadExpiredDocuments();
        }
    }
    
    // Süresi geçmiş liste güncelleme yardımcı fonksiyonu
    function updateExpiredList(listId, items) {
        const listElement = document.getElementById(listId);
        listElement.innerHTML = '';
        
        if (items.length > 0) {
            // En fazla 5 tanesini göster
            const limit = Math.min(items.length, 5);
            for (let i = 0; i < limit; i++) {
                const item = items[i];
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="align-middle fw-bold">${item.plate}</td>
                    <td class="align-middle text-center">${item.date}</td>
                    <td class="align-middle text-center"><span class="badge bg-danger rounded-pill">${item.days} gün geçmiş</span></td>
                `;
                listElement.appendChild(row);
            }
        } else {
            const row = document.createElement('tr');
            let message = 'Süresi geçmiş';
            
            if (listId.includes('Inspection')) {
                message += ' muayene';
            } else if (listId.includes('Traffic')) {
                message += ' trafik sigortası';
            } else if (listId.includes('Casco')) {
                message += ' kasko sigortası';
            }
            
            row.innerHTML = `<td colspan="3" class="text-center py-3">${message} yok</td>`;
            listElement.appendChild(row);
        }
    }
    
    // İstatistikleri hesapla - InsuranceHelpers yüklenmediğinde kullanılır
    function calculateStats() {
        let upcomingInspectionCount = 0;
        let upcomingTrafficCount = 0;
        let upcomingCascoCount = 0;
        let expiredDocCount = 0;
        
        // Tablo verisini kontrol et
        const table = document.getElementById('vehiclesTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0) {
                // Muayene kontrolü
                const inspectionDays = cells[5].textContent.trim();
                if (inspectionDays.includes('gün') && !inspectionDays.includes('geçmiş')) {
                    const days = parseInt(inspectionDays);
                    if (!isNaN(days) && days <= 30) {
                        upcomingInspectionCount++;
                    }
                } else if (inspectionDays.includes('geçmiş')) {
                    expiredDocCount++;
                }
                
                // Trafik sigortası kontrolü
                const trafficDays = cells[7].textContent.trim();
                if (trafficDays.includes('gün') && !trafficDays.includes('geçmiş')) {
                    const days = parseInt(trafficDays);
                    if (!isNaN(days) && days <= 30) {
                        upcomingTrafficCount++;
                    }
                } else if (trafficDays.includes('geçmiş')) {
                    expiredDocCount++;
                }
                
                // Kasko sigortası kontrolü
                const cascoDays = cells[9].textContent.trim();
                if (cascoDays.includes('gün') && !cascoDays.includes('geçmiş')) {
                    const days = parseInt(cascoDays);
                    if (!isNaN(days) && days <= 30) {
                        upcomingCascoCount++;
                    }
                } else if (cascoDays.includes('geçmiş')) {
                    expiredDocCount++;
                }
            }
        }
        
        // İstatistik değerlerini güncelle
        document.getElementById('upcomingInspectionCount').textContent = upcomingInspectionCount;
        document.getElementById('upcomingTrafficCount').textContent = upcomingTrafficCount;
        document.getElementById('upcomingCascoCount').textContent = upcomingCascoCount;
        document.getElementById('expiredDocCount').textContent = expiredDocCount;
        document.getElementById('expiredDocTotal').textContent = expiredDocCount;
    }
    
    // Süresi geçmiş evrakları yükle - InsuranceHelpers yüklenmediğinde kullanılır
    function loadExpiredDocuments() {
        // Tablodaki verileri kullanarak süresi geçmiş olanları listele
        const table = document.getElementById('vehiclesTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        let expiredInspections = [];
        let expiredTraffic = [];
        let expiredCasco = [];
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0) {
                const plate = cells[0].textContent.trim();
                
                // Muayene kontrolü
                const inspectionDate = cells[4].textContent.trim();
                const inspectionDays = cells[5].textContent.trim();
                if (inspectionDays.includes('geçmiş')) {
                    const days = parseInt(inspectionDays);
                    if (!isNaN(days)) {
                        expiredInspections.push({
                            plate: plate,
                            date: inspectionDate,
                            days: days
                        });
                    }
                }
                
                // Trafik sigortası kontrolü
                const trafficDate = cells[6].textContent.trim().split('\n')[0];
                const trafficDays = cells[7].textContent.trim();
                if (trafficDays.includes('geçmiş')) {
                    const days = parseInt(trafficDays);
                    if (!isNaN(days)) {
                        expiredTraffic.push({
                            plate: plate,
                            date: trafficDate,
                            days: days
                        });
                    }
                }
                
                // Kasko sigortası kontrolü
                const cascoDate = cells[8].textContent.trim().split('\n')[0];
                const cascoDays = cells[9].textContent.trim();
                if (cascoDays.includes('geçmiş')) {
                    const days = parseInt(cascoDays);
                    if (!isNaN(days)) {
                        expiredCasco.push({
                            plate: plate,
                            date: cascoDate,
                            days: days
                        });
                    }
                }
            }
        }
        
        // Süresi geçmiş muayeneleri listele
        const expiredInspectionsList = document.getElementById('expiredInspectionsList');
        expiredInspectionsList.innerHTML = '';
        
        if (expiredInspections.length > 0) {
            // En fazla 5 tanesini göster
            const limit = Math.min(expiredInspections.length, 5);
            for (let i = 0; i < limit; i++) {
                const item = expiredInspections[i];
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="align-middle fw-bold">${item.plate}</td>
                    <td class="align-middle text-center">${item.date}</td>
                    <td class="align-middle text-center"><span class="badge bg-danger rounded-pill">${item.days} gün geçmiş</span></td>
                `;
                expiredInspectionsList.appendChild(row);
            }
        } else {
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="3" class="text-center py-3">Süresi geçmiş muayene yok</td>';
            expiredInspectionsList.appendChild(row);
        }
        
        // Süresi geçmiş trafik sigortalarını listele
        const expiredTrafficList = document.getElementById('expiredTrafficList');
        expiredTrafficList.innerHTML = '';
        
        if (expiredTraffic.length > 0) {
            // En fazla 5 tanesini göster
            const limit = Math.min(expiredTraffic.length, 5);
            for (let i = 0; i < limit; i++) {
                const item = expiredTraffic[i];
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="align-middle fw-bold">${item.plate}</td>
                    <td class="align-middle text-center">${item.date}</td>
                    <td class="align-middle text-center"><span class="badge bg-danger rounded-pill">${item.days} gün geçmiş</span></td>
                `;
                expiredTrafficList.appendChild(row);
            }
        } else {
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="3" class="text-center py-3">Süresi geçmiş trafik sigortası yok</td>';
            expiredTrafficList.appendChild(row);
        }
        
        // Süresi geçmiş kasko sigortalarını listele
        const expiredCascoList = document.getElementById('expiredCascoList');
        expiredCascoList.innerHTML = '';
        
        if (expiredCasco.length > 0) {
            // En fazla 5 tanesini göster
            const limit = Math.min(expiredCasco.length, 5);
            for (let i = 0; i < limit; i++) {
                const item = expiredCasco[i];
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="align-middle fw-bold">${item.plate}</td>
                    <td class="align-middle text-center">${item.date}</td>
                    <td class="align-middle text-center"><span class="badge bg-danger rounded-pill">${item.days} gün geçmiş</span></td>
                `;
                expiredCascoList.appendChild(row);
            }
        } else {
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="3" class="text-center py-3">Süresi geçmiş kasko sigortası yok</td>';
            expiredCascoList.appendChild(row);
        }
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 