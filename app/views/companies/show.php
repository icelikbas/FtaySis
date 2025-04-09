<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-building text-primary mr-2"></i>Şirket Detayı
            </h1>
            <p class="mb-4">Şirket bilgilerini ve ilişkili verileri görüntüleyin.</p>
        </div>
    </div>

    <?php flash('company_message'); ?>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Şirket Bilgi Kartı -->
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Şirket Bilgileri</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">İşlemler:</div>
                            <a class="dropdown-item" href="<?php echo URLROOT; ?>/companies/edit/<?php echo $data['company']->id; ?>">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>Düzenle
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="silmeOnayi('<?php echo $data['company']->id; ?>', '<?php echo htmlspecialchars($data['company']->company_name); ?>');">
                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>Sil
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if (!empty($data['company']->logo_url)): ?>
                            <img src="<?php echo URLROOT . '/' . htmlspecialchars($data['company']->logo_url); ?>" alt="Logo" class="img-fluid rounded" style="max-height: 150px;">
                        <?php else: ?>
                            <div class="rounded bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                <i class="fas fa-building fa-5x text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                        
                        <h4 class="mt-3 mb-0 font-weight-bold"><?php echo htmlspecialchars($data['company']->company_name); ?></h4>
                        <span class="badge badge-pill badge-<?php echo $data['company']->status === 'Aktif' ? 'success' : 'danger'; ?> px-3 py-2 mt-2">
                            <?php echo htmlspecialchars($data['company']->status); ?>
                        </span>
                    </div>

                    <dl class="row">
                        <dt class="col-sm-4">Vergi No:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($data['company']->tax_number); ?></dd>
                        
                        <dt class="col-sm-4">Vergi Dairesi:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($data['company']->tax_office); ?></dd>
                        
                        <dt class="col-sm-4">Telefon:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($data['company']->phone ?? '-'); ?></dd>
                        
                        <dt class="col-sm-4">E-posta:</dt>
                        <dd class="col-sm-8">
                            <?php if (!empty($data['company']->email)): ?>
                                <a href="mailto:<?php echo htmlspecialchars($data['company']->email); ?>">
                                    <?php echo htmlspecialchars($data['company']->email); ?>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </dd>
                        
                        <dt class="col-sm-4">Adres:</dt>
                        <dd class="col-sm-8"><?php echo nl2br(htmlspecialchars($data['company']->address ?? '-')); ?></dd>
                        
                        <dt class="col-sm-4">Kayıt Tarihi:</dt>
                        <dd class="col-sm-8"><?php echo date('d.m.Y H:i', strtotime($data['company']->created_at)); ?></dd>
                        
                        <dt class="col-sm-4">Son Güncelleme:</dt>
                        <dd class="col-sm-8"><?php echo date('d.m.Y H:i', strtotime($data['company']->updated_at)); ?></dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100">
                        <a href="<?php echo URLROOT; ?>/companies/edit/<?php echo $data['company']->id; ?>" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i>Düzenle
                        </a>
                        <a href="<?php echo URLROOT; ?>/companies" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Listeye Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- İstatistikler -->
            <div class="row mb-4">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Toplam Sürücü Sayısı</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $data['company']->driver_count; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Toplam Araç Sayısı</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $data['company']->vehicle_count; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-car fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bağlı Araçlar ve Sürücüler Tab -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="companyTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="vehicles-tab" data-bs-toggle="tab" href="#vehicles" role="tab" aria-controls="vehicles" aria-selected="true">
                                <i class="fas fa-car mr-1"></i>Araçlar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="drivers-tab" data-bs-toggle="tab" href="#drivers" role="tab" aria-controls="drivers" aria-selected="false">
                                <i class="fas fa-users mr-1"></i>Sürücüler
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="companyTabContent">
                        <div class="tab-pane fade show active" id="vehicles" role="tabpanel" aria-labelledby="vehicles-tab">
                            <?php if(empty($data['company_vehicles'])) : ?>
                                <div class="alert alert-light border text-center">
                                    <i class="fas fa-info-circle"></i> Bu şirkete ait araç kaydı bulunmamaktadır.
                                </div>
                                <div class="text-center mt-3">
                                    <a href="<?php echo URLROOT; ?>/vehicles/add" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Yeni Araç Ekle
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mini-table" id="companyVehiclesTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Plaka</th>
                                                <th>Marka/Model</th>
                                                <th>Araç Tipi</th>
                                                <th>Durum</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['company_vehicles'] as $vehicle) : ?>
                                                <tr>
                                                    <td><?php echo $vehicle->plate_number; ?></td>
                                                    <td><?php echo $vehicle->brand.' '.$vehicle->model; ?></td>
                                                    <td><?php echo $vehicle->vehicle_type; ?></td>
                                                    <td>
                                                        <?php if($vehicle->status == 'Aktif') : ?>
                                                            <span class="badge badge-success">Aktif</span>
                                                        <?php elseif($vehicle->status == 'Pasif') : ?>
                                                            <span class="badge badge-secondary">Pasif</span>
                                                        <?php elseif($vehicle->status == 'Bakımda') : ?>
                                                            <span class="badge badge-warning">Bakımda</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->id; ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $vehicle->id; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="drivers" role="tabpanel" aria-labelledby="drivers-tab">
                            <?php if(empty($data['company_drivers'])) : ?>
                                <div class="alert alert-light border text-center">
                                    <i class="fas fa-info-circle"></i> Bu şirkete ait sürücü kaydı bulunmamaktadır.
                                </div>
                                <div class="text-center mt-3">
                                    <a href="<?php echo URLROOT; ?>/drivers/add" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Yeni Sürücü Ekle
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mini-table" id="companyDriversTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>TC Kimlik</th>
                                                <th>Ad Soyad</th>
                                                <th>Telefon</th>
                                                <th>Ehliyet Sınıfı</th>
                                                <th>Durum</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['company_drivers'] as $driver) : ?>
                                                <tr>
                                                    <td><?php echo $driver->identity_number; ?></td>
                                                    <td><?php echo $driver->name.' '.$driver->surname; ?></td>
                                                    <td><?php echo $driver->phone; ?></td>
                                                    <td><span class="badge badge-primary"><?php echo $driver->primary_license_type; ?></span></td>
                                                    <td>
                                                        <?php if($driver->status == 'Aktif') : ?>
                                                            <span class="badge badge-success">Aktif</span>
                                                        <?php elseif($driver->status == 'Pasif') : ?>
                                                            <span class="badge badge-secondary">Pasif</span>
                                                        <?php elseif($driver->status == 'İzinli') : ?>
                                                            <span class="badge badge-info">İzinli</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?php echo URLROOT; ?>/drivers/edit/<?php echo $driver->id; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
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
    </div>
</div>

<!-- Silme Onay Modal -->
<div class="modal fade" id="silmeOnayModal" tabindex="-1" aria-labelledby="silmeOnayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="silmeOnayModalLabel">Silme Onayı</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="silmeOnayMesaj">Bu şirketi silmek istediğinize emin misiniz?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Uyarı:</strong> Bu işlem geri alınamaz ve şirkete bağlı tüm verileri etkileyebilir.
                </div>
            </div>
            <div class="modal-footer">
                <form id="silmeForm" action="" method="post">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i>Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Silme onayı modal
function silmeOnayi(id, companyName) {
    document.getElementById('silmeOnayMesaj').innerText = '"' + companyName + '" şirketini silmek istediğinize emin misiniz?';
    document.getElementById('silmeForm').action = '<?php echo URLROOT; ?>/companies/delete/' + id;
    
    // Bootstrap 5 için modal'ı göster
    const silmeModal = new bootstrap.Modal(document.getElementById('silmeOnayModal'));
    silmeModal.show();
}

// Tab kontrollerini manuel olarak ayarlayalım
document.addEventListener('DOMContentLoaded', function() {
    // URL'deki hash'i kontrol et
    const hash = window.location.hash;
    if (hash === '#drivers') {
        // Drivers tab'ını aktif hale getir
        const driversTab = document.getElementById('drivers-tab');
        const driversPane = document.getElementById('drivers');
        const vehiclesTab = document.getElementById('vehicles-tab');
        const vehiclesPane = document.getElementById('vehicles');
        
        // Aktif tab'ı değiştir
        driversTab.classList.add('active');
        driversPane.classList.add('show', 'active');
        vehiclesTab.classList.remove('active');
        vehiclesPane.classList.remove('show', 'active');
    }
    
    // Tab geçişlerini ayarla
    const tabs = document.querySelectorAll('#companyTabs .nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Tüm tabları pasif yap
            tabs.forEach(t => {
                t.classList.remove('active');
                const target = document.querySelector(t.getAttribute('href'));
                target.classList.remove('show', 'active');
            });
            
            // Tıklanan tabı aktif yap
            this.classList.add('active');
            const targetPane = document.querySelector(this.getAttribute('href'));
            targetPane.classList.add('show', 'active');
            
            // URL'yi güncelle
            window.location.hash = this.getAttribute('href');
        });
    });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 