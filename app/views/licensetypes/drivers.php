<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-id-badge"></i> <?php echo $data['licenseType']->name; ?> Ehliyet Sahibi Sürücüler</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/licensetypes" class="btn btn-light float-right">
            <i class="fa fa-backward"></i> Ehliyet Sınıflarına Dön
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-info text-white"><?php echo $data['licenseType']->code; ?></span>
                <span class="ml-2"><?php echo $data['licenseType']->name; ?></span>
            </div>
            <div>
                <span class="badge bg-primary"><?php echo count($data['drivers']); ?> Sürücü</span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <p class="card-text"><?php echo $data['licenseType']->description; ?></p>
    </div>
</div>

<?php if(empty($data['drivers'])) : ?>
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i> Bu ehliyet sınıfına sahip sürücü bulunmamaktadır.
    </div>
<?php else : ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="driversTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Ad Soyad</th>
                            <th>Ehliyet No</th>
                            <th>Verilme Tarihi</th>
                            <th>Geçerlilik Tarihi</th>
                            <th>Durumu</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['drivers'] as $driver) : ?>
                            <tr>
                                <td><?php echo $driver->name . ' ' . $driver->surname; ?></td>
                                <td><?php echo $driver->license_number; ?></td>
                                <td>
                                    <?php 
                                        // Controller'dan gelen ehliyet bilgilerini kullan
                                        $licenseInfo = $data['driverLicenses'][$driver->id];
                                        echo ($licenseInfo && $licenseInfo->issue_date) ? date('d/m/Y', strtotime($licenseInfo->issue_date)) : 'Belirtilmemiş';
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        echo ($licenseInfo && $licenseInfo->expiry_date) ? date('d/m/Y', strtotime($licenseInfo->expiry_date)) : 'Belirtilmemiş';
                                    ?>
                                </td>
                                <td>
                                    <?php if($licenseInfo && $licenseInfo->expiry_date) : ?>
                                        <?php if(strtotime($licenseInfo->expiry_date) < time()) : ?>
                                            <span class="badge bg-danger">Süresi Dolmuş</span>
                                        <?php elseif(strtotime($licenseInfo->expiry_date) < strtotime('+3 months')) : ?>
                                            <span class="badge bg-warning">Yakında Dolacak</span>
                                        <?php else : ?>
                                            <span class="badge bg-success">Geçerli</span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">Belirtilmemiş</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detay
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/licenses/index/<?php echo $driver->id; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-id-card"></i> Ehliyetler
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const driversTable = initDataTable('driversTable', {
            "order": [[ 0, "asc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 5 }
            ]
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 