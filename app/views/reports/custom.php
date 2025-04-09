<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-cogs"></i> Özel Rapor Oluşturma</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="#" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-print"></i> Yazdır
                </a>
                <a href="<?php echo URLROOT; ?>/reports" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Geri
                </a>
            </div>
        </div>
    </div>
    
    <!-- Rapor Oluşturma Formu -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-alt"></i> Özel Rapor Kriterleri
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo URLROOT; ?>/reports/custom" class="row">
                <div class="col-md-12 mb-3">
                    <label for="report_name">Rapor Adı</label>
                    <input type="text" class="form-control" id="report_name" name="report_name" 
                        value="<?php echo isset($data['form']['report_name']) ? $data['form']['report_name'] : ''; ?>" 
                        placeholder="Özel Rapor" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="report_type">Rapor Türü</label>
                    <select class="form-control" id="report_type" name="report_type" required>
                        <option value="">Seçiniz</option>
                        <option value="vehicles" <?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'vehicles' ? 'selected' : ''; ?>>Araç Raporu</option>
                        <option value="drivers" <?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'drivers' ? 'selected' : ''; ?>>Sürücü Raporu</option>
                        <option value="fuel" <?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'fuel' ? 'selected' : ''; ?>>Yakıt Raporu</option>
                        <option value="maintenance" <?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'maintenance' ? 'selected' : ''; ?>>Bakım Raporu</option>
                        <option value="assignments" <?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'assignments' ? 'selected' : ''; ?>>Görevlendirme Raporu</option>
                        <option value="costs" <?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'costs' ? 'selected' : ''; ?>>Maliyet Raporu</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="date_range">Tarih Aralığı</label>
                    <select class="form-control" id="date_range" name="date_range" required>
                        <option value="">Seçiniz</option>
                        <option value="today" <?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'today' ? 'selected' : ''; ?>>Bugün</option>
                        <option value="this_week" <?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'this_week' ? 'selected' : ''; ?>>Bu Hafta</option>
                        <option value="this_month" <?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'this_month' ? 'selected' : ''; ?>>Bu Ay</option>
                        <option value="last_month" <?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'last_month' ? 'selected' : ''; ?>>Geçen Ay</option>
                        <option value="this_year" <?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'this_year' ? 'selected' : ''; ?>>Bu Yıl</option>
                        <option value="last_year" <?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'last_year' ? 'selected' : ''; ?>>Geçen Yıl</option>
                        <option value="custom" <?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'custom' ? 'selected' : ''; ?>>Özel Tarih Aralığı</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3 custom-date-range" style="<?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'custom' ? '' : 'display: none;'; ?>">
                    <label for="start_date">Başlangıç Tarihi</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="<?php echo isset($data['form']['start_date']) ? $data['form']['start_date'] : ''; ?>">
                </div>
                
                <div class="col-md-6 mb-3 custom-date-range" style="<?php echo isset($data['form']['date_range']) && $data['form']['date_range'] == 'custom' ? '' : 'display: none;'; ?>">
                    <label for="end_date">Bitiş Tarihi</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="<?php echo isset($data['form']['end_date']) ? $data['form']['end_date'] : ''; ?>">
                </div>
                
                <!-- Dinamik Filtreler - rapor türüne göre değişir -->
                <div class="vehicle-filters filters-section" style="<?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'vehicles' ? '' : 'display: none;'; ?>">
                    <div class="col-md-6 mb-3">
                        <label for="vehicle_status">Araç Durumu</label>
                        <select class="form-control" id="vehicle_status" name="vehicle_status">
                            <option value="">Tümü</option>
                            <option value="Aktif" <?php echo isset($data['form']['vehicle_status']) && $data['form']['vehicle_status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Pasif" <?php echo isset($data['form']['vehicle_status']) && $data['form']['vehicle_status'] == 'Pasif' ? 'selected' : ''; ?>>Pasif</option>
                            <option value="Bakımda" <?php echo isset($data['form']['vehicle_status']) && $data['form']['vehicle_status'] == 'Bakımda' ? 'selected' : ''; ?>>Bakımda</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="vehicle_type">Araç Tipi</label>
                        <select class="form-control" id="vehicle_type" name="vehicle_type">
                            <option value="">Tümü</option>
                            <option value="Otomobil" <?php echo isset($data['form']['vehicle_type']) && $data['form']['vehicle_type'] == 'Otomobil' ? 'selected' : ''; ?>>Otomobil</option>
                            <option value="Kamyonet" <?php echo isset($data['form']['vehicle_type']) && $data['form']['vehicle_type'] == 'Kamyonet' ? 'selected' : ''; ?>>Kamyonet</option>
                            <option value="Kamyon" <?php echo isset($data['form']['vehicle_type']) && $data['form']['vehicle_type'] == 'Kamyon' ? 'selected' : ''; ?>>Kamyon</option>
                            <option value="Otobüs" <?php echo isset($data['form']['vehicle_type']) && $data['form']['vehicle_type'] == 'Otobüs' ? 'selected' : ''; ?>>Otobüs</option>
                        </select>
                    </div>
                </div>
                
                <div class="driver-filters filters-section" style="<?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'drivers' ? '' : 'display: none;'; ?>">
                    <div class="col-md-6 mb-3">
                        <label for="driver_status">Sürücü Durumu</label>
                        <select class="form-control" id="driver_status" name="driver_status">
                            <option value="">Tümü</option>
                            <option value="Aktif" <?php echo isset($data['form']['driver_status']) && $data['form']['driver_status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                            <option value="İzinli" <?php echo isset($data['form']['driver_status']) && $data['form']['driver_status'] == 'İzinli' ? 'selected' : ''; ?>>İzinli</option>
                            <option value="Pasif" <?php echo isset($data['form']['driver_status']) && $data['form']['driver_status'] == 'Pasif' ? 'selected' : ''; ?>>Pasif</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="license_type">Ehliyet Türü</label>
                        <select class="form-control" id="license_type" name="license_type">
                            <option value="">Tümü</option>
                            <option value="B" <?php echo isset($data['form']['license_type']) && $data['form']['license_type'] == 'B' ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?php echo isset($data['form']['license_type']) && $data['form']['license_type'] == 'C' ? 'selected' : ''; ?>>C</option>
                            <option value="D" <?php echo isset($data['form']['license_type']) && $data['form']['license_type'] == 'D' ? 'selected' : ''; ?>>D</option>
                            <option value="E" <?php echo isset($data['form']['license_type']) && $data['form']['license_type'] == 'E' ? 'selected' : ''; ?>>E</option>
                        </select>
                    </div>
                </div>
                
                <div class="fuel-filters filters-section" style="<?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'fuel' ? '' : 'display: none;'; ?>">
                    <div class="col-md-12 mb-3">
                        <label for="fuel_type">Yakıt Türü</label>
                        <select class="form-control" id="fuel_type" name="fuel_type">
                            <option value="">Tümü</option>
                            <option value="Benzin" <?php echo isset($data['form']['fuel_type']) && $data['form']['fuel_type'] == 'Benzin' ? 'selected' : ''; ?>>Benzin</option>
                            <option value="Dizel" <?php echo isset($data['form']['fuel_type']) && $data['form']['fuel_type'] == 'Dizel' ? 'selected' : ''; ?>>Dizel</option>
                            <option value="LPG" <?php echo isset($data['form']['fuel_type']) && $data['form']['fuel_type'] == 'LPG' ? 'selected' : ''; ?>>LPG</option>
                            <option value="Elektrik" <?php echo isset($data['form']['fuel_type']) && $data['form']['fuel_type'] == 'Elektrik' ? 'selected' : ''; ?>>Elektrik</option>
                        </select>
                    </div>
                </div>
                
                <div class="maintenance-filters filters-section" style="<?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'maintenance' ? '' : 'display: none;'; ?>">
                    <div class="col-md-6 mb-3">
                        <label for="maintenance_type">Bakım Türü</label>
                        <select class="form-control" id="maintenance_type" name="maintenance_type">
                            <option value="">Tümü</option>
                            <option value="Periyodik Bakım" <?php echo isset($data['form']['maintenance_type']) && $data['form']['maintenance_type'] == 'Periyodik Bakım' ? 'selected' : ''; ?>>Periyodik Bakım</option>
                            <option value="Arıza" <?php echo isset($data['form']['maintenance_type']) && $data['form']['maintenance_type'] == 'Arıza' ? 'selected' : ''; ?>>Arıza</option>
                            <option value="Kaza Onarım" <?php echo isset($data['form']['maintenance_type']) && $data['form']['maintenance_type'] == 'Kaza Onarım' ? 'selected' : ''; ?>>Kaza Onarım</option>
                            <option value="Motor" <?php echo isset($data['form']['maintenance_type']) && $data['form']['maintenance_type'] == 'Motor' ? 'selected' : ''; ?>>Motor</option>
                            <option value="Şanzıman" <?php echo isset($data['form']['maintenance_type']) && $data['form']['maintenance_type'] == 'Şanzıman' ? 'selected' : ''; ?>>Şanzıman</option>
                            <option value="Fren Sistemi" <?php echo isset($data['form']['maintenance_type']) && $data['form']['maintenance_type'] == 'Fren Sistemi' ? 'selected' : ''; ?>>Fren Sistemi</option>
                            <option value="Lastik Değişimi" <?php echo isset($data['form']['maintenance_type']) && $data['form']['maintenance_type'] == 'Lastik Değişimi' ? 'selected' : ''; ?>>Lastik Değişimi</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="maintenance_status">Bakım Durumu</label>
                        <select class="form-control" id="maintenance_status" name="maintenance_status">
                            <option value="">Tümü</option>
                            <option value="Tamamlandı" <?php echo isset($data['form']['maintenance_status']) && $data['form']['maintenance_status'] == 'Tamamlandı' ? 'selected' : ''; ?>>Tamamlandı</option>
                            <option value="Devam Ediyor" <?php echo isset($data['form']['maintenance_status']) && $data['form']['maintenance_status'] == 'Devam Ediyor' ? 'selected' : ''; ?>>Devam Ediyor</option>
                            <option value="Planlandı" <?php echo isset($data['form']['maintenance_status']) && $data['form']['maintenance_status'] == 'Planlandı' ? 'selected' : ''; ?>>Planlandı</option>
                        </select>
                    </div>
                </div>
                
                <div class="assignment-filters filters-section" style="<?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'assignments' ? '' : 'display: none;'; ?>">
                    <div class="col-md-12 mb-3">
                        <label for="assignment_status">Görevlendirme Durumu</label>
                        <select class="form-control" id="assignment_status" name="assignment_status">
                            <option value="">Tümü</option>
                            <option value="Devam Ediyor" <?php echo isset($data['form']['assignment_status']) && $data['form']['assignment_status'] == 'Devam Ediyor' ? 'selected' : ''; ?>>Devam Ediyor</option>
                            <option value="Tamamlandı" <?php echo isset($data['form']['assignment_status']) && $data['form']['assignment_status'] == 'Tamamlandı' ? 'selected' : ''; ?>>Tamamlandı</option>
                            <option value="İptal Edildi" <?php echo isset($data['form']['assignment_status']) && $data['form']['assignment_status'] == 'İptal Edildi' ? 'selected' : ''; ?>>İptal Edildi</option>
                        </select>
                    </div>
                </div>
                
                <div class="cost-filters filters-section" style="<?php echo isset($data['form']['report_type']) && $data['form']['report_type'] == 'costs' ? '' : 'display: none;'; ?>">
                    <div class="col-md-12 mb-3">
                        <label for="cost_type">Maliyet Türü</label>
                        <select class="form-control" id="cost_type" name="cost_type">
                            <option value="">Tümü</option>
                            <option value="fuel" <?php echo isset($data['form']['cost_type']) && $data['form']['cost_type'] == 'fuel' ? 'selected' : ''; ?>>Yakıt</option>
                            <option value="maintenance" <?php echo isset($data['form']['cost_type']) && $data['form']['cost_type'] == 'maintenance' ? 'selected' : ''; ?>>Bakım</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="report_format">Rapor Formatı</label>
                    <select class="form-control" id="report_format" name="report_format" required>
                        <option value="detailed" <?php echo isset($data['form']['report_format']) && $data['form']['report_format'] == 'detailed' ? 'selected' : ''; ?>>Detaylı</option>
                        <option value="summary" <?php echo isset($data['form']['report_format']) && $data['form']['report_format'] == 'summary' ? 'selected' : ''; ?>>Özet</option>
                    </select>
                </div>
                
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-primary">Raporu Oluştur</button>
                    <button type="reset" class="btn btn-secondary">Temizle</button>
                </div>
            </form>
        </div>
    </div>
    
    <?php if(isset($data['report_generated']) && $data['report_generated']) : ?>
    <!-- Oluşturulan Rapor Sonuçları -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-alt"></i> 
            <?php echo $data['report_title']; ?>
            <span class="float-end">
                <small class="text-muted">Oluşturulma: <?php echo date('d.m.Y H:i'); ?></small>
            </span>
        </div>
        <div class="card-body">
            <?php if(isset($data['report_summary'])) : ?>
            <div class="alert alert-info">
                <h4 class="alert-heading">Rapor Özeti</h4>
                <p class="mb-0"><?php echo $data['report_summary']; ?></p>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($data['report_data'])) : ?>
                <?php if($data['form']['report_format'] == 'summary') : ?>
                    <!-- Özet Rapor Görünümü -->
                    <div class="row mb-4">
                        <?php foreach($data['summary_stats'] as $stat) : ?>
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    <?php echo $stat['title']; ?></div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stat['value']; ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="<?php echo $stat['icon']; ?> fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Rapor Verisi Tablosu -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <?php foreach($data['table_headers'] as $header) : ?>
                                    <th><?php echo $header; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['report_data'] as $row) : ?>
                                <tr>
                                    <?php foreach($row as $key => $value) : ?>
                                        <td><?php echo $value; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="alert alert-warning">
                    <p class="mb-0">Belirlenen kriterlere uygun veri bulunamadı.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DataTables'ı başlat (rapor sonuçları varsa)
    if (document.getElementById('dataTable')) {
        const customReportTable = initDataTable('dataTable', {
            "pageLength": 25,
            "dom": '<"top"f>rt<"bottom"lip>',
            "stateSave": true
        });
    }
    
    // Tarih aralığı seçimine göre özel tarih alanlarını göster/gizle
    document.getElementById('date_range').addEventListener('change', function() {
        const customDateFields = document.querySelectorAll('.custom-date-range');
        
        if (this.value === 'custom') {
            customDateFields.forEach(field => field.style.display = 'block');
        } else {
            customDateFields.forEach(field => field.style.display = 'none');
        }
    });
    
    // Rapor türüne göre filtreleri göster/gizle
    document.getElementById('report_type').addEventListener('change', function() {
        const allFilterSections = document.querySelectorAll('.filters-section');
        allFilterSections.forEach(section => section.style.display = 'none');
        
        const reportType = this.value;
        if (reportType) {
            const activeFilterSection = document.querySelector('.' + reportType + '-filters');
            if (activeFilterSection) {
                activeFilterSection.style.display = 'block';
            }
        }
    });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 