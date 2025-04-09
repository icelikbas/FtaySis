<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/tanks" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-gas-pump me-2"></i>Tank Detayları</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="row mb-4">
                    <!-- Ana Bilgiler -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Temel Bilgiler</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Tank Adı:</strong>
                                        <span><?php echo $data['tank']->name; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Tank Tipi:</strong>
                                        <span><?php echo $data['tank']->type; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Yakıt Tipi:</strong>
                                        <span><?php echo $data['tank']->fuel_type ?? 'Belirtilmemiş'; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Lokasyon:</strong>
                                        <span><?php echo $data['tank']->location; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Durum:</strong>
                                        <span>
                                            <span class="badge bg-<?php echo ($data['tank']->status == 'Aktif') ? 'success' : (($data['tank']->status == 'Pasif') ? 'secondary' : 'warning'); ?>">
                                                <?php echo $data['tank']->status; ?>
                                            </span>
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Kayıt Tarihi:</strong>
                                        <span><?php echo date('d.m.Y H:i', strtotime($data['tank']->created_at)); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Doluluk Bilgileri -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Doluluk Durumu</h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                    $fillRate = ($data['tank']->capacity > 0) ? ($data['tank']->current_amount / $data['tank']->capacity) * 100 : 0;
                                    $fillRateClass = ($fillRate > 75) ? 'success' : (($fillRate > 25) ? 'warning' : 'danger');
                                ?>
                                
                                <!-- Yeni doluluk göstergesi - basit ve güvenilir versiyonu -->
                                <div class="text-center mb-4">
                                    <div class="display-4 fw-bold text-<?php echo $fillRateClass; ?> mb-2">
                                        <?php echo number_format($fillRate, 0); ?>%
                                    </div>
                                    
                                    <div class="progress" style="height: 24px; border-radius: 12px;">
                                        <div class="progress-bar bg-<?php echo $fillRateClass; ?>" 
                                             style="width: <?php echo $fillRate; ?>%;"
                                             role="progressbar" 
                                             aria-valuenow="<?php echo $fillRate; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <span class="fw-bold"><?php echo number_format($fillRate, 0); ?>%</span>
                                        </div>
                                    </div>
                                    <div class="text-muted mt-2">Doluluk Oranı</div>
                                </div>
                                
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Kapasite:</strong>
                                        <span><?php echo number_format($data['tank']->capacity, 2); ?> Lt</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Mevcut Miktar:</strong>
                                        <span><?php echo number_format($data['tank']->current_amount, 2); ?> Lt</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Boş Alan:</strong>
                                        <span><?php echo number_format($data['tank']->capacity - $data['tank']->current_amount, 2); ?> Lt</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- İşlemler Bölümü - yeniden tasarım -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>İşlemler</h5>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <!-- Ana işlemler -->
                        <div class="mb-3">
                            <a href="<?php echo URLROOT; ?>/tanks/edit/<?php echo $data['tank']->id; ?>" class="btn btn-warning w-100 py-2 mb-2">
                                <i class="fas fa-edit me-2"></i> Tankı Düzenle
                            </a>
                            
                            <?php if(isAdmin()) : ?>
                                <button type="button" class="btn btn-danger w-100 py-2" 
                                       onclick="if(confirm('Bu tankı silmek istediğinize emin misiniz?')) { 
                                           document.getElementById('delete-tank-form').submit(); 
                                       }">
                                    <i class="fas fa-trash me-2"></i> Tankı Sil
                                </button>
                                <form id="delete-tank-form" action="<?php echo URLROOT; ?>/tanks/delete/<?php echo $data['tank']->id; ?>" method="post" class="d-none"></form>
                            <?php endif; ?>
                        </div>
                        
                        <div class="border-top pt-3 mb-3">
                            <h6 class="text-muted mb-3">Yakıt İşlemleri</h6>
                            
                            <a href="<?php echo URLROOT; ?>/purchases/add/<?php echo $data['tank']->id; ?>" class="btn btn-success d-flex align-items-center justify-content-between w-100 py-2 mb-2">
                                <span><i class="fas fa-gas-pump me-2"></i> Yakıt Alımı Ekle</span>
                                <i class="fas fa-angle-right"></i>
                            </a>
                            
                            <a href="<?php echo URLROOT; ?>/transfers/add/<?php echo $data['tank']->id; ?>" class="btn btn-info d-flex align-items-center justify-content-between w-100 py-2">
                                <span><i class="fas fa-exchange-alt me-2"></i> Yakıt Transferi Yap</span>
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </div>
                        
                        <div class="mt-auto text-center">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <small class="text-muted">Son Güncelleme:</small>
                                    <div><?php echo date('d.m.Y H:i', strtotime($data['tank']->updated_at ?? $data['tank']->created_at)); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Yakıt Hareketleri -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Yakıt Hareketleri</h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="fuelMovementTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button" role="tab" aria-controls="purchases" aria-selected="true">Yakıt Alımları</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="transfers-tab" data-bs-toggle="tab" data-bs-target="#transfers" type="button" role="tab" aria-controls="transfers" aria-selected="false">Yakıt Transferleri</button>
            </li>
        </ul>
        <div class="tab-content" id="fuelMovementTabContent">
            <div class="tab-pane fade show active" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                <div class="p-3">
                    <?php if(empty($data['purchases'])) : ?>
                        <p class="text-muted">Bu tanka ait yakıt alımı bulunmamaktadır.</p>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table id="purchasesTable" class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%">ID</th>
                                        <th style="width: 12%">Tarih</th>
                                        <th style="width: 15%">Miktar (Lt)</th>
                                        <th style="width: 12%">Birim Fiyat</th>
                                        <th style="width: 15%">Toplam Tutar</th>
                                        <th style="width: 25%">Tedarikçi</th>
                                        <th style="width: 15%">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['purchases'] as $purchase) : ?>
                                        <tr>
                                            <td><?php echo $purchase->id; ?></td>
                                            <td><?php echo date('d.m.Y', strtotime($purchase->date)); ?></td>
                                            <td><?php echo number_format($purchase->amount, 2); ?> Lt</td>
                                            <td><?php echo number_format($purchase->unit_price, 2); ?> TL</td>
                                            <td><?php echo number_format($purchase->cost, 2); ?> TL</td>
                                            <td><?php echo $purchase->supplier_name; ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/purchases/show/<?php echo $purchase->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detay
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <th colspan="2">Toplam</th>
                                        <th>
                                            <?php 
                                                $totalAmount = 0;
                                                foreach($data['purchases'] as $purchase) {
                                                    $totalAmount += $purchase->amount;
                                                }
                                                echo number_format($totalAmount, 2) . ' Lt';
                                            ?>
                                        </th>
                                        <th>
                                            <?php
                                                $avgUnitPrice = 0;
                                                if ($totalAmount > 0) {
                                                    $totalCost = 0;
                                                    foreach($data['purchases'] as $purchase) {
                                                        $totalCost += $purchase->cost;
                                                    }
                                                    $avgUnitPrice = $totalCost / $totalAmount;
                                                }
                                                echo number_format($avgUnitPrice, 2) . ' TL';
                                            ?>
                                        </th>
                                        <th>
                                            <?php
                                                $totalCost = 0;
                                                foreach($data['purchases'] as $purchase) {
                                                    $totalCost += $purchase->cost;
                                                }
                                                echo number_format($totalCost, 2) . ' TL';
                                            ?>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane fade" id="transfers" role="tabpanel" aria-labelledby="transfers-tab">
                <div class="p-3">
                    <?php if(empty($data['transfers'])) : ?>
                        <p class="text-muted">Bu tanka ait yakıt transferi bulunmamaktadır.</p>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table id="transfersTable" class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%">ID</th>
                                        <th style="width: 12%">Tarih</th>
                                        <th style="width: 25%">Kaynak</th>
                                        <th style="width: 25%">Hedef</th>
                                        <th style="width: 15%">Miktar (Lt)</th>
                                        <th style="width: 10%">Yakıt Tipi</th>
                                        <th style="width: 8%">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['transfers'] as $transfer) : ?>
                                        <tr>
                                            <td><?php echo $transfer->id; ?></td>
                                            <td><?php echo date('d.m.Y', strtotime($transfer->transfer_date)); ?></td>
                                            <td>
                                                <?php if($transfer->source_tank_id == $data['tank']->id): ?>
                                                    <span class="text-danger"><i class="fas fa-arrow-right"></i> <?php echo $transfer->source_name; ?></span>
                                                <?php else: ?>
                                                    <?php echo $transfer->source_name; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($transfer->destination_tank_id == $data['tank']->id): ?>
                                                    <span class="text-success"><i class="fas fa-arrow-left"></i> <?php echo $transfer->destination_name; ?></span>
                                                <?php else: ?>
                                                    <?php echo $transfer->destination_name; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($transfer->source_tank_id == $data['tank']->id): ?>
                                                    <span class="text-danger">-<?php echo number_format($transfer->amount, 2); ?> Lt</span>
                                                <?php else: ?>
                                                    <span class="text-success">+<?php echo number_format($transfer->amount, 2); ?> Lt</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $transfer->fuel_type; ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/transfers/show/<?php echo $transfer->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detay
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap tablarını manuel olarak etkinleştirelim
    const tabLinks = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabLinks.forEach(tabLink => {
        tabLink.addEventListener('click', function(event) {
            event.preventDefault();
            const tabPaneSelector = this.getAttribute('data-bs-target');
            const tabPane = document.querySelector(tabPaneSelector);
            
            // Aktif tabı kaldır
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(button => {
                button.classList.remove('active');
                button.setAttribute('aria-selected', 'false');
            });
            
            // Yeni tabı aktif yap
            tabPane.classList.add('show', 'active');
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
        });
    });
    
    // URL'de # sonrası bir kısım varsa ve bu bir tab ise onu aktif yap
    if (window.location.hash) {
        const tabId = window.location.hash.substring(1);
        const tab = document.querySelector(`button[data-bs-target="#${tabId}"]`);
        if (tab) {
            tab.click();
        }
    }

    // DataTables init
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        try {
            // Alımlar tablosu
            if (document.getElementById('purchasesTable')) {
                $('#purchasesTable').DataTable({
                    "responsive": true,
                    "scrollX": false,
                    "autoWidth": false,
                    "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
                    "paging": false,
                    "searching": false,
                    "info": false,
                    "ordering": false
                });
            }
            
            // Transferler tablosu
            if (document.getElementById('transfersTable')) {
                $('#transfersTable').DataTable({
                    "responsive": true,
                    "scrollX": false,
                    "autoWidth": false,
                    "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
                    "paging": false,
                    "searching": false,
                    "info": false,
                    "ordering": false
                });
            }
        } catch (error) {
            console.error("DataTable başlatılırken hata oluştu:", error);
        }
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 