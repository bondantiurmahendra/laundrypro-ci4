<!DOCTYPE html>
<html>

<head>
  <title>Checkout Midtrans</title>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-9T416znFL7X9DEX9"></script>
</head>

<body>

  <button id="pay-button">Bayar Sekarang</button>

  <script>
  document.getElementById('pay-button').addEventListener('click', function() {
    fetch('/checkout/token')
      .then(res => res.json())
      .then(data => {
        snap.pay(data.snapToken, {
          onSuccess: function(result) {
            alert("Pembayaran sukses!");
            console.log(result);
          },
          onPending: function(result) {
            alert("Menunggu pembayaran...");
            console.log(result);
          },
          onError: function(result) {
            alert("Pembayaran gagal!");
            console.log(result);
          },
          onClose: function() {
            alert("Kamu menutup popup tanpa menyelesaikan pembayaran");
          }
        });
      });
  });
  </script>

</body>

</html>