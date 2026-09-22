# Bump Version no style.css e cria GitHub Release (Windows PowerShell)
# Uso: .\scripts\bump-and-release.ps1 -Version 1.0.1 -Notes "Ajustes na Home"

param(
  [Parameter(Mandatory = $true)][string]$Version,
  [string]$Notes = "Release $Version"
)

$ErrorActionPreference = "Stop"
$style = Join-Path $PSScriptRoot "..\style.css" | Resolve-Path

$content = Get-Content $style -Raw
if ($content -notmatch "(?m)^Version:") {
  throw "style.css sem linha Version:"
}

$content = [regex]::Replace($content, "(?m)^Version:.*", "Version: $Version")
Set-Content -Path $style -Value $content -NoNewline

git add style.css
git commit -m "chore: release v$Version"
git push origin main
gh release create "v$Version" --title "v$Version" --notes $Notes

Write-Host "Release v$Version publicado. O WP deve solicitar atualização do tema."
