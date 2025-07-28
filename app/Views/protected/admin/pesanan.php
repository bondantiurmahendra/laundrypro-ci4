<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Pesanan & Transaksi<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="pesananList()" class="space-y-6" x-init="init()">

  <!-- Filter Tabs / Select -->
  <div class="w-full">
    <!-- Mobile: Select -->
    <div class="md:hidden mb-3">
      <select x-model="selectedTab"
        class="w-full border border-outline rounded p-2 text-sm dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark">
        <template x-for="tab in tabs" :key="tab.key">
          <option :value="tab.key" x-text="tab.label"></option>
        </template>
      </select>
    </div>

    <!-- downloader -->
    <div class="flex justify-end gap-2">
      <button @click="exportCSV" class="px-3 py-1.5 text-sm rounded bg-blue-500 hover:bg-blue-600 text-white">
        <i class="fas fa-file-csv mr-1"></i> Export CSV
      </button>
      <button @click="exportXLSX" class="px-3 py-1.5 text-sm rounded bg-green-500 hover:bg-green-600 text-white">
        <i class="fas fa-file-excel mr-1"></i> Export Excel
      </button>
    </div>

    <!-- Desktop Tabs -->
    <div class="hidden md:flex gap-2 overflow-x-auto border-b border-outline dark:border-outline-dark" role="tablist">
      <template x-for="tab in tabs" :key="tab.key">
        <button type="button" class="flex items-center gap-2 px-4 py-2 text-sm h-min" role="tab"
          :aria-selected="selectedTab === tab.key" :tabindex="selectedTab === tab.key ? '0' : '-1'"
          :class="selectedTab === tab.key
            ? 'font-bold text-primary border-b-2 border-primary dark:border-primary-dark dark:text-primary-dark'
            : 'text-on-surface font-medium dark:text-on-surface-dark hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong dark:hover:border-b-outline-dark-strong'"
          x-on:click="selectedTab = tab.key">
          <i :class="tab.icon + ' fa-sm'"></i>
          <span x-text="tab.label"></span>
        </button>
      </template>
    </div>
  </div>

  <!-- Table atau Kosong -->
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
            name="search" placeholder="Cari pesanan..." aria-label="search" />
        </div>
        <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
          class="px-3 py-2 text-xs rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
          Clear
        </button>
      </div>
      <div x-show="searchQuery.length > 0" class="mt-2 text-xs text-gray-600 dark:text-gray-400">
        <span x-text="`Menampilkan ${filteredPesanan.length} dari ${pesanan.length} pesanan`"></span>
      </div>
    </div>

    <template x-if="filteredPesanan.length > 0">
      <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
        <thead
          class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
          <tr>
            <th class="p-4">#</th>
            <th class="p-4">Nama</th>
            <th class="p-4">Layanan</th>
            <th class="p-4">Total</th>
            <th class="p-4">Status</th>
            <th class="p-4">Bayar</th>
            <th class="p-4">Tgl Bayar</th>
            <th class="p-4">Masuk</th>
            <th class="p-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
          <template x-for="(row, i) in filteredPesanan" :key="row.id">
            <tr>
              <td class="p-4" x-text="i + 1"></td>
              <td class="p-4" x-text="row.nama"></td>
              <td class="p-4" x-text="row.layanan"></td>
              <td class="p-4" x-text="formatRupiah(row.total)"></td>
              <td class="p-4">
                <span class="inline-block text-xs px-2 py-1 rounded-full font-medium text-white"
                  :class="badgeClass(row.status)" x-text="row.status"></span>
              </td>
              <td class="p-4">
                <span class="inline-block text-xs px-2 py-1 rounded-full font-medium text-white"
                  :class="badgeBayar(row.status_bayar)" x-text="row.status_bayar"></span>
              </td>
              <td class="p-4" x-text="row.tgl_bayar ? formatTanggal(row.tgl_bayar) : '-'"></td>
              <td class="p-4" x-text="formatTanggal(row.created_at)"></td>
              <td class="p-4">
                <button @click="showUpdateModal(row)"
                  class="px-3 py-1.5 text-sm rounded bg-primary text-white hover:bg-primary/90 flex items-center gap-2">
                  <i class="fas fa-edit fa-sm"></i>
                  Update
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </template>

    <template x-if="filteredPesanan.length === 0">
      <div class="flex flex-col items-center justify-center text-center p-10 gap-4 text-muted dark:text-muted-dark">
        <i class="fas fa-inbox fa-3x opacity-30"></i>
        <p class="text-sm">Tidak ada pesanan untuk kategori ini.</p>
      </div>
    </template>
  </div>

  <!-- Modal Update Status -->
  <div x-show="showStatusModal" x-cloak x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-2xl border border-outline dark:border-outline-dark">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">Detail & Update Pesanan</h2>
        <button @click="showStatusModal = false" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
      </div>

      <template x-if="selectedOrder">
        <div class="space-y-6">
          <!-- Detail Pesanan -->
          <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <h3 class="font-semibold mb-3 text-on-surface dark:text-on-surface-dark">Detail Pesanan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Nama:</span>
                <span class="ml-2" x-text="selectedOrder.nama"></span>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Layanan:</span>
                <span class="ml-2" x-text="selectedOrder.layanan"></span>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Kategori:</span>
                <span class="ml-2" x-text="selectedOrder.kategori"></span>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Jumlah:</span>
                <span class="ml-2" x-text="selectedOrder.jumlah"></span>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Total:</span>
                <span class="ml-2" x-text="formatRupiah(selectedOrder.total)"></span>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Tipe:</span>
                <span class="ml-2" x-text="selectedOrder.tipe"></span>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Metode Bayar:</span>
                <span class="ml-2" x-text="selectedOrder.metode_bayar"></span>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Status Bayar:</span>
                <span class="ml-2" x-text="selectedOrder.status_bayar"></span>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Tanggal Masuk:</span>
                <span class="ml-2" x-text="formatTanggal(selectedOrder.created_at)"></span>
              </div>
              <template x-if="selectedOrder.tipe === 'jemput'">
                <div class="md:col-span-2">
                  <span class="font-medium text-gray-600 dark:text-gray-400">Alamat Penjemputan:</span>
                  <span class="ml-2" x-text="selectedOrder.alamat"></span>
                </div>
              </template>
              <template x-if="selectedOrder.catatan">
                <div class="md:col-span-2">
                  <span class="font-medium text-gray-600 dark:text-gray-400">Catatan:</span>
                  <span class="ml-2" x-text="selectedOrder.catatan"></span>
                </div>
              </template>
            </div>
          </div>

          <!-- Form Update -->
          <div class="border-t pt-4">
            <h3 class="font-semibold mb-3 text-on-surface dark:text-on-surface-dark">Update Pesanan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm mb-2">Status Pesanan:</label>
                <select x-model="newStatus"
                  class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
                  <option value="">Pilih status...</option>
                  <option value="Dijemput">Dijemput</option>
                  <option value="Diterima">Diterima</option>
                  <option value="Dicuci">Dicuci</option>
                  <option value="Dikeringkan">Dikeringkan</option>
                  <option value="Disetrika">Disetrika</option>
                  <option value="Dilipat">Dilipat</option>
                  <option value="Diantar">Diantar</option>
                  <option value="Selesai">Selesai</option>
                </select>
              </div>
              <div>
                <label class="block text-sm mb-2">Status Pembayaran:</label>
                <select x-model="newStatusBayar"
                  class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
                  <option value="">Pilih status...</option>
                  <option value="Belum Bayar">Belum Bayar</option>
                  <option value="Pending">Pending</option>
                  <option value="Lunas">Lunas</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-between items-center pt-4 border-t">
            <button @click="hapus(selectedOrder.id)"
              class="px-4 py-2 text-sm rounded bg-red-500 text-white hover:bg-red-600 flex items-center gap-2">
              <i class="fas fa-trash fa-sm"></i>
              Hapus Pesanan
            </button>
            <div class="flex gap-2">
              <button @click="showStatusModal = false"
                class="px-4 py-2 text-sm rounded bg-gray-300 dark:bg-gray-700">Batal</button>
              <button @click="confirmUpdateOrder()" :disabled="!newStatus && !newStatusBayar"
                class="px-4 py-2 text-sm rounded bg-primary text-white hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                <i class="fas fa-save fa-sm"></i>
                Simpan Perubahan
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function pesananList() {
  return {
    selectedTab: 'Semua',
    searchQuery: '',
    tabs: [{
        key: 'Semua',
        label: 'Semua',
        icon: 'fas fa-list'
      },
      {
        key: 'Dijemput',
        label: 'Dijemput',
        icon: 'fas fa-truck'
      },
      {
        key: 'Diterima',
        label: 'Diterima',
        icon: 'fas fa-inbox'
      },
      {
        key: 'Dicuci',
        label: 'Dicuci',
        icon: 'fas fa-water'
      },
      {
        key: 'Dikeringkan',
        label: 'Dikeringkan',
        icon: 'fas fa-wind'
      },
      {
        key: 'Disetrika',
        label: 'Disetrika',
        icon: 'fas fa-fire'
      },
      {
        key: 'Dilipat',
        label: 'Dilipat',
        icon: 'fas fa-layer-group'
      },
      {
        key: 'Diantar',
        label: 'Diantar',
        icon: 'fas fa-motorcycle'
      },
      {
        key: 'Selesai',
        label: 'Selesai',
        icon: 'fas fa-check-circle'
      }
    ],
    pesanan: [],
    loading: true,
    showStatusModal: false,
    selectedOrder: null,
    newStatus: '',
    newStatusBayar: '',

    async fetchPesanan() {
      try {
        this.loading = true;
        const snapshot = await firebase.firestore()
          .collection('orders')
          .orderBy('created_at', 'desc')
          .get();

        this.pesanan = snapshot.docs.map(doc => ({
          id: doc.id,
          ...doc.data()
        }));
      } catch (error) {
        console.error('Error fetching orders:', error);
        alert('Gagal memuat data pesanan: ' + error.message);
      } finally {
        this.loading = false;
      }
    },

    async hapus(id) {
      if (!confirm('Yakin ingin menghapus pesanan ini?')) {
        return;
      }

      try {
        await firebase.firestore().collection('orders').doc(id).delete();
        this.pesanan = this.pesanan.filter(p => p.id !== id);
        alert('Pesanan berhasil dihapus!');
      } catch (error) {
        console.error('Error deleting order:', error);
        alert('Gagal menghapus pesanan: ' + error.message);
      }
    },

    async updateStatus(id, newStatus) {
      try {
        await firebase.firestore().collection('orders').doc(id).update({
          status: newStatus,
          updated_at: firebase.firestore.FieldValue.serverTimestamp()
        });

        // Update local data
        const index = this.pesanan.findIndex(p => p.id === id);
        if (index !== -1) {
          this.pesanan[index].status = newStatus;
        }

        alert('Status berhasil diupdate!');
      } catch (error) {
        console.error('Error updating status:', error);
        alert('Gagal update status: ' + error.message);
      }
    },

    init() {
      this.fetchPesanan();
    },

    showUpdateModal(order) {
      this.selectedOrder = order;
      this.newStatus = '';
      this.newStatusBayar = '';
      this.showStatusModal = true;
    },

    async confirmUpdateOrder() {
      if (!this.newStatus && !this.newStatusBayar) {
        alert('Pilih minimal satu status untuk diupdate!');
        return;
      }

      try {
        const updateData = {};

        if (this.newStatus) {
          updateData.status = this.newStatus;
        }

        if (this.newStatusBayar) {
          updateData.status_bayar = this.newStatusBayar;
          if (this.newStatusBayar === 'Lunas') {
            updateData.tgl_bayar = new Date().toISOString();
          }
        }

        updateData.updated_at = firebase.firestore.FieldValue.serverTimestamp();

        await firebase.firestore().collection('orders').doc(this.selectedOrder.id).update(updateData);

        // Update local data
        const index = this.pesanan.findIndex(p => p.id === this.selectedOrder.id);
        if (index !== -1) {
          if (this.newStatus) this.pesanan[index].status = this.newStatus;
          if (this.newStatusBayar) this.pesanan[index].status_bayar = this.newStatusBayar;
          if (this.newStatusBayar === 'Lunas') this.pesanan[index].tgl_bayar = new Date().toISOString();
        }

        this.showStatusModal = false;
        this.selectedOrder = null;
        this.newStatus = '';
        this.newStatusBayar = '';

        alert('Pesanan berhasil diupdate!');
      } catch (error) {
        console.error('Error updating order:', error);
        alert('Gagal mengupdate pesanan: ' + error.message);
      }
    },

    get filteredPesanan() {
      let data = this.pesanan;
      if (this.selectedTab !== 'Semua') {
        data = data.filter(p => p.status === this.selectedTab);
      }
      if (this.searchQuery && this.searchQuery.trim() !== '') {
        const q = this.searchQuery.trim().toLowerCase();
        data = data.filter(p => {
          return (
            (p.nama && p.nama.toLowerCase().includes(q)) ||
            (p.layanan && p.layanan.toLowerCase().includes(q)) ||
            (p.status && p.status.toLowerCase().includes(q)) ||
            (p.status_bayar && p.status_bayar.toLowerCase().includes(q)) ||
            (p.metode_bayar && p.metode_bayar.toLowerCase().includes(q)) ||
            (typeof p.total !== 'undefined' && String(p.total).toLowerCase().includes(q)) ||
            (p.tgl_bayar && p.tgl_bayar.toLowerCase().includes(q)) ||
            (p.created_at && p.created_at.toLowerCase().includes(q))
          );
        });
      }
      return data;
    },
    formatRupiah(n) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
      }).format(n).replace(",00", "");
    },
    formatTanggal(firestoreTimestampOrIsoString, forSearch = false) {
      if (!firestoreTimestampOrIsoString) {
        return forSearch ? '' : '-'; // Return empty string for search if null/undefined
      }

      let dateObj;
      // Cek apakah itu objek Timestamp dari Firebase Firestore
      if (typeof firestoreTimestampOrIsoString.toDate === 'function') {
        dateObj = firestoreTimestampOrIsoString.toDate();
      } else if (typeof firestoreTimestampOrIsoString === 'string') {
        // Jika itu string (dari data lama atau ekspor toISOString())
        dateObj = new Date(firestoreTimestampOrIsoString);
      } else {
        return forSearch ? '' : 'Invalid Date Type';
      }

      if (isNaN(dateObj.getTime())) {
        return forSearch ? '' : 'Invalid Date'; // Tangani jika parsing gagal
      }

      // Format sesuai kebutuhan tampilan
      return dateObj.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      });
    },
    badgeClass(status) {
      return {
        'Dijemput': 'bg-blue-400 dark:bg-blue-700',
        'Diterima': 'bg-sky-400 dark:bg-sky-700',
        'Dicuci': 'bg-indigo-400 dark:bg-indigo-700',
        'Dikeringkan': 'bg-yellow-400 dark:bg-yellow-700',
        'Disetrika': 'bg-orange-400 dark:bg-orange-700',
        'Dilipat': 'bg-pink-400 dark:bg-pink-700',
        'Diantar': 'bg-purple-400 dark:bg-purple-700',
        'Selesai': 'bg-green-400 dark:bg-green-700',
      } [status] || 'bg-gray-400 dark:bg-gray-700';
    },
    badgeBayar(status) {
      return {
        'Lunas': 'bg-green-500 dark:bg-green-700',
        'Belum Bayar': 'bg-red-500 dark:bg-red-700',
        'Pending': 'bg-yellow-400 dark:bg-yellow-700',
      } [status] || 'bg-gray-400 dark:bg-gray-700';
    },
    exportCSV() {
      const rows = this.pesanan.map(p => ({
        ID: p.id,
        Nama: p.nama,
        Layanan: p.layanan,
        Total: p.total,
        Status: p.status,
        Status_Bayar: p.status_bayar,
        Metode: p.metode_bayar,
        Tgl_Bayar: p.tgl_bayar,
        Tanggal_Masuk: p.created_at
      }));
      const csv = [
        Object.keys(rows[0]).join(','),
        ...rows.map(row => Object.values(row).join(','))
      ].join('\n');

      const blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8;'
      });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.setAttribute('download', 'pesanan.csv');
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    exportXLSX() {
      const rows = this.pesanan.map(p => ({
        ID: p.id,
        Nama: p.nama,
        Layanan: p.layanan,
        Total: p.total,
        Status: p.status,
        Status_Bayar: p.status_bayar,
        Metode: p.metode_bayar,
        Tgl_Bayar: p.tgl_bayar,
        Tanggal_Masuk: p.created_at
      }));
      const worksheet = XLSX.utils.json_to_sheet(rows);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, "Pesanan");
      XLSX.writeFile(workbook, "pesanan.xlsx");
    }
  }
}
</script>
<?= $this->endSection() ?>