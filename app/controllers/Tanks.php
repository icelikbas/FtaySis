<?php
class Tanks extends Controller {
    private $tankModel;
    private $userModel;

    public function __construct(){
        // Oturum kontrolü
        if(!isLoggedIn()){
            redirect('users/login');
        }

        // Model Yükleme
        $this->tankModel = $this->model('FuelTank');
        $this->userModel = $this->model('User');
    }

    // Ana sayfa (tüm tankları listeler)
    public function index(){
        // Tankları getir
        $tanks = $this->tankModel->getAllTanks();

        $data = [
            'title' => 'Yakıt Tankları',
            'tanks' => $tanks
        ];

        $this->view('tanks/index', $data);
    }

    // Tank detayı
    public function show($id){
        // Tank bilgilerini getir
        $tank = $this->tankModel->getTankById($id);

        if(!$tank){
            flash('tank_message', 'Tank bulunamadı!', 'alert alert-danger');
            redirect('tanks');
        }

        // Yakıt alımları ve transferleri getir
        $purchaseModel = $this->model('FuelPurchase');
        $transferModel = $this->model('FuelTransfer');
        
        $purchases = $purchaseModel->getPurchasesByTank($id);
        $transfersIn = $transferModel->getTransfersByDestinationTank($id);
        $transfersOut = $transferModel->getTransfersBySourceTank($id);
        
        // Tüm transferleri birleştir
        $transfers = array_merge($transfersIn, $transfersOut);
        
        // Tarihe göre sırala (en yeni en üstte)
        usort($transfers, function($a, $b) {
            return strtotime($b->transfer_date) - strtotime($a->transfer_date);
        });

        $data = [
            'title' => 'Tank Detayları',
            'tank' => $tank,
            'purchases' => $purchases,
            'transfers' => $transfers
        ];

        $this->view('tanks/show', $data);
    }

    // Yeni tank ekle sayfası
    public function add(){
        // POST kontrolü
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Form bilgilerini doğrula
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini hazırla
            $data = [
                'title' => 'Yeni Tank Ekle',
                'name' => trim($_POST['name']),
                'type' => trim($_POST['type']),
                'capacity' => trim($_POST['capacity']),
                'current_amount' => trim($_POST['current_amount']),
                'location' => trim($_POST['location']),
                'status' => trim($_POST['status']),
                'fuel_type' => trim($_POST['fuel_type']),
                'name_err' => '',
                'type_err' => '',
                'capacity_err' => '',
                'current_amount_err' => '',
                'fuel_type_err' => ''
            ];

            // Validasyon
            if(empty($data['name'])){
                $data['name_err'] = 'Lütfen tank adını giriniz';
            }

            if(empty($data['type'])){
                $data['type_err'] = 'Lütfen tank tipini seçiniz';
            }

            if(empty($data['capacity']) || !is_numeric($data['capacity']) || $data['capacity'] <= 0){
                $data['capacity_err'] = 'Lütfen geçerli bir kapasite değeri giriniz';
            }

            if(empty($data['current_amount'])){
                $data['current_amount'] = '0'; // Varsayılan olarak sıfır
            } else if(!is_numeric($data['current_amount']) || $data['current_amount'] < 0){
                $data['current_amount_err'] = 'Lütfen geçerli bir miktar değeri giriniz';
            } else if(floatval($data['current_amount']) > floatval($data['capacity'])){
                $data['current_amount_err'] = 'Mevcut miktar kapasiteden büyük olamaz';
            }
            
            if(empty($data['fuel_type'])){
                $data['fuel_type_err'] = 'Lütfen yakıt tipini seçiniz';
            }

            // Hata yoksa kaydet
            if(empty($data['name_err']) && empty($data['type_err']) && empty($data['capacity_err']) && empty($data['current_amount_err']) && empty($data['fuel_type_err'])){
                // Tank verilerini hazırla
                $tankData = [
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'capacity' => $data['capacity'],
                    'current_amount' => $data['current_amount'],
                    'location' => $data['location'],
                    'status' => $data['status'],
                    'fuel_type' => $data['fuel_type']
                ];

                // Tank ekle
                if($this->tankModel->addTank($tankData)){
                    flash('tank_message', 'Tank başarıyla eklendi');
                    redirect('tanks');
                } else {
                    flash('tank_message', 'Tank eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('tanks/add', $data);
                }
            } else {
                // Hatalarla birlikte form göster
                $this->view('tanks/add', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Yeni Tank Ekle',
                'name' => '',
                'type' => '',
                'capacity' => '',
                'current_amount' => '0',
                'location' => '',
                'status' => 'Aktif',
                'fuel_type' => '',
                'name_err' => '',
                'type_err' => '',
                'capacity_err' => '',
                'current_amount_err' => '',
                'fuel_type_err' => ''
            ];

            $this->view('tanks/add', $data);
        }
    }

    // Tank düzenleme sayfası
    public function edit($id){
        // Tank bilgilerini getir
        $tank = $this->tankModel->getTankById($id);

        if(!$tank){
            flash('tank_message', 'Tank bulunamadı!', 'alert alert-danger');
            redirect('tanks');
        }

        // POST kontrolü
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Form bilgilerini doğrula
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini hazırla
            $data = [
                'title' => 'Tank Düzenle',
                'id' => $id,
                'name' => trim($_POST['name']),
                'type' => trim($_POST['type']),
                'capacity' => trim($_POST['capacity']),
                'current_amount' => trim($_POST['current_amount']),
                'location' => trim($_POST['location']),
                'status' => trim($_POST['status']),
                'fuel_type' => trim($_POST['fuel_type']),
                'name_err' => '',
                'type_err' => '',
                'capacity_err' => '',
                'current_amount_err' => '',
                'fuel_type_err' => ''
            ];

            // Validasyon
            if(empty($data['name'])){
                $data['name_err'] = 'Lütfen tank adını giriniz';
            }

            if(empty($data['type'])){
                $data['type_err'] = 'Lütfen tank tipini seçiniz';
            }

            if(empty($data['capacity']) || !is_numeric($data['capacity']) || $data['capacity'] <= 0){
                $data['capacity_err'] = 'Lütfen geçerli bir kapasite değeri giriniz';
            }

            if(empty($data['current_amount'])){
                $data['current_amount'] = '0'; // Varsayılan olarak sıfır
            } else if(!is_numeric($data['current_amount']) || $data['current_amount'] < 0){
                $data['current_amount_err'] = 'Lütfen geçerli bir miktar değeri giriniz';
            } else if(floatval($data['current_amount']) > floatval($data['capacity'])){
                $data['current_amount_err'] = 'Mevcut miktar kapasiteden büyük olamaz';
            }
            
            if(empty($data['fuel_type'])){
                $data['fuel_type_err'] = 'Lütfen yakıt tipini seçiniz';
            }

            // Hata yoksa güncelle
            if(empty($data['name_err']) && empty($data['type_err']) && empty($data['capacity_err']) && empty($data['current_amount_err']) && empty($data['fuel_type_err'])){
                // Tank verilerini hazırla
                $tankData = [
                    'id' => $id,
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'capacity' => $data['capacity'],
                    'current_amount' => $data['current_amount'],
                    'location' => $data['location'],
                    'status' => $data['status'],
                    'fuel_type' => $data['fuel_type']
                ];

                // Tank güncelle
                if($this->tankModel->updateTank($tankData)){
                    flash('tank_message', 'Tank başarıyla güncellendi');
                    redirect('tanks');
                } else {
                    flash('tank_message', 'Tank güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('tanks/edit', $data);
                }
            } else {
                // Hatalarla birlikte form göster
                $this->view('tanks/edit', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Tank Düzenle',
                'id' => $id,
                'name' => $tank->name,
                'type' => $tank->type,
                'capacity' => $tank->capacity,
                'current_amount' => $tank->current_amount,
                'location' => $tank->location,
                'status' => $tank->status,
                'fuel_type' => $tank->fuel_type ?? '',
                'name_err' => '',
                'type_err' => '',
                'capacity_err' => '',
                'current_amount_err' => '',
                'fuel_type_err' => ''
            ];

            $this->view('tanks/edit', $data);
        }
    }

    // Tank silme işlemi
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Tank bilgilerini getir
            $tank = $this->tankModel->getTankById($id);

            if(!$tank){
                flash('tank_message', 'Tank bulunamadı!', 'alert alert-danger');
                redirect('tanks');
            }

            // Tank sil
            if($this->tankModel->deleteTank($id)){
                flash('tank_message', 'Tank başarıyla silindi');
            } else {
                flash('tank_message', 'Tank silinirken bir hata oluştu. Tank kullanımda olabilir.', 'alert alert-danger');
            }

            redirect('tanks');
        } else {
            redirect('tanks');
        }
    }
} 