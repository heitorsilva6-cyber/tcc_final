<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Órbita dos Números | Matemática em missão</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="starfield"></div>
  <div id="toast" class="toast" role="status"></div>
  <div id="authView" class="auth-shell">
    <section class="auth-copy"><span class="eyebrow">MISSÃO 01 · BASE ORBITAL</span><h1>Domine os sinais.<br><em>Conquiste o espaço.</em></h1><p>Uma jornada gamificada para transformar números inteiros em decisões de alto impacto.</p><div class="orbit-art"><span class="planet">−7</span><span class="orbit-line"></span><span class="satellite">+4</span></div></section>
    <section class="auth-panel"><div class="brand"><span class="brand-mark">∓</span><span>ÓRBITA<br><b>DOS NÚMEROS</b></span></div><div class="auth-tabs"><button class="auth-tab active" data-auth="login">Entrar</button><button class="auth-tab" data-auth="register">Criar conta</button></div>
      <form id="loginForm" class="auth-form"><h2>Pronto para decolar?</h2><p class="muted">Continue sua trilha de aprendizagem.</p><label>E-mail<input type="email" name="email" placeholder="voce@exemplo.com" required></label><label>Senha<input type="password" name="password" placeholder="••••••••" required></label><button class="primary wide" type="submit">Entrar na missão <span>→</span></button><p class="form-message"></p></form>
      <form id="registerForm" class="auth-form hidden"><h2>Crie seu perfil</h2><p class="muted">Sua nave começa com um nome e uma senha.</p><label>Como quer ser chamado?<input name="name" placeholder="Nome do astronauta" required></label><label>E-mail<input type="email" name="email" placeholder="voce@exemplo.com" required></label><label>Senha<input type="password" name="password" placeholder="Mínimo de 6 caracteres" minlength="6" required></label><button class="primary wide" type="submit">Criar meu perfil <span>→</span></button><p class="form-message"></p></form>
    </section>
  </div>
  <div id="appView" class="app-shell hidden"><aside class="sidebar"><div class="brand"><span class="brand-mark">∓</span><span>ÓRBITA<br><b>DOS NÚMEROS</b></span></div><nav><button class="nav-item active" data-page="home"><span>⌂</span> Hub de missão</button><button class="nav-item" data-page="games"><span>✦</span> Jogos</button><button class="nav-item" data-page="profile"><span>◉</span> Meu progresso</button><button class="nav-item" data-page="ranking"><span>♜</span> Ranking</button></nav><div class="side-footer"><div class="mini-avatar">✦</div><div><strong id="sideName">Astronauta</strong><small>Cadete em treinamento</small></div><button id="logoutBtn" class="icon-button" title="Sair">↪</button></div></aside><main class="main"><header class="topbar"><button id="mobileMenu" class="mobile-menu">☰</button><div><span class="top-kicker">CENTRO DE CONTROLE / <span id="pageLabel">HUB DE MISSÃO</span></span><h2 id="pageTitle">Olá, cadete.</h2></div><div class="top-actions"><div class="streak"><span>✹</span><b id="streakValue">0</b><small>dias de foco</small></div><div class="top-avatar">✦</div></div></header><div id="pageContent"></div></main></div>
  <script src="app.js"></script>
</body>
</html>
