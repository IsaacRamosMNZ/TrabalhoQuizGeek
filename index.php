<?php
$jogador = $_COOKIE['jogador'] ?? null;
$jogadas = isset($_COOKIE['jogadas']) ? (int) $_COOKIE['jogadas'] : 0;
$cacheApagado = isset($_GET['cache']) && $_GET['cache'] === 'apagado';
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz de Personalidade Geek</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="page-wrap">
        <section class="card hero fade-in animate__animated animate__fadeInDown" data-aos="zoom-in"
            data-aos-duration="700">
            <canvas id="heroHackerCanvas" class="hero-hacker-canvas" aria-hidden="true"></canvas>
            <div class="hero-grid">
                <div class="hero-content">
                    <h1 class="animate__animated animate__jackInTheBox animate__delay-1s">Qual e o seu Perfil Geek?</h1>
                    <p>
                        Responda 10 perguntas e descubra qual e a sua identidade no universo da tecnologia.
                        No final, voce recebe um perfil completo com descricao personalizada.
                    </p>
                </div>
            </div>

            <?php if ($jogador): ?>
                <div class="welcome">
                    <p>Bem-vindo de volta, <strong><?= htmlspecialchars($jogador) ?></strong>!</p>
                    <p>Voce ja jogou <strong><?= $jogadas ?></strong> vez(es).</p>
                </div>
            <?php else: ?>
                <div class="welcome">
                    <p>Primeira vez por aqui? Prepare-se para o desafio geek.</p>
                </div>
            <?php endif; ?>

            <?php if ($cacheApagado): ?>
                <div class="notice-ok" data-aos="fade-up" data-aos-delay="100">
                    Cache apagado com sucesso. Agora voce pode informar um novo nome na proxima jogada.
                </div>
            <?php endif; ?>

            <div class="home-actions">
                <a class="btn animate__animated animate__pulse animate__infinite animate__slower"
                    href="quiz.php">Comecar Quiz</a>
                <button type="button" class="btn btn-danger" id="openResetModal">Apagar cache</button>
            </div>

            <p class="cache-help">Apagar cache remove o nome salvo e zera o contador de jogadas, permitindo mudar o
                nome.</p>
        </section>
    </main>

    <div class="modal-backdrop" id="resetModal" aria-hidden="true">
        <section class="modal-card" role="dialog" aria-modal="true" aria-labelledby="resetTitle">
            <h2 id="resetTitle">Confirmar apagar cache</h2>
            <p>
                Esta acao vai remover o nome salvo e apagar o total de jogadas registradas.
                Depois disso, voce podera escolher um novo nome no inicio do quiz.
            </p>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" id="cancelReset">Cancelar</button>
                <form action="apagar_cache.php" method="POST">
                    <button type="submit" class="btn btn-danger" id="confirmReset" disabled>Confirmar (5)</button>
                </form>
            </div>
        </section>
    </div>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        if (window.AOS) {
            AOS.init({ duration: 700, once: true, offset: 20 });
        }

        const hackerCanvas = document.getElementById('heroHackerCanvas');
        if (hackerCanvas) {
            const ctx = hackerCanvas.getContext('2d');
            const glyphs = '01ABCDEFGHIJKLMNOPQRSTUVWXYZ#$%&@';
            let drops = [];
            let fontSize = 14;
            let columns = 0;

            function setupCanvas() {
                const rect = hackerCanvas.getBoundingClientRect();
                hackerCanvas.width = rect.width;
                hackerCanvas.height = rect.height;

                fontSize = rect.width < 480 ? 12 : 14;
                columns = Math.floor(hackerCanvas.width / fontSize);
                drops = Array.from({ length: columns }, () => Math.random() * -20);
            }

            function drawMatrix() {
                ctx.fillStyle = 'rgba(8, 18, 14, 0.18)';
                ctx.fillRect(0, 0, hackerCanvas.width, hackerCanvas.height);

                ctx.fillStyle = '#4cff86';
                ctx.font = fontSize + 'px monospace';

                for (let i = 0; i < drops.length; i++) {
                    const text = glyphs.charAt(Math.floor(Math.random() * glyphs.length));
                    const x = i * fontSize;
                    const y = drops[i] * fontSize;

                    ctx.fillText(text, x, y);

                    if (y > hackerCanvas.height && Math.random() > 0.975) {
                        drops[i] = 0;
                    }
                    drops[i] += 0.7;
                }
            }

            setupCanvas();
            setInterval(drawMatrix, 55);
            window.addEventListener('resize', setupCanvas);
        }

        const openModalBtn = document.getElementById('openResetModal');
        const resetModal = document.getElementById('resetModal');
        const cancelResetBtn = document.getElementById('cancelReset');
        const confirmResetBtn = document.getElementById('confirmReset');

        let timerId = null;

        function closeResetModal() {
            resetModal.classList.remove('is-open');
            resetModal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
            }
            confirmResetBtn.disabled = true;
            confirmResetBtn.textContent = 'Confirmar (5)';
        }

        function startConfirmTimer() {
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
            }

            let segundos = 5;
            confirmResetBtn.disabled = true;
            confirmResetBtn.textContent = 'Confirmar (' + segundos + ')';

            timerId = setInterval(() => {
                segundos -= 1;
                if (segundos > 0) {
                    confirmResetBtn.textContent = 'Confirmar (' + segundos + ')';
                    return;
                }

                clearInterval(timerId);
                timerId = null;
                confirmResetBtn.disabled = false;
                confirmResetBtn.textContent = 'Confirmar e apagar cache';
            }, 1000);
        }

        openModalBtn.addEventListener('click', () => {
            resetModal.classList.add('is-open');
            resetModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
            startConfirmTimer();
        });

        cancelResetBtn.addEventListener('click', (event) => {
            event.preventDefault();
            closeResetModal();
        });

        resetModal.addEventListener('click', (event) => {
            if (event.target === resetModal) {
                closeResetModal();
            }
        });
    </script>
</body>

</html>