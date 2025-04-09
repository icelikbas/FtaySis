<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-id-badge"></i> Ehliyet Sınıfları</h1>
    </div>
    <div class="col-md-6">
        <?php if(isAdmin()) : ?>
        <a href="<?php echo URLROOT; ?>/licensetypes/add" class="btn btn-primary float-right">
            <i class="fas fa-plus"></i> Yeni Ehliyet Sınıfı Ekle
        </a>
        <?php endif; ?>
    </div>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="row">
    <!-- Veritabanı Listesi -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
             <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-database me-2"></i> Kayıtlı Ehliyet Sınıfları</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover data-table-buttons" id="licenseTypesTable">
                        <thead class="table-light">
                    <tr>
                        <th width="100">Kod</th>
                        <th>Ehliyet Sınıfı</th>
                        <th>Açıklama</th>
                        <th>Sürücü Sayısı</th>
                        <th width="150">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['licenseTypes'] as $licenseType) : ?>
                        <tr>
                            <td><span class="badge bg-info text-white"><?php echo $licenseType->code; ?></span></td>
                            <td><?php echo $licenseType->name; ?></td>
                            <td><?php echo $licenseType->description; ?></td>
                            <td>
                                <?php
                                    $driverCount = $licenseType->driver_count;
                                    if ($driverCount > 0) {
                                        echo '<a href="'.URLROOT.'/licensetypes/drivers/'.$licenseType->id.'" class="badge bg-primary text-white">'.$driverCount.' sürücü</a>';
                                    } else {
                                        echo '<span class="badge bg-secondary text-white">0 sürücü</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php if(isAdmin()) : ?>
                                <a href="<?php echo URLROOT; ?>/licensetypes/edit/<?php echo $licenseType->id; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form class="d-inline" action="<?php echo URLROOT; ?>/licensetypes/delete/<?php echo $licenseType->id; ?>" method="post">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu ehliyet sınıfını silmek istediğinize emin misiniz?');">
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
    </div>

    <!-- Statik Bilgi Alanı (Akordeon) -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Türkiye Ehliyet Sınıfları (Genel Bilgi)</h5>
            </div>
            <div class="card-body p-0">
                <div class="accordion accordion-flush" id="licenseInfoAccordion">
                    <!-- Motosiklet -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingMotorcycle">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMotorcycle" aria-expanded="false" aria-controls="collapseMotorcycle">
                                <i class="fas fa-motorcycle me-2"></i> Motosiklet Ehliyet Sınıfları
                            </button>
                        </h2>
                        <div id="collapseMotorcycle" class="accordion-collapse collapse" aria-labelledby="headingMotorcycle" data-bs-parent="#licenseInfoAccordion">
                            <div class="accordion-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">M</span>
                                        <strong>M Sınıfı:</strong> Motorlu bisiklet (Moped).
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">A1</span>
                                        <strong>A1 Sınıfı:</strong> 125 cc'ye kadar, 11 kW'ı geçmeyen.
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">A2</span>
                                        <strong>A2 Sınıfı:</strong> 35 kW'ı geçmeyen, güç/ağırlık oranı 0,2'yi geçmeyen.
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">A</span>
                                        <strong>A Sınıfı:</strong> 35 kW'ı veya güç/ağırlık oranı 0,2'yi geçen.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Otomobil / Kamyonet -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingCar">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCar" aria-expanded="false" aria-controls="collapseCar">
                                <i class="fas fa-car me-2"></i> Otomobil & Kamyonet Sınıfları
                            </button>
                        </h2>
                        <div id="collapseCar" class="accordion-collapse collapse" aria-labelledby="headingCar" data-bs-parent="#licenseInfoAccordion">
                            <div class="accordion-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">B1</span>
                                        <strong>B1 Sınıfı:</strong> 4 tekerlekli motosiklet (Quad).
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">B</span>
                                        <strong>B Sınıfı:</strong> Otomobil ve kamyonet (3500 kg'a kadar).
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">BE</span>
                                        <strong>BE Sınıfı:</strong> B sınıfı + römork.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Kamyon / Çekici -->
                     <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTruck">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTruck" aria-expanded="false" aria-controls="collapseTruck">
                                <i class="fas fa-truck me-2"></i> Kamyon & Çekici Sınıfları
                            </button>
                        </h2>
                        <div id="collapseTruck" class="accordion-collapse collapse" aria-labelledby="headingTruck" data-bs-parent="#licenseInfoAccordion">
                            <div class="accordion-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">C1</span>
                                        <strong>C1 Sınıfı:</strong> 3.500 - 7.500 kg arası kamyon/çekici.
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">C1E</span>
                                        <strong>C1E Sınıfı:</strong> C1 sınıfı + römork (>750 kg).
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">C</span>
                                        <strong>C Sınıfı:</strong> Kamyon ve Çekici (Tır).
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">CE</span>
                                        <strong>CE Sınıfı:</strong> C sınıfı + römork.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Otobüs / Minibüs -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingBus">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBus" aria-expanded="false" aria-controls="collapseBus">
                                <i class="fas fa-bus me-2"></i> Otobüs & Minibüs Sınıfları
                            </button>
                        </h2>
                        <div id="collapseBus" class="accordion-collapse collapse" aria-labelledby="headingBus" data-bs-parent="#licenseInfoAccordion">
                            <div class="accordion-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">D1</span>
                                        <strong>D1 Sınıfı:</strong> Minibüs.
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">D1E</span>
                                        <strong>D1E Sınıfı:</strong> D1 sınıfı + römork (>750 kg).
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">D</span>
                                        <strong>D Sınıfı:</strong> Otobüs.
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">DE</span>
                                        <strong>DE Sınıfı:</strong> D sınıfı + römork.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Diğer -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOther">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOther" aria-expanded="false" aria-controls="collapseOther">
                                <i class="fas fa-tractor me-2"></i> Diğer Sınıflar
                            </button>
                        </h2>
                        <div id="collapseOther" class="accordion-collapse collapse" aria-labelledby="headingOther" data-bs-parent="#licenseInfoAccordion">
                            <div class="accordion-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">F</span>
                                        <strong>F Sınıfı:</strong> Traktör.
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-info text-white me-2">G</span>
                                        <strong>G Sınıfı:</strong> İş makinası.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> <!-- /accordion -->
            </div> <!-- /card-body -->
        </div> <!-- /card -->
    </div> <!-- /col-12 -->
</div> <!-- /row -->

<script>
    // DataTable başlatma fonksiyonu (varsa main.js'den çağrılır, yoksa burada tanımlanır)
    // Eğer main.js içinde genel bir initDataTable fonksiyonu yoksa, aşağıdaki gibi bir yapı kullanılabilir:
    /*
    function initDataTable(tableId, options = {}) {
        const defaultOptions = {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]]
        };
        const finalOptions = $.extend(true, {}, defaultOptions, options); // Deep merge
        return $('#' + tableId).DataTable(finalOptions);
    }
    */

    document.addEventListener('DOMContentLoaded', function() {
        // DataTable'ı başlat (main.js'deki genel fonksiyona güveniyoruz veya yukarıdaki gibi tanımlıyoruz)
        // Eğer main.js'de 'data-table-buttons' sınıfı için özel bir başlatma yoksa, buradaki scripti kullanabiliriz.
        // Ancak footer.php'de zaten 'data-table-buttons' için bir başlatıcı vardı, bu yüzden bu script bloğu gereksiz olabilir.
        // Şimdilik yorumda bırakalım, eğer tablo çalışmazsa aktif edilebilir.
        /*
        const licenseTypesTable = initDataTable('licenseTypesTable', {
            "order": [[ 0, "asc" ]], // Koda göre sırala
            "columnDefs": [
                { "orderable": false, "targets": 4 } // İşlemler sütunu sıralanamaz
            ],
            buttons: [ // Butonları ekleyelim
                 {extend: 'copy', text: '<i class="fas fa-copy"></i> Kopyala', className: 'btn btn-secondary btn-sm'},
                 {extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-success btn-sm'},
                 {extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-danger btn-sm'},
                 {extend: 'print', text: '<i class="fas fa-print"></i> Yazdır', className: 'btn btn-info btn-sm'},
                 {extend: 'colvis', text: '<i class="fas fa-columns"></i> Sütunlar', className: 'btn btn-warning btn-sm'}
            ],
            // Butonları göstermek için dom yapısını güncelle
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                 "<'row'<'col-sm-12'B>>", // B harfi butonları temsil eder
        });
        */
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
