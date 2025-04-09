                    </div>
                </div>
            </main>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; <?php echo date('Y'); ?> <?php echo SITENAME; ?> | Tüm Hakları Saklıdır</span>
        </div>
    </footer>

    <!-- Bootstrap JS - CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS - Uyumlu CDN Sürümleri -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Ana JS Dosyası - En son yüklenmeli -->
    <script src="<?php echo getPublicUrl('js/main.js'); ?>"></script>
    
    <script>
        // Bootstrap 5 dropdown menüler için
        document.addEventListener('DOMContentLoaded', function() {
            // Bootstrap 5 için dropdown'ları manuel başlat
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            // DataTable'ları başlat (standart tablolar ve filtrelenebilir tablolar için)
            if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                // Filtrelenebilir tablolar için ayarlar
                $('table.data-table').each(function() {
                    if (!$(this).hasClass('dataTable')) {
                        var tableId = $(this).attr('id');
                        $(this).DataTable({
                            responsive: true,
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                            },
                            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                                 "<'row'<'col-sm-12'tr>>" +
                                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
                            buttons: [
                                'copy', 'excel', 'pdf', 'print', 'colvis'
                            ]
                        });
                        
                        console.log('DataTable initialized for #' + tableId);
                    }
                });

                // Filtrelenebilir ve dışa aktarma butonlu tablolar
                $('table.data-table-buttons').each(function() {
                    if (!$(this).hasClass('dataTable')) {
                        var tableId = $(this).attr('id');
                        $(this).DataTable({
                            responsive: true,
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                            },
                            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                                 "<'row'<'col-sm-12'tr>>" +
                                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                                 "<'row'<'col-sm-12'B>>",
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
                            buttons: [
                                {extend: 'copy', text: 'Kopyala'},
                                {extend: 'excel', text: 'Excel'},
                                {extend: 'pdf', text: 'PDF'},
                                {extend: 'print', text: 'Yazdır'},
                                {extend: 'colvis', text: 'Sütunlar'}
                            ]
                        });
                        
                        console.log('DataTable with buttons initialized for #' + tableId);
                    }
                });
            }
            
            // Select2'leri başlat
            if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                $('select.select2').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            language: 'tr'
                        });
                    }
                });
            }
            
            // Flash mesajları için
            const flashMessages = document.querySelectorAll('.alert-dismissible');
            if (flashMessages.length > 0) {
                flashMessages.forEach(function(flash) {
                    setTimeout(function() {
                        $(flash).fadeOut('slow');
                    }, 4000);
                });
            }
        });
    </script>

    <?php
    // Sayfaya özel ekstra JS dosyaları veya kod bloğu
    if(isset($data['extraJS'])) {
        echo $data['extraJS'];
    }
    ?>
</body>
</html> 