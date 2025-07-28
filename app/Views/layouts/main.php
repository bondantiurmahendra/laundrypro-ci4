<!DOCTYPE html>
<html lang="en" :data-theme="theme" :class="{ 'dark': isDark }" data-theme="arctic" class="dark">

<head>
  <meta charset=" UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->renderSection('title') ?> - Pro Laundry</title>
  <link href="<?= base_url('css/o.css') ?>" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Firebase SDK -->
  <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>

  <!-- Alpine Plugins -->
  <script defer src="https://unpkg.com/@alpinejs/mask@3.14.1/dist/cdn.min.js"></script>
  <script defer src="https://unpkg.com/@alpinejs/focus@3.14.1/dist/cdn.min.js"></script>
  <script defer src="https://unpkg.com/@alpinejs/collapse@3.14.1/dist/cdn.min.js"></script>

  <!-- Alpine Persist -->
  <script defer src="https://unpkg.com/@alpinejs/persist@3.14.1/dist/cdn.min.js"></script>

  <!-- Alpine Core -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

  <!-- Inisialisasi Alpine Store untuk loader dan darkMode -->
  <script>
  // console.log(Alpine)
  document.addEventListener('alpine:init', () => {
    window.Alpine = Alpine
    Alpine.store('app', {
      loaded: false,
      darkMode: true
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    // Sekarang Alpine sudah pasti tersedia
    console.log("Alpine ready:", Alpine);
  });
  </script>

  <!-- Firebase Config -->
  <script>
  const firebaseConfig = {
    apiKey: "<?= getenv('firebase.apiKey') ?>",
    authDomain: "<?= getenv('firebase.authDomain') ?>",
    projectId: "<?= getenv('firebase.projectId') ?>",
    storageBucket: "<?= getenv('firebase.storageBucket') ?>",
    messagingSenderId: "<?= getenv('firebase.messagingSenderId') ?>",
    appId: "<?= getenv('firebase.appId') ?>"
  };
  firebase.initializeApp(firebaseConfig);
  </script>
</head>

<body x-data="themeSwitcher()" x-init="initTheme()">
  <div x-show="!$store.app.loaded"
    class="fixed top-0 left-0 z-999999 flex h-screen w-screen items-center justify-center text-on-surface dark:text-on-surface-dark bg-surface-alt dark:bg-surface-dark-alt">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
      class="size-20 fill-on-surface motion-safe:animate-spin dark:fill-on-surface-dark">
      <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
      <path
        d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" />
    </svg>

  </div>
  <div x-show="$store.app.loaded">
    <?= $this->renderSection('content') ?>
  </div>
  <script>
  const pathname = window.location.pathname
  document.addEventListener('alpine:init', () => {
    Alpine.store('app').loaded = false;

    firebase.auth().onAuthStateChanged(async function(user) {
      const pathname = window.location.pathname;

      if (user) {
        const db = firebase.firestore();
        const userRef = db.collection('users').doc(user.uid);
        const doc = await userRef.get();
        const {
          role
        } = doc.data()
        if (role == "user" && !pathname.replace("/", "").includes(role)) {
          return window.location.href = '/' + role
        }
        if (role == "admin" && !pathname.replace("/", "").includes(role)) {
          return window.location.href = '/' + role
        }
      } else {
        if (pathname != "/") return window.location.href = "/"
      }
      Alpine.store('app').loaded = true;
    });
  });
  </script>

  <script>
  function themeSwitcher() {
    return {
      isDark: false,
      theme: 'arctic',

      initTheme() {
        const savedTheme = localStorage.getItem('theme')
        const savedMode = localStorage.getItem('dark')

        if (savedTheme) this.theme = savedTheme
        if (savedMode) this.isDark = savedMode === 'true'

        document.documentElement.setAttribute('data-theme', this.theme)
        document.documentElement.classList.toggle('dark', this.isDark)
        window.activeMode = this.isDark
      },

      setTheme(newTheme) {
        this.theme = newTheme
        document.documentElement.setAttribute('data-theme', newTheme)
        localStorage.setItem('theme', newTheme)
      },

      toggleDark() {
        this.isDark = !this.isDark
        document.documentElement.classList.toggle('dark', this.isDark)
        localStorage.setItem('dark', this.isDark)
        window.activeMode = this.isDark
      }
    }
  }
  </script>
  <script>
  window.themeList = [{
      name: 'arctic',
      label: 'Arctic',
      font: 'font-inter',
      rounded: 'rounded-lg',
      preview: ['bg-blue-700', 'bg-indigo-700'],
    },
    {
      name: 'high-contrast',
      label: 'High Contrast',
      font: 'font-inter',
      rounded: 'rounded-sm',
      preview: ['bg-sky-900', 'bg-indigo-900'],
    },
    {
      name: 'minimal',
      label: 'Minimal',
      font: 'font-montserrat',
      rounded: 'rounded-none',
      preview: ['bg-black', 'bg-neutral-800'],
    },
    {
      name: 'modern',
      label: 'Modern',
      font: 'font-lato',
      rounded: 'rounded-sm',
      preview: ['bg-black', 'bg-neutral-800'],
    },
    {
      name: 'punk',
      label: 'Neo Brutalism',
      font: 'font-space-mono',
      rounded: 'rounded-none',
      preview: ['bg-violet-500', 'bg-lime-400'],
    },
    {
      name: 'halloween',
      label: 'Halloween',
      font: 'font-poppins',
      rounded: 'rounded-xl',
      preview: ['bg-orange-400', 'bg-purple-600'],
    },
    {
      name: 'pastel',
      label: 'Pastel',
      font: 'font-playpen-sans',
      rounded: 'rounded-xl',
      preview: ['bg-rose-400', 'bg-orange-200'],
    },
    {
      name: 'christmas',
      label: 'Christmas',
      font: 'font-lato',
      rounded: 'rounded-md',
      preview: ['bg-red-600', 'bg-emerald-700'],
    },
    {
      name: 'news',
      label: 'News',
      font: 'font-inter',
      rounded: 'rounded-sm',
      preview: ['bg-sky-700', 'bg-black'],
    },
    {
      name: 'industrial',
      label: 'Industrial',
      font: 'font-poppins',
      rounded: 'rounded-none',
      preview: ['bg-amber-500', 'bg-stone-900'],
    }
  ];
  </script>
</body>

</html>