#!/bin/bash

OLD_USER="jacksparoy8-cloud"
NEW_USER="$1"
TOKEN="$2"

REPOS=("leboncoin-com" "Leboncoin.com" "lydia" "lydia-authentification" "vinted" "wero-eu-es" "wero-eu-wallet" "wero-eu-wallet-v2" "wero-project85")

mkdir -p /tmp/migration
cd /tmp/migration

for repo_name in "${REPOS[@]}"; do
  echo "=== Création du repo $repo_name sur le nouveau compte ==="
  curl -s -X POST \
    -H "Authorization: token $TOKEN" \
    -H "Accept: application/vnd.github.v3+json" \
    -d "{\"name\":\"$repo_name\",\"private\":false}" \
    https://api.github.com/user/repos > /dev/null
  
  echo "=== Migration de $repo_name ==="
  
  git clone --mirror https://github.com/$OLD_USER/$repo_name.git 2>&1 | tail -1
  cd $repo_name.git
  
  git push --mirror https://$TOKEN@github.com/$NEW_USER/$repo_name.git 2>&1 | tail -5
  
  cd ..
  rm -rf $repo_name.git
  echo "✓ $repo_name terminé"
  echo ""
done

echo "✅ Migration complète!"
