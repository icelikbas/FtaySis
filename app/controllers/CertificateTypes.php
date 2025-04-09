<?php
class CertificateTypes extends Controller {
    private $certificateTypeModel;
    private $driverModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->certificateTypeModel = $this->model('CertificateType');
        $this->driverModel = $this->model('Driver');
    }

    // Belge tipleri listesini göster
    public function index() {
        // Belge tiplerini getir
        $certificateTypes = $this->certificateTypeModel->getCertificateTypes();

        $data = [
            'certificateTypes' => $certificateTypes,
            'title' => 'Sertifika Türleri'
        ];

        $this->view('certificateTypes/index', $data);
    }

    // Yeni belge tipi ekleme sayfası
    public function add() {
        // Sadece admin kullanıcılar belge tipi ekleyebilir
        if (!isAdmin()) {
            flash('error', 'Bu işlem için yetkiniz yok', 'alert alert-danger');
            redirect('certificateTypes');
        }

        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'description_err' => ''
            ];

            // Doğrulama
            if (empty($data['name'])) {
                $data['name_err'] = 'Lütfen sertifika türü adını giriniz';
            } else {
                // Sertifika türü var mı kontrol et
                if ($this->certificateTypeModel->certificateTypeExists($data['name'])) {
                    $data['name_err'] = 'Bu sertifika türü zaten mevcut';
                }
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Lütfen açıklama giriniz';
            }

            // Hata yoksa kaydet
            if (empty($data['name_err']) && empty($data['description_err'])) {
                if ($this->certificateTypeModel->addCertificateType($data)) {
                    flash('certificateType_message', 'Sertifika türü başarıyla eklendi');
                    redirect('certificateTypes');
                } else {
                    die('Bir hata oluştu');
                }
            } else {
                $this->view('certificateTypes/add', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde varsayılan veri
            $data = [
                'name' => '',
                'description' => '',
                'name_err' => '',
                'description_err' => ''
            ];

            $this->view('certificateTypes/add', $data);
        }
    }

    // Belge tipi düzenleme sayfası
    public function edit($id) {
        // Sadece admin kullanıcılar belge tipini düzenleyebilir
        if (!isAdmin()) {
            flash('error', 'Bu işlem için yetkiniz yok', 'alert alert-danger');
            redirect('certificateTypes');
        }

        // Belge tipi bilgilerini getir
        $certificateType = $this->certificateTypeModel->getCertificateTypeById($id);

        if (!$certificateType) {
            flash('error', 'Belge tipi bulunamadı', 'alert alert-danger');
            redirect('certificateTypes');
        }

        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'description_err' => ''
            ];

            // Doğrulama
            if (empty($data['name'])) {
                $data['name_err'] = 'Lütfen sertifika türü adını giriniz';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Lütfen açıklama giriniz';
            }

            // Hata yoksa güncelle
            if (empty($data['name_err']) && empty($data['description_err'])) {
                if ($this->certificateTypeModel->updateCertificateType($data)) {
                    flash('certificateType_message', 'Sertifika türü başarıyla güncellendi');
                    redirect('certificateTypes');
                } else {
                    die('Bir hata oluştu');
                }
            } else {
                $this->view('certificateTypes/edit', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde mevcut verileri göster
            $data = [
                'id' => $certificateType->id,
                'name' => $certificateType->name,
                'description' => $certificateType->description,
                'name_err' => '',
                'description_err' => ''
            ];

            $this->view('certificateTypes/edit', $data);
        }
    }

    // Belge tipi silme işlemi
    public function delete($id) {
        // Sadece admin kullanıcılar belge tipini silebilir
        if (!isAdmin()) {
            flash('error', 'Bu işlem için yetkiniz yok', 'alert alert-danger');
            redirect('certificateTypes');
        }

        // Yalnızca POST istekleri kabul edilir
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('certificateTypes');
        }

        // Belge tipi bilgilerini getir
        $certificateType = $this->certificateTypeModel->getCertificateTypeById($id);

        if (!$certificateType) {
            flash('error', 'Belge tipi bulunamadı', 'alert alert-danger');
            redirect('certificateTypes');
        }

        // Belge tipini kullanan sürücüler var mı kontrol et
        $driversWithCertificateType = $this->certificateTypeModel->getDriversWithCertificateType($id);
        if (count($driversWithCertificateType) > 0) {
            flash('error', 'Bu belge tipi kullanımda olduğu için silinemez', 'alert alert-danger');
            redirect('certificateTypes');
        }

        // Belge tipi silme işlemi
        if ($this->certificateTypeModel->deleteCertificateType($id)) {
            flash('certificateType_message', 'Sertifika türü başarıyla silindi');
        } else {
            flash('certificateType_message', 'Sertifika türü silinirken bir hata oluştu', 'alert alert-danger');
        }

        redirect('certificateTypes');
    }

    // Belirli bir belge tipine sahip sürücüleri listele
    public function drivers($id) {
        // Belge tipi bilgilerini getir
        $certificateType = $this->certificateTypeModel->getCertificateTypeById($id);

        if (!$certificateType) {
            flash('error', 'Belge tipi bulunamadı', 'alert alert-danger');
            redirect('certificateTypes');
        }

        // Belge tipine sahip sürücüleri getir
        $drivers = $this->certificateTypeModel->getDriversWithCertificateType($id);

        $data = [
            'certificateType' => $certificateType,
            'drivers' => $drivers,
            'title' => $certificateType->name . ' - Sürücüler'
        ];

        $this->view('certificateTypes/drivers', $data);
    }
} 