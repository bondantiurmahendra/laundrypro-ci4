<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $this->renderSection('title') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ===== Page Wrapper Start ===== -->
<div x-data="{ sidebarLeftOpen: false, sidebarRightOpen: false }" class="relative flex w-full flex-col md:flex-row">
  <!-- This allows screen readers to skip the sidebar and go directly to the main content. -->
  <a class="sr-only" href="#main-content">skip to the main content</a>

  <!-- SIDEBAR KANAN -->
  <nav x-cloak x-show="sidebarRightOpen" x-trap="sidebarRightOpen" x-on:click.outside="sidebarRightOpen = false"
    x-on:keydown.esc.window="sidebarRightOpen = false"
    class="fixed right-0 z-20 flex h-svh w-80 shrink-0 flex-col border-l border-outline bg-surface-alt p-4 transition-transform duration-300 dark:border-outline-dark dark:bg-surface-dark-alt"
    aria-label="shopping cart" x-transition:enter="transition duration-200 ease-out"
    x-transition:enter-end="translate-x-0" x-transition:enter-start=" translate-x-80"
    x-transition:leave="transition ease-in duration-200 " x-transition:leave-end="translate-x-80"
    x-transition:leave-start="translate-x-0">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-medium text-on-surface-strong dark:text-on-surface-dark-strong">Pengaturan Tema</h3>
      <button class="text-on-surface dark:text-on-surface-dark" x-on:click="sidebarRightOpen = false">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5"
          class="size-6" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
        <span class="sr-only">close sidebar</span>
      </button>
    </div>

    <div class="hide-scrollbar flex flex-col gap-2 overflow-y-auto py-4 h-full">
      <?= view('components/btn-theme') ?>
    </div>
  </nav>
  <!-- SIDEBAR KANAN -->

  <!-- dark overlay for when the sidebar is open on smaller screens  -->
  <div x-cloak x-show="sidebarLeftOpen" class="fixed inset-0 z-20 bg-surface-dark/10 backdrop-blur-xs md:hidden"
    aria-hidden="true" x-on:click="sidebarLeftOpen = false" x-transition.opacity></div>

  <!-- SIDEBAR KIRI -->
  <nav x-cloak
    class="fixed left-0 z-30 flex h-svh w-60 shrink-0 flex-col border-r border-outline bg-surface-alt p-4 transition-transform duration-300 md:w-64 md:translate-x-0 md:relative dark:border-outline-dark dark:bg-surface-dark-alt"
    x-bind:class="sidebarLeftOpen ? 'translate-x-0' : '-translate-x-60'" aria-label="sidebar navigation">

    <?= view('icons/logo') ?>

    <!-- sidebar links  -->
    <div x-data="sidebarMenu()" class="flex flex-col gap-2 overflow-y-auto pb-6">
      <template x-for="item in menu" :key="item.href">
        <a :href="item.href" :class="[
         isActive(item.href)
           ? 'bg-primary/10 text-on-surface-strong dark:bg-primary-dark/10 dark:text-on-surface-dark-strong'
           : 'hover:bg-primary/5 hover:text-on-surface-strong dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong text-on-surface dark:text-on-surface-dark',
         'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium underline-offset-2 focus-visible:underline focus:outline-hidden'
       ]">
          <div class="w-10 flex items-center justify-center p-1">
            <i :class="item.icon + ' fa-xl fill-amber-400'"></i>
          </div>
          <span x-text="item.label"></span>
        </a>
      </template>


      <button @click="() => {
      sidebarRightOpen = true
      sidebarLeftOpen = !sidebarRightOpen
    }"
        class="hover:bg-primary/5 hover:text-on-surface-strong dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong text-on-surface dark:text-on-surface-dark flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium underline-offset-2 focus-visible:underline focus:outline-hidden">
        <div class="w-10 flex items-center justify-center p-1">
          <i class="fa-solid fa-palette fa-xl"></i>
        </div>
        <span>Tema</span>
      </button>
    </div>
  </nav>
  <!-- SIDEBAR KIRI -->

  <!-- top navbar & main content  -->
  <div class="h-svh w-full overflow-y-auto bg-surface dark:bg-surface-dark">
    <!-- top navbar  -->
    <nav
      class="sticky top-0 z-10 flex items-center justify-between border-b border-outline bg-surface-alt px-4 py-2 dark:border-outline-dark dark:bg-surface-dark-alt"
      aria-label="top navibation bar">

      <!-- BUTTON SIDEBAR HP UKURAN KECIL -->
      <button type="button" class="md:hidden inline-block text-on-surface dark:text-on-surface-dark"
        x-on:click="sidebarLeftOpen = true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-5"
          aria-hidden="true">
          <path
            d="M0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5-1v12h9a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1zM4 2H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h2z" />
        </svg>
        <span class="sr-only">sidebar toggle</span>
      </button>

      <!-- breadcrumbs  -->
      <nav x-data="sidebarMenu()"
        class="hidden md:inline-block text-sm font-medium text-on-surface dark:text-on-surface-dark"
        aria-label="breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
          <li class="flex items-center gap-1">
            <a href="/admin" class="hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none"
              stroke-width="2" class="size-4" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
          </li>

          <template x-if="breadcrumb && breadcrumb.href !== '/admin'">
            <li class="flex items-center gap-1 font-bold text-on-surface-strong dark:text-on-surface-dark-strong"
              aria-current="page" x-text="breadcrumb.title"></li>
          </template>
        </ol>
      </nav>




      <!-- Profile Menu  -->
      <div x-show="$store.user.isReady" x-data="{ userDropdownIsOpen: false }" class="relative"
        x-on:keydown.esc.window="userDropdownIsOpen = false">
        <button type="button"
          class="flex w-full items-center rounded-radius gap-2 p-2 text-left text-on-surface hover:bg-primary/5 hover:text-on-surface-strong focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark"
          x-bind:class="userDropdownIsOpen ? 'bg-primary/10 dark:bg-primary-dark/10' : ''" aria-haspopup="true"
          x-on:click="userDropdownIsOpen = ! userDropdownIsOpen" x-bind:aria-expanded="userDropdownIsOpen">
          <img :src="$store.user.avatar" class="size-8 object-cover rounded-radius" alt="avatar" aria-hidden="true" />
          <div class="hidden md:flex flex-col">
            <span x-text="$store.user.name"
              class="text-sm font-bold text-on-surface-strong dark:text-on-surface-dark-strong"></span>
            <span class="text-xs" aria-hidden="true">Admin</span>
            <span class="sr-only">profile settings</span>
          </div>
        </button>

        <!-- menu -->
        <div x-cloak x-show="userDropdownIsOpen"
          class="absolute top-14 right-0 z-20 h-fit w-48 border divide-y divide-outline border-outline bg-surface dark:divide-outline-dark dark:border-outline-dark dark:bg-surface-dark rounded-radius"
          role="menu" x-on:click.outside="userDropdownIsOpen = false" x-on:keydown.down.prevent="$focus.wrap().next()"
          x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition="" x-trap="userDropdownIsOpen">

          <?= view('components/btn-signout') ?>
        </div>
      </div>
    </nav>
    <!-- main content  -->
    <div id="main-content" class="p-4">
      <div class="overflow-y-auto">
        <?= $this->renderSection('content') ?>
        <!-- Add main content here  -->
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.store('user', {
    uid: null,
    name: null,
    email: null,
    role: null,
    avatar: null,
    isReady: false,
    set(data) {
      this.uid = data.uid
      this.name = data.name
      this.email = data.email
      this.role = data.role
      this.avatar = data.avatar
      this.isReady = true
    }
  })
})
</script>
<script>
firebase.auth().onAuthStateChanged(async function(user) {
  if (user) {
    const db = firebase.firestore();
    const userRef = db.collection('users').doc(user.uid);
    const doc = await userRef.get();
    if (doc.exists) {
      const userData = doc.data();
      Alpine.store('user').set({
        uid: user.uid,
        name: userData.name || '-',
        email: userData.email || '-',
        role: userData.role || '-',
        avatar: userData.avatar || null
      });
    }
  } else {
    // optionally reset store on logout
    Alpine.store('user').set({
      uid: null,
      name: null,
      email: null,
      role: null,
      avatar: null
    });
  }
});
</script>
<script>
function sidebarMenu() {
  const menu = [{
      label: "Dashboard",
      title: "Halaman Utama",
      href: "/admin",
      icon: "fas fa-tachometer-alt"
    },
    {
      label: "Pesanan",
      title: "Data Pesanan",
      href: "/admin/pesanan",
      icon: "fas fa-clipboard-list"
    },
    {
      label: "Layanan",
      title: "Jenis Layanan",
      href: "/admin/layanan",
      icon: "fas fa-concierge-bell"
    },
    {
      label: "Pelanggan",
      title: "Data Pelanggan",
      href: "/admin/pelanggan",
      icon: "fas fa-users"
    },
    {
      label: "Akun",
      title: "Kelola Akun",
      href: "/admin/akun",
      icon: "fa-solid fa-user-tie"
    }
  ];

  const current = window.location.pathname;

  function isActive(href) {
    return current === href
  }

  function getBreadcrumb() {
    return menu.find(m => current === m.href) || null;
  }

  return {
    menu,
    current,
    isActive,
    get breadcrumb() {
      return getBreadcrumb();
    }
  };
}
</script>


<!-- ===== Page Wrapper End ===== -->
<?= $this->endSection() ?>