<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="adminDashboard()" x-init="init()" class="space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-on-surface dark:text-on-surface-dark">Selamat datang di Dashboard Admin</h1>
        <p class="text-sm text-muted dark:text-muted-dark">Pantau aktivitas laundry Anda di sini.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <template x-for="item in stats" :key="item.label">
            <article
                class="group grid rounded-radius max-w-2xl overflow-hidden border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
                <div class="flex items-center justify-between w-full p-6">
                    <div class="flex flex-col justify-center w-full">
                        <p class="text-sm mb-2 text-muted" x-text="item.label"></p>
                        <h2 class="text-2xl font-bold" x-text="item.value"></h2>
                    </div>
                    <i :class="item.icon + ' fa-2x ' + item.color"></i>
                </div>
            </article>
        </template>
        <template x-if="loadingStats">
            <div class="col-span-full text-center p-4">
                <i class="fas fa-spinner fa-spin text-2xl text-primary"></i>
                <p class="ml-2">Memuat statistik...</p>
            </div>
        </template>
    </div>

    <div class="bg-surface rounded-xl dark:bg-surface-dark p-6 shadow">
        <h3 class="text-lg font-semibold mb-2 text-on-surface dark:text-on-surface-dark">Grafik Pesanan & Pendapatan 7 Hari Terakhir</h3>
        <div class="h-64 w-full rounded flex items-center justify-center text-muted border border-outline dark:border-outline-dark">
            <div x-show="loadingChart" class="flex items-center justify-center w-full h-full">
                <i class="fas fa-spinner fa-spin text-2xl"></i>
                <p class="ml-2">Memuat grafik...</p>
            </div>
            <canvas id="ordersChart" style="width: 100%; height: 100%;" x-show="!loadingChart"></canvas>
        </div>
    </div>

    <div class="bg-surface rounded-xl dark:bg-surface-dark p-6 shadow">
        <h3 class="text-lg font-semibold mb-2 text-on-surface dark:text-on-surface-dark">Transaksi terakhir</h3>
        <div x-show="loadingTransactions" class="flex items-center justify-center text-muted py-4">
            <i class="fas fa-spinner fa-spin text-2xl"></i>
            <p class="ml-2">Memuat transaksi...</p>
        </div>
        <div x-show="!loadingTransactions && latestTransactions.length > 0" class="border border-outline rounded-radius overflow-x-auto dark:border-outline-dark">
            <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                <thead
                    class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                    <tr>
                        <th scope="col" class="p-4">ID Pesanan</th>
                        <th scope="col" class="p-4">Nama Pelanggan</th>
                        <th scope="col" class="p-4">Layanan</th>
                        <th scope="col" class="p-4">Status</th>
                        <th scope="col" class="p-4">Bayar</th>
                        <th scope="col" class="p-4">Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    <template x-for="transaction in latestTransactions" :key="transaction.id">
                        <tr>
                            <td class="p-4" x-text="transaction.id.substring(0, 6)"></td>
                            <td class="p-4" x-text="transaction.nama"></td>
                            <td class="p-4" x-text="transaction.layanan"></td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold text-white"
                                    :class="badgeClass(transaction.status)" x-text="transaction.status"></span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold text-white"
                                    :class="badgeBayar(transaction.status_bayar)" x-text="transaction.status_bayar"></span>
                            </td>
                            <td class="p-4" x-text="formatTanggal(transaction.created_at)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div x-show="!loadingTransactions && latestTransactions.length === 0" class="text-center text-muted py-4">
            Tidak ada transaksi terbaru.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function adminDashboard() {
        return {
            loadingStats: true,
            loadingChart: true,
            loadingTransactions: true,
            stats: [{
                label: "Pesanan Hari Ini",
                value: 0,
                icon: "fas fa-clipboard-list",
                color: "text-primary"
            }, {
                label: "Dalam Proses",
                value: 0,
                icon: "fas fa-sync-alt",
                color: "text-yellow-500"
            }, {
                label: "Total Pelanggan",
                value: 0,
                icon: "fas fa-users",
                color: "text-blue-500"
            }, {
                label: "Pendapatan Hari Ini",
                value: "Rp 0",
                icon: "fas fa-money-bill-wave",
                color: "text-green-600"
            }],
            latestTransactions: [],
            ordersChart: null, // Initialized to null

            async init() {
                // Inisialisasi Chart.js di sini, hanya sekali
                this.initChart(); // Buat instance chart awal (kosong)

                // Kemudian ambil data untuk semua bagian dashboard secara paralel
                await Promise.all([
                    this.fetchStats(),
                    this.fetchChartData(), // Ini akan mengisi data dan update chart
                    this.fetchLatestTransactions()
                ]);
            },

            async fetchStats() {
                this.loadingStats = true;
                try {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Set to start of today local time
                    const startOfTodayTimestamp = firebase.firestore.Timestamp.fromDate(today);

                    const tomorrow = new Date(today);
                    tomorrow.setDate(today.getDate() + 1);
                    tomorrow.setHours(0, 0, 0, 0);
                    const endOfTodayTimestamp = firebase.firestore.Timestamp.fromDate(tomorrow);

                    console.log('Querying orders created from (Timestamp):', startOfTodayTimestamp.toDate());
                    console.log('Querying orders created until (Timestamp - exclusive):', endOfTodayTimestamp.toDate());

                    // Pesanan Hari Ini
                    const todayOrdersSnapshot = await firebase.firestore().collection('orders')
                        .where('created_at', '>=', startOfTodayTimestamp)
                        .where('created_at', '<', endOfTodayTimestamp)
                        .get();
                    this.stats[0].value = todayOrdersSnapshot.size;
                    console.log('Pesanan Hari Ini fetched:', todayOrdersSnapshot.size);

                    // Dalam Proses
                    const processingStatuses = ['Dijemput', 'Diterima', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Dilipat', 'Diantar'];
                    const processingOrdersSnapshot = await firebase.firestore().collection('orders')
                        .where('status', 'in', processingStatuses)
                        .get();
                    this.stats[1].value = processingOrdersSnapshot.size;
                    console.log('Pesanan Dalam Proses fetched:', processingOrdersSnapshot.size);

                    // Total Pelanggan
                    const totalUsersSnapshot = await firebase.firestore().collection('users').get();
                    this.stats[2].value = totalUsersSnapshot.size;
                    console.log('Total Pelanggan fetched:', totalUsersSnapshot.size);

                    // Pendapatan Hari Ini (hanya yang LUNAS dan dibuat hari ini)
                    let totalRevenueToday = 0;
                    const paidTodayOrdersSnapshot = await firebase.firestore().collection('orders')
                        .where('created_at', '>=', startOfTodayTimestamp)
                        .where('created_at', '<', endOfTodayTimestamp)
                        .where('status_bayar', '==', 'Lunas')
                        .get();

                    paidTodayOrdersSnapshot.forEach(doc => {
                        const order = doc.data();
                        totalRevenueToday += order.total || 0;
                    });
                    this.stats[3].value = "Rp " + totalRevenueToday.toLocaleString('id-ID');
                    console.log('Pendapatan Hari Ini fetched:', totalRevenueToday);

                } catch (error) {
                    console.error("Error fetching stats:", error);
                } finally {
                    this.loadingStats = false;
                }
            },

            async fetchChartData() {
                // loadingChart akan diatur setelah data diambil dan chart diupdate
                try {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Set to start of today local time

                    const dates = [];
                    const ordersByDate = {};
                    const revenueByDate = {};

                    for (let i = 6; i >= 0; i--) { // Loop mundur dari 6 hari lalu sampai hari ini
                        const d = new Date(today);
                        d.setDate(today.getDate() - i);
                        d.setHours(0, 0, 0, 0);
                        const dateString = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                        dates.push(dateString);
                        ordersByDate[dateString] = 0;
                        revenueByDate[dateString] = 0;
                    }

                    const periodStart = new Date(today);
                    periodStart.setDate(today.getDate() - 6);
                    periodStart.setHours(0, 0, 0, 0);
                    const startOfPeriodTimestamp = firebase.firestore.Timestamp.fromDate(periodStart);

                    const periodEnd = new Date(today);
                    periodEnd.setDate(today.getDate() + 1); // Until start of tomorrow
                    periodEnd.setHours(0, 0, 0, 0);
                    const endOfPeriodTimestamp = firebase.firestore.Timestamp.fromDate(periodEnd);

                    console.log('Querying chart data from (Timestamp):', startOfPeriodTimestamp.toDate());
                    console.log('Querying chart data until (Timestamp - exclusive):', endOfPeriodTimestamp.toDate());

                    const ordersSnapshot = await firebase.firestore().collection('orders')
                        .where('created_at', '>=', startOfPeriodTimestamp)
                        .where('created_at', '<', endOfPeriodTimestamp)
                        .orderBy('created_at', 'asc')
                        .get();

                    ordersSnapshot.forEach(doc => {
                        const order = doc.data();
                        let orderDate;
                        if (order.created_at && typeof order.created_at.toDate === 'function') {
                            orderDate = order.created_at.toDate();
                        } else {
                            console.warn("created_at is not a Firestore Timestamp, skipping:", order.created_at);
                            return;
                        }

                        const dateString = orderDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });

                        if (ordersByDate[dateString] !== undefined) {
                            ordersByDate[dateString]++;
                            if (order.status_bayar === 'Lunas') {
                                revenueByDate[dateString] += order.total || 0;
                            }
                        }
                    });

                    // Pastikan ordersChart sudah diinisialisasi
                    if (this.ordersChart) {
                        this.ordersChart.data.labels = dates;
                        this.ordersChart.data.datasets[0].data = Object.values(ordersByDate);
                        this.ordersChart.data.datasets[1].data = Object.values(revenueByDate);
                        this.ordersChart.update();
                    } else {
                        console.error("Chart instance not found when trying to update data.");
                    }

                } catch (error) {
                    console.error("Error fetching chart data:", error);
                } finally {
                    this.loadingChart = false; // Setelah data berhasil diambil dan chart diupdate
                }
            },

            async fetchLatestTransactions() {
                this.loadingTransactions = true;
                try {
                    const snapshot = await firebase.firestore().collection('orders')
                        .orderBy('created_at', 'desc')
                        .limit(5)
                        .get();

                    this.latestTransactions = snapshot.docs.map(doc => ({
                        id: doc.id,
                        ...doc.data(),
                        nama: doc.data().nama || 'N/A',
                        layanan: doc.data().layanan || 'N/A',
                        total: doc.data().total || 0,
                        status: doc.data().status || 'N/A',
                        status_bayar: doc.data().status_bayar || 'N/A',
                        tgl_bayar: doc.data().tgl_bayar,
                        created_at: doc.data().created_at
                    }));
                } catch (error) {
                    console.error("Error fetching latest transactions:", error);
                } finally {
                    this.loadingTransactions = false;
                }
            },

            initChart() {
                const ctx = document.getElementById('ordersChart').getContext('2d');

                this.ordersChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [], // Dimulai dengan label kosong
                        datasets: [{
                            label: 'Jumlah Pesanan',
                            data: [], // Dimulai dengan data kosong
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            yAxisID: 'y'
                        }, {
                            label: 'Pendapatan',
                            data: [], // Dimulai dengan data kosong
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.2)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#fff',
                            yAxisID: 'y1'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#64748b',
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y;
                                        if (context.dataset.yAxisID === 'y1') {
                                            return label + ': Rp ' + value.toLocaleString('id-ID');
                                        }
                                        return label + ': ' + value;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: '#64748b'
                                },
                                grid: {
                                    color: '#e5e7eb'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                position: 'left',
                                ticks: {
                                    color: '#64748b'
                                },
                                grid: {
                                    color: '#e5e7eb'
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah Pesanan',
                                    color: '#64748b',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                ticks: {
                                    color: '#64748b',
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'k';
                                    }
                                }
                            }
                        }
                    }
                });
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

            formatRupiah(n) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(n).replace(",00", "");
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
                }[status] || 'bg-gray-400 dark:bg-gray-700';
            },
            badgeBayar(status) {
                return {
                    'Lunas': 'bg-green-500 dark:bg-green-700',
                    'Belum Bayar': 'bg-red-500 dark:bg-red-700',
                    'Pending': 'bg-yellow-400 dark:bg-yellow-700',
                }[status] || 'bg-gray-400 dark:bg-gray-700';
            },
        }
    }
</script>
<?= $this->endSection() ?>