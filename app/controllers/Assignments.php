<?php
class Assignments extends Controller {
    private $assignmentModel;
    private $vehicleModel;
    private $driverModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->assignmentModel = $this->model('Assignment');
        $this->vehicleModel = $this->model('Vehicle');
        $this->driverModel = $this->model('Driver');
    }

    // Görevlendirme listesini görüntüleme
    public function index() {
        // Tüm görevlendirmeleri getir
        $assignments = $this->assignmentModel->getAssignments();

        $data = [
            'assignments' => $assignments,
            'title' => 'Görevlendirme Listesi'
        ];

        $this->view('assignments/index', $data);
    }

    // Görevlendirme detaylarını görüntüleme
    public function show($id) {
        $assignment = $this->assignmentModel->getAssignmentById($id);

        // Görevlendirme bulunamadıysa ana sayfaya yönlendir
        if (!$assignment) {
            flash('assignment_message', 'Görevlendirme bulunamadı', 'alert alert-danger');
            redirect('assignments');
        }

        $data = [
            'assignment' => $assignment
        ];

        $this->view('assignments/show', $data);
    }

    // Yeni görevlendirme ekleme sayfası
    public function add() {
        // Aktif araç ve şoför listelerini getir
        $vehicles = $this->assignmentModel->getActiveVehiclesForSelect();
        $drivers = $this->assignmentModel->getActiveDriversForSelect();

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'vehicle_id' => trim($_POST['vehicle_id']),
                'driver_id' => trim($_POST['driver_id']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => !empty($_POST['end_date']) ? trim($_POST['end_date']) : null,
                'status' => trim($_POST['status']),
                'notes' => trim($_POST['notes']),
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'vehicle_id_err' => '',
                'driver_id_err' => '',
                'start_date_err' => '',
                'status_err' => ''
            ];

            // Araç doğrulama
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen bir araç seçiniz';
            } elseif ($this->assignmentModel->checkVehicleHasActiveAssignment($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Bu araç zaten aktif bir görevlendirmede kullanılıyor';
            }

            // Şoför doğrulama
            if (empty($data['driver_id'])) {
                $data['driver_id_err'] = 'Lütfen bir şoför seçiniz';
            } elseif ($this->assignmentModel->checkDriverHasActiveAssignment($data['driver_id'])) {
                $data['driver_id_err'] = 'Bu şoför zaten aktif bir görevlendirmede görevli';
            }

            // Başlangıç tarihi doğrulama
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Lütfen başlangıç tarihini giriniz';
            }

            // Durum doğrulama
            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen durumu seçiniz';
            }

            // Hata yoksa işleme devam et
            if (empty($data['vehicle_id_err']) && 
                empty($data['driver_id_err']) && 
                empty($data['start_date_err']) && 
                empty($data['status_err'])) {
                
                // Veritabanına kaydet
                if ($this->assignmentModel->addAssignment($data)) {
                    flash('assignment_message', 'Görevlendirme başarıyla eklendi');
                    redirect('assignments');
                } else {
                    flash('assignment_message', 'Görevlendirme eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('assignments/add', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $this->view('assignments/add', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde varsayılan veri
            $data = [
                'vehicle_id' => '',
                'driver_id' => '',
                'start_date' => date('Y-m-d'),
                'end_date' => '',
                'status' => 'Aktif',
                'notes' => '',
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'vehicle_id_err' => '',
                'driver_id_err' => '',
                'start_date_err' => '',
                'status_err' => ''
            ];

            $this->view('assignments/add', $data);
        }
    }

    // Görevlendirme düzenleme sayfası
    public function edit($id) {
        // Görevlendirme bilgisini al
        $assignment = $this->assignmentModel->getAssignmentById($id);

        // Görevlendirme bulunamadıysa ana sayfaya yönlendir
        if (!$assignment) {
            flash('assignment_message', 'Görevlendirme bulunamadı', 'alert alert-danger');
            redirect('assignments');
        }

        // Aktif araç ve şoför listelerini getir
        $vehicles = $this->assignmentModel->getActiveVehiclesForSelect();
        $drivers = $this->assignmentModel->getActiveDriversForSelect();

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'id' => $id,
                'vehicle_id' => trim($_POST['vehicle_id']),
                'driver_id' => trim($_POST['driver_id']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => !empty($_POST['end_date']) ? trim($_POST['end_date']) : null,
                'status' => trim($_POST['status']),
                'notes' => trim($_POST['notes']),
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'vehicle_id_err' => '',
                'driver_id_err' => '',
                'start_date_err' => '',
                'status_err' => ''
            ];

            // Araç doğrulama
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen bir araç seçiniz';
            } elseif ($data['vehicle_id'] != $assignment->vehicle_id && 
                    $this->assignmentModel->checkVehicleHasActiveAssignment($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Bu araç zaten aktif bir görevlendirmede kullanılıyor';
            }

            // Şoför doğrulama
            if (empty($data['driver_id'])) {
                $data['driver_id_err'] = 'Lütfen bir şoför seçiniz';
            } elseif ($data['driver_id'] != $assignment->driver_id && 
                    $this->assignmentModel->checkDriverHasActiveAssignment($data['driver_id'])) {
                $data['driver_id_err'] = 'Bu şoför zaten aktif bir görevlendirmede görevli';
            }

            // Başlangıç tarihi doğrulama
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Lütfen başlangıç tarihini giriniz';
            }

            // Durum doğrulama
            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen durumu seçiniz';
            }

            // Eğer durum "Tamamlandı" ise bitiş tarihi olmalı
            if ($data['status'] == 'Tamamlandı' && empty($data['end_date'])) {
                $data['end_date'] = date('Y-m-d'); // Bugünün tarihini otomatik ekle
            }

            // Hata yoksa işleme devam et
            if (empty($data['vehicle_id_err']) && 
                empty($data['driver_id_err']) && 
                empty($data['start_date_err']) && 
                empty($data['status_err'])) {
                
                // Veritabanında güncelle
                if ($this->assignmentModel->updateAssignment($data)) {
                    flash('assignment_message', 'Görevlendirme başarıyla güncellendi');
                    redirect('assignments');
                } else {
                    flash('assignment_message', 'Görevlendirme güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('assignments/edit', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $this->view('assignments/edit', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde mevcut verileri göster
            $data = [
                'id' => $id,
                'vehicle_id' => $assignment->vehicle_id,
                'driver_id' => $assignment->driver_id,
                'start_date' => $assignment->start_date,
                'end_date' => $assignment->end_date,
                'status' => $assignment->status,
                'notes' => $assignment->notes,
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'vehicle_id_err' => '',
                'driver_id_err' => '',
                'start_date_err' => '',
                'status_err' => ''
            ];

            $this->view('assignments/edit', $data);
        }
    }

    // Görevlendirme durumunu güncelleme
    public function updateStatus($id) {
        // Görevlendirme bilgisini al
        $assignment = $this->assignmentModel->getAssignmentById($id);

        // Görevlendirme bulunamadıysa ana sayfaya yönlendir
        if (!$assignment) {
            flash('assignment_message', 'Görevlendirme bulunamadı', 'alert alert-danger');
            redirect('assignments');
        }

        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $status = trim($_POST['status']);
            $end_date = ($status == 'Tamamlandı' || $status == 'İptal') ? date('Y-m-d') : null;
            
            // Durumu güncelle
            if ($this->assignmentModel->updateAssignmentStatus($id, $status, $end_date)) {
                flash('assignment_message', 'Görevlendirme durumu başarıyla güncellendi');
            } else {
                flash('assignment_message', 'Görevlendirme durumu güncellenirken bir hata oluştu', 'alert alert-danger');
            }
        }
        
        redirect('assignments/show/' . $id);
    }

    // Görevlendirme silme işlemi
    public function delete($id) {
        // Yalnızca admin kullanıcılar silebilir
        if (!isAdmin()) {
            flash('assignment_message', 'Görevlendirme silme yetkisine sahip değilsiniz', 'alert alert-danger');
            redirect('assignments');
        }

        // Görevlendirmeyi al
        $assignment = $this->assignmentModel->getAssignmentById($id);
        
        // Görevlendirme bulunamadıysa ana sayfaya yönlendir
        if (!$assignment) {
            flash('assignment_message', 'Görevlendirme bulunamadı', 'alert alert-danger');
            redirect('assignments');
        }

        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Görevlendirmeyi sil
            if ($this->assignmentModel->deleteAssignment($id)) {
                flash('assignment_message', 'Görevlendirme başarıyla silindi');
            } else {
                flash('assignment_message', 'Görevlendirme silinirken bir hata oluştu', 'alert alert-danger');
            }
        }

        redirect('assignments');
    }

    // Araç ID'sine göre atanmış aktif sürücüyü JSON olarak döndür
    public function getDriverForVehicle($id) {
        if(!isLoggedIn()) {
            redirect('users/login');
        }

        // Aracın aktif görevlendirmesini al
        $activeAssignment = $this->assignmentModel->getActiveAssignmentByVehicle($id);
        
        // API yanıtı formatında JSON döndür
        header('Content-Type: application/json');
        
        if($activeAssignment) {
            echo json_encode([
                'success' => true,
                'driver_id' => $activeAssignment->driver_id,
                'driver_name' => $activeAssignment->driver_name
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Bu araca atanmış aktif sürücü bulunamadı'
            ]);
        }
        
        exit;
    }
} 