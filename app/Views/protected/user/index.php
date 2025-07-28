<?= $this->extend('layouts/user') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="dashboardUser()" class="max-w-2xl mx-auto py-8" x-init="init()">
  <h1 class="text-2xl font-bold mb-2">Halo, <span x-text="$store.user.name || 'User'">User</span>!</h1>
  <p class="text-on-surface/70 mb-6">Selamat datang di dashboard Laundry Online.</p>

  <div class="bg-surface-alt dark:bg-surface-dark-alt rounded-radius p-4 mb-6 shadow">
    <h2 class="font-semibold mb-2 text-lg">Status Pesanan Terakhir</h2>
    
    <!-- Jika ada pesanan terakhir -->
    <template x-if="lastOrder">
      <div class="flex items-center gap-3">
        <i class="fas fa-tshirt text-primary text-2xl"></i>
        <div>
          <div class="font-medium" x-text="lastOrder.layanan"></div>
          <div class="text-sm text-on-surface/60">Status: <span class="font-semibold text-primary" x-text="lastOrder.status"></span></div>
          <div class="text-xs text-on-surface/40" x-text="'Pesanan #' + lastOrder.id.substring(0, 6) + ', ' + formatTanggal(lastOrder.created_at)"></div>
        </div>
      </div>
    </template>

    <!-- Jika tidak ada pesanan -->
    <template x-if="!lastOrder && !loading">
      <div class="flex items-center gap-3 text-on-surface/60">
        <i class="fas fa-inbox text-2xl"></i>
        <div>
          <div class="font-medium">Belum ada pesanan</div>
          <div class="text-sm">Buat pesanan pertama Anda sekarang!</div>
        </div>
      </div>
    </template>

    <!-- Loading pesanan -->
    <template x-if="loading">
      <div class="flex items-center gap-3 text-on-surface/60">
        <i class="fas fa-spinner fa-spin text-2xl"></i>
        <div>
          <div class="font-medium">Memuat pesanan...</div>
        </div>
      </div>
    </template>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <a href="/user/order" class="flex flex-col items-center justify-center bg-primary/10 hover:bg-primary/20 rounded-radius p-4 transition">
      <i class="fas fa-clipboard-list text-2xl mb-2 text-primary"></i>
      <span class="font-medium">Lihat Pesanan</span>
    </a>
    <a href="/user/akun" class="flex flex-col items-center justify-center bg-primary/10 hover:bg-primary/20 rounded-radius p-4 transition">
      <i class="fa-solid fa-user-tie text-2xl mb-2 text-primary"></i>
      <span class="font-medium">Akun Saya</span>
    </a>
  </div>
</div>

<script>
function dashboardUser() {
  return {
    loading: true,
    pesanan: [],
    lastOrder: null,

    async init() {
      while (!this.$store.user.isReady) {
        await new Promise(resolve => setTimeout(resolve, 100));
      }
      
      await this.fetchPesanan();
      this.loading = false;
    },

    async fetchPesanan() {
      if (!this.$store.user.uid) return;
      
      try {
        const snapshot = await firebase.firestore()
          .collection('orders')
          .where('user_id', '==', this.$store.user.uid)
          .orderBy('created_at', 'desc')
          .get();
        
        this.pesanan = snapshot.docs.map(doc => ({
          id: doc.id,
          ...doc.data()
        }));

        // Set pesanan terakhir (yang paling baru)
        this.lastOrder = this.pesanan.length > 0 ? this.pesanan[0] : null;
      } catch (err) {
        console.error('Gagal memuat pesanan:', err);
      }
    },

    formatTanggal(timestamp) {
        if (!timestamp) return 'N/A';
        let date;
        if (typeof timestamp.toDate === 'function') {
            date = timestamp.toDate();
        } else if (typeof timestamp === 'string') {
            date = new Date(timestamp);
        } else {
            return 'N/A';
        }
        if (isNaN(date.getTime())) {
            return 'Invalid Date';
        }
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    },

  }
}
</script>
<?= $this->endSection() ?>
