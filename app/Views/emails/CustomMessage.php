<!DOCTYPE html>
<html lang='en'>

<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title><?= esc($subject ?? 'Pesan dari Pro Laundry') ?></title>
</head>

<body style='font-family: Arial, sans-serif; background:#f9f9f9; margin:0; padding:20px;'>
  <table
    style='max-width:600px; margin:auto; background:#fff; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.05);'>
    <tr>
      <td
        style='background-color:#2563eb; padding:20px; color:#fff; border-top-left-radius:8px; border-top-right-radius:8px; text-align:center;'>
        <h2 style='margin:0;'>Pro Laundry</h2>
      </td>
    </tr>
    <tr>
      <td style='padding:30px;'>
        <h3 style='color:#111;'>Halo, <?= esc($customerName ?? 'Pelanggan') ?>!</h3>
        <p style='color:#333; font-size:16px;'>
          Anda menerima pesan baru dari kami:
        </p>
        <div style='margin:24px 0; padding:16px; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:4px;'>
          <p style='color:#333; font-size:16px; white-space: pre-wrap;'><?= nl2br(esc($customMessage ?? '')) ?></p>
        </div>
        <p style='color:#555;'>Jika ada pertanyaan, jangan ragu untuk menghubungi kami.</p>
        <p style='font-size:14px; color:#999;'>Salam hangat,<br>Tim Pro Laundry</p>
      </td>
    </tr>
    <tr>
      <td
        style='text-align:center; padding:15px; font-size:13px; color:#aaa; background:#f3f4f6; border-bottom-left-radius:8px; border-bottom-right-radius:8px;'>
        &copy; <?= date('Y') ?> Pro Laundry. All rights reserved.
      </td>
    </tr>
  </table>
</body>

</html>