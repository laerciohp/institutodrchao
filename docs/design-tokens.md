# Design tokens — Instituto Dr. Chao

Fonte visual oficial: Figma `SRIgoZrVREsbQ5qDam6Kwn`.  
Arquivos canônicos: `assets/css/tokens.css` e `theme.json`.

| Token | Hex | Uso Figma / UI |
|-------|-----|----------------|
| `--idc-azul-900` | `#333d4f` | Navy principal, headers, strip CTA |
| `--idc-azul-700` | `#414a5c` | Navy secundário |
| `--idc-azul-500` | `#5c6470` | Texto muted / ícones |
| `--idc-terracota-800` | `#593400` | Accent escuro |
| `--idc-terracota-700` | `#96602f` | Accent botões / links |
| `--idc-ouro` | `#b6873b` | Destaque tipográfico / eyebrow |
| `--idc-ink` | `#45474c` | Corpo de texto |
| `--idc-cream` | `#fcf9f4` | Fundo de página |
| `--idc-sand` | `#e5e2dd` | Neutro de superfície |
| `--idc-sand-warm` | `#f3e9db` | Cards hub / seções quentes |
| `--idc-peach` | `#f1e1cc` | Cards hub tom peach |
| `--idc-border` | `#c5c6cd` | Bordas |
| `--idc-white` | `#ffffff` | Superfície clara |

## Tipografia

| Token | Família | Uso |
|-------|---------|-----|
| `--idc-font-sans` | Montserrat | UI, leads, botões |
| `--idc-font-serif` | Roboto Serif | Títulos display |

## Componentes (`template-parts/components/`)

| Partial | Props principais |
|---------|------------------|
| `button` | `label`, `href`, `variant`, `arrow`, `origem` |
| `eyebrow` | `text`, `variant` (`accent`\|`muted`) |
| `section-header` | `eyebrow`, `title`, `lead`, `title_id` |
| `hub-card` | `title`, `text`, `link_*`, `image`, `tone` |
| `accordion` | `title`, `items[]` |
| `strip-cta` | `title`, `lead`, `label`, `secondary_*`, `origem` |
| `testimonial-card` | `quote`, `name`, `role`, `photo`, `rating` |
| `breadcrumb` | (ver partial) |
| `logo` / `whatsapp-fab` | branding / FAB |

Variantes só via props/classes do DS — evitar CSS one-off por página.

## Canva Brand Kit

Se o Brand Kit for enviado depois: cruzar tipografia/cores/logo e ajustar tokens nesta tabela. Layouts continuam Figma.
