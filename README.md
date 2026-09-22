# Instituto Dr. Chao — Tema WordPress

Desenvolvido por **[Hitechsp](https://hitechsp.com.br)**.

Tema institucional com **atualizações automáticas via GitHub Releases**.

Quando a versão no repositório sobe e um Release é publicado, o WordPress exibe o aviso de atualização do tema em **Aparência → Temas**.

## Instalação no WordPress

1. Baixe o ZIP do último Release (ou clone e compacte a pasta do tema).
2. Em WP: **Aparência → Temas → Adicionar → Enviar tema**.
3. Ative **Instituto Dr. Chao**.

A pasta do tema dentro de `wp-content/themes/` deve se chamar `instituto-dr-chao`.

## Como publicar uma atualização (fluxo obrigatório)

1. Altere a `Version:` em `style.css` (ex.: `1.0.0` → `1.0.1`).
2. Commit + push na branch `main`.
3. Crie um **GitHub Release** com tag **igual à Version** (com ou sem `v`):
   ```bash
   gh release create v1.0.1 --title "v1.0.1" --notes "Correções e ajustes"
   ```
4. No painel do WP, aguarde a checagem (ou force em **Painel → Atualizações**) — o tema pedirá atualização.

O updater usa [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) apontando para este repositório.

## Repo privado

Se o repositório for privado, adicione no `wp-config.php`:

```php
define('IDC_GITHUB_TOKEN', 'ghp_seu_token_com_leitura_do_repo');
define('IDC_GITHUB_THEME_REPO', 'laerciohp/institutodrchao');
```

## Estrutura

```
instituto-dr-chao/
├── theme.json
├── style.css
├── functions.php
├── front-page.php
├── header.php / footer.php / index.php
├── assets/css/            # tokens, base, header, footer, hero
├── assets/icons/          # logo e ícones do Figma
├── assets/images/
├── template-parts/
│   ├── components/        # logo, button
│   └── home/              # hero (+ próximas seções)
├── inc/
│   ├── helpers.php        # WhatsApp ?origem=
│   ├── acf/options.php
│   ├── cpt/               # profissional, tratamento
│   └── plugin-update-checker/
└── README.md
```

## Dependências no WP

- **ACF** (Advanced Custom Fields) — opções da clínica, Hero e campos dos CPTs.
- Menu em **Aparência → Menus** (local: Menu principal).

## Setup em staging / após atualizar o tema

O setup cria páginas (`/instalacoes/`, especialidades, etc.), menus, seeds ACF e corpo clínico.

1. Em **Aparência → Temas**, use o aviso **“Configurar páginas do Instituto”** (ou reative o tema) para rodar `idc_setup_ensure_pages` de novo.
2. Em **Configurações → Links permanentes**, clique em **Salvar** para fazer flush das rewrite rules — necessário para `/corpo-clinico/` (CPT) e URLs novas.
3. Confira se `/instalacoes/` e `/corpo-clinico/` respondem 200.

Sem esse re-run + flush, o staging pode continuar sem Instalações no menu ou com 404 no arquivo do corpo clínico.

## Desenvolvimento (Figma → código)

Ordem: tokens → componentes → templates → ACF → QA frame a frame (1280 / 390).
Sem Elementor. Ver plano operacional no board do projeto.

Páginas internas de especialidade (v1.5+) usam hero **split** (foto local em `assets/images/pages/`), blocos ricos (`treatment-cards`, `phases`, `integrativa-grid`) e copy padrão em `inc/defaults-pages.php` (fonte `_import-prod`).
