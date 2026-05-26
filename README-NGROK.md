# Accès en ligne avec Ngrok - Plateforme E-books

## Installation rapide

1. **Créez un compte Ngrok gratuit**
   - Allez sur https://dashboard.ngrok.com/signup
   - Inscrivez-vous gratuitement

2. **Obtenez votre authtoken**
   - Connectez-vous à votre dashboard
   - Allez sur https://dashboard.ngrok.com/get-started/your-authtoken
   - Copiez votre authtoken

3. **Lancez la plateforme en ligne**
   ```bash
   # Double-cliquez sur ce fichier ou exécutez dans le terminal:
   start-platform.bat
   ```
   
   Ou manuellement:
   ```bash
   # 1. Configurez ngrok avec votre authtoken
   ngrok config add-authtoken VOTRE_AUTHTOKEN
   
   # 2. Démarrez le serveur Laravel
   php artisan serve --host=0.0.0.0 --port=8000
   
   # 3. Dans un autre terminal, démarrez ngrok
   ngrok http 8000
   ```

## URLs d'accès

Une fois ngrok démarré, vous aurez:

- **URL publique ngrok**: `https://xxxxx.ngrok-free.app` (partagez cette URL)
- **Interface ngrok**: `http://127.0.0.1:4040` (pour voir les logs)

## Fonctionnalités accessibles

Via l'URL publique ngrok, les utilisateurs pourront:

- 📚 Consulter le catalogue d'e-books
- 👤 Créer un compte et se connecter  
- 📥 Télécharger les e-books gratuits
- 💰 Acheter les e-books payants (si configuré)
- 🔐 Accéder à leur dashboard personnel

## Notes importantes

- L'URL ngrok change à chaque démarrage (sauf avec compte payant)
- Le serveur doit rester allumé pour que l'URL soit accessible
- Pour un usage professionnel, envisagez ngrok Pro ou un hébergement dédié

## Dépannage

**"ERR_NGROK_4018"**: Vous devez configurer votre authtoken (voir étape 2)

**"Port 8000 déjà utilisé"**: Arrêtez les autres serveurs Laravel ou changez le port

**Timeout**: Vérifiez votre connexion internet
