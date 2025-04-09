// Ana JS Dosyası
document.addEventListener('DOMContentLoaded', function() {
    /**
     * DataTable'ı başlatmak için global yardımcı fonksiyon
     * @param {string} tableId - Başlatılacak tablo ID'si
     * @param {object} customOptions - Özel DataTable ayarları
     * @returns {object} DataTable örneği
     */
    window.initDataTable = function(tableId, customOptions = {}) {
        try {
            // jQuery ve DataTables kontrolü
            if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                console.error('jQuery veya DataTables yüklenmemiş!');
                return null;
            }

            const table = document.getElementById(tableId);
            if (!table) {
                console.error(`'${tableId}' ID'li tablo bulunamadı.`);
                return null;
            }

            // Eğer zaten bir DataTable örneği varsa, onu yok et
            if ($.fn.dataTable.isDataTable('#' + tableId)) {
                $('#' + tableId).DataTable().destroy();
            }

            // Varsayılan ayarlar
            const defaultOptions = {
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                },
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
                pageLength: 25,
                stateSave: true,
                autoWidth: false
            };

            // Tüm seçenekleri birleştir
            const options = {...defaultOptions, ...customOptions};

            // DataTable'ı başlat
            console.log(`'${tableId}' tablosu başlatılıyor...`);
            const dataTable = $('#' + tableId).DataTable(options);
            
            // Pencere boyutu değiştiğinde sütunları ayarla
            $(window).resize(function() {
                dataTable.columns.adjust();
            });
            
            return dataTable;
        } catch (err) {
            console.error(`'${tableId}' tablosu başlatılırken hata oluştu:`, err);
            return null;
        }
    };

    /**
     * DataTables yardımcı fonksiyonları
     */
    const DataTableHelpers = {
        // Türkçe dil desteği
        turkishLanguage: {
            "sDecimal": ",",
            "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
            "sInfo": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
            "sInfoEmpty": "Kayıt yok",
            "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "Sayfada _MENU_ kayıt göster",
            "sLoadingRecords": "Yükleniyor...",
            "sProcessing": "İşleniyor...",
            "sSearch": "Ara:",
            "sZeroRecords": "Eşleşen kayıt bulunamadı",
            "oPaginate": {
                "sFirst": "İlk",
                "sLast": "Son",
                "sNext": "Sonraki",
                "sPrevious": "Önceki"
            },
            "oAria": {
                "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
            },
            "select": {
                "rows": {
                    "_": "%d kayıt seçildi",
                    "0": "",
                    "1": "1 kayıt seçildi"
                }
            }
        },

        // Varsayılan DataTable ayarları
        defaultOptions: function() {
            return {
                "language": this.turkishLanguage,
                "responsive": {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: 'none',
                        renderer: function (api, rowIdx, columns) {
                            const data = $.map(columns, function (col, i) {
                                return col.hidden ?
                                    '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">' +
                                    '<td class="fw-bold">'+col.title+':</td> ' +
                                    '<td>'+col.data+'</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');
                            
                            return data ?
                                $('<table class="table table-sm w-100"/>').append(data) :
                                false;
                        }
                    }
                },
                "order": [
                    [0, "desc"]
                ],
                "pageLength": 25,
                "autoWidth": false,
                "stateSave": true,
                "processing": true
            };
        },

        // Mini tablolar için basitleştirilmiş ayarlar
        miniTableOptions: function() {
            return {
                "language": this.turkishLanguage,
                "responsive": {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: 'none'
                    }
                },
                "paging": false,
                "searching": false,
                "info": false,
                "ordering": true,
                "autoWidth": false,
                "dom": 't'
            };
        },

        // Tablo doğrulama ve onarma
        validateTable: function(table) {
            if (!table) return false;

            // Tablo yapısını kontrol et
            const thead = table.querySelector('thead');
            const tbody = table.querySelector('tbody');

            if (!thead || !tbody) return false;

            // Başlık satırını kontrol et
            const headerRow = thead.querySelector('tr');
            if (!headerRow) return false;

            // Sütun sayısını al
            const columnCount = headerRow.querySelectorAll('th').length;

            // Tüm satırların doğru sütun sayısına sahip olup olmadığını kontrol et
            const rows = tbody.querySelectorAll('tr');
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll('td');
                if (cells.length > 0 && cells.length !== columnCount) {
                    // Eksik hücreleri ekle
                    if (cells.length < columnCount) {
                        const diff = columnCount - cells.length;
                        for (let j = 0; j < diff; j++) {
                            const td = document.createElement('td');
                            td.textContent = '-';
                            rows[i].appendChild(td);
                        }
                    }
                }
            }

            // Boş hücreleri düzelt
            const allCells = tbody.querySelectorAll('td');
            for (let i = 0; i < allCells.length; i++) {
                if (allCells[i].textContent.trim() === '') {
                    allCells[i].textContent = '-';
                }
            }

            return true;
        },

        // Tabloyu DataTable olarak başlat
        initTable: function(tableId, customOptions = {}) {
            try {
                const table = document.getElementById(tableId);
                if (!this.validateTable(table)) {
                    console.warn(`'${tableId}' tablosu doğrulanamadı.`);
                    return null;
                }

                // Eğer zaten bir DataTable örneği varsa, onu yok et
                if ($.fn.dataTable.isDataTable('#' + tableId)) {
                    $('#' + tableId).DataTable().destroy();
                }

                // Mini tablo mu kontrol et
                const isMiniTable = table.classList.contains('mini-table');

                // Tüm seçenekleri birleştir - mini tablo ise ona göre seçenekler kullan
                const baseOptions = isMiniTable ? this.miniTableOptions() : this.defaultOptions();
                const options = {...baseOptions, ...customOptions };

                // DataTable'ı başlat
                return $('#' + tableId).DataTable(options);
            } catch (err) {
                console.error(`'${tableId}' tablosu başlatılırken hata oluştu:`, err);
                return null;
            }
        },

        // Tüm veri tablolarını otomatik olarak başlat
        autoInitTables: function() {
            // Tabloları başlatmayı dene
            const initTablesAttempt = () => {
                // jQuery ve DataTables kontrol
                if (typeof jQuery === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                    console.warn('jQuery veya DataTables henüz yüklenmedi, 500ms sonra tekrar deneniyor...');
                    setTimeout(initTablesAttempt, 500);
                    return;
                }

                console.info('DataTables başlatılıyor...');

                // data-table sınıfına sahip tüm tabloları başlat
                $('table.data-table').each((index, table) => {
                    if (!table.id) {
                        table.id = 'data-table-' + index;
                    }

                    if (!$.fn.dataTable.isDataTable('#' + table.id)) {
                        this.initTable(table.id);
                    }
                });

                // mini-table sınıfına sahip tüm tabloları başlat
                $('table.mini-table').each((index, table) => {
                    if (!table.id) {
                        table.id = 'mini-table-' + index;
                    }

                    if (!$.fn.dataTable.isDataTable('#' + table.id)) {
                        this.initTable(table.id);
                    }
                });

                // Belirli sınıfa sahip olmayan tabloları da basit tablo haline getir
                $('table:not(.data-table):not(.dataTable):not(.mini-table)').each((index, table) => {
                    $(table).addClass('w-100 table-responsive');
                });

                // Pencere boyutu değiştiğinde tüm DataTable'ları yeniden düzenle
                $(window).resize(function() {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                });
            };

            // İlk denemeyi başlat
            initTablesAttempt();
        }
    };

    /**
     * Insurance modülü için yardımcı fonksiyonlar
     */
    const InsuranceHelpers = {
        // Günlere göre metin ve renk belirle
        getDaysStatus: function(days) {
            let status = {
                text: days + ' gün',
                class: 'bg-success'
            };

            if (days < 0) {
                status.text = Math.abs(days) + ' gün geçmiş';
                status.class = 'bg-danger';
            } else if (days <= 7) {
                status.class = 'bg-danger';
            } else if (days <= 30) {
                status.class = 'bg-warning';
            }

            return status;
        },

        // Araç durumuna göre renk sınıfı belirle
        getVehicleStatusClass: function(status) {
            switch (status) {
                case 'Aktif':
                    return 'bg-success';
                case 'Pasif':
                    return 'bg-secondary';
                case 'Bakımda':
                    return 'bg-warning';
                case 'Arızalı':
                    return 'bg-danger';
                default:
                    return 'bg-info';
            }
        },
        
        // Sigorta DataTables için responsive ayarları
        getInsuranceResponsiveConfig: function() {
            return {
                details: {
                    renderer: function(api, rowIdx, columns) {
                        const data = $.map(columns, function(col, i) {
                            if (col.hidden) {
                                // Varsayılan görünümden daha özelleştirilmiş bir tasarım için
                                let cellContent = col.data;
                                let cellClass = '';
                                
                                // "Kalan Gün" sütunu için özel görünüm
                                if (col.title.includes('Kalan') || col.title.includes('Gün')) {
                                    const days = parseInt(col.data);
                                    if (!isNaN(days)) {
                                        const statusInfo = InsuranceHelpers.getDaysStatus(days);
                                        cellClass = 'badge ' + statusInfo.class;
                                    }
                                }
                                
                                return '<tr>' +
                                    '<td class="fw-bold">' + col.title + ':</td>' +
                                    '<td' + (cellClass ? ' class="' + cellClass + '"' : '') + '>' + 
                                    cellContent + 
                                    '</td>' +
                                    '</tr>';
                            }
                            return '';
                        }).join('');
                        
                        return data ?
                            $('<table class="table table-sm w-100 table-responsive"/>').append(data) :
                            false;
                    }
                }
            };
        },

        // Tarih formatını Türkçe olarak ayarla
        formatDate: function(dateString) {
            if (!dateString) return 'Belirtilmemiş';
            return new Date(dateString).toLocaleDateString('tr-TR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        },

        // İki tarih arasındaki gün farkını hesapla
        calculateDaysDifference: function(date1, date2) {
            const oneDay = 24 * 60 * 60 * 1000; // Bir günün milisaniye cinsinden değeri
            return Math.round(Math.abs((date1 - date2) / oneDay));
        },

        // Yaklaşan tarihleri hesapla
        getUpcomingDates: function(dates, days = 30) {
            const today = new Date();
            const future = new Date();
            future.setDate(future.getDate() + days);
            
            return dates.filter(date => {
                const dateObj = new Date(date);
                return dateObj >= today && dateObj <= future;
            });
        },

        // İstatistikleri hesapla
        calculateStats: function(tableId) {
            let upcomingInspection = 0;
            let upcomingTraffic = 0;
            let upcomingCasco = 0;
            let expiredDoc = 0;
            
            // Tablo verisini kontrol et
            const table = document.getElementById(tableId);
            if (!table) return;
            
            const rows = table.querySelector('tbody').querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll('td');
                if (cells.length > 0) {
                    // Muayene kontrolü
                    const inspectionDays = cells[5].textContent.trim();
                    if (inspectionDays.includes('gün') && !inspectionDays.includes('geçmiş')) {
                        const days = parseInt(inspectionDays);
                        if (!isNaN(days) && days <= 30) {
                            upcomingInspection++;
                        }
                    } else if (inspectionDays.includes('geçmiş')) {
                        expiredDoc++;
                    }
                    
                    // Trafik sigortası kontrolü
                    const trafficDays = cells[7].textContent.trim();
                    if (trafficDays.includes('gün') && !trafficDays.includes('geçmiş')) {
                        const days = parseInt(trafficDays);
                        if (!isNaN(days) && days <= 30) {
                            upcomingTraffic++;
                        }
                    } else if (trafficDays.includes('geçmiş')) {
                        expiredDoc++;
                    }
                    
                    // Kasko sigortası kontrolü
                    const cascoDays = cells[9].textContent.trim();
                    if (cascoDays.includes('gün') && !cascoDays.includes('geçmiş')) {
                        const days = parseInt(cascoDays);
                        if (!isNaN(days) && days <= 30) {
                            upcomingCasco++;
                        }
                    } else if (cascoDays.includes('geçmiş')) {
                        expiredDoc++;
                    }
                }
            }
            
            // İstatistik değerlerini döndür
            return {
                upcomingInspection,
                upcomingTraffic,
                upcomingCasco,
                expiredDoc
            };
        },

        // Süresi geçmiş evrakları tablo verilerinden oluştur
        loadExpiredDocuments: function(tableId) {
            // Tablodaki verileri kullanarak süresi geçmiş olanları listele
            const table = document.getElementById(tableId);
            if (!table) return {};
            
            const rows = table.querySelector('tbody').querySelectorAll('tr');
            
            let expiredInspections = [];
            let expiredTraffic = [];
            let expiredCasco = [];
            
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll('td');
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
            
            return {
                expiredInspections,
                expiredTraffic,
                expiredCasco
            };
        }
    };

    // DataTables hata kontrol mekanizması
    const errorHandler = function(event) {
        // DataTables ile ilgili hataları yakala
        if (event.message && (
                event.message.includes('DataTables') ||
                event.message.includes('_DT_CellIndex') ||
                event.message.includes('undefined column') ||
                event.message.includes('No data available') ||
                event.message.includes('Invalid JSON'))) {

            console.info('DataTables hatası yakalandı:', event.message);
        }
    };

    // Hata yakalama ekle
    window.addEventListener('error', errorHandler);

    // jQuery hazır olduğunda
    if (typeof jQuery !== 'undefined') {
        try {
            // Tooltip aktivasyonu
            if ($.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip();
            }

            // DataTable'ları otomatik başlat
            DataTableHelpers.autoInitTables();

            // Global değişkene erişim
            window.DataTableHelpers = DataTableHelpers;
            window.InsuranceHelpers = InsuranceHelpers;
        } catch (e) {
            console.error('DataTables başlatma hatası:', e);
        }
    } else {
        // jQuery yüklenmediyse, window load event'i ile tekrar dene
        window.addEventListener('load', function() {
            if (typeof jQuery !== 'undefined') {
                try {
                    console.info('jQuery gecikmeli olarak yüklendi, DataTables başlatılıyor...');

                    if ($.fn.tooltip) {
                        $('[data-toggle="tooltip"]').tooltip();
                    }

                    DataTableHelpers.autoInitTables();
                    window.DataTableHelpers = DataTableHelpers;
                    window.InsuranceHelpers = InsuranceHelpers;
                } catch (e) {
                    console.error('Gecikmeli DataTables başlatma hatası:', e);
                }
            }
        });
    }

    // Flash mesajlarını otomatik kapat
    const flashMessage = document.getElementById('msg-flash');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.opacity = '0';
            setTimeout(() => {
                flashMessage.remove();
            }, 500);
        }, 3000);
    }

    // Form doğrulama
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Sidebar aktif link
    const currentLocation = window.location.href;
    const menuItems = document.querySelectorAll('.sidebar .nav-link');

    menuItems.forEach(item => {
        if (currentLocation.includes(item.getAttribute('href'))) {
            item.classList.add('active');
        }
    });
});