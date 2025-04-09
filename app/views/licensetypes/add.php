<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-id-badge"></i> Yeni Ehliyet Sınıfı Ekle</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/licensetypes" class="btn btn-light float-right">
            <i class="fa fa-backward"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card card-body bg-light mt-4">
    <form action="<?php echo URLROOT; ?>/licensetypes/add" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Ehliyet Kodu: <sup class="text-danger">*</sup></label>
                    <input type="text" name="code" class="form-control <?php echo (!empty($data['code_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['code']; ?>" maxlength="5">
                    <span class="invalid-feedback"><?php echo $data['code_err']; ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Ehliyet Adı: <sup class="text-danger">*</sup></label>
                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Açıklama:</label>
            <textarea name="description" class="form-control" rows="3"><?php echo $data['description']; ?></textarea>
        </div>
        
        <div class="row">
            <div class="col">
                <input type="submit" value="Kaydet" class="btn btn-success btn-block">
            </div>
            <div class="col">
                <a href="<?php echo URLROOT; ?>/licensetypes" class="btn btn-light btn-block">İptal</a>
            </div>
        </div>
    </form>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Ehliyet Kodu Örnekleri</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info text-white mr-2">M</span>
                            <span>Motorlu Bisiklet (Moped)</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary select-code" data-code="M">Seç</button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info text-white mr-2">A1</span>
                            <span>Küçük Motosiklet</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary select-code" data-code="A1">Seç</button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info text-white mr-2">B</span>
                            <span>Otomobil, Kamyonet</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary select-code" data-code="B">Seç</button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info text-white mr-2">C</span>
                            <span>Kamyon, Çekici</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary select-code" data-code="C">Seç</button>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info text-white mr-2">CE</span>
                            <span>Kamyon (Römorklu)</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary select-code" data-code="CE">Seç</button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info text-white mr-2">D</span>
                            <span>Otobüs</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary select-code" data-code="D">Seç</button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info text-white mr-2">F</span>
                            <span>Traktör</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary select-code" data-code="F">Seç</button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info text-white mr-2">G</span>
                            <span>İş Makinası</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary select-code" data-code="G">Seç</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ehliyet kodu seçme butonları
        const codeButtons = document.querySelectorAll('.select-code');
        const codeInput = document.querySelector('input[name="code"]');
        
        codeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                codeInput.value = code;
                
                // Ehliyet adını otomatik doldurma
                const nameInput = document.querySelector('input[name="name"]');
                if (!nameInput.value) {
                    switch(code) {
                        case 'M': nameInput.value = 'M Sınıfı'; break;
                        case 'A1': nameInput.value = 'A1 Sınıfı'; break;
                        case 'A2': nameInput.value = 'A2 Sınıfı'; break;
                        case 'A': nameInput.value = 'A Sınıfı'; break;
                        case 'B1': nameInput.value = 'B1 Sınıfı'; break;
                        case 'B': nameInput.value = 'B Sınıfı'; break;
                        case 'BE': nameInput.value = 'BE Sınıfı'; break;
                        case 'C1': nameInput.value = 'C1 Sınıfı'; break;
                        case 'C1E': nameInput.value = 'C1E Sınıfı'; break;
                        case 'C': nameInput.value = 'C Sınıfı'; break;
                        case 'CE': nameInput.value = 'CE Sınıfı'; break;
                        case 'D1': nameInput.value = 'D1 Sınıfı'; break;
                        case 'D1E': nameInput.value = 'D1E Sınıfı'; break;
                        case 'D': nameInput.value = 'D Sınıfı'; break;
                        case 'DE': nameInput.value = 'DE Sınıfı'; break;
                        case 'F': nameInput.value = 'F Sınıfı'; break;
                        case 'G': nameInput.value = 'G Sınıfı'; break;
                    }
                }
                
                // Açıklama alanını otomatik doldurma
                const descInput = document.querySelector('textarea[name="description"]');
                if (!descInput.value) {
                    switch(code) {
                        case 'M': descInput.value = 'Motorlu bisiklet (Moped) kullanımı için'; break;
                        case 'A1': descInput.value = 'Silindir hacmi 125 cc\'ye kadar, gücü 11 kilovatı geçmeyen sepetsiz iki tekerlekli motosikletler'; break;
                        case 'A2': descInput.value = 'Gücü 35 kilovatı geçmeyen, gücü/ağırlığı 0,2 kilovatı/kiloğramı geçmeyen iki tekerlekli motosikletler'; break;
                        case 'A': descInput.value = 'Gücü 35 kilovatı veya gücü/ağırlığı 0,2 kilovatı/kiloğramı geçen iki tekerlekli motosikletler'; break;
                        case 'B1': descInput.value = 'Net motor gücü 15 kilovatı ve net ağırlığı 400 kilogram geçmeyen dört tekerlekli motosikletler'; break;
                        case 'B': descInput.value = 'Otomobil ve kamyonet (3500 kg\'a kadar)'; break;
                        case 'BE': descInput.value = 'B sınıfı sürücü belgesi ile sürülebilen otomobil veya kamyonetin römork takılmış hali'; break;
                        case 'C1': descInput.value = 'Azami yüklü ağırlığı 3.500 kg\'ın üzerinde olan ve 7.500 kg\'ı geçmeyen kamyon ve çekiciler'; break;
                        case 'C1E': descInput.value = 'C1 sınıfı sürücü belgesi ile sürülebilen araçlara takılan ve azami yüklü ağırlığı 750 kg\'ı geçen römorklu kamyonlar'; break;
                        case 'C': descInput.value = 'Kamyon ve Çekici (Tır)'; break;
                        case 'CE': descInput.value = 'C sınıfı sürücü belgesi ile sürülebilen araçlarla römork takılan hali'; break;
                        case 'D1': descInput.value = 'Minibüs'; break;
                        case 'D1E': descInput.value = 'D1 sınıfı sürücü belgesi ile sürülebilen araçlara takılan ve azami yüklü ağırlığı 750 kg\'ı geçen römorklu halı'; break;
                        case 'D': descInput.value = 'Otobüs'; break;
                        case 'DE': descInput.value = 'D sınıfı sürücü belgesi ile sürülebilen araçlara römork takılan hali'; break;
                        case 'F': descInput.value = 'Traktör kullanımı için'; break;
                        case 'G': descInput.value = 'İş makinası türündeki motorlu araçları kullanabilme'; break;
                    }
                }
            });
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 