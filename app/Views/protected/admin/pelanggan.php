<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Pelanggan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="pelangganList()" class="space-y-6">
  <h1 class="text-xl font-bold text-on-surface dark:text-on-surface-dark">Daftar Pelanggan</h1>

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
          name="search" placeholder="Cari pelanggan..." aria-label="search" />
      </div>
      <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
        class="px-3 py-2 text-xs rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
        Clear
      </button>
    </div>
    <div x-show="searchQuery.length > 0" class="mt-2 text-xs text-gray-600 dark:text-gray-400">
      <span x-text="`Menampilkan ${filteredPelanggan.length} dari ${pelanggan.length} pelanggan`"></span>
    </div>
  </div>

  <!-- Table Pelanggan -->
  <div class="overflow-x-auto border border-outline rounded-xl dark:border-outline-dark mt-4">
    <template x-if="filteredPelanggan.length > 0">
      <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
        <thead
          class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
          <tr>
            <th class="p-4">#</th>
            <th class="p-4">Nama</th>
            <th class="p-4">Email</th>
            <th class="p-4">No. Telepon</th>
            <th class="p-4">Total Pesanan</th>
            <th class="p-4">Terdaftar</th>
            <th class="p-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
          <template x-for="(item, index) in filteredPelanggan" :key="item.id">
            <tr>
              <td class="p-4" x-text="index + 1"></td>
              <td class="p-4" x-text="item.name"></td>
              <td class="p-4" x-text="item.email"></td>
              <td class="p-4" x-text="item.telepon"></td>
              <td class="p-4" x-text="item.total_pesanan ?? 0 "></td>
              <td class="p-4" x-text="formatTanggal(item.createdAt)"></td>
              <td class="p-4 flex gap-2">
                <button @click="editPelanggan(item)" class="text-blue-600 hover:text-blue-800 p-2 rounded transition"
                  title="Edit">
                  <i class="fas fa-pen-to-square"></i>
                </button>
                <button @click="hapusPelanggan(item)" class="text-red-600 hover:text-red-800 p-2 rounded transition"
                  title="Hapus">
                  <i class="fas fa-trash"></i>
                </button>
                <button @click="openEmailModal(item)" class="text-green-600 hover:text-green-800 p-2 rounded transition"
                  title="Kirim Email">
                  <i class="fas fa-envelope"></i>
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </template>
    <template x-if="filteredPelanggan.length === 0">
      <div class="flex flex-col items-center justify-center text-center p-10 gap-4 text-muted dark:text-muted-dark">
        <i class="fas fa-inbox fa-3x opacity-30"></i>
        <p class="text-sm">Tidak ada pelanggan ditemukan.</p>
      </div>
    </template>
  </div>

  <!-- Modal Kirim Email -->
  <div x-show="modalEmailOpen" x-cloak x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-md border border-outline dark:border-outline-dark">
      <h2 class="text-lg font-semibold mb-4 text-on-surface dark:text-on-surface-dark">Kirim Email ke <span
          x-text="emailTarget.email"></span></h2>
      <form @submit.prevent="sendEmail">
        <div class="mb-4">
          <label class="block text-sm mb-1">Subject</label>
          <input type="text" x-model="emailForm.subject"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark"
            required>
        </div>
        <div class="mb-4">
          <label class="block text-sm mb-1">Pesan</label>
          <textarea x-model="emailForm.message"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark"
            rows="4" required></textarea>
        </div>
        <div class="flex justify-end gap-2 mt-6">
          <button type="button" @click="modalEmailOpen = false"
            class="px-3 py-1.5 text-sm rounded bg-gray-300 dark:bg-gray-700">Batal</button>
          <button type="submit" :disabled="isLoadingEmail"
            class="px-3 py-1.5 text-sm rounded bg-primary text-white hover:bg-primary/90 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg x-show="isLoadingEmail" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
            <span x-text="isLoadingEmail ? 'Mengirim...' : 'Kirim'"></span>
          </button>
        </div>
      </form>
      <div x-show="emailSuccess" class="mt-4 p-2 rounded bg-green-100 text-green-800">Email berhasil dikirim!</div>
      <div x-show="emailError" class="mt-4 p-2 rounded bg-red-100 text-red-800" x-text="emailErrorMsg"></div>
    </div>
  </div>
</div>

<script>
function pelangganList() {
  return {
    searchQuery: '',
    pelanggan: [],
    modalEmailOpen: false,
    emailTarget: {},
    emailForm: {
      subject: '',
      message: ''
    },
    isLoadingEmail: false,
    emailSuccess: false,
    emailError: false,
    emailErrorMsg: '',
    async fetchData() {
      try {
        if (!firebase || !firebase.firestore) {
          throw new Error('Firebase belum terinisialisasi');
        }
        const snapshot = await firebase.firestore().collection('users').get();
        this.pelanggan = snapshot.docs
          .map(doc => ({
            id: doc.id,
            ...doc.data()
          }))
          .filter(u => u.role === 'user')
          .map(u => ({
            id: u.id,
            name: u.name || '-',
            email: u.email || '-',
            telepon: u.telepon || u.no_telepon || '-',
            total_pesanan: u.total_pesanan ?? '-',
            createdAt: u.createdAt ?? u.tanggal_daftar ?? null
          }));
      } catch (err) {
        console.error('Gagal memuat data pelanggan:', err);
        alert('Gagal memuat data pelanggan: ' + err.message);
      }
    },
    get filteredPelanggan() {
      if (!this.searchQuery.trim()) return this.pelanggan;
      const q = this.searchQuery.trim().toLowerCase();
      return this.pelanggan.filter(p =>
        (p.name && p.name.toLowerCase().includes(q)) ||
        (p.email && p.email.toLowerCase().includes(q)) ||
        (p.telepon && p.telepon.toLowerCase().includes(q)) ||
        (typeof p.total_pesanan !== 'undefined' && String(p.total_pesanan).includes(q)) ||
        (p.createdAt && this.formatTanggal(p.createdAt).toLowerCase().includes(q))
      );
    },
    formatTanggal(t) {
      if (!t) return '-';
      const d = new Date(t.seconds ? t.seconds * 1000 : t); // Firestore Timestamp support
      return d.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      });
    },
    editPelanggan(item) {
      alert('Edit: ' + item.name);
    },
    hapusPelanggan(item) {
      alert('Hapus: ' + item.name);
    },
    openEmailModal(item) {
      this.emailTarget = item;
      this.emailForm = {
        subject: '',
        message: ''
      };
      this.modalEmailOpen = true;
      this.emailSuccess = false;
      this.emailError = false;
      this.emailErrorMsg = '';
    },
    async sendEmail() {
      this.isLoadingEmail = true;
      this.emailSuccess = false;
      this.emailError = false;
      this.emailErrorMsg = '';
      try {
        const res = await fetch('/send-email', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            to: this.emailTarget.email,
            subject: this.emailForm.subject,
            viewData: {
              customerName: this.emailTarget.name,
              customMessage: this.emailForm.message,
              subject: this.emailForm.subject,
            },
            view: 'emails/CustomMessage'
          })
        });
        const data = await res.json();
        if (res.ok && data.status === 'success') {
          this.emailSuccess = true;
          setTimeout(() => {
            this.modalEmailOpen = false;
          }, 1500);
        } else {
          this.emailError = true;
          this.emailErrorMsg = data.message || 'Gagal mengirim email.';
        }
      } catch (err) {
        this.emailError = true;
        this.emailErrorMsg = err.message;
      } finally {
        this.isLoadingEmail = false;
      }
    },
    init() {
      this.fetchData();
    }
  }
}
</script>
<?= $this->endSection() ?>