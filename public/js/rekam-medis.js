document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tanggal').valueAsDate = new Date();
    lockSections();
    toggleAlamatMode();

    const searchInput = document.getElementById('searchPasien');
    const searchResults = document.getElementById('searchResults');
    const searchStage = document.getElementById('searchStage');
    const patientCard = document.getElementById('patientCard');
    const patientCardStatus = document.getElementById('patientCardStatus');

    let currentHewanData = [];
    let currentPemilikData = [];

    let searchTimeout;
    if(searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            const q = searchInput.value.trim();
            
            if (q.length < 2) { 
                searchResults.classList.add('hidden'); 
                searchResults.innerHTML = ''; 
                return; 
            }

            searchResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">Mencari...</div>';
            searchResults.classList.remove('hidden');

            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`/rekam-medis/search?q=${encodeURIComponent(q)}`);
                    const data = await response.json();
                    
                    currentHewanData = data.hewans;
                    currentPemilikData = data.pemiliks;

                    renderSearchResults(currentHewanData, currentPemilikData);
                } catch (error) {
                    console.error("Error fetching search results:", error);
                    searchResults.innerHTML = '<div class="px-4 py-3 text-sm text-red-500">Gagal mengambil data pencarian.</div>';
                }
            }, 300);
        });
    }

    function renderSearchResults(hewans, pemiliks) {
        let html = '';

        hewans.forEach(a => {
            const owner = a.pemilik;
            html += `
            <button type="button" onclick="selectExistingPet('${a.id_hewan}')" class="w-full text-left px-4 py-3 hover:bg-brand-primary/5 dark:hover:bg-gray-700/50 transition-colors flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-brand-bg dark:bg-brand-primary/20 flex items-center justify-center text-brand-dark dark:text-brand-light flex-shrink-0">
                    <i class="fa-solid fa-paw"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-white truncate">${a.nama_hewan} <span class="text-gray-400 font-normal">· ID: ${a.id_hewan}</span></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Pemilik: ${owner.nama_pemilik} · ${owner.no_hp}</p>
                </div>
            </button>`;
        });

        pemiliks.forEach(o => {
            html += `
            <div class="px-4 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300 flex-shrink-0">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-white truncate">${o.nama_pemilik} <span class="text-gray-400 font-normal">· ${o.no_hp}</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Punya ${o.hewans.length} hewan terdaftar</p>
                    </div>
                </div>
                <button type="button" onclick="selectOwnerForNewPet('${o.id_pemilik}')" class="text-xs font-medium text-brand-primary dark:text-brand-light hover:underline whitespace-nowrap flex-shrink-0">+ Hewan Baru</button>
            </div>`;
        });

        if (!hewans.length && !pemiliks.length) {
            html = `
            <div class="px-4 py-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2"><i class="fa-regular fa-circle-xmark mr-1"></i>Data tidak ditemukan</p>
                <button type="button" onclick="selectNewRegistration()" class="text-sm font-medium text-brand-primary dark:text-brand-light hover:underline">+ Daftar Pasien & Pemilik Baru</button>
            </div>`;
        }

        searchResults.innerHTML = html;
    }

    // Ekspor fungsi ke global window agar bisa dipanggil dari atribut onclick di HTML
    window.renderSearchResults = renderSearchResults;
    window.selectExistingPet = selectExistingPet;
    window.selectOwnerForNewPet = selectOwnerForNewPet;
    window.selectNewRegistration = selectNewRegistration;
    window.toggleEdit = toggleEdit;
    window.resetSearch = resetSearch;
    window.updateRetribusi = updateRetribusi;
    window.toggleAlamatMode = toggleAlamatMode;
    window.handleSimpan = handleSimpan;

    const pemilikFields = ['nama_pemilik', 'no_hp_pemilik', 'lokasi_dalam_kota', 'lokasi_luar_kota', 'alamat_kelurahan', 'alamat_manual'];
    const hewanFields = ['nama_hewan', 'jenis_hewan', 'jenis_kelamin', 'umur_tahun', 'umur_bulan', 'warna_hewan', 'berat_badan'];

    function toggleAlamatMode() {
        const radioDalam = document.getElementById('lokasi_dalam_kota');
        if(!radioDalam) return;
        
        const dalam = radioDalam.checked;
        document.getElementById('alamatDalamKotaWrap').classList.toggle('hidden', !dalam);
        document.getElementById('alamatLuarKotaWrap').classList.toggle('hidden', dalam);

        document.getElementById('alamat_kelurahan').disabled = !dalam;
        document.getElementById('alamat_manual').disabled = dalam;
    }

    function loadAlamat(alamatValue) {
        const selectKelurahan = document.getElementById('alamat_kelurahan');
        const textareaManual = document.getElementById('alamat_manual');
        const radioDalam = document.getElementById('lokasi_dalam_kota');
        const radioLuar = document.getElementById('lokasi_luar_kota');

        const matchOption = Array.from(selectKelurahan.options).find(o => o.value === alamatValue);

        if (matchOption) {
            radioDalam.checked = true;
            selectKelurahan.value = alamatValue;
            textareaManual.value = '';
        } else {
            radioLuar.checked = true;
            selectKelurahan.selectedIndex = 0;
            textareaManual.value = (alamatValue && alamatValue !== '-') ? alamatValue : '';
        }
        toggleAlamatMode();
    }

    function updateUmurHidden() {
        const tahun = parseInt(document.getElementById('umur_tahun').value) || 0;
        const bulan = parseInt(document.getElementById('umur_bulan').value) || 0;
        const parts = [];
        if (tahun > 0) parts.push(tahun + ' Tahun');
        if (bulan > 0) parts.push(bulan + ' Bulan');
        document.getElementById('umur_hewan').value = parts.length ? parts.join(' ') : '-';
    }

    function loadUmur(umurValue) {
        const str = (umurValue ?? '').toString().trim();
        const tahunMatch = str.match(/(\d+)\s*Tahun/i);
        const bulanMatch = str.match(/(\d+)\s*Bulan/i);

        if (tahunMatch || bulanMatch) {
            document.getElementById('umur_tahun').value = tahunMatch ? tahunMatch[1] : '';
            document.getElementById('umur_bulan').value = bulanMatch ? bulanMatch[1] : '';
        } else if (/^\d+$/.test(str)) {
            document.getElementById('umur_tahun').value = str;
            document.getElementById('umur_bulan').value = '';
        } else {
            document.getElementById('umur_tahun').value = '';
            document.getElementById('umur_bulan').value = '';
        }
        document.getElementById('umur_hewan').value = str;
    }

    function selectExistingPet(id_hewan) {
        const pet = currentHewanData.find(a => String(a.id_hewan) === String(id_hewan));
        const owner = pet.pemilik;

        document.getElementById('id_pemilik').value = owner.id_pemilik;
        document.getElementById('id_hewan').value = pet.id_hewan;

        document.getElementById('nama_pemilik').value = owner.nama_pemilik;
        document.getElementById('no_hp_pemilik').value = owner.no_hp;
        loadAlamat(owner.alamat);

        document.getElementById('nama_hewan').value = pet.nama_hewan;
        document.getElementById('jenis_hewan').value = pet.id_jenis;
        document.getElementById('jenis_kelamin').value = pet.jenis_kelamin;
        loadUmur(pet.umur);
        document.getElementById('warna_hewan').value = pet.warna;
        document.getElementById('berat_badan').value = pet.berat_badan ?? '';

        setFieldsState([...pemilikFields, ...hewanFields], true);
        toggleAlamatMode();
        
        patientCardStatus.innerHTML = '<i class="fa-solid fa-circle-check"></i> Pasien Lama — Data Ditemukan';
        showPatientCard();
        filterPelayanan();
    }

    function selectOwnerForNewPet(id_pemilik) {
        const owner = currentPemilikData.find(o => String(o.id_pemilik) === String(id_pemilik));

        document.getElementById('id_pemilik').value = owner.id_pemilik;
        document.getElementById('id_hewan').value = ""; 

        document.getElementById('nama_pemilik').value = owner.nama_pemilik;
        document.getElementById('no_hp_pemilik').value = owner.no_hp;
        loadAlamat(owner.alamat);

        clearFields(hewanFields);
        setFieldsState(['nama_pemilik', 'no_hp_pemilik', 'lokasi_dalam_kota', 'lokasi_luar_kota', 'alamat_kelurahan', 'alamat_manual'], true);
        setFieldsState(hewanFields, false);

        patientCardStatus.innerHTML = '<i class="fa-solid fa-plus"></i> Pemilik Lama — Hewan Baru';
        showPatientCard();
        resetFilterPelayanan();
        toggleAlamatMode();
        document.getElementById('nama_hewan').focus();
    }

    function selectNewRegistration() {
        document.getElementById('id_pemilik').value = "";
        document.getElementById('id_hewan').value = "";

        clearFields(pemilikFields);
        clearFields(hewanFields);
        setFieldsState([...pemilikFields, ...hewanFields], false);

        patientCardStatus.innerHTML = '<i class="fa-solid fa-user-plus"></i> Pendaftaran Baru';
        showPatientCard();
        resetFilterPelayanan();
        toggleAlamatMode();
        document.getElementById('nama_pemilik').focus();
    }

    function toggleEdit(group) {
        const ids = group === 'pemilik' ? pemilikFields : hewanFields;
        const btn = group === 'pemilik' ? document.getElementById('btnEditPemilik') : document.getElementById('btnEditHewan');
        const isCurrentlyEditable = ids.some(id => {
            const el = document.getElementById(id);
            if (!el) return false;
            return (el.tagName === 'SELECT' || el.type === 'radio') ? !el.disabled : !el.readOnly;
        });
        setFieldsState(ids, isCurrentlyEditable);
        if (group === 'pemilik') { toggleAlamatMode(); }
        btn.innerHTML = isCurrentlyEditable
            ? '<i class="fa-solid fa-pen mr-1"></i>Edit'
            : '<i class="fa-solid fa-check mr-1"></i>Selesai';
    }

    function clearFields(ids) { 
        ids.forEach(id => { 
            const el = document.getElementById(id); 
            if (!el) return;
            if (el.type === 'radio') {
                el.checked = (id === 'lokasi_dalam_kota');
            } else {
                el.value = '';
            }
        }); 
    }
    
    function setFieldsState(ids, readonly) {
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (el.tagName === 'SELECT' || el.type === 'radio' || el.type === 'checkbox') {
                el.disabled = readonly;
            } else {
                el.readOnly = readonly;
            }
            el.classList.toggle('bg-gray-100', readonly);
            el.classList.toggle('dark:bg-gray-800', readonly);
            el.classList.toggle('cursor-not-allowed', readonly);
            el.classList.toggle('bg-gray-50', !readonly);
            el.classList.toggle('dark:bg-gray-900', !readonly);
        });
    }
    
    function showPatientCard() {
        searchStage.classList.add('hidden');
        patientCard.classList.remove('hidden');
        document.getElementById('btnEditPemilik').classList.remove('hidden');
        document.getElementById('btnEditHewan').classList.remove('hidden');
        unlockSections();
    }
    
    function resetSearch() {
        patientCard.classList.add('hidden');
        searchStage.classList.remove('hidden');
        searchInput.value = '';
        searchResults.classList.add('hidden');
        document.getElementById('id_pemilik').value = "";
        document.getElementById('id_hewan').value = "";
        lockSections();
        resetFilterPelayanan();
    }

    function lockSections() {
        document.getElementById('lockOverlayMedis').classList.remove('hidden');
        document.getElementById('lockOverlayBiaya').classList.remove('hidden');
    }
    
    function unlockSections() {
        document.getElementById('lockOverlayMedis').classList.add('hidden');
        document.getElementById('lockOverlayBiaya').classList.add('hidden');
    }

    function updateRetribusi() {
        const select = document.getElementById('pelayanan');
        const retribusiInput = document.getElementById('retribusi');
        if(!select || !retribusiInput) return;
        
        if(select.selectedIndex > 0) {
            const tarif = select.options[select.selectedIndex].getAttribute('data-tarif');
            retribusiInput.value = new Intl.NumberFormat('id-ID').format(tarif);
        } else {
            retribusiInput.value = '0';
        }
    }

    const pelayananSelect = document.getElementById('pelayanan');
    const pelayananOptions = pelayananSelect ? Array.from(pelayananSelect.options).filter(o => o.value !== "") : [];
    const jenisHewanSelect = document.getElementById('jenis_hewan');
    const jenisKelaminSelect = document.getElementById('jenis_kelamin');

    function filterPelayanan() {
        if(!jenisHewanSelect || !jenisKelaminSelect) return;
        
        const idJenisHewan = jenisHewanSelect.value;
        const kelaminHewan = jenisKelaminSelect.value;

        const norm = (v) => (v || '').toString().trim().toLowerCase();

        pelayananOptions.forEach(opt => {
            const optJenis = opt.getAttribute('data-jenis') || '';
            const optKelamin = opt.getAttribute('data-kelamin') || '';
            const jenisCocok = !optJenis || norm(optJenis) === norm(idJenisHewan);
            const kelaminCocok = !optKelamin || norm(optKelamin) === norm(kelaminHewan);
            opt.hidden = !(jenisCocok && kelaminCocok);
        });

        const activeOption = pelayananSelect.options[pelayananSelect.selectedIndex];
        if (pelayananSelect.selectedIndex > 0 && activeOption && activeOption.hidden) {
            pelayananSelect.selectedIndex = 0;
            updateRetribusi();
        }
    }

    function resetFilterPelayanan() {
        pelayananOptions.forEach(opt => { opt.hidden = false; });
    }

    if(jenisHewanSelect) jenisHewanSelect.addEventListener('change', filterPelayanan);
    if(jenisKelaminSelect) jenisKelaminSelect.addEventListener('change', filterPelayanan);

    function initMultiSelect(containerId, inputName) {
        const container = document.getElementById(containerId);
        if(!container) return { reset: () => {} };

        const searchBox = container.querySelector('.ms-search');
        const dropdown = container.querySelector('.ms-dropdown');
        const options = Array.from(dropdown.querySelectorAll('.ms-option'));
        const hiddenWrap = container.querySelector('.ms-hidden');
        const tableBody = container.querySelector('.ms-tbody');
        const emptyRow = container.querySelector('.ms-empty');
        const selected = new Set();

        function updateEmptyRow() {
            emptyRow.classList.toggle('hidden', selected.size > 0);
        }

        function addItem(id, name) {
            if (selected.has(id)) return;
            selected.add(id);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = inputName;
            hidden.value = id;
            hidden.dataset.id = id;
            hiddenWrap.appendChild(hidden);

            const row = document.createElement('tr');
            row.dataset.id = id;
            row.innerHTML = `
                <td class="p-3 text-gray-800 dark:text-gray-200 text-sm">${name}</td>
                <td class="p-3 w-10 text-center">
                    <button type="button" class="ms-remove-btn text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </td>`;
            row.querySelector('.ms-remove-btn').addEventListener('click', () => removeItem(id));
            tableBody.appendChild(row);

            const optBtn = options.find(o => o.dataset.id === id);
            if (optBtn) optBtn.classList.add('hidden');

            updateEmptyRow();
        }

        function removeItem(id) {
            selected.delete(id);
            const hiddenInput = hiddenWrap.querySelector(`input[data-id="${id}"]`);
            if (hiddenInput) hiddenInput.remove();
            const row = tableBody.querySelector(`tr[data-id="${id}"]`);
            if (row) row.remove();
            const optBtn = options.find(o => o.dataset.id === id);
            if (optBtn) optBtn.classList.remove('hidden');
            updateEmptyRow();
        }

        function reset() {
            selected.clear();
            hiddenWrap.innerHTML = '';
            tableBody.querySelectorAll('tr:not(.ms-empty)').forEach(r => r.remove());
            options.forEach(o => o.classList.remove('hidden'));
            searchBox.value = '';
            dropdown.classList.add('hidden');
            updateEmptyRow();
        }

        options.forEach(btn => {
            btn.addEventListener('click', () => {
                addItem(btn.dataset.id, btn.dataset.name);
                searchBox.value = '';
                options.forEach(o => o.classList.remove('hidden'));
                selected.forEach(id => {
                    const b = options.find(o => o.dataset.id === id);
                    if (b) b.classList.add('hidden');
                });
            });
        });

        searchBox.addEventListener('input', () => {
            const q = searchBox.value.trim().toLowerCase();
            dropdown.classList.remove('hidden');
            options.forEach(btn => {
                const match = btn.dataset.name.toLowerCase().includes(q);
                const isSelected = selected.has(btn.dataset.id);
                btn.classList.toggle('hidden', !match || isSelected);
            });
        });

        searchBox.addEventListener('focus', () => {
            dropdown.classList.remove('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        updateEmptyRow();
        return { reset };
    }

    const anamnesaMS = initMultiSelect('anamnesaMultiSelect', 'anamnesa[]');
    const obatMS = initMultiSelect('obatMultiSelect', 'terapi[]');

    async function handleSimpan(e) {
        e.preventDefault();
        
        setFieldsState([...pemilikFields, ...hewanFields], false);
        toggleAlamatMode();
        updateUmurHidden();

        const form = e.target;
        const btn = document.getElementById('btnSubmit');
        const originalContent = btn.innerHTML;
        const formData = new FormData(form);
        
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Menyimpan...</span>';
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');

        try {
            // Mengambil URL dari atribut action pada form
            const targetUrl = form.action;

            const response = await fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if(response.ok && result.success) {
                btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>' + result.message + '</span>';
                btn.classList.replace('bg-brand-primary', 'bg-green-600');
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    btn.classList.replace('bg-green-600', 'bg-brand-primary');
                    btn.classList.remove('opacity-80', 'cursor-not-allowed');
                    
                    form.reset();

                    anamnesaMS.reset();
                    obatMS.reset();
                    
                    document.getElementById('tanggal').valueAsDate = new Date();
                    document.getElementById('retribusi').value = '0';
                    document.getElementById('umur_hewan').value = '';
                    resetSearch();
                    toggleAlamatMode();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 2000);
            } else {
                throw new Error(result.message || "Terjadi kesalahan di server.");
            }
        } catch (error) {
            console.error("Submit Error:", error);
            alert("Gagal menyimpan data: " + error.message);
            btn.innerHTML = originalContent;
            btn.disabled = false;
            btn.classList.remove('opacity-80', 'cursor-not-allowed');
        }
    }
});