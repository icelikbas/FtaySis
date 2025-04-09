<?php
class Purchases extends Controller {
    private $purchaseModel;
    private $tankModel;
    private $userModel;

    public function __construct(){
        // Oturum kontrolü
        if(!isLoggedIn()){
            redirect('users/login');
        }

        // Model Yükleme
        $this->purchaseModel = $this->model('FuelPurchase');
        $this->tankModel = $this->model('FuelTank');
        $this->userModel = $this->model('User');
    }

    // Ana sayfa (tüm yakıt alımlarını listeler)
    public function index(){
        // Yakıt alımlarını getir
        $purchases = $this->purchaseModel->getAllPurchases();

        $data = [
            'title' => 'Yakıt Alımları',
            'purchases' => $purchases
        ];

        $this->view('purchases/index', $data);
    }

    // Yakıt alımı detayı
    public function show($id){
        // Yakıt alımı bilgilerini getir
        $purchase = $this->purchaseModel->getPurchaseById($id);

        if(!$purchase){
            flash('purchase_message', 'Yakıt alımı bulunamadı!', 'alert alert-danger');
            redirect('purchases');
        }

        $data = [
            'title' => 'Yakıt Alımı Detayları',
            'purchase' => $purchase
        ];

        $this->view('purchases/show', $data);
    }

    // Yeni yakıt alımı ekle sayfası
    public function add(){
        // Aktif tankları getir
        $tanks = $this->tankModel->getActiveTanks();
        
        // POST kontrolü
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Form bilgilerini doğrula
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini hazırla
            $data = [
                'title' => 'Yeni Yakıt Alımı Ekle',
                'supplier_name' => trim($_POST['supplier_name']),
                'fuel_type' => trim($_POST['fuel_type']),
                'amount' => trim($_POST['amount']),
                'cost' => trim($_POST['cost']),
                'unit_price' => trim($_POST['unit_price']),
                'tank_id' => trim($_POST['tank_id']),
                'invoice_number' => trim($_POST['invoice_number']),
                'date' => trim($_POST['date']),
                'notes' => trim($_POST['notes']),
                'tanks' => $tanks,
                'supplier_name_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'cost_err' => '',
                'unit_price_err' => '',
                'tank_id_err' => '',
                'date_err' => ''
            ];

            // Validasyon
            if(empty($data['supplier_name'])){
                $data['supplier_name_err'] = 'Lütfen tedarikçi adını giriniz';
            }

            if(empty($data['fuel_type'])){
                $data['fuel_type_err'] = 'Lütfen yakıt tipini seçiniz';
            }

            if(empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0){
                $data['amount_err'] = 'Lütfen geçerli bir miktar değeri giriniz';
            }

            if(empty($data['cost']) || !is_numeric($data['cost']) || $data['cost'] <= 0){
                $data['cost_err'] = 'Lütfen geçerli bir maliyet değeri giriniz';
            }

            if(empty($data['unit_price']) || !is_numeric($data['unit_price']) || $data['unit_price'] <= 0){
                $data['unit_price_err'] = 'Lütfen geçerli bir birim fiyat değeri giriniz';
            }

            if(empty($data['tank_id'])){
                $data['tank_id_err'] = 'Lütfen tank seçiniz';
            }

            if(empty($data['date'])){
                $data['date_err'] = 'Lütfen tarih giriniz';
            }

            // Birim fiyat hesaplama (eğer kullanıcı tarafından değiştirilmemişse)
            if (empty($data['unit_price_err']) && empty($data['cost_err']) && empty($data['amount_err'])) {
                // Birim fiyat kontrolü - maliyetin miktara bölümü yaklaşık olarak birim fiyata eşit olmalı
                $calculated_unit_price = round($data['cost'] / $data['amount'], 2);
                $user_unit_price = round($data['unit_price'], 2);
                
                // Birim fiyatlar arasında %1'den fazla fark varsa uyar
                $diff_percentage = abs(($calculated_unit_price - $user_unit_price) / $calculated_unit_price) * 100;
                if ($diff_percentage > 1) {
                    $data['unit_price_err'] = 'Birim fiyat tutarsız. Hesaplanan: ' . $calculated_unit_price;
                }
            }

            // Hata yoksa kaydet
            if(empty($data['supplier_name_err']) && empty($data['fuel_type_err']) && empty($data['amount_err']) && 
               empty($data['cost_err']) && empty($data['unit_price_err']) && empty($data['tank_id_err']) && 
               empty($data['date_err'])){
                
                // Yakıt alımı verilerini hazırla
                $purchaseData = [
                    'supplier_name' => $data['supplier_name'],
                    'fuel_type' => $data['fuel_type'],
                    'amount' => $data['amount'],
                    'cost' => $data['cost'],
                    'unit_price' => $data['unit_price'],
                    'tank_id' => $data['tank_id'],
                    'invoice_number' => $data['invoice_number'],
                    'date' => $data['date'],
                    'notes' => $data['notes'],
                    'created_by' => $_SESSION['user_id']
                ];

                // Yakıt alımı ekle
                if($this->purchaseModel->addPurchase($purchaseData)){
                    flash('purchase_message', 'Yakıt alımı başarıyla eklendi');
                    redirect('purchases');
                } else {
                    flash('purchase_message', 'Yakıt alımı eklenirken bir hata oluştu. Tank kapasitesi kontrol ediniz.', 'alert alert-danger');
                    $this->view('purchases/add', $data);
                }
            } else {
                // Hatalarla birlikte form göster
                $this->view('purchases/add', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Yeni Yakıt Alımı Ekle',
                'supplier_name' => '',
                'fuel_type' => '',
                'amount' => '',
                'cost' => '',
                'unit_price' => '',
                'tank_id' => '',
                'invoice_number' => '',
                'date' => date('Y-m-d'),
                'notes' => '',
                'tanks' => $tanks,
                'supplier_name_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'cost_err' => '',
                'unit_price_err' => '',
                'tank_id_err' => '',
                'date_err' => ''
            ];

            $this->view('purchases/add', $data);
        }
    }

    // Yakıt alımı düzenleme sayfası
    public function edit($id){
        // Yakıt alımı bilgilerini getir
        $purchase = $this->purchaseModel->getPurchaseById($id);

        if(!$purchase){
            flash('purchase_message', 'Yakıt alımı bulunamadı!', 'alert alert-danger');
            redirect('purchases');
        }

        // Aktif tankları getir
        $tanks = $this->tankModel->getActiveTanks();

        // POST kontrolü
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Form bilgilerini doğrula
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini hazırla
            $data = [
                'title' => 'Yakıt Alımı Düzenle',
                'id' => $id,
                'supplier_name' => trim($_POST['supplier_name']),
                'fuel_type' => trim($_POST['fuel_type']),
                'amount' => trim($_POST['amount']),
                'cost' => trim($_POST['cost']),
                'unit_price' => trim($_POST['unit_price']),
                'tank_id' => trim($_POST['tank_id']),
                'invoice_number' => trim($_POST['invoice_number']),
                'date' => trim($_POST['date']),
                'notes' => trim($_POST['notes']),
                'tanks' => $tanks,
                'supplier_name_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'cost_err' => '',
                'unit_price_err' => '',
                'tank_id_err' => '',
                'date_err' => ''
            ];

            // Validasyon
            if(empty($data['supplier_name'])){
                $data['supplier_name_err'] = 'Lütfen tedarikçi adını giriniz';
            }

            if(empty($data['fuel_type'])){
                $data['fuel_type_err'] = 'Lütfen yakıt tipini seçiniz';
            }

            if(empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0){
                $data['amount_err'] = 'Lütfen geçerli bir miktar değeri giriniz';
            }

            if(empty($data['cost']) || !is_numeric($data['cost']) || $data['cost'] <= 0){
                $data['cost_err'] = 'Lütfen geçerli bir maliyet değeri giriniz';
            }

            if(empty($data['unit_price']) || !is_numeric($data['unit_price']) || $data['unit_price'] <= 0){
                $data['unit_price_err'] = 'Lütfen geçerli bir birim fiyat değeri giriniz';
            }

            if(empty($data['tank_id'])){
                $data['tank_id_err'] = 'Lütfen tank seçiniz';
            }

            if(empty($data['date'])){
                $data['date_err'] = 'Lütfen tarih giriniz';
            }

            // Birim fiyat hesaplama (eğer kullanıcı tarafından değiştirilmemişse)
            if (empty($data['unit_price_err']) && empty($data['cost_err']) && empty($data['amount_err'])) {
                // Birim fiyat kontrolü
                $calculated_unit_price = round($data['cost'] / $data['amount'], 2);
                $user_unit_price = round($data['unit_price'], 2);
                
                // Birim fiyatlar arasında %1'den fazla fark varsa uyar
                $diff_percentage = abs(($calculated_unit_price - $user_unit_price) / $calculated_unit_price) * 100;
                if ($diff_percentage > 1) {
                    $data['unit_price_err'] = 'Birim fiyat tutarsız. Hesaplanan: ' . $calculated_unit_price;
                }
            }

            // Hata yoksa güncelle
            if(empty($data['supplier_name_err']) && empty($data['fuel_type_err']) && empty($data['amount_err']) && 
               empty($data['cost_err']) && empty($data['unit_price_err']) && empty($data['tank_id_err']) && 
               empty($data['date_err'])){
                
                // Yakıt alımı verilerini hazırla
                $purchaseData = [
                    'id' => $id,
                    'supplier_name' => $data['supplier_name'],
                    'fuel_type' => $data['fuel_type'],
                    'amount' => $data['amount'],
                    'cost' => $data['cost'],
                    'unit_price' => $data['unit_price'],
                    'tank_id' => $data['tank_id'],
                    'invoice_number' => $data['invoice_number'],
                    'date' => $data['date'],
                    'notes' => $data['notes']
                ];

                // Yakıt alımı güncelle
                if($this->purchaseModel->updatePurchase($purchaseData)){
                    flash('purchase_message', 'Yakıt alımı başarıyla güncellendi');
                    redirect('purchases');
                } else {
                    flash('purchase_message', 'Yakıt alımı güncellenirken bir hata oluştu. Tank kapasitesi kontrol ediniz.', 'alert alert-danger');
                    $this->view('purchases/edit', $data);
                }
            } else {
                // Hatalarla birlikte form göster
                $this->view('purchases/edit', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Yakıt Alımı Düzenle',
                'id' => $id,
                'supplier_name' => $purchase->supplier_name,
                'fuel_type' => $purchase->fuel_type,
                'amount' => $purchase->amount,
                'cost' => $purchase->cost,
                'unit_price' => $purchase->unit_price,
                'tank_id' => $purchase->tank_id,
                'invoice_number' => $purchase->invoice_number,
                'date' => $purchase->date,
                'notes' => $purchase->notes,
                'tanks' => $tanks,
                'supplier_name_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'cost_err' => '',
                'unit_price_err' => '',
                'tank_id_err' => '',
                'date_err' => ''
            ];

            $this->view('purchases/edit', $data);
        }
    }

    // Yakıt alımı silme işlemi
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Yakıt alımı bilgilerini getir
            $purchase = $this->purchaseModel->getPurchaseById($id);

            if(!$purchase){
                flash('purchase_message', 'Yakıt alımı bulunamadı!', 'alert alert-danger');
                redirect('purchases');
            }

            // Yakıt alımı sil
            if($this->purchaseModel->deletePurchase($id)){
                flash('purchase_message', 'Yakıt alımı başarıyla silindi');
            } else {
                flash('purchase_message', 'Yakıt alımı silinirken bir hata oluştu', 'alert alert-danger');
            }

            redirect('purchases');
        } else {
            redirect('purchases');
        }
    }

    // Tanka göre yakıt alımları
    public function tank($tankId){
        // Tank bilgilerini getir
        $tank = $this->tankModel->getTankById($tankId);

        if(!$tank){
            flash('purchase_message', 'Tank bulunamadı!', 'alert alert-danger');
            redirect('purchases');
        }

        // Bu tanka ait yakıt alımlarını getir
        $purchases = $this->purchaseModel->getPurchasesByTank($tankId);

        $data = [
            'title' => $tank->name . ' - Yakıt Alımları',
            'tank' => $tank,
            'purchases' => $purchases
        ];

        $this->view('purchases/tank', $data);
    }

    // Birim fiyat hesaplama AJAX işlemi
    public function calculate_unit_price(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // JSON verisini al
            $json = file_get_contents('php://input');
            $data = json_decode($json);
            
            $amount = floatval($data->amount);
            $cost = floatval($data->cost);
            
            $response = ['success' => false, 'unit_price' => 0];
            
            if($amount > 0 && $cost > 0){
                $unit_price = round($cost / $amount, 2);
                $response = ['success' => true, 'unit_price' => $unit_price];
            }
            
            echo json_encode($response);
        }
    }
} 