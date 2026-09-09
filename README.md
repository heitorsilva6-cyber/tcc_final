# Órbita dos Números

Aplicação web full-stack de aprendizagem gamificada para **Operações com Números Inteiros** no Ensino Fundamental II. O estudante aprende regra de sinais por meio de missões espaciais e submarinas, recebendo feedback imediato e acompanhando sua evolução.

## Como executar

1. Abra este diretório pelo Laragon e inicie Apache, ou use o PHP do Laragon:

```powershell
& 'C:\Users\sesi2a\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' -S 127.0.0.1:8080 -t .
```

2. Acesse `http://127.0.0.1:8080`.
3. Crie uma conta. O arquivo `data/orbitanumeros.sqlite` é criado automaticamente na primeira requisição.

## Arquitetura

- **Frontend:** HTML semântico, CSS responsivo Mobile First e JavaScript modular no navegador.
- **Backend:** PHP 8.3 com sessões persistentes e API JSON em `api.php`.
- **Banco:** SQLite via PDO, com tabelas de usuários, tentativas e medalhas criadas automaticamente em `database.php`.
- **Segurança:** senhas com `password_hash`, consultas preparadas, sessão no servidor e validação de entrada.

## Funcionalidades implementadas

- Cadastro, login, logout e sessão persistente.
- Hub do aluno com trilha de aprendizagem, pontos, precisão e missões.
- Quatro jogos funcionais: Comando Inteiros, Profundidade Abissal, Órbita em Movimento e Saldo de Energia.
- Três níveis por jogo, com 3 questões, cálculo de pontuação e sincronização com o banco.
- Dica progressiva sem revelar diretamente a resposta.
- Feedback pós-erro com alternativa correta destacada e explicação.
- Medalhas: Primeiro voo, Mira perfeita e Explorador orbital.
- Perfil com histórico de acertos/erros e ranking da base.
- Layout responsivo para celular, tablet e desktop.

## Divisão sugerida da equipe

- **UI/UX Designer:** identidade visual, protótipo, acessibilidade e testes de navegação.
- **Frontend Developer:** estados da interface, desafios, feedback e responsividade.
- **Backend & Database Engineer:** API, sessão, modelo SQLite e persistência.
- **QA/Testes:** casos de cadastro, respostas, pontuação, mobile e regressões.
- **Especialista Pedagógico:** progressão de dificuldade, linguagem e validação das explicações.

## Roteiro de pitch em até 20 minutos

1. **2 min:** problema pedagógico e proposta de valor.
2. **3 min:** arquitetura frontend, backend, SQLite e responsividade.
3. **8 min:** demonstração ao vivo: cadastro, login, dois jogos, dica, erro, medalha, perfil e ranking.
4. **3 min:** mostrar no banco/histórico que a pontuação foi persistida.
5. **2 min:** organização do time e obstáculos superados.
6. **2 min:** perguntas da banca.
