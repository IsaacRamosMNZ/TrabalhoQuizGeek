<?php
$jogadorSalvo = trim($_COOKIE['jogador'] ?? '');

$perguntas = [
    [
        'enunciado' => 'Em um projeto novo, qual parte te anima primeiro?',
        'opcoes' => [
            'frontend' => 'Criar a interface visual',
            'bugs' => 'Investigar falhas e corrigir',
            'arquitetura' => 'Planejar estrutura e organizacao',
            'algoritmos' => 'Otimizar regras e desempenho',
        ],
    ],
    [
        'enunciado' => 'Qual atividade voce faria por horas?',
        'opcoes' => [
            'frontend' => 'Escolher cores, fontes e layouts',
            'bugs' => 'Testar cenarios estranhos',
            'arquitetura' => 'Desenhar diagramas de sistema',
            'algoritmos' => 'Resolver desafios de logica',
        ],
    ],
    [
        'enunciado' => 'Quando algo quebra, sua primeira reacao e:',
        'opcoes' => [
            'frontend' => 'Ver se a interface orienta melhor o usuario',
            'bugs' => 'Ler logs e reproduzir o erro',
            'arquitetura' => 'Revisar dependencias e fluxo geral',
            'algoritmos' => 'Checar regras e casos de borda',
        ],
    ],
    [
        'enunciado' => 'Qual frase combina mais com voce?',
        'opcoes' => [
            'frontend' => 'A experiencia do usuario vem primeiro',
            'bugs' => 'Todo bug deixa pistas',
            'arquitetura' => 'Sem base boa, nada escala',
            'algoritmos' => 'Sempre existe uma solucao mais elegante',
        ],
    ],
    [
        'enunciado' => 'Em uma equipe, voce normalmente e quem:',
        'opcoes' => [
            'frontend' => 'Da vida ao produto com visual marcante',
            'bugs' => 'Garante qualidade antes da entrega',
            'arquitetura' => 'Define padroes e organiza modulos',
            'algoritmos' => 'Resolve os problemas mais complexos',
        ],
    ],
    [
        'enunciado' => 'Qual ferramenta te chama mais atencao?',
        'opcoes' => [
            'frontend' => 'Figma e bibliotecas de UI',
            'bugs' => 'Debugger e suite de testes',
            'arquitetura' => 'Documentacao e modelagem de sistema',
            'algoritmos' => 'Editor com plugins de produtividade',
        ],
    ],
    [
        'enunciado' => 'Qual desafio parece mais divertido?',
        'opcoes' => [
            'frontend' => 'Criar uma landing page inesquecivel',
            'bugs' => 'Eliminar um erro raro de producao',
            'arquitetura' => 'Quebrar um monolito em modulos claros',
            'algoritmos' => 'Reduzir tempo de execucao pela metade',
        ],
    ],
    [
        'enunciado' => 'Quando recebe feedback, voce costuma:',
        'opcoes' => [
            'frontend' => 'Refinar usabilidade e acessibilidade',
            'bugs' => 'Verificar casos nao cobertos',
            'arquitetura' => 'Ajustar padroes e acoplamento',
            'algoritmos' => 'Simplificar regras sem perder desempenho',
        ],
    ],
    [
        'enunciado' => 'O que mais te orgulha em uma entrega?',
        'opcoes' => [
            'frontend' => 'Interface intuitiva e bonita',
            'bugs' => 'Aplicacao estavel e sem surpresas',
            'arquitetura' => 'Codigo organizado para crescer',
            'algoritmos' => 'Solucao elegante para problema complexo',
        ],
    ],
    [
        'enunciado' => 'Se tivesse 1 hora livre no projeto, voce escolheria:',
        'opcoes' => [
            'frontend' => 'Melhorar animacoes e detalhes visuais',
            'bugs' => 'Criar testes para cenarios criticos',
            'arquitetura' => 'Refatorar estrutura de pastas e camadas',
            'algoritmos' => 'Otimizar consultas e processamento',
        ],
    ],
];

shuffle($perguntas);
$efeitosAos = ['fade-up', 'fade-left', 'fade-right', 'zoom-in-up'];
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Geek - Perguntas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="page-wrap">
        <section class="card quiz-card fade-in animate__animated animate__fadeInUp">
            <canvas id="quizHackerCanvas" class="quiz-hacker-canvas" aria-hidden="true"></canvas>
            <h1 class="animate__animated animate__fadeInDown">Quiz de Personalidade Geek</h1>
            <p>Responda as 10 perguntas. O nome e pedido apenas na primeira jogada.</p>

            <form action="resultado.php" method="POST" class="quiz-form" id="quizForm">
                <?php if ($jogadorSalvo === ''): ?>
                    <label for="nome">Nome do jogador</label>
                    <input type="text" id="nome" name="nome" required placeholder="Digite seu nome">
                <?php else: ?>
                    <div class="welcome compact">
                        <p>Jogador atual: <strong><?= htmlspecialchars($jogadorSalvo) ?></strong></p>
                        <p>Nome bloqueado para manter consistencia do perfil.</p>
                    </div>
                    <input type="hidden" name="nome" value="<?= htmlspecialchars($jogadorSalvo) ?>">
                <?php endif; ?>

                <div class="quiz-progress" aria-live="polite">
                    <div class="quiz-progress-top">
                        <span id="stepText">Pergunta 1 de <?= count($perguntas) ?></span>
                        <span id="stepPercent">10%</span>
                    </div>
                    <div class="quiz-progress-bar">
                        <div class="quiz-progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <p class="quiz-feedback" id="quizFeedback" aria-live="assertive"></p>

                <?php foreach ($perguntas as $indice => $pergunta): ?>
                    <?php
                    $nomeCampo = 'q' . ($indice + 1);
                    $opcoes = $pergunta['opcoes'];
                    $chaves = array_keys($opcoes);
                    shuffle($chaves);
                    $efeitoAtual = $efeitosAos[$indice % count($efeitosAos)];
                    ?>
                    <fieldset class="quiz-step" data-step="<?= $indice ?>" data-aos="<?= $efeitoAtual ?>" data-aos-delay="<?= 40 * ($indice + 1) ?>">
                        <legend><?= ($indice + 1) ?>) <?= htmlspecialchars($pergunta['enunciado']) ?></legend>
                        <?php foreach ($chaves as $ordem => $perfil): ?>
                            <label class="option-item">
                                <input type="radio" name="<?= $nomeCampo ?>" value="<?= htmlspecialchars($perfil) ?>">
                                <?= htmlspecialchars($opcoes[$perfil]) ?>
                            </label>
                        <?php endforeach; ?>
                    </fieldset>
                <?php endforeach; ?>

                <div class="quiz-controls">
                    <button type="button" class="btn btn-ghost" id="prevQuestion">Anterior</button>
                    <button type="button" class="btn" id="nextQuestion">Proxima</button>
                    <button type="submit" class="btn" id="finishQuiz">Descobrir meu perfil</button>
                </div>
            </form>

            <a href="index.php" class="inline-link">Voltar para inicio</a>
        </section>
    </main>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        if (window.AOS) {
            AOS.init({ duration: 650, once: true, offset: 14, easing: 'ease-out-cubic' });
        }

        const quizForm = document.getElementById('quizForm');
        const steps = Array.from(document.querySelectorAll('.quiz-step'));
        const prevBtn = document.getElementById('prevQuestion');
        const nextBtn = document.getElementById('nextQuestion');
        const finishBtn = document.getElementById('finishQuiz');
        const feedback = document.getElementById('quizFeedback');
        const progressFill = document.getElementById('progressFill');
        const stepText = document.getElementById('stepText');
        const stepPercent = document.getElementById('stepPercent');

        let currentStep = 0;
        const totalSteps = steps.length;
        let audioCtx = null;

        quizForm.classList.add('is-enhanced');

        function playUiTone(type) {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }

                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                const presets = {
                    select: { freq: 520, time: 0.06, wave: 'triangle', volume: 0.03 },
                    move: { freq: 640, time: 0.05, wave: 'sine', volume: 0.025 },
                    warn: { freq: 220, time: 0.09, wave: 'sawtooth', volume: 0.02 }
                };

                const preset = presets[type] || presets.select;
                const now = audioCtx.currentTime;
                osc.type = preset.wave;
                osc.frequency.setValueAtTime(preset.freq, now);

                gain.gain.setValueAtTime(0.0001, now);
                gain.gain.exponentialRampToValueAtTime(preset.volume, now + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.0001, now + preset.time);

                osc.start(now);
                osc.stop(now + preset.time);
            } catch (e) {
                // Ignora restricoes de autoplay sem quebrar o fluxo.
            }
        }

        function refreshOptionState() {
            steps.forEach((step) => {
                const radios = step.querySelectorAll('input[type="radio"]');
                radios.forEach((radio) => {
                    const label = radio.closest('.option-item');
                    if (label) {
                        label.classList.toggle('is-picked', radio.checked);
                    }
                });
            });
        }

        function hasAnswer(index) {
            const radios = steps[index].querySelectorAll('input[type="radio"]');
            return Array.from(radios).some((radio) => radio.checked);
        }

        function updateProgress() {
            const percent = Math.round(((currentStep + 1) / totalSteps) * 100);
            progressFill.style.width = percent + '%';
            stepText.textContent = 'Pergunta ' + (currentStep + 1) + ' de ' + totalSteps;
            stepPercent.textContent = percent + '%';
        }

        function showStep(index) {
            steps.forEach((step, stepIndex) => {
                step.classList.toggle('is-active', stepIndex === index);
            });

            prevBtn.disabled = index === 0;
            nextBtn.style.display = index === totalSteps - 1 ? 'none' : 'inline-block';
            finishBtn.style.display = index === totalSteps - 1 ? 'inline-block' : 'none';
            updateProgress();
            feedback.textContent = '';
            refreshOptionState();
        }

        nextBtn.addEventListener('click', () => {
            if (!hasAnswer(currentStep)) {
                feedback.textContent = 'Escolha uma alternativa para continuar.';
                playUiTone('warn');
                return;
            }

            if (currentStep < totalSteps - 1) {
                currentStep += 1;
                showStep(currentStep);
                playUiTone('move');
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep -= 1;
                showStep(currentStep);
                playUiTone('move');
            }
        });

        quizForm.addEventListener('change', (event) => {
            if (event.target.matches('input[type="radio"]')) {
                refreshOptionState();
                playUiTone('select');
            }
        });

        quizForm.addEventListener('submit', (event) => {
            const unanswered = steps.findIndex((_, index) => !hasAnswer(index));
            if (unanswered !== -1) {
                event.preventDefault();
                currentStep = unanswered;
                showStep(currentStep);
                feedback.textContent = 'Ainda faltam perguntas. Complete todas para ver seu perfil.';
                playUiTone('warn');
            }
        });

        const quizCanvas = document.getElementById('quizHackerCanvas');
        if (quizCanvas) {
            const ctx = quizCanvas.getContext('2d');
            const glyphs = '0123456789ABCDEF[]{}<>/=+*';
            let drops = [];
            let columns = 0;
            let fontSize = 13;

            function setupQuizCanvas() {
                const rect = quizCanvas.getBoundingClientRect();
                quizCanvas.width = rect.width;
                quizCanvas.height = rect.height;
                fontSize = rect.width < 480 ? 11 : 13;
                columns = Math.floor(quizCanvas.width / fontSize);
                drops = Array.from({ length: columns }, () => Math.random() * -20);
            }

            function drawQuizMatrix() {
                ctx.fillStyle = 'rgba(8, 14, 26, 0.2)';
                ctx.fillRect(0, 0, quizCanvas.width, quizCanvas.height);

                ctx.fillStyle = '#4cd7ff';
                ctx.font = fontSize + 'px monospace';

                for (let i = 0; i < drops.length; i++) {
                    const text = glyphs.charAt(Math.floor(Math.random() * glyphs.length));
                    const x = i * fontSize;
                    const y = drops[i] * fontSize;

                    ctx.fillText(text, x, y);

                    if (y > quizCanvas.height && Math.random() > 0.975) {
                        drops[i] = 0;
                    }
                    drops[i] += 0.7;
                }
            }

            setupQuizCanvas();
            setInterval(drawQuizMatrix, 58);
            window.addEventListener('resize', setupQuizCanvas);
        }

        showStep(currentStep);
    </script>
</body>
</html>
