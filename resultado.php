<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: quiz.php');
    exit;
}

$nomeCookie = trim($_COOKIE['jogador'] ?? '');
$nomePost = trim($_POST['nome'] ?? 'Jogador');
$nome = $nomeCookie !== '' ? $nomeCookie : $nomePost;
if ($nome === '') {
    $nome = 'Jogador';
}

$pontuacao = [
    'frontend' => 0,
    'bugs' => 0,
    'arquitetura' => 0,
    'algoritmos' => 0,
];

$respostas = [];
foreach ($_POST as $campo => $valor) {
    if (preg_match('/^q\d+$/', $campo)) {
        $respostas[] = $valor;
    }
}

foreach ($respostas as $resposta) {
    if (isset($pontuacao[$resposta])) {
        $pontuacao[$resposta]++;
    }
}

$frontend = $pontuacao['frontend'];
$bugs = $pontuacao['bugs'];
$arquitetura = $pontuacao['arquitetura'];
$algoritmos = $pontuacao['algoritmos'];

if ($frontend >= $bugs && $frontend >= $arquitetura && $frontend >= $algoritmos) {
    $perfilKey = 'frontend';
} elseif ($bugs >= $frontend && $bugs >= $arquitetura && $bugs >= $algoritmos) {
    $perfilKey = 'bugs';
} elseif ($arquitetura >= $frontend && $arquitetura >= $bugs && $arquitetura >= $algoritmos) {
    $perfilKey = 'arquitetura';
} else {
    $perfilKey = 'algoritmos';
}

$perfis = [
    'frontend' => [
        'titulo' => 'Ninja do Front-end',
        'imagem' => 'img/ninja-frontend.svg',
        'descricao' => [
            'Voce transforma ideias em interfaces claras e marcantes.',
            'Sua forca esta na experiencia do usuario e nos detalhes visuais.',
            'Cada botao, fonte e espacamento ganha sentido nas suas maos.',
            'Com voce, tecnologia tambem comunica emocao e estilo.',
        ],
    ],
    'bugs' => [
        'titulo' => 'Cacador de Bugs',
        'imagem' => 'img/cacador-bugs.svg',
        'descricao' => [
            'Voce enxerga padroes onde os outros so veem caos.',
            'Sua paciencia para investigar falhas e impressionante.',
            'Ao depurar, voce protege a qualidade e a confianca do sistema.',
            'Com voce no time, problemas viram aprendizado rapido.',
        ],
    ],
    'arquitetura' => [
        'titulo' => 'Arquiteto do Codigo',
        'imagem' => 'img/arquiteto-codigo.svg',
        'descricao' => [
            'Voce pensa no todo antes de escrever a primeira linha.',
            'Sua especialidade e criar estruturas limpas e escalaveis.',
            'Decisoes tecnicas ganham consistencia quando passam por voce.',
            'Seu trabalho faz projetos crescerem com estabilidade.',
        ],
    ],
    'algoritmos' => [
        'titulo' => 'Mestre dos Algoritmos',
        'imagem' => 'img/mestre-algoritmos.svg',
        'descricao' => [
            'Voce resolve desafios com raciocinio rapido e preciso.',
            'Otimizacao e elegancia logica fazem parte do seu estilo.',
            'Problemas complexos viram etapas claras sob sua analise.',
            'Seu talento acelera sistemas e inspira a equipe.',
        ],
    ],
    'entidade' => [
        'titulo' => 'Entidade do Codigo',
        'imagem' => 'img/mestre-algoritmos.svg',
        'descricao' => [
            'Resultado secreto desbloqueado por uma chance extremamente rara.',
            'Voce pensa alem da interface, alem da logica e alem da arquitetura.',
            'Seu perfil representa dominio completo do ecossistema digital.',
            'Quando voce aparece, o codigo simplesmente obedece.',
        ],
    ],
];

// Easter egg: 1% de chance de revelar um perfil secreto.
if (mt_rand(1, 100) === 1) {
    $perfilKey = 'entidade';
}

$perfil = $perfis[$perfilKey];
$jogadas = isset($_COOKIE['jogadas']) ? (int) $_COOKIE['jogadas'] + 1 : 1;

$maxScore = max($frontend, $bugs, $arquitetura, $algoritmos);
$xpGanho = 40 + ($maxScore * 12);
if ($perfilKey === 'entidade') {
    $xpGanho += 80;
}

$xpAtual = isset($_COOKIE['xp_total']) ? (int) $_COOKIE['xp_total'] : 0;
$xpTotal = $xpAtual + $xpGanho;
$nivel = (int) floor($xpTotal / 120) + 1;
$xpNoNivel = $xpTotal % 120;
$xpProximo = 120;
$xpPercent = (int) round(($xpNoNivel / $xpProximo) * 100);

$titulosNivel = [
    1 => 'Recruta Geek',
    2 => 'Programador Iniciante',
    3 => 'Dev em Ascensao',
    4 => 'Codigo Tatico',
    5 => 'Mestre Tech',
    6 => 'Lenda Digital',
];
$tituloNivel = $titulosNivel[min($nivel, 6)];

$curiosidades = [
    'O primeiro bug de computador famoso foi uma mariposa encontrada em 1947.',
    'O termo algoritmo vem do matematico persa Al-Khwarizmi.',
    'A linguagem C influenciou diretamente C++, Java, C# e JavaScript.',
    'O primeiro site da historia ainda pode ser visitado no CERN.',
    'Mais de 90% dos dados do mundo foram criados nos ultimos anos.',
    'A tecla Caps Lock existe desde as antigas maquinas de escrever.',
    'Python recebeu esse nome inspirado no grupo Monty Python.',
    'Linux esta presente em servidores, celulares, TVs e ate carros.',
    'Muitos jogos usam IA simples com maquinas de estado para NPCs.',
    'CSS significa Cascading Style Sheets por causa da cascata de estilos.',
];
$curiosidade = $curiosidades[array_rand($curiosidades)];

$classesPerfil = [
    'frontend' => 'theme-frontend',
    'bugs' => 'theme-bugs',
    'arquitetura' => 'theme-arquitetura',
    'algoritmos' => 'theme-algoritmos',
    'entidade' => 'theme-entidade',
];
$classeResultado = $classesPerfil[$perfilKey] ?? '';

setcookie('jogador', $nome, time() + 3600);
setcookie('jogadas', (string) $jogadas, time() + 3600);
setcookie('xp_total', (string) $xpTotal, time() + 3600);
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Quiz Geek</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="<?= htmlspecialchars($classeResultado) ?>">
    <main class="page-wrap">
        <section class="card result-card fade-in blast-in animate__animated animate__zoomIn" data-aos="flip-up" data-aos-duration="720">
            <div class="result-sparks" aria-hidden="true">
                <span></span><span></span><span></span><span></span><span></span><span></span>
            </div>
            <h1>Resultado do Quiz</h1>
            <p class="player">Jogador: <strong><?= htmlspecialchars($nome) ?></strong></p>
            <p class="counter">Esta foi sua jogada numero <strong><?= $jogadas ?></strong>.</p>

            <section class="xp-panel" data-aos="fade-up" data-aos-delay="70">
                <h3>Progresso Geek</h3>
                <p>Nivel <strong><?= $nivel ?></strong> - <?= htmlspecialchars($tituloNivel) ?></p>
                <p>XP ganho nesta rodada: <strong>+<?= $xpGanho ?></strong> | XP total: <strong><?= $xpTotal ?></strong></p>
                <div class="xp-bar"><div class="xp-fill" style="width: <?= $xpPercent ?>%"></div></div>
                <small><?= $xpNoNivel ?>/<?= $xpProximo ?> XP para o proximo nivel</small>
            </section>

            <img src="<?= htmlspecialchars($perfil['imagem']) ?>" alt="Imagem do perfil geek" class="profile-image" data-aos="zoom-in" data-aos-delay="120">
            <h2 class="impact-title animate__animated animate__rubberBand animate__delay-1s"><?= htmlspecialchars($perfil['titulo']) ?></h2>

            <div class="description">
                <?php foreach ($perfil['descricao'] as $linha): ?>
                    <p><?= htmlspecialchars($linha) ?></p>
                <?php endforeach; ?>
            </div>

            <h3>Placar final</h3>
            <ul class="score-list">
                <li>Ninja do Front-end: <?= $frontend ?> ponto(s)</li>
                <li>Cacador de Bugs: <?= $bugs ?> ponto(s)</li>
                <li>Arquiteto do Codigo: <?= $arquitetura ?> ponto(s)</li>
                <li>Mestre dos Algoritmos: <?= $algoritmos ?> ponto(s)</li>
            </ul>

            <section class="curiosity-box" data-aos="fade-up" data-aos-delay="110">
                <h3>Curiosidade Geek do Dia</h3>
                <p><?= htmlspecialchars($curiosidade) ?></p>
            </section>

            <section class="share-signature" id="shareCard" data-aos="zoom-in-up" data-aos-delay="140">
                <p class="share-title">Assinatura Compartilhavel</p>
                <p><strong><?= htmlspecialchars($nome) ?></strong></p>
                <p><?= htmlspecialchars($perfil['titulo']) ?></p>
                <p>Nivel <?= $nivel ?> | XP total <?= $xpTotal ?></p>
            </section>

            <div class="actions">
                <a class="btn" href="quiz.php">Jogar de novo</a>
                <button type="button" class="btn btn-ghost" id="copySignature">Copiar assinatura</button>
                <button type="button" class="btn btn-ghost" id="shareSignature">Compartilhar</button>
                <a class="inline-link" href="index.php">Voltar ao inicio</a>
            </div>

            <p class="share-status" id="shareStatus" aria-live="polite"></p>
        </section>
    </main>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <script>
        if (window.AOS) {
            AOS.init({ duration: 700, once: true, easing: 'ease-out-back' });
        }

        let audioCtx = null;
        function playWinTone() {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                const now = audioCtx.currentTime;
                const notes = [440, 554, 660];

                notes.forEach((freq, index) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(freq, now + (index * 0.05));
                    gain.gain.setValueAtTime(0.0001, now + (index * 0.05));
                    gain.gain.exponentialRampToValueAtTime(0.02, now + (index * 0.05) + 0.01);
                    gain.gain.exponentialRampToValueAtTime(0.0001, now + (index * 0.05) + 0.12);
                    osc.start(now + (index * 0.05));
                    osc.stop(now + (index * 0.05) + 0.12);
                });
            } catch (e) {
                // Ignora bloqueio de autoplay.
            }
        }

        playWinTone();

        const perfil = '<?= $perfilKey ?>';
        const paletas = {
            frontend: ['#5be6ff', '#7cffcb', '#dff7ff'],
            bugs: ['#ff5d5d', '#ffb36c', '#ffe1cf'],
            arquitetura: ['#7ef0a0', '#c5ffd1', '#f4fff1'],
            algoritmos: ['#90a6ff', '#c6d0ff', '#eef1ff'],
            entidade: ['#f5d16b', '#f3b874', '#f7efe0']
        };

        const cores = paletas[perfil] || ['#ffffff', '#b7cddd'];
        const duracao = 1300;
        const fim = Date.now() + duracao;

        (function soltarConfete() {
            confetti({
                particleCount: 7,
                angle: 60,
                spread: 72,
                origin: { x: 0.1, y: 0.72 },
                colors: cores
            });
            confetti({
                particleCount: 7,
                angle: 120,
                spread: 72,
                origin: { x: 0.9, y: 0.72 },
                colors: cores
            });

            if (Date.now() < fim) {
                requestAnimationFrame(soltarConfete);
            }
        })();

        const shareText = 'Eu sou <?= addslashes($perfil['titulo']) ?> no Quiz Geek. Nivel <?= $nivel ?> com <?= $xpTotal ?> XP!';
        const copyBtn = document.getElementById('copySignature');
        const shareBtn = document.getElementById('shareSignature');
        const shareStatus = document.getElementById('shareStatus');

        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(shareText);
                shareStatus.textContent = 'Assinatura copiada para a area de transferencia.';
            } catch (e) {
                shareStatus.textContent = 'Nao foi possivel copiar automaticamente.';
            }
        });

        shareBtn.addEventListener('click', async () => {
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: 'Meu Perfil Geek',
                        text: shareText,
                        url: window.location.origin + window.location.pathname
                    });
                    shareStatus.textContent = 'Compartilhamento enviado.';
                } catch (e) {
                    shareStatus.textContent = 'Compartilhamento cancelado.';
                }
            } else {
                shareStatus.textContent = 'Seu navegador nao suporta compartilhar direto. Use "Copiar assinatura".';
            }
        });
    </script>
</body>

</html>