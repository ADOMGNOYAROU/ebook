# Déploiement avec Ngrok - Assets CSS/JS

## Problème résolu

Les assets CSS/JS ne se chargeaient pas via ngrok car Vite les servait en localhost.

## Solution appliquée

1. **Construction des assets pour production**
   ```bash
   npm run build
   ```

2. **Configuration de l'URL publique**
   - Modifié `APP_URL` dans `.env` vers l'URL ngrok
   - Clear du cache de configuration

3. **Vérification**
   - Assets maintenant dans `public/build/`
   - Accessibles via l'URL ngrok publique

## Pour les futurs déploiements

### Étapes rapides
```bash
# 1. Construire les assets
npm run build

# 2. Mettre à jour l'URL dans .env
# Remplacer APP_URL par l'URL ngrok actuelle

# 3. Clear les caches
php artisan config:clear
php artisan cache:clear

# 4. Redémarrer les services si nécessaire
```

### Note importante
- L'URL ngrok change à chaque redémarrage (sauf compte payant)
- Pensez à reconstruire les assets si vous modifiez le CSS/JS
- Gardez `npm run dev` pour le développement local

## Accès actuel
- **URL publique** : https://vizarded-kittenishly-bennett.ngrok-free.dev
- **Dashboard ngrok** : http://127.0.0.1:4040
