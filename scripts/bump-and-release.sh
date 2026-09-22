#!/usr/bin/env bash
# bump-and-release.sh 1.0.1 "Notas do release"
set -euo pipefail

VERSION="${1:?Uso: ./bump-and-release.sh 1.0.1 \"Notas\"}"
NOTES="${2:-Release $VERSION}"

if ! grep -q "Version:" style.css; then
  echo "style.css sem Version:" >&2
  exit 1
fi

# Atualiza Version no style.css (GNU/BSD sed)
if sed --version >/dev/null 2>&1; then
  sed -i "s/^Version:.*/Version: ${VERSION}/" style.css
else
  sed -i '' "s/^Version:.*/Version: ${VERSION}/" style.css
fi

git add style.css
git commit -m "chore: release v${VERSION}"
git push origin main
gh release create "v${VERSION}" --title "v${VERSION}" --notes "${NOTES}"

echo "Release v${VERSION} publicado. O WP deve solicitar atualização do tema."
