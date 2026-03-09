# Quiz de Personalidade Geek

Projeto pratico em PHP + HTML + CSS para descobrir o perfil tecnologico do jogador com base em um quiz interativo.

## Objetivo
Criar um site divertido que identifica o perfil geek do usuario e entrega um resultado personalizado, com persistencia de dados usando cookies.

## Tecnologias usadas
- HTML5
- CSS3
- PHP
- JavaScript
- Cookies
- Bibliotecas via CDN:
  - Animate.css
  - AOS (Animate On Scroll)
  - canvas-confetti

## Estrutura do projeto
```text
quiz_geek/
|-- index.php
|-- quiz.php
|-- resultado.php
|-- apagar_cache.php
|-- style.css
|-- README.md
`-- img/
    |-- ninja-frontend.svg
    |-- cacador-bugs.svg
    |-- arquiteto-codigo.svg
    `-- mestre-algoritmos.svg
```

## Funcionalidades implementadas
- Tela inicial com boas-vindas e contador de jogadas
- Botao para iniciar quiz
- Botao "Apagar cache" com popup de confirmacao
- Timer de 5 segundos no botao de confirmacao
- Limpeza de cookies de nome e jogadas
- Nome pedido apenas na primeira vez
- Quiz com 10 perguntas e respostas embaralhadas
- Navegacao por etapas (uma pergunta por vez)
- Barra de progresso
- Feedback visual ao selecionar opcao
- Efeitos sonoros leves (navegacao, selecao, alerta, resultado)
- Resultado personalizado com 4 perfis principais
- Perfil secreto raro (1%): Entidade do Codigo
- Sistema de XP e nivel por cookie
- Curiosidade geek aleatoria no resultado
- Card de assinatura compartilhavel
- Botao para copiar/compartilhar assinatura
- Tema visual diferente por perfil no resultado

## Como executar (XAMPP)
1. Coloque a pasta `quiz_geek` dentro de `C:\xampp\htdocs\Trabalho1\`.
2. Inicie o Apache no XAMPP.
3. Abra no navegador:
   - `http://localhost/Trabalho1/quiz_geek/index.php`

## Rascunho das telas (wireframe)

### 1) Tela inicial (`index.php`)
```text
+-------------------------------------------------------------+
|                  QUIZ DE PERSONALIDADE GEEK                 |
|-------------------------------------------------------------|
| [animacao hacker no fundo do card hero]                     |
|                                                             |
|  "Responda 10 perguntas..."                                |
|                                                             |
|  [mensagem de boas-vindas / jogadas]                        |
|                                                             |
|  [ Comecar Quiz ]   [ Apagar cache ]                        |
|                                                             |
|  texto de ajuda: "apagar cache permite mudar nome"         |
+-------------------------------------------------------------+

Popup apagar cache:
+---------------------------------------------+
| Confirmar apagar cache                      |
|---------------------------------------------|
| remove nome salvo e zera jogadas            |
|                                             |
| [Cancelar]   [Confirmar (5)... habilita]    |
+---------------------------------------------+
```

### 2) Tela de perguntas (`quiz.php`)
```text
+-------------------------------------------------------------+
|                QUIZ - PERGUNTA X DE 10                      |
|-------------------------------------------------------------|
| [fundo animado hacker em cor diferente da home]             |
|                                                             |
| Nome (so aparece na primeira vez)                           |
|                                                             |
| Barra de progresso [########------] 40%                     |
|                                                             |
| Pergunta atual                                              |
|  ( ) Opcao A                                                |
|  ( ) Opcao B                                                |
|  ( ) Opcao C                                                |
|  ( ) Opcao D                                                |
|                                                             |
| [Anterior]                [Proxima] / [Descobrir perfil]    |
| Mensagem de feedback caso nao selecione resposta            |
+-------------------------------------------------------------+
```

### 3) Tela de resultado (`resultado.php`)
```text
+-------------------------------------------------------------+
|                    RESULTADO DO QUIZ                        |
|-------------------------------------------------------------|
| Jogador: NOME         Jogada: N                             |
|                                                             |
| [painel XP] Nivel, titulo, XP ganho, barra de progresso     |
|                                                             |
| [imagem do perfil]                                          |
| Nome do perfil encontrado                                   |
| descricao em 4 linhas                                       |
|                                                             |
| Placar final por categoria                                  |
|                                                             |
| Curiosidade Geek do Dia                                     |
|                                                             |
| Assinatura Compartilhavel                                   |
| [Copiar assinatura] [Compartilhar]                          |
|                                                             |
| [Jogar de novo] [Voltar ao inicio]                          |
+-------------------------------------------------------------+

Obs: cores mudam conforme o perfil final.
```

## Regras de negocio resumidas
- Se existe cookie `jogador`, o nome nao e pedido novamente.
- Cada partida incrementa `jogadas`.
- Apagar cache remove `jogador`, `jogadas` e permite novo nome.
- XP acumulado e salvo em cookie `xp_total`.
- Perfil secreto pode aparecer com chance de 1%.

## Autor
Projeto academico - Quiz de Personalidade Geek.
