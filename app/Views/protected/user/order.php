<?= $this->extend('layouts/user') ?>

<?= $this->section('title') ?>Pesanan Saya<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="pesananUser()" class="space-y-6" x-init="init()">
  <h1 class="text-2xl font-bold mb-4">Daftar Pesanan</h1>

  <div class="flex justify-end mb-4">
    <button @click="modalOpen = true"
      class="px-4 py-2 rounded bg-primary text-white font-semibold hover:bg-primary/90 flex items-center gap-2">
      <i class="fas fa-plus"></i>
      Buat Pesanan
    </button>
  </div>

  <!-- Modal Buat Pesanan -->
  <div x-show="modalOpen" x-cloak x-transition.opacity x-on:keydown.escape.window="modalOpen = false"
    class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-md border border-outline dark:border-outline-dark">
      <h2 class="text-lg font-semibold mb-4 text-on-surface dark:text-on-surface-dark">Buat Pesanan Baru</h2>
      <form @submit.prevent="submitPesanan">
        <div class="space-y-4">
          <div>
            <label class="block text-sm mb-1">Kategori Layanan</label>
            <select x-model="form.kategori"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
              <option value="" disabled>Pilih kategori...</option>
              <template x-for="kat in kategoriList" :key="kat.key">
                <option :value="kat.key" x-text="kat.label"></option>
              </template>
            </select>
          </div>
          <div>
            <label class="block text-sm mb-1">Layanan</label>
            <select x-model="form.layanan" :disabled="!form.kategori"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
              <option value="" disabled>Pilih layanan...</option>
              <template x-for="item in layananAktif.filter(l => l.kategori === form.kategori)" :key="item.id">
                <option :value="item.id"
                  x-text="item.nama + ' - ' + item.jenis + ' (' + formatHarga(item.harga, item.satuan) + ')'"></option>
              </template>
            </select>
          </div>
          <div>
            <label class="block text-sm mb-1">Jumlah/Berat</label>
            <input type="number" min="1" x-model.number="form.jumlah"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark"
              placeholder="Masukkan jumlah atau berat (misal: 3)">
          </div>
          <div>
            <label class="block text-sm mb-1">Pilih Antar/Jemput</label>
            <select x-model="form.tipe"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
              <option value="" disabled>Pilih tipe...</option>
              <option value="antar">Antar</option>
              <option value="jemput">Jemput</option>
            </select>
          </div>
          <div>
            <label class="block text-sm mb-1">Metode Pembayaran</label>
            <select x-model="form.metode_bayar"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
              <option value="" disabled>Pilih metode pembayaran...</option>
              <option value="Cash">Cash</option>
              <option value="Online">Online</option>
            </select>
          </div>
          <template x-if="form.tipe === 'jemput'">
            <div>
              <label class="block text-sm mb-1">Alamat Penjemputan</label>
              <button type="button" @click="getLocation"
                class="px-3 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 flex items-center gap-1 mb-2"
                :disabled="lokasiLoading">
                <i class="fas fa-location-crosshairs"></i>
                <span x-show="!lokasiLoading">Gunakan Lokasi Saya</span>
                <span x-show="lokasiLoading">Loading...</span>
              </button>
              <template x-if="form.alamat">
                <div class="text-xs text-green-600 mb-1">Lokasi: <span x-text="form.alamat"></span></div>
              </template>
              <template x-if="lokasiError">
                <div class="text-xs text-red-500 mb-1" x-text="lokasiError"></div>
              </template>
              <input type="text" x-model="form.catatan_alamat"
                class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark mt-1"
                placeholder="Catatan alamat, misal: di samping rumah ini...">
            </div>
          </template>
          <div>
            <label class="block text-sm mb-1">Catatan</label>
            <textarea x-model="form.catatan"
              class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark"
              placeholder="Catatan tambahan (opsional)"></textarea>
          </div>
        </div>
        <div class="flex justify-end gap-2 mt-6">
          <button type="button" @click="modalOpen = false"
            class="px-3 py-1.5 text-sm rounded bg-gray-300 dark:bg-gray-700">Batal</button>
          <button type="submit"
            class="px-3 py-1.5 text-sm rounded bg-primary text-white hover:bg-primary/90 flex items-center gap-2"
            :disabled="loadingSubmit">
            <span x-show="loadingSubmit"><i class='fas fa-spinner fa-spin'></i></span>
            <span x-show="!loadingSubmit">Simpan</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Filter Tabs / Select -->
  <div class="w-full">
    <!-- Mobile: Select -->
    <div class="md:hidden mb-3">
      <select x-model="selectedTab"
        class="w-full border border-outline rounded p-2 text-sm dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark">
        <option value="Semua">Semua</option>
        <template x-for="tab in dynamicTabs" :key="tab">
          <option :value="tab" x-text="tab"></option>
        </template>
      </select>
    </div>
    <!-- Desktop Tabs -->
    <div class="hidden md:flex gap-2 overflow-x-auto border-b border-outline dark:border-outline-dark" role="tablist">
      <button type="button" class="flex items-center gap-2 px-4 py-2 text-sm h-min" role="tab"
        :aria-selected="selectedTab === 'Semua'" :tabindex="selectedTab === 'Semua' ? '0' : '-1'"
        :class="selectedTab === 'Semua'
          ? 'font-bold text-primary border-b-2 border-primary dark:border-primary-dark dark:text-primary-dark'
          : 'text-on-surface font-medium dark:text-on-surface-dark hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong dark:hover:border-b-outline-dark-strong'"
        x-on:click="selectedTab = 'Semua'">
        <span>Semua</span>
      </button>
      <template x-for="tab in dynamicTabs" :key="tab">
        <button type="button" class="flex items-center gap-2 px-4 py-2 text-sm h-min" role="tab"
          :aria-selected="selectedTab === tab" :tabindex="selectedTab === tab ? '0' : '-1'"
          :class="selectedTab === tab
            ? 'font-bold text-primary border-b-2 border-primary dark:border-primary-dark dark:text-primary-dark'
            : 'text-on-surface font-medium dark:text-on-surface-dark hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong dark:hover:border-b-outline-dark-strong'"
          x-on:click="selectedTab = tab">
          <span x-text="tab"></span>
        </button>
      </template>
    </div>
  </div>

  <!-- Search Bar -->
  <div class="p-4 border border-outline rounded-xl dark:border-outline-dark bg-surface-alt dark:bg-surface-dark-alt">
    <div class="flex items-center gap-2 mb-2">
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

  <!-- Table atau Kosong -->
  <div class="overflow-x-auto border border-outline rounded-xl dark:border-outline-dark mt-4">
    <template x-if="filteredPesanan.length > 0">
      <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
        <thead
          class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
          <tr>
            <th class="p-4">#</th>
            <th class="p-4">Tanggal</th>
            <th class="p-4">Layanan</th>
            <th class="p-4">Status</th>
            <th class="p-4">Metode Bayar</th>
            <th class="p-4">Status Bayar</th>
            <th class="p-4">Total</th>
            <th class="p-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
          <template x-for="(row, i) in filteredPesanan" :key="row.id">
            <tr>
              <td class="p-4" x-text="i + 1"></td>
              <td class="p-4" x-text="formatTanggal(row.created_at)"></td>
              <td class="p-4" x-text="row.layanan"></td>
              <td class="p-4">
                <span class="inline-block text-xs px-2 py-1 rounded-full font-medium text-white"
                  :class="badgeClass(row.status)" x-text="row.status"></span>
              </td>
              <td class="p-4" x-text="row.metode_bayar"></td>
              <td class="p-4" x-text="row.status_bayar"></td>
              <td class="p-4" x-text="formatRupiah(row.total)"></td>
              <td class="p-4">
                <div class="flex gap-2">
                  <button @click="showDetail(row)"
                    class="px-2 py-1 text-xs rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">Detail</button>
                  <template x-if="row.metode_bayar === 'Online' && row.status_bayar === 'Belum Bayar'">
                    <button @click="bayarPesanan(row)"
                      class="px-2 py-1 text-xs rounded bg-primary text-white hover:bg-primary/90">Bayar</button>
                  </template>
                </div>
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

  <template x-if="alertBox">
    <div class="relative w-full overflow-hidden rounded-sm border"
      :class="'border-' + alertBox.color + '-500' + ' bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark'"
      role="alert">
      <div class="flex w-full items-center gap-2" :class="'bg-' + alertBox.color + '-500/10 p-4'">
        <div :class="'bg-' + alertBox.color + '-500/15 text-' + alertBox.color + '-500 rounded-full p-1'"
          aria-hidden="true" x-html="alertBox.icon"></div>
        <div class="ml-2">
          <h3 class="text-sm font-semibold"
            :class="'text-' + (alertBox.color === 'gray' ? 'on-surface' : alertBox.color)" x-text="alertBox.title"></h3>
          <p class="text-xs font-medium sm:text-sm" x-text="alertBox.message"></p>
        </div>
        <button class="ml-auto" aria-label="dismiss alert" @click="alertBox = null">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
            fill="none" stroke-width="2.5" class="size-4 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </template>

  <div x-show="modalDetail" x-cloak x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-md border border-outline dark:border-outline-dark relative">
      <button @click="modalDetail = false"
        class="absolute top-2 right-2 text-xl text-gray-500 hover:text-gray-700">&times;</button>
      <h2 class="text-lg font-semibold mb-4 text-on-surface dark:text-on-surface-dark">Detail Pesanan</h2>
      <template x-if="detailPesanan">
        <div class="space-y-2 text-sm">
          <div><span class="font-semibold">Layanan:</span> <span x-text="detailPesanan.layanan"></span></div>
          <div><span class="font-semibold">Status:</span> <span x-text="detailPesanan.status"></span></div>
          <div><span class="font-semibold">Metode Bayar:</span> <span x-text="detailPesanan.metode_bayar"></span></div>
          <div><span class="font-semibold">Status Bayar:</span> <span x-text="detailPesanan.status_bayar"></span></div>
          <div><span class="font-semibold">Total:</span> <span x-text="formatRupiah(detailPesanan.total)"></span></div>
          <div><span class="font-semibold">Tanggal:</span> <span
              x-text="formatTanggal(detailPesanan.created_at)"></span></div>
          <div><span class="font-semibold">Tipe:</span> <span x-text="detailPesanan.tipe"></span></div>
          <template x-if="detailPesanan.tipe === 'jemput'">
            <div><span class="font-semibold">Alamat Penjemputan:</span> <span x-text="detailPesanan.alamat"></span>
            </div>
          </template>
          <div><span class="font-semibold">Catatan:</span> <span x-text="detailPesanan.catatan"></span></div>
          <template x-if="detailPesanan.idpembayaran">
            <div><span class="font-semibold">ID Pembayaran:</span> <span x-text="detailPesanan.idpembayaran"></span>
            </div>
          </template>
        </div>
      </template>
      <div class="flex justify-end mt-6">
        <button @click="modalDetail = false"
          class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-9T416znFL7X9DEX9"></script>
<script src="/js/utils/emails.js"></script>
<script>
function pesananUser() {
  return {
    selectedTab: 'Semua',
    searchQuery: '',
    pesanan: [],
    loadingSubmit: false,
    modalOpen: false,
    layananAktif: [],
    form: {
      kategori: '',
      layanan: '',
      jumlah: 1,
      catatan: '',
      metode_bayar: '',
      alamat: '',
      catatan_alamat: '',
      tipe: ''
    },
    lokasiLoading: false,
    lokasiError: '',
    alertBox: null,
    modalDetail: false,
    detailPesanan: null,
    getLocation() {
      this.lokasiError = '';
      this.lokasiLoading = true;
      if (!navigator.geolocation) {
        this.lokasiError = 'Geolocation tidak didukung browser.';
        this.lokasiLoading = false;
        return;
      }
      navigator.geolocation.getCurrentPosition(
        async (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            this.form.alamat = `Lat: ${lat}, Lng: ${lng}`;
            this.lokasiLoading = false;
          },
          (err) => {
            this.lokasiError = 'Gagal mengambil lokasi: ' + err.message;
            this.lokasiLoading = false;
          }, {
            enableHighAccuracy: true,
            timeout: 10000
          }
      );
    },
    get kategoriList() {
      const kategoriSet = new Set(this.layananAktif.map(l => l.kategori));
      return Array.from(kategoriSet).map(k => ({
        key: k,
        label: k.charAt(0).toUpperCase() + k.slice(1).replace(/_/g, ' ')
      }));
    },
    get dynamicTabs() {
      // Ambil status unik dari data pesanan
      const statusSet = new Set(this.pesanan.map(p => p.status).filter(Boolean));
      return Array.from(statusSet).sort();
    },
    async fetchLayanan() {
      try {
        if (!firebase || !firebase.firestore) {
          throw new Error('Firebase belum terinisialisasi');
        }
        const snapshot = await firebase.firestore().collection('layanan').orderBy('created_at', 'desc').get();
        this.layananAktif = snapshot.docs
          .map(doc => ({
            id: doc.id,
            ...doc.data()
          }))
          .filter(l => l.status === true);
      } catch (err) {
        console.error('Gagal memuat data layanan:', err);
        alert('Gagal memuat data layanan: ' + err.message);
      }
    },
    async fetchPesanan() {
      const user = firebase.auth().currentUser;
      if (!user) return;
      this.pesanan = [];
      try {
        const snapshot = await firebase.firestore()
          .collection('orders')
          .where('user_id', '==', user.uid)
          .orderBy('created_at', 'desc')
          .get();
        this.pesanan = snapshot.docs.map(doc => ({
          id: doc.id,
          ...doc.data()
        }));
      } catch (err) {
        console.log('Gagal memuat pesanan: ' + err.message);
      }
    },
    async init() {
      await this.fetchLayanan();
      await this.fetchPesanan();
    },
    get filteredPesanan() {
      let data = this.pesanan;
      if (this.selectedTab !== 'Semua') {
        data = data.filter(p => p.status === this.selectedTab);
      }
      if (this.searchQuery && this.searchQuery.trim() !== '') {
        const q = this.searchQuery.trim().toLowerCase();
        data = data.filter(p =>
          (p.layanan && p.layanan.toLowerCase().includes(q)) ||
          (p.status && p.status.toLowerCase().includes(q)) ||
          (typeof p.total !== 'undefined' && String(p.total).toLowerCase().includes(q)) ||
          (p.created_at && p.created_at.toLowerCase().includes(q))
        );
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
    formatHarga(nominal, satuan) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
      }).format(nominal).replace(",00", "") + '/' + satuan;
    },
    badgeClass(status) {
      return {
        'Sedang Diproses': 'bg-blue-500 dark:bg-blue-700',
        'Selesai': 'bg-green-500 dark:bg-green-700',
      } [status] || 'bg-gray-400 dark:bg-gray-700';
    },
    async submitPesanan() {
      // Validasi sederhana
      if (!this.form.kategori || !this.form.layanan || !this.form.jumlah || !this.form.tipe || !this.form
        .metode_bayar) {
        alert('Mohon lengkapi semua field yang wajib!');
        return;
      }
      if (this.form.tipe === 'jemput' && !this.form.alamat) {
        alert('Alamat penjemputan wajib diisi untuk tipe jemput!');
        return;
      }
      this.loadingSubmit = true;
      const user = firebase.auth().currentUser;
      const nama = user ? user.displayName || user.email || 'User' : 'User';
      const user_id = user ? user.uid : null;
      const status = this.form.tipe === 'antar' ? 'Diterima' : 'Dijemput';
      const layananObj = this.layananAktif.find(l => l.id === this.form.layanan) || {};
      const dataPesanan = {
        user_id: user_id,
        nama: nama,
        layanan: layananObj.nama || '',
        kategori: this.form.kategori,
        jumlah: this.form.jumlah,
        catatan: this.form.catatan,
        tipe: this.form.tipe,
        alamat: this.form.tipe === 'jemput' ? this.form.alamat : '',
        catatan_alamat: this.form.tipe === 'jemput' ? this.form.catatan_alamat : '',
        metode_bayar: this.form.metode_bayar,
        status: status,
        status_bayar: 'Belum Bayar',
        total: (layananObj.harga || 0) * this.form.jumlah,
        created_at: firebase.firestore.FieldValue.serverTimestamp(),
        tgl_bayar: null
      };
      firebase.firestore().collection('orders').add(dataPesanan)
        .then((docRef) => {
          email.createdPesanan(dataPesanan)
          // fetch('/send-email', {
          //   method: 'POST',
          //   headers: {
          //     'Content-Type': 'application/json'
          //   },
          //   body: JSON.stringify({
          //     to: 'jackkolor69@gmail.com',
          //     subject: 'Pesanan Baru Masuk',
          //     view: 'emails/OrderCreated',
          //     viewData: dataPesanan
          //   })
          // });
          alert('Pesanan berhasil dibuat!');
          this.modalOpen = false;
          this.form = {
            kategori: '',
            layanan: '',
            jumlah: 1,
            catatan: '',
            metode_bayar: '',
            alamat: '',
            catatan_alamat: '',
            tipe: ''
          };
          this.fetchPesanan();
        })
        .catch(err => {
          alert('Gagal menyimpan pesanan: ' + err.message);
        })
        .finally(() => {
          this.loadingSubmit = false;
        });
    },
    showDetail(row) {
      this.detailPesanan = row;
      this.modalDetail = true;
    },
    bayarPesanan(row) {
      // Panggil endpoint backend untuk dapatkan snapToken
      fetch('/checkout/token', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            order_id: row.id,
            total: row.total,
            nama: row.nama
          })
        })
        .then(res => res.json())
        .then(data => {
          snap.pay(data.snapToken, {
            onSuccess: (result) => {
              firebase.firestore().collection('orders').doc(row.id).update({
                status_bayar: 'Lunas',
                idpembayaran: result.transaction_id
              }).then(() => {
                this.fetchPesanan();
                this.showAlert('success', 'Pembayaran Berhasil', 'Pembayaran Anda telah berhasil!');
              });
            },
            onPending: (result) => {
              this.showAlert('pending', 'Menunggu Pembayaran', 'Transaksi Anda sedang menunggu pembayaran.');
            },
            onError: (result) => {
              this.showAlert('error', 'Pembayaran Gagal', 'Terjadi kesalahan saat proses pembayaran.');
            },
            onClose: () => {
              this.showAlert('close', 'Pembayaran Dibatalkan',
                'Anda menutup popup tanpa menyelesaikan pembayaran.');
            }
          });
        });
    },
    showAlert(type, title, message) {
      let color = 'green';
      let icon =
        `<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" class=\"size-6\" aria-hidden=\"true\"><path fill-rule=\"evenodd\" d=\"M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z\" clip-rule=\"evenodd\"/></svg>`;
      if (type === 'pending') color = 'yellow';
      if (type === 'error') color = 'red';
      if (type === 'close') color = 'gray';
      this.alertBox = {
        color,
        icon,
        title,
        message
      };
      setTimeout(() => {
        this.alertBox = null;
      }, 7000);
    }
  }
}
</script>
<?= $this->endSection() ?>