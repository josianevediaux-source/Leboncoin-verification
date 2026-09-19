#!/bin/bash

# Configuration
OLD_USER="jacksparoy8-cloud"
NEW_USER="$1"  # Remplacer par ton nouveau compte
TOKEN="$2"    # Remplacer par ton PAT

if [ -z "$NEW_USER" ] || [ -z "$TOKEN" ]; then
  echo "Usage: ./migrate_repos.sh <nouveau_compte> <token_github>"
  exit 1
fi

REPOS=("leboncoin-com" "Leboncoin.com" "lydia" "lydia-authentification" "vinted" "wero-eu-es" "wero-eu-wallet" "wero-eu-wallet-v2" "wero-project85")

mkdir -p migration
cd migration

echo "🚀 Début de la migration..."
echo "Ancien compte: $OLD_USER"
echo "Nouveau compte: $NEW_USER"
echo ""

for repo_name in "${REPOS[@]}"; do
  echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
  echo "📦 Traitement de: $repo_name"
  echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
  
  # Créer le repo sur le nouveau compte
  echo "📝 Création du repo sur le nouveau compte..."
  response=$(curl -s -X POST \
    -H "Authorization: token $TOKEN" \
    -H "Accept: application/vnd.github.v3+json" \
    -d "{\"name\":\"$repo_name\",\"private\":false}" \
    https://api.github.com/user/repos)
  
  # Vérifier si c'est un erreur (repo existe déjà)
  if echo "$response" | grep -q "errors"; then
    echo "   ℹ️  Repo existe déjà (ou erreur API)"
  else
    echo "   ✓ Repo créé"
  fi
  
  # Clone mirror
  echo "📥 Clone du repo (mirror)..."
  if git clone --mirror https://github.com/$OLD_USER/$repo_name.git; then
    cd $repo_name.git
    
    # Push mirror
    echo "📤 Push vers le nouveau compte..."
    if git push --mirror https://$TOKEN@github.com/$NEW_USER/$repo_name.git; then
      echo "   ✓ Push réussi"
    else
      echo "   ✗ Erreur lors du push"
    fi
    
    cd ..
    rm -rf $repo_name.git
    echo "✅ $repo_name migré avec succès"
  else
    echo "   ✗ Erreur: impossible de cloner $repo_name"
  fi
  
  echo ""
done

cd ..
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✨ Migration complète!"
echo "Tous les repos de $OLD_USER sont maintenant sur $NEW_USER"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
