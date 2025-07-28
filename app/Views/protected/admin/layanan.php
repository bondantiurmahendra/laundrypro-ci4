<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Layanan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="layananTabs" x-init="init()" class="space-y-6">

  <h1 class="text-xl font-bold text-on-surface dark:text-on-surface-dark">Layanan Pakaian</h1>

  <!-- Success Alert -->
  <div x-show="showSuccessAlert" x-transition.opacity
    class="fixed bottom-4 right-4 z-50 w-96 max-w-sm overflow-hidden rounded-lg border border-green-500 bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark shadow-lg"
    role="alert">
    <div class="flex w-full items-center gap-2 bg-success/10 p-4">
      <div class="bg-green-500/15 text-green-500 rounded-full p-1" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6"
          aria-hidden="true">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd" />
        </svg>
      </div>
      <div class="ml-2 flex-1">
        <h3 class="text-sm font-semibold text-success">Berhasil Diperbarui</h3>
        <p class="text-xs font-medium sm:text-sm" x-text="successMessage">Data layanan berhasil diperbarui!</p>
      </div>
      <button @click="showSuccessAlert = false" class="ml-auto text-gray-500 hover:text-gray-700"
        aria-label="dismiss alert">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none"
          stroke-width="2.5" class="size-4 shrink-0">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile: Select -->
  <div class="md:hidden mb-4">
    <select x-model="selectedTab"
      class="w-full border border-outline rounded p-2 text-sm dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark">
      <template x-for="tab in tabs" :key="tab.key">
        <option :value="tab.key" x-text="tab.label"></option>
      </template>
    </select>
  </div>

  <!-- Desktop Tabs -->
  <div class="hidden md:flex gap-2 overflow-x-auto border-b border-outline dark:border-outline-dark">
    <template x-for="tab in tabs" :key="tab.key">
      <button type="button" class="flex items-center gap-2 px-4 py-2 text-sm h-min"
        :class="selectedTab === tab.key
          ? 'font-bold text-primary border-b-2 border-primary dark:border-primary-dark dark:text-primary-dark'
          : 'text-on-surface font-medium dark:text-on-surface-dark hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong dark:hover:border-b-outline-dark-strong'"
        x-on:click="selectedTab = tab.key">
        <i :class="tab.icon + ' fa-sm'"></i>
        <span x-text="tab.label"></span>
      </button>
    </template>
  </div>

  <!-- Button Tambah Layanan Baru -->
  <div class="flex justify-end mb-2">
    <button @click="openAddModal()"
      class="px-4 py-2 rounded bg-primary text-white font-semibold hover:bg-primary/90 dark:bg-primary-dark dark:hover:bg-primary-dark/90 flex items-center gap-2">
      <i class="fas fa-plus"></i>
      Tambah Layanan Baru
    </button>
  </div>

  <!-- Table Layanan -->
  <div class="overflow-x-auto border border-outline rounded-xl dark:border-outline-dark mt-4">
    <!-- Search Bar -->
    <div class="p-4 border-b border-outline dark:border-outline-dark">
      <div class="flex items-center gap-2">
        <div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            aria-hidden="true"
            class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-on-surface/50 dark:text-on-surface-dark/50">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          <input type="search" x-model="searchQuery"
            class="w-full rounded-radius border border-outline bg-surface-alt py-2 pl-10 pr-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
            name="search" placeholder="Cari layanan..." aria-label="search" />
        </div>
        <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
          class="px-3 py-2 text-xs rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
          Clear
        </button>
      </div>
      <div x-show="searchQuery.length > 0" class="mt-2 text-xs text-gray-600 dark:text-gray-400">
        <span x-text="`Menampilkan ${filteredLayanan.length} dari ${layanan.length} layanan`"></span>
      </div>
    </div>

    <template x-if="filteredLayanan.length > 0">
      <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
        <thead
          class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
          <tr>
            <th class="p-4">#</th>
            <th class="p-4">Nama</th>
            <th class="p-4">Jenis</th>
            <th class="p-4">Harga</th>
            <th class="p-4">Estimasi</th>
            <th class="p-4">Status</th>
            <th class="p-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
          <template x-for="(item, index) in filteredLayanan" :key="item.id">
            <tr>
              <td class="p-4" x-text="index + 1"></td>
              <td class="p-4" x-text="item.nama"></td>
              <td class="p-4" x-text="item.jenis"></td>
              <td class="p-4" x-text="formatHarga(item.harga, item.satuan)"></td>
              <td class="p-4" x-text="item.estimasi"></td>
              <td class="p-4">
                <span class="inline-block text-xs px-2 py-1 rounded-full font-medium text-white"
                  :class="getStatusClass(item.status)" x-text="getStatusText(item.status)"></span>
              </td>
              <td class="p-4 flex gap-2">
                <button @click="openEditModal(item)" class="text-blue-600 hover:text-blue-800 p-2 rounded transition"
                  title="Edit">
                  <i class="fas fa-pen-to-square"></i>
                </button>
                <button @click="deleteData = item; modalDeleteOpen = true"
                  class="text-red-600 hover:text-red-800 p-2 rounded transition" title="Hapus">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </template>

    <template x-if="filteredLayanan.length === 0">
      <div class="flex flex-col items-center justify-center text-center p-10 gap-4 text-muted dark:text-muted-dark">
        <i class="fas fa-inbox fa-3x opacity-30"></i>
        <p class="text-sm">Tidak ada layanan pada kategori ini.</p>
      </div>
    </template>
  </div>

  <!-- Modal Edit -->
  <div x-show="modalEditOpen" x-cloak x-transition.opacity x-on:keydown.escape.window="modalEditOpen = false"
    class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-md border border-outline dark:border-outline-dark">
      <h2 class="text-lg font-semibold mb-4 text-on-surface dark:text-on-surface-dark">Edit Layanan</h2>
      <div class="space-y-4">
        <div>
          <label class="block text-sm mb-1">Nama</label>
          <input type="text" x-model="editData.nama"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
        </div>
        <div>
          <label class="block text-sm mb-1">Jenis</label>
          <input type="text" x-model="editData.jenis"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-sm mb-1">Harga</label>
            <input type="number" x-model.number="editData.harga"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
          </div>
          <div>
            <label class="block text-sm mb-1">Satuan</label>
            <input type="text" x-model="editData.satuan"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
          </div>
        </div>
        <div>
          <label class="block text-sm mb-1">Estimasi</label>
          <input type="text" x-model="editData.estimasi"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
        </div>
        <div>
          <label class="block text-sm mb-1">Status</label>
          <select x-model="editData.status"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
            <option :value="true">Aktif</option>
            <option :value="false">Nonaktif</option>
          </select>
          <!-- Preview status -->
          <div class="mt-2">
            <span class="inline-block text-xs px-2 py-1 rounded-full font-medium text-white"
              :class="getStatusClass(editData.status)" x-text="getStatusText(editData.status)"></span>
          </div>
        </div>
        <div>
          <label class="block text-sm mb-1">Kategori</label>
          <select x-model="editData.kategori"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
            <option value="reguler">Reguler</option>
            <option value="express">Express</option>
            <option value="super">Super Express</option>
            <option value="satuan">Satuan</option>
            <option value="dryclean">Dry Cleaning</option>
          </select>
        </div>
      </div>
      <div class="flex justify-end gap-2 mt-6">
        <button @click="modalEditOpen = false"
          class="px-3 py-1.5 text-sm rounded bg-gray-300 dark:bg-gray-700">Batal</button>
        <button @click="submitUpdate"
          class="px-3 py-1.5 text-sm rounded bg-blue-500 text-white hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          :disabled="isLoading">
          <svg x-show="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          <span x-text="isLoading ? 'Menyimpan...' : 'Simpan'"></span>
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Layanan Baru -->
  <div x-show="modalAddOpen" x-cloak x-transition.opacity x-on:keydown.escape.window="modalAddOpen = false"
    class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-md border border-outline dark:border-outline-dark">
      <h2 class="text-lg font-semibold mb-4 text-on-surface dark:text-on-surface-dark">Tambah Layanan Baru</h2>
      <div class="space-y-4">
        <div>
          <label class="block text-sm mb-1">Nama</label>
          <input type="text" x-model="addData.nama"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
        </div>
        <div>
          <label class="block text-sm mb-1">Jenis</label>
          <input type="text" x-model="addData.jenis"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-sm mb-1">Harga</label>
            <input type="number" x-model.number="addData.harga"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
          </div>
          <div>
            <label class="block text-sm mb-1">Satuan</label>
            <select x-model="addData.satuan"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
              <option value="Kg">Kg</option>
              <option value="Satuan">Satuan</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-sm mb-1">Estimasi</label>
          <input type="text" x-model="addData.estimasi"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
        </div>
        <div>
          <label class="block text-sm mb-1">Status</label>
          <select x-model="addData.status"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
            <option :value="true">Aktif</option>
            <option :value="false">Nonaktif</option>
          </select>
        </div>
        <div>
          <label class="block text-sm mb-1">Kategori</label>
          <select x-model="addData.kategori"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
            <option value="reguler">Reguler</option>
            <option value="express">Express</option>
            <option value="super">Super Express</option>
            <option value="satuan">Satuan</option>
            <option value="dryclean">Dry Cleaning</option>
          </select>
        </div>
      </div>
      <div class="flex justify-end gap-2 mt-6">
        <button @click="modalAddOpen = false"
          class="px-3 py-1.5 text-sm rounded bg-gray-300 dark:bg-gray-700">Batal</button>
        <button @click="addLayanan" :disabled="isLoadingAdd"
          class="px-3 py-1.5 text-sm rounded bg-primary text-white hover:bg-primary/90 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
          <svg x-show="isLoadingAdd" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          <span x-text="isLoadingAdd ? 'Menyimpan...' : 'Simpan'"></span>
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus Layanan -->
  <div x-show="modalDeleteOpen" x-cloak x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-sm border border-outline dark:border-outline-dark">
      <h2 class="text-lg font-semibold mb-4 text-on-surface dark:text-on-surface-dark">Konfirmasi Hapus</h2>
      <p class="mb-4 text-on-surface dark:text-on-surface-dark">Apakah Anda yakin ingin menghapus layanan <span
          class="font-bold" x-text="deleteData?.nama"></span>?</p>
      <div class="flex justify-end gap-2 mt-6">
        <button @click="modalDeleteOpen = false"
          class="px-3 py-1.5 text-sm rounded bg-gray-300 dark:bg-gray-700">Batal</button>
        <button @click="hapusLayananConfirm" :disabled="isLoadingDelete"
          class="px-3 py-1.5 text-sm rounded bg-red-600 text-white hover:bg-red-700 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
          <svg x-show="isLoadingDelete" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          <span x-text="isLoadingDelete ? 'Menghapus...' : 'Hapus'"></span>
        </button>
      </div>
    </div>
  </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('layananTabs', () => ({
    selectedTab: 'semua',
    modalEditOpen: false,
    editData: {},
    isLoading: false,
    showSuccessAlert: false,
    successMessage: '',
    searchQuery: '',
    modalAddOpen: false,
    addData: {},
    isLoadingAdd: false,
    modalDeleteOpen: false,
    deleteData: null,
    isLoadingDelete: false,
    tabs: [{
        key: 'semua',
        label: 'Semua',
        icon: 'fas fa-list'
      },
      {
        key: 'reguler',
        label: 'Reguler',
        icon: 'fas fa-clock'
      },
      {
        key: 'express',
        label: 'Express',
        icon: 'fas fa-bolt'
      },
      {
        key: 'super',
        label: 'Super Express',
        icon: 'fas fa-stopwatch'
      },
      {
        key: 'satuan',
        label: 'Satuan',
        icon: 'fas fa-tshirt'
      },
      {
        key: 'dryclean',
        label: 'Dry Cleaning',
        icon: 'fas fa-spray-can'
      }
    ],
    layanan: [],
    get filteredLayanan() {
      let data = this.layanan;
      if (this.selectedTab !== 'semua') {
        data = data.filter(l => l.kategori === this.selectedTab);
      }
      if (this.searchQuery && this.searchQuery.trim() !== '') {
        const q = this.searchQuery.trim().toLowerCase();
        data = data.filter(l => {
          return (
            (l.nama && l.nama.toLowerCase().includes(q)) ||
            (l.jenis && l.jenis.toLowerCase().includes(q)) ||
            (l.kategori && l.kategori.toLowerCase().includes(q)) ||
            (l.satuan && l.satuan.toLowerCase().includes(q)) ||
            (l.estimasi && l.estimasi.toLowerCase().includes(q)) ||
            (typeof l.harga !== 'undefined' && String(l.harga).toLowerCase().includes(q)) ||
            (typeof l.status !== 'undefined' && (l.status === true ? 'aktif' : 'nonaktif').includes(
              q))
          );
        });
      }
      return data;
    },
    formatHarga(nominal, satuan) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
      }).format(nominal).replace(",00", "") + '/' + satuan;
    },
    async fetchData() {
      try {
        if (!firebase || !firebase.firestore) {
          throw new Error('Firebase belum terinisialisasi');
        }
        const snapshot = await firebase.firestore().collection('layanan').orderBy('created_at', 'desc')
          .get();
        this.layanan = snapshot.docs.map(doc => ({
          id: doc.id,
          ...doc.data()
        }));
      } catch (err) {
        console.error('Gagal memuat data layanan:', err);
        alert('Gagal memuat data layanan: ' + err.message);
      }
    },
    openEditModal(item) {
      // Pastikan status adalah boolean yang benar
      let processedStatus = false;
      if (item.status === true || item.status === 'true' || item.status === 1) {
        processedStatus = true;
      }

      this.editData = {
        id: item.id,
        nama: item.nama || '',
        jenis: item.jenis || '',
        harga: item.harga || 0,
        satuan: item.satuan || '',
        estimasi: item.estimasi || '',
        status: processedStatus,
        kategori: item.kategori || 'reguler'
      };

      this.modalEditOpen = true;
    },
    async submitUpdate() {
      try {
        // Set loading state
        this.isLoading = true;

        // Validasi Firebase
        if (!firebase || !firebase.firestore) {
          throw new Error('Firebase belum terinisialisasi');
        }

        // Validasi data sebelum update
        if (!this.editData.nama || !this.editData.jenis || !this.editData.harga || !this.editData.satuan ||
          !this.editData.estimasi) {
          alert('Semua field harus diisi!');
          this.isLoading = false;
          return;
        }

        const {
          id,
          ...data
        } = this.editData;

        // Pastikan data yang akan diupdate tidak kosong
        const updateData = {
          nama: data.nama.trim(),
          jenis: data.jenis.trim(),
          harga: Number(data.harga),
          satuan: data.satuan.trim(),
          estimasi: data.estimasi.trim(),
          status: data.status === true || data.status === 'true' || data.status ===
            1, // Handle berbagai tipe data
          kategori: data.kategori || 'reguler' // Default kategori jika tidak ada
        };

        // Update ke Firestore
        await firebase.firestore().collection('layanan').doc(id).update(updateData);

        // Update data di local state juga
        const index = this.layanan.findIndex(item => item.id === id);
        if (index !== -1) {
          this.layanan[index] = {
            ...this.layanan[index],
            ...updateData
          };
        }

        // Tutup modal
        this.modalEditOpen = false;

        // Delay kecil untuk memastikan Firestore sudah menyelesaikan operasi
        await new Promise(resolve => setTimeout(resolve, 500));

        // Refresh data dari server untuk memastikan konsistensi
        await this.fetchData();

        // Tampilkan alert sukses
        this.successMessage = `Layanan "${updateData.nama}" berhasil diperbarui!`;
        this.showSuccessAlert = true;

        // Auto hide alert setelah 5 detik
        setTimeout(() => {
          this.showSuccessAlert = false;
        }, 5000);

      } catch (err) {
        console.error('Error updating layanan:', err);

        // Error handling yang lebih spesifik
        if (err.code === 'permission-denied') {
          alert('Tidak memiliki izin untuk mengupdate layanan');
        } else if (err.code === 'not-found') {
          alert('Dokumen layanan tidak ditemukan');
        } else {
          alert('Gagal mengupdate layanan: ' + err.message);
        }
      } finally {
        // Reset loading state
        this.isLoading = false;
      }
    },
    init() {
      this.testFirebaseConnection();
      this.fetchData();
    },
    async testFirebaseConnection() {
      try {
        if (!firebase || !firebase.firestore) {
          console.error('Firebase not initialized');
          return;
        }

        // Test koneksi dengan mencoba membaca collection
        const testSnapshot = await firebase.firestore().collection('layanan').limit(1).get();
      } catch (err) {
        console.error('Firebase connection test failed:', err);
      }
    },
    getStatusClass(status) {
      // Hanya true yang akan menjadi aktif
      const isActive = status === true;
      return isActive ? 'bg-green-500 dark:bg-green-700' : 'bg-red-500 dark:bg-red-700';
    },
    getStatusText(status) {
      // Hanya true yang akan menjadi aktif
      const isActive = status === true;
      return isActive ? 'Aktif' : 'Nonaktif';
    },
    closeSuccessAlert() {
      this.showSuccessAlert = false;
    },
    async addLayanan() {
      try {
        this.isLoadingAdd = true;
        if (!firebase || !firebase.firestore) {
          throw new Error('Firebase belum terinisialisasi');
        }
        if (!this.addData.nama || !this.addData.jenis || !this.addData.harga || !this.addData.satuan ||
          !this.addData.estimasi) {
          alert('Semua field harus diisi!');
          this.isLoadingAdd = false;
          return;
        }
        const data = this.addData;
        const newData = {
          nama: data.nama.trim(),
          jenis: data.jenis.trim(),
          harga: Number(data.harga),
          satuan: data.satuan.trim(),
          estimasi: data.estimasi.trim(),
          status: data.status === true || data.status === 'true' || data.status === 1, // boolean fix
          kategori: data.kategori || 'reguler',
          created_at: firebase.firestore.FieldValue.serverTimestamp()
        };
        await firebase.firestore().collection('layanan').add(newData);
        this.modalAddOpen = false;
        await new Promise(resolve => setTimeout(resolve, 500));
        await this.fetchData();
        this.successMessage = `Layanan "${newData.nama}" berhasil ditambahkan!`;
        this.showSuccessAlert = true;
        setTimeout(() => {
          this.showSuccessAlert = false;
        }, 5000);
      } catch (err) {
        console.error('Error adding layanan:', err);
        if (err.code === 'permission-denied') {
          alert('Tidak memiliki izin untuk menambahkan layanan');
        } else if (err.code === 'not-found') {
          alert('Dokumen layanan tidak ditemukan');
        } else {
          alert('Gagal menambahkan layanan: ' + err.message);
        }
      } finally {
        this.isLoadingAdd = false;
      }
    },
    openAddModal() {
      this.addData = {
        nama: '',
        jenis: '',
        harga: '',
        satuan: 'Kg',
        estimasi: '',
        status: true,
        kategori: 'reguler'
      };
      this.modalAddOpen = true;
    },
    async hapusLayanan(id) {
      try {
        if (!firebase || !firebase.firestore) {
          throw new Error('Firebase belum terinisialisasi');
        }
        await firebase.firestore().collection('layanan').doc(id).delete();
        await this.fetchData();
        this.successMessage = 'Layanan berhasil dihapus!';
        this.showSuccessAlert = true;
        setTimeout(() => {
          this.showSuccessAlert = false;
        }, 5000);
      } catch (err) {
        console.error('Error menghapus layanan:', err);
        alert('Gagal menghapus layanan: ' + err.message);
      }
    },
    async hapusLayananConfirm() {
      try {
        this.isLoadingDelete = true;
        if (!firebase || !firebase.firestore) {
          throw new Error('Firebase belum terinisialisasi');
        }
        await firebase.firestore().collection('layanan').doc(this.deleteData.id).delete();
        await this.fetchData();
        this.successMessage = 'Layanan berhasil dihapus!';
        this.showSuccessAlert = true;
        setTimeout(() => {
          this.showSuccessAlert = false;
        }, 5000);
      } catch (err) {
        console.error('Error menghapus layanan:', err);
        alert('Gagal menghapus layanan: ' + err.message);
      } finally {
        this.isLoadingDelete = false;
        this.modalDeleteOpen = false;
      }
    }
  }));
});
</script>
<?= $this->endSection() ?>