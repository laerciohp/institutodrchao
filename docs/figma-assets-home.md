# Assets Figma → Home (file `SRIgoZrVREsbQ5qDam6Kwn`)

Fonte: download via browser (Propriedades → “Botão para baixar imagem”), pasta `assets/images/_figma-browser/` (2026-09-23).
MCP Starter está rate-limited; Export 2× no canvas View-only falhou — fills JPEG/PNG do painel são a fonte.

| Seção | Node | Arquivo no tema | Notas |
|-------|------|-----------------|-------|
| Hero (foto paciente) | `133:342` | `assets/images/hero-photo.png` (+ `hero-photo-figma.png`) | fill hash `4c5c2120…` (~3.6 MB). Copy Figma: «A dor não precisa definir a **sua vida.**» |
| Hero (pattern fundo) | `133:310` | `assets/icons/hero-bg-pattern.svg` | SVG |
| Pilares — foto Ortopedia | `133:407` | `assets/images/pillar-ortopedia.jpg` | **exame do joelho** (fill ~32 KB `9ae84f…`). Não usar hub/modelo anatômico. |
| Pilares — título | — | ACF | «Três pilares de **cuidado**» |
| Pilares — ícone Fisio | `133:365` | `assets/icons/icon-fisioterapia-figma.svg` | SVG |
| Pilares — ícone Integrativa | `133:381` | `assets/icons/icon-integrativa-figma.svg` | SVG |
| Pilares — ícone Ortopedia | `133:396` | `assets/icons/icon-ortopedia-figma.svg` | SVG |
| Pilares — pattern CTA | `133:410` | `assets/icons/pillars-cta-pattern.svg` | SVG |
| Por que escolher | `133:424` | `assets/images/why-choose.png` (= `why-choose-figma.png`) | PNG 1201×880 (~517 KB) |
| Hub Especialidades — Orto | `133:1042` (fill) | `assets/images/pages/hub-ortopedia.jpg` | modelo anatômico (só hub) |
| Hub Especialidades — Fisio | `133:1059` (fill) | `assets/images/pages/hub-fisio.jpg` | ~2.3 MB |
| Hub Especialidades — Integrativa | `133:1076` (fill) | `assets/images/pages/hub-integrativa.jpg` | ~4.5 MB |
| Hero páginas especialidade | fills distintos | `hero-ortopedia.png` (médico+modelo), `hero-fisioterapia.jpg` (= hub), `hero-integrativa.jpg` (= hub) | Orto: PNG preferido via `idc_theme_image` |
| Carreiras (Image Container) | `242:17395` (fill) | `assets/images/pages/hero-carreiras.jpg` | ~3 MB |
| O Instituto (Left Image) | `133:2196` (fill) | `assets/images/pages/instituto-interior.jpg` | (+ PNG se sincronizado) |
| Contato | — | sem foto de hero (mapa embed) | OK |
| Instalações / equipe / blog posts | produção | `instalacoes/`, `team/`, `blog/` | não são fills Site Figma |
| Blog Home — card 1 | `136:13078` | `assets/images/blog/figma-joelho-corrida.jpg` | fill |
| Blog Home — card 2 | `136:13095` | `assets/images/blog/figma-acupuntura.jpg` | fill |
| Blog Home — card 3 | `136:13112` | `assets/images/blog/figma-fisio-pos-op.jpg` | fill |
| Diferenciais — ícones | — | `assets/icons/diff-{1..4}-figma.svg` | SVG |

## CMS (painel WP)

- **Página Início** → ACF `Imagem do hero`, `Imagem` (Diferenciais), card Ortopedia em `Cards dos pilares`.
- Vazio = fallback do tema. Com mídia = override (modal «Adicionar imagem» → Biblioteca / Enviar).
- `IDC Opções → Home` pode falhar sem ACF Pro; use a página Início.
