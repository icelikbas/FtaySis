<?php
class Transfers extends Controller {
    private $transferModel;
    private $tankModel;
    private $userModel;

    public function __construct(){
        // Oturum kontrolü
        if(!isLoggedIn()){
            redirect('users/login');
        }

        // Model Yükleme
        $this->transferModel = $this->model('FuelTransfer');
        $this->tankModel = $this->model('FuelTank');
        $this->userModel = $this->model('User');
    }

    // Ana sayfa (tüm transferleri listeler)
    public function index(){
        // Transferleri getir
        $transfers = $this->transferModel->getAllTransfers();

        $data = [
            'title' => 'Yakıt Transferleri',
            'transfers' => $transfers
        ];

        $this->view('transfers/index', $data);
    }

    // Transfer detayı
    public function show($id){
        // Transfer bilgilerini getir
        $transfer = $this->transferModel->getTransferById($id);

        if(!$transfer){
            flash('transfer_message', 'Transfer bulunamadı!', 'alert alert-danger');
            redirect('transfers');
        }
        
        // Kaynak ve hedef tank bilgilerini getir
        $sourceTank = $this->tankModel->getTankById($transfer->source_tank_id);
        $destinationTank = $this->tankModel->getTankById($transfer->destination_tank_id);
        
        // Transfer objesine tank bilgilerini ekle
        if($sourceTank) {
            $transfer->source_current_amount = $sourceTank->current_amount;
            $transfer->source_capacity = $sourceTank->capacity;
            $transfer->source_tank_info = $sourceTank;
        }
        
        if($destinationTank) {
            $transfer->destination_current_amount = $destinationTank->current_amount;
            $transfer->destination_capacity = $destinationTank->capacity;
            $transfer->destination_tank_info = $destinationTank;
        }

        $data = [
            'title' => 'Transfer Detayları',
            'transfer' => $transfer
        ];

        $this->view('transfers/show', $data);
    }

    // Yeni transfer ekle sayfası
    public function add(){
        // Aktif tankları getir
        $tanks = $this->tankModel->getActiveTanks();
        
        // POST kontrolü
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Form bilgilerini doğrula
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini hazırla
            $data = [
                'title' => 'Yeni Transfer Ekle',
                'source_tank_id' => trim($_POST['source_tank_id']),
                'destination_tank_id' => trim($_POST['destination_tank_id']),
                'fuel_type' => trim($_POST['fuel_type']),
                'amount' => trim($_POST['amount']),
                'date' => trim($_POST['date']),
                'notes' => trim($_POST['notes']),
                'tanks' => $tanks,
                'source_tank_id_err' => '',
                'destination_tank_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'date_err' => ''
            ];

            // Validasyon
            if(empty($data['source_tank_id'])){
                $data['source_tank_id_err'] = 'Lütfen kaynak tankı seçiniz';
            }

            if(empty($data['destination_tank_id'])){
                $data['destination_tank_id_err'] = 'Lütfen hedef tankı seçiniz';
            } else if($data['source_tank_id'] == $data['destination_tank_id']){
                $data['destination_tank_id_err'] = 'Kaynak ve hedef tank aynı olamaz';
            }

            if(empty($data['fuel_type'])){
                $data['fuel_type_err'] = 'Lütfen yakıt tipini seçiniz';
            }

            if(empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0){
                $data['amount_err'] = 'Lütfen geçerli bir miktar değeri giriniz';
            } else {
                // Kaynak tankta yeterli yakıt var mı kontrol et
                $sourceTank = $this->tankModel->getTankById($data['source_tank_id']);
                if($sourceTank && $data['amount'] > $sourceTank->current_amount){
                    $data['amount_err'] = 'Kaynak tankta yeterli yakıt yok. Mevcut: ' . $sourceTank->current_amount;
                }
                
                // Hedef tankın kapasitesi yeterli mi kontrol et
                $destinationTank = $this->tankModel->getTankById($data['destination_tank_id']);
                if($destinationTank){
                    $remainingCapacity = $destinationTank->capacity - $destinationTank->current_amount;
                    if($data['amount'] > $remainingCapacity){
                        $data['amount_err'] = 'Hedef tankın kapasitesi yeterli değil. Kalan kapasite: ' . $remainingCapacity;
                    }
                }
            }

            if(empty($data['date'])){
                $data['date_err'] = 'Lütfen tarih giriniz';
            }

            // Hata yoksa kaydet
            if(empty($data['source_tank_id_err']) && empty($data['destination_tank_id_err']) && 
               empty($data['fuel_type_err']) && empty($data['amount_err']) && empty($data['date_err'])){
                
                // Transfer verilerini hazırla
                $transferData = [
                    'source_tank_id' => $data['source_tank_id'],
                    'destination_tank_id' => $data['destination_tank_id'],
                    'fuel_type' => $data['fuel_type'],
                    'amount' => $data['amount'],
                    'date' => $data['date'],
                    'notes' => $data['notes'],
                    'created_by' => $_SESSION['user_id']
                ];

                // Transfer ekle
                if($this->transferModel->addTransfer($transferData)){
                    flash('transfer_message', 'Transfer başarıyla eklendi');
                    redirect('transfers');
                } else {
                    flash('transfer_message', 'Transfer eklenirken bir hata oluştu. Tank miktarlarını kontrol ediniz.', 'alert alert-danger');
                    $this->view('transfers/add', $data);
                }
            } else {
                // Hatalarla birlikte form göster
                $this->view('transfers/add', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Yeni Transfer Ekle',
                'source_tank_id' => '',
                'destination_tank_id' => '',
                'fuel_type' => '',
                'amount' => '',
                'date' => date('Y-m-d'),
                'notes' => '',
                'tanks' => $tanks,
                'source_tank_id_err' => '',
                'destination_tank_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'date_err' => ''
            ];

            $this->view('transfers/add', $data);
        }
    }

    // Transfer düzenleme sayfası
    public function edit($id){
        // Transfer bilgilerini getir
        $transfer = $this->transferModel->getTransferById($id);

        if(!$transfer){
            flash('transfer_message', 'Transfer bulunamadı!', 'alert alert-danger');
            redirect('transfers');
        }

        // Aktif tankları getir
        $tanks = $this->tankModel->getActiveTanks();

        // POST kontrolü
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Form bilgilerini doğrula
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini hazırla
            $data = [
                'title' => 'Transfer Düzenle',
                'id' => $id,
                'source_tank_id' => trim($_POST['source_tank_id']),
                'destination_tank_id' => trim($_POST['destination_tank_id']),
                'fuel_type' => trim($_POST['fuel_type']),
                'amount' => trim($_POST['amount']),
                'date' => trim($_POST['date']),
                'notes' => trim($_POST['notes']),
                'tanks' => $tanks,
                'source_tank_id_err' => '',
                'destination_tank_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'date_err' => ''
            ];

            // Validasyon
            if(empty($data['source_tank_id'])){
                $data['source_tank_id_err'] = 'Lütfen kaynak tankı seçiniz';
            }

            if(empty($data['destination_tank_id'])){
                $data['destination_tank_id_err'] = 'Lütfen hedef tankı seçiniz';
            } else if($data['source_tank_id'] == $data['destination_tank_id']){
                $data['destination_tank_id_err'] = 'Kaynak ve hedef tank aynı olamaz';
            }

            if(empty($data['fuel_type'])){
                $data['fuel_type_err'] = 'Lütfen yakıt tipini seçiniz';
            }

            if(empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0){
                $data['amount_err'] = 'Lütfen geçerli bir miktar değeri giriniz';
            } else if($data['source_tank_id'] == $transfer->source_tank_id && $data['amount'] > ($transfer->amount + $this->tankModel->getTankById($data['source_tank_id'])->current_amount)){
                // Kaynak tank aynı fakat miktar artırılıyorsa, tankta yeterli yakıt var mı kontrol et
                $data['amount_err'] = 'Kaynak tankta yeterli yakıt yok.';
            } else if($data['destination_tank_id'] == $transfer->destination_tank_id){
                // Hedef tank aynı ise, kapasiteyi kontrol et
                $destTank = $this->tankModel->getTankById($data['destination_tank_id']);
                $additionalAmount = $data['amount'] - $transfer->amount;
                if($additionalAmount > 0 && ($destTank->current_amount + $additionalAmount) > $destTank->capacity){
                    $data['amount_err'] = 'Hedef tankın kapasitesi yeterli değil.';
                }
            }

            if(empty($data['date'])){
                $data['date_err'] = 'Lütfen tarih giriniz';
            }

            // Hata yoksa güncelle
            if(empty($data['source_tank_id_err']) && empty($data['destination_tank_id_err']) && 
               empty($data['fuel_type_err']) && empty($data['amount_err']) && empty($data['date_err'])){
                
                // Transfer verilerini hazırla
                $transferData = [
                    'id' => $id,
                    'source_tank_id' => $data['source_tank_id'],
                    'destination_tank_id' => $data['destination_tank_id'],
                    'fuel_type' => $data['fuel_type'],
                    'amount' => $data['amount'],
                    'date' => $data['date'],
                    'notes' => $data['notes']
                ];

                // Transfer güncelle
                if($this->transferModel->updateTransfer($transferData)){
                    flash('transfer_message', 'Transfer başarıyla güncellendi');
                    redirect('transfers');
                } else {
                    flash('transfer_message', 'Transfer güncellenirken bir hata oluştu. Tank miktarlarını kontrol ediniz.', 'alert alert-danger');
                    $this->view('transfers/edit', $data);
                }
            } else {
                // Hatalarla birlikte form göster
                $this->view('transfers/edit', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Transfer Düzenle',
                'id' => $id,
                'source_tank_id' => $transfer->source_tank_id,
                'destination_tank_id' => $transfer->destination_tank_id,
                'fuel_type' => $transfer->fuel_type,
                'amount' => $transfer->amount,
                'date' => $transfer->date,
                'notes' => $transfer->notes,
                'tanks' => $tanks,
                'source_tank_id_err' => '',
                'destination_tank_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'date_err' => ''
            ];

            $this->view('transfers/edit', $data);
        }
    }

    // Transfer silme işlemi
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Transfer bilgilerini getir
            $transfer = $this->transferModel->getTransferById($id);

            if(!$transfer){
                flash('transfer_message', 'Transfer bulunamadı!', 'alert alert-danger');
                redirect('transfers');
            }

            // Transfer sil
            if($this->transferModel->deleteTransfer($id)){
                flash('transfer_message', 'Transfer başarıyla silindi');
            } else {
                flash('transfer_message', 'Transfer silinirken bir hata oluştu', 'alert alert-danger');
            }

            redirect('transfers');
        } else {
            redirect('transfers');
        }
    }

    // Tanka göre transferler
    public function tank($tankId){
        // Tank bilgilerini getir
        $tank = $this->tankModel->getTankById($tankId);

        if(!$tank){
            flash('transfer_message', 'Tank bulunamadı!', 'alert alert-danger');
            redirect('transfers');
        }

        // Bu tanka ait transferleri getir (kaynak veya hedef olarak)
        $transfers = $this->transferModel->getTransfersByTank($tankId);

        $data = [
            'title' => $tank->name . ' - Yakıt Transferleri',
            'tank' => $tank,
            'transfers' => $transfers
        ];

        $this->view('transfers/tank', $data);
    }

    // Yakıt Tipi değişikliğinde kaynak tankın yakıt türünü getiren AJAX işlemi
    public function get_tank_fuel_type(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // JSON verisini al
            $json = file_get_contents('php://input');
            $data = json_decode($json);
            
            $tankId = intval($data->tank_id);
            
            $response = ['success' => false, 'fuel_type' => ''];
            
            if($tankId > 0){
                $tank = $this->tankModel->getTankById($tankId);
                if($tank){
                    // Burada varsayalım ki tankın son aldığı yakıt tipini biliyoruz 
                    // (Gerçek uygulamada tank tablosuna fuel_type alanı eklenebilir)
                    $lastPurchase = $this->model('FuelPurchase')->getPurchasesByTank($tankId);
                    $fuelType = '';
                    
                    if(count($lastPurchase) > 0){
                        $fuelType = $lastPurchase[0]->fuel_type;
                    }
                    
                    $response = ['success' => true, 'fuel_type' => $fuelType, 'current_amount' => $tank->current_amount];
                }
            }
            
            echo json_encode($response);
        }
    }
} 