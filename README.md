# Instituto Dr. Chao — Tema WordPress

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
define('IDC_GITHUB_THEME_REPO', 'SEU_USER/instituto-dr-chao');
```

## Estrutura inicial

```
instituto-dr-chao/
├── style.css              # Cabeçalho do tema + Version
├── functions.php          # Setup + updater GitHub
├── header.php / footer.php / index.php
├── inc/plugin-update-checker/
└── README.md
```

## Desenvolvimento

Telas e componentes serão implementados a partir do Figma (Instituto Dr. Chao).
