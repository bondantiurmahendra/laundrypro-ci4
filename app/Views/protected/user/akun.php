<?= $this->extend('layouts/user') ?>

<?= $this->section('title') ?>Akun<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="accountPage()" class="max-w-xl mx-auto py-8 space-y-8">
  <h1 class="text-xl font-bold text-on-surface dark:text-on-surface-dark mb-6">Profil User</h1>

  <!-- Profile Card -->
  <div
    class="bg-surface-alt dark:bg-surface-dark-alt rounded-xl shadow p-6 flex flex-col items-center gap-4 border border-outline dark:border-outline-dark">
    <div
      class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-4xl text-primary font-bold">
      <template x-if="profile.avatar">
        <img :src="profile.avatar" alt="Foto Profil" class="w-full h-full object-cover rounded-full" />
      </template>
      <template x-if="!profile.avatar">
        <span x-text="profile.name.charAt(0)"></span>
      </template>
    </div>

    <div class="text-center">
      <div class="text-lg font-semibold" x-text="profile.name"></div>
      <div class="text-sm text-gray-600 dark:text-gray-400" x-text="profile.email"></div>
      <div class="text-xs mt-1 px-2 py-0.5 rounded bg-blue-100 text-blue-700 inline-block">User</div>
    </div>

    <!-- User Details -->
    <div class="w-full mt-4 space-y-3">
      <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Nomor Telepon:</span>
        <span class="text-sm" x-text="profile.telepon"></span>
      </div>
      <div class="flex justify-between items-start py-2 border-b border-gray-200 dark:border-gray-700">
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Alamat:</span>
        <span class="text-sm text-right" x-text="profile.address"></span>
      </div>
    </div>

    <div class="flex gap-2 mt-4">
      <button @click="openEditModal"
        class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90 text-sm flex items-center gap-2">
        <i class="fas fa-pen"></i> Edit Profil
      </button>
    </div>
  </div>

  <!-- Modal Edit Profil -->
  <div x-show="modalEdit" x-cloak x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-md border border-outline dark:border-outline-dark">
      <h2 class="text-lg font-semibold mb-4">Edit Profil</h2>
      <form @submit.prevent="saveProfile">
        <div class="mb-4">
          <label class="block text-sm mb-1">Nama Lengkap</label>
          <input type="text" x-model="editForm.name"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark"
            required>
        </div>
        <div class="mb-4">
          <label class="block text-sm mb-1">Email</label>
          <input type="email" x-model="editForm.email"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark"
            required>
        </div>
        <div class="mb-4">
          <label class="block text-sm mb-1">Nomor Telepon</label>
          <input type="tel" x-model="editForm.telepon"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark">
        </div>
        <div class="mb-4">
          <label class="block text-sm mb-1">Alamat</label>
          <textarea x-model="editForm.address" rows="3"
            class="w-full border border-outline rounded p-2 dark:bg-surface-dark dark:border-outline-dark dark:text-on-surface-dark"></textarea>
        </div>
        <div class="flex justify-end gap-2 mt-6">
          <button type="button" @click="modalEdit = false"
            class="px-3 py-1.5 text-sm rounded bg-gray-300 dark:bg-gray-700">Batal</button>
          <button type="submit" :disabled="isLoadingUpdate"
            class="px-3 py-1.5 text-sm rounded bg-primary text-white hover:bg-primary/90 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg x-show="isLoadingUpdate" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
            <span x-text="isLoadingUpdate ? 'Menyimpan...' : 'Simpan'"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function accountPage() {
  return {
    profile: {
      name: '-',
      email: '-',
      telepon: '-',
      address: '-',
      avatar: ''
    },
    modalEdit: false,
    isLoadingUpdate: false,
    editForm: {
      name: '',
      email: '',
      telepon: '',
      address: ''
    },

    async fetchProfile() {
      try {
        let user = null;
        let tries = 0;

        // Polling up to 10x (2 detik total) untuk menunggu Firebase auth siap
        while (tries < 10) {
          user = firebase.auth().currentUser;
          if (user) {
            break;
          }
          // Tunggu 200ms lalu cek lagi
          await new Promise(r => setTimeout(r, 200));
          tries++;
        }

        if (!user) {
          console.log('User tidak ditemukan setelah polling');
          this.showNotification('Anda belum login!', 'error');
          return;
        }

        console.log('User found:', user.uid, user.email);

        const db = firebase.firestore();
        const userRef = db.collection('users').doc(user.uid);
        const doc = await userRef.get();

        if (doc.exists) {
          const data = doc.data();
          console.log('Data from Firestore:', data);

          this.profile = {
            name: data.name || user.displayName || 'User',
            email: data.email || user.email || 'user@example.com',
            telepon: data.telepon || 'Belum diisi',
            address: data.address || 'Belum diisi',
            avatar: data.avatar || ''
          };

          // Update Alpine store
          Alpine.store('user').set({
            uid: user.uid,
            name: this.profile.name,
            email: this.profile.email,
            role: data.role || 'user',
            avatar: this.profile.avatar
          });
        } else {
          console.log('Document tidak ada, membuat baru');

          // Create new user document if it doesn't exist
          const newUserData = {
            name: user.displayName || 'User',
            email: user.email || 'user@example.com',
            telepon: '',
            address: '',
            avatar: user.photoURL || '',
            role: 'user',
            createdAt: firebase.firestore.FieldValue.serverTimestamp(),
            updatedAt: firebase.firestore.FieldValue.serverTimestamp()
          };

          await userRef.set(newUserData);

          this.profile = {
            name: newUserData.name,
            email: newUserData.email,
            telepon: 'Belum diisi',
            address: 'Belum diisi',
            avatar: newUserData.avatar
          };
        }

        console.log('Profile updated:', this.profile);
      } catch (error) {
        console.error('Error loading user data:', error);
        this.showNotification('Gagal memuat data pengguna: ' + error.message, 'error');
      }
    },

    openEditModal() {
      this.editForm.name = this.profile.name;
      this.editForm.email = this.profile.email;
      this.editForm.telepon = this.profile.telepon === 'Belum diisi' ? '' : this.profile.telepon;
      this.editForm.address = this.profile.address === 'Belum diisi' ? '' : this.profile.address;
      this.modalEdit = true;
    },

    async saveProfile() {
      this.isLoadingUpdate = true;
      try {
        const user = firebase.auth().currentUser;
        if (!user) {
          this.showNotification('User tidak ditemukan', 'error');
          return;
        }

        const db = firebase.firestore();
        const userRef = db.collection('users').doc(user.uid);

        await userRef.update({
          name: this.editForm.name,
          email: this.editForm.email,
          telepon: this.editForm.telepon,
          address: this.editForm.address,
          updatedAt: firebase.firestore.FieldValue.serverTimestamp()
        });

        // Update profile display
        this.profile.name = this.editForm.name;
        this.profile.email = this.editForm.email;
        this.profile.telepon = this.editForm.telepon;
        this.profile.address = this.editForm.address;

        // Update Alpine store
        Alpine.store('user').set({
          ...Alpine.store('user'),
          name: this.profile.name,
          email: this.profile.email,
          avatar: this.profile.avatar
        });

        this.modalEdit = false;
        this.showNotification('Profil berhasil diperbarui', 'success');
      } catch (error) {
        console.error('Error saving profile:', error);
        this.showNotification('Gagal memperbarui profil', 'error');
      } finally {
        this.isLoadingUpdate = false;
      }
    },

    showNotification(message, type = 'info') {
      // Simple notification - you can replace with your preferred notification system
      const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
      };

      const notification = document.createElement('div');
      notification.className =
        `fixed top-4 right-4 z-50 px-6 py-3 rounded-radius text-white ${colors[type]} transition-all duration-300`;
      notification.textContent = message;

      document.body.appendChild(notification);

      setTimeout(() => {
        notification.remove();
      }, 3000);
    },

    init() {
      this.fetchProfile();
    }
  };
}
</script>
<?= $this->endSection() ?>