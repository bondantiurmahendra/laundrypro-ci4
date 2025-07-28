<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Halaman Tidak Ditemukan</title>
  <link href="<?= base_url('css/o.css') ?>" rel="stylesheet">
  <style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    overflow-x: hidden;
  }

  .container {
    text-align: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 3rem 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 90%;
    position: relative;
    overflow: hidden;
  }

  .container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    animation: shine 3s infinite;
  }

  @keyframes shine {
    0% {
      transform: translateX(-100%) translateY(-100%) rotate(45deg);
    }

    100% {
      transform: translateX(100%) translateY(100%) rotate(45deg);
    }
  }

  .error-code {
    font-size: 8rem;
    font-weight: 900;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  }

  .error-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
    letter-spacing: -0.5px;
  }

  .error-message {
    font-size: 1.1rem;
    color: #718096;
    margin-bottom: 2.5rem;
    line-height: 1.6;
  }

  .home-button {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
  }

  .home-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
  }

  .home-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
  }

  .home-button:hover::before {
    left: 100%;
  }

  .home-button:active {
    transform: translateY(-1px);
  }

  .floating-elements {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
  }

  .floating-element {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
  }

  .floating-element:nth-child(1) {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
  }

  .floating-element:nth-child(2) {
    width: 60px;
    height: 60px;
    top: 60%;
    right: 10%;
    animation-delay: 2s;
  }

  .floating-element:nth-child(3) {
    width: 40px;
    height: 40px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
  }

  @keyframes float {

    0%,
    100% {
      transform: translateY(0px) rotate(0deg);
    }

    50% {
      transform: translateY(-20px) rotate(180deg);
    }
  }

  @media (max-width: 768px) {
    .container {
      padding: 2rem 1.5rem;
      margin: 1rem;
    }

    .error-code {
      font-size: 6rem;
    }

    .error-title {
      font-size: 1.5rem;
    }

    .error-message {
      font-size: 1rem;
    }

    .home-button {
      padding: 0.8rem 1.5rem;
      font-size: 1rem;
    }
  }

  @media (max-width: 480px) {
    .error-code {
      font-size: 4rem;
    }

    .error-title {
      font-size: 1.2rem;
    }
  }
  </style>
</head>

<body>
  <div class="floating-elements">
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
  </div>

  <div class="container">
    <div class="error-code">404</div>
    <h1 class="error-title">Oops! Halaman tidak ditemukan</h1>
    <p class="error-message">Sepertinya halaman yang kamu cari tidak tersedia atau telah dipindahkan ke lokasi lain.</p>
    <a href="<?= base_url() ?>" class="home-button">
      Kembali ke Beranda
    </a>
  </div>
</body>

</html>