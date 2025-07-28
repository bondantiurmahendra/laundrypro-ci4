<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Akun Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="akunAdmin()" class="max-w-xl mx-auto py-8 space-y-8">
  <h1 class="text-xl font-bold text-on-surface dark:text-on-surface-dark mb-6">Profil Admin</h1>
  <div
    class="bg-surface-alt dark:bg-surface-dark-alt rounded-xl shadow p-6 flex flex-col items-center gap-4 border border-outline dark:border-outline-dark">
    <div
      class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-4xl text-primary font-bold">
      <template x-if="profile.photoURL">
        <img :src="profile.photoURL" alt="Foto Profil" class="w-full h-full object-cover rounded-full" />
      </template>
      <template x-if="!profile.photoURL">
        <span x-text="profile.name.charAt(0)"></span>
      </template>
    </div>
    <div class="text-center">
      <div class="text-lg font-semibold" x-text="profile.name"></div>
      <div class="text-sm text-gray-600 dark:text-gray-400" x-text="profile.email"></div>
      <div class="text-xs mt-1 px-2 py-0.5 rounded bg-blue-100 text-blue-700 inline-block">Admin</div>
    </div>
    <div class="flex gap-2 mt-4">
      <button @click="openEditModal"
        class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90 text-sm flex items-center gap-2">
        <i class="fas fa-pen"></i> Edit Profil
      </button>
      <button @click="logout"
        class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600 text-sm flex items-center gap-2">
        <i class="fas fa-sign-out-alt"></i> Logout
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
          <label class="block text-sm mb-1">Nama</label>
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

  <!-- Modal Konfirmasi Logout -->
  <div x-show="modalLogout" x-cloak x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div
      class="bg-surface dark:bg-surface-dark p-6 rounded-radius w-full max-w-sm border border-outline dark:border-outline-dark text-center">
      <h2 class="text-lg font-semibold mb-4">Konfirmasi Logout</h2>
      <p class="mb-6">Yakin ingin logout dari akun admin?</p>
      <div class="flex justify-center gap-3">
        <button @click="modalLogout = false" class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-700">Batal</button>
        <button @click="confirmLogout" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600">Logout</button>
      </div>
    </div>
  </div>
</div>

<script>
function akunAdmin() {
  return {
    profile: {
      name: '-',
      email: '-',
      photoURL: '',
      role: '-',
    },
    modalEdit: false,
    modalLogout: false,
    isLoadingUpdate: false,
    editForm: {
      name: '',
      email: '',
    },
    async fetchProfile() {
      try {
        let email = null;
        let tries = 0;
        // Polling up to 10x (2 detik total)
        while (tries < 10) {
          const storeUser = window.Alpine && Alpine.store && Alpine.store('user') ? Alpine.store('user') : null;
          console.log('Alpine store user:', storeUser);
          if (storeUser && storeUser.email) {
            email = storeUser.email;
            break;
          }
          if (firebase && firebase.auth && firebase.auth().currentUser && firebase.auth().currentUser.email) {
            email = firebase.auth().currentUser.email;
            break;
          }
          // Tunggu 200ms lalu cek lagi
          await new Promise(r => setTimeout(r, 200));
          tries++;
        }
        if (!email) {
          alert('Anda belum login!');
          return;
        }
        // Ambil data user dari Firestore berdasarkan email
        const q = await firebase.firestore().collection('users').where('email', '==', email).limit(1).get();
        if (!q.empty) {
          const data = q.docs[0].data();
          this.profile = {
            name: data.name || '-',
            email: data.email || '-',
            photoURL: data.photoURL || '',
            role: data.role || '-',
          };
        } else {
          alert('Data admin tidak ditemukan di database!');
        }
      } catch (err) {
        alert('Gagal mengambil data profil: ' + err.message);
      }
    },
    openEditModal() {
      this.editForm.name = this.profile.name;
      this.editForm.email = this.profile.email;
      this.modalEdit = true;
    },
    saveProfile() {
      if (!this.profile.email) {
        alert('Email tidak ditemukan!');
        return;
      }
      const email = this.profile.email;
      this.isLoadingUpdate = true;
      firebase.firestore().collection('users').where('email', '==', email).limit(1).get()
        .then(q => {
          if (!q.empty) {
            const docId = q.docs[0].id;
            return firebase.firestore().collection('users').doc(docId).update({
              name: this.editForm.name,
              email: this.editForm.email
            });
          } else {
            throw new Error('Data admin tidak ditemukan!');
          }
        })
        .then(() => {
          this.profile.name = this.editForm.name;
          this.profile.email = this.editForm.email;
          this.modalEdit = false;
          if (window.Alpine && Alpine.store && Alpine.store('user')) {
            Alpine.store('user').name = this.editForm.name;
            Alpine.store('user').email = this.editForm.email;
          }
        })
        .catch(err => {
          alert('Gagal update profil: ' + err.message);
        })
        .finally(() => {
          this.isLoadingUpdate = false;
        });
    },
    logout() {
      this.modalLogout = true;
    },
    confirmLogout() {
      if (firebase && firebase.auth) {
        firebase.auth().signOut().then(() => {
          // window.location.href = '/login';
        });
      } else {
        alert('Logout berhasil!');
      }
    },
    init() {
      this.fetchProfile();
    }
  }
}
</script>
<?= $this->endSection() ?>