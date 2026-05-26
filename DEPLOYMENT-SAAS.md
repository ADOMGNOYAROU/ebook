# 🚀 Guide de Déploiement - Plateforme E-Book SaaS

## 📋 Prérequis

### Infrastructure
- **Serveur**: Ubuntu 20.04+ ou CentOS 8+
- **RAM**: Minimum 4GB (recommandé 8GB+)
- **Stockage**: 50GB+ SSD
- **CPU**: 2+ coeurs
- **Domaine**: Nom de domaine principal configuré

### Logiciels
- **PHP**: 8.2+
- **MySQL**: 8.0+
- **Redis**: 6.0+
- **Nginx**: 1.18+
- **Node.js**: 18+
- **Composer**: 2.0+
- **Git**: 2.0+

---

## 🏗 Architecture de Déploiement

```
┌─────────────────────────────────────────────────────────────┐
│                    SERVEUR PRODUCTION                       │
├─────────────────────────────────────────────────────────────┤
│  Nginx (Reverse Proxy + SSL)                               │
│  ├─ votre-domaine.com (Landing)                            │
│  ├─ app.votre-domaine.com (API Gateway)                    │
│  ├─ tenant1.votre-domaine.com (Client 1)                  │
│  ├─ tenant2.votre-domaine.com (Client 2)                  │
│  └─ ...                                                    │
├─────────────────────────────────────────────────────────────┤
│  PHP-FPM (Laravel Application)                              │
│  ├─ Codebase partagée                                      │
│  ├─ Multi-tenant middleware                               │
│  └─ Queue Worker                                            │
├─────────────────────────────────────────────────────────────┤
│  MySQL (Base de données)                                   │
│  ├─ saas_main (tenants, plans, subscriptions)             │
│  ├─ tenant_1 (données client 1)                           │
│  ├─ tenant_2 (données client 2)                           │
│  └─ ...                                                    │
├─────────────────────────────────────────────────────────────┤
│  Redis (Cache + Queues)                                    │
│  ├─ Cache sessions                                          │
│  ├─ Cache applications                                      │
│  └─ Queue jobs                                             │
├─────────────────────────────────────────────────────────────┤
│  Stockage                                                   │
│  ├─ /var/www/storage/app/public/ (couvertures)             │
│  ├─ /var/www/storage/app/private/ (PDFs)                   │
│  └─ /var/www/storage/logs/                                 │
└─────────────────────────────────────────────────────────────┘
```

---

## 📦 Étape 1: Configuration du Serveur

### 1.1 Mise à jour du système
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip
```

### 1.2 Installation de PHP 8.2
```bash
sudo apt install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-redis php8.2-zip php8.2-gd php8.2-curl php8.2-xml php8.2-mbstring php8.2-bcmath
```

### 1.3 Installation de MySQL
```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

### 1.4 Installation de Redis
```bash
sudo apt install -y redis-server
sudo systemctl enable redis-server
```

### 1.5 Installation de Nginx
```bash
sudo apt install -y nginx
sudo systemctl enable nginx
```

### 1.6 Installation de Node.js
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 1.7 Installation de Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

---

## 🗄️ Étape 2: Configuration des Bases de Données

### 2.1 Création de la base principale
```sql
mysql -u root -p
CREATE DATABASE saas_main CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'saas_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe';
GRANT ALL PRIVILEGES ON saas_main.* TO 'saas_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2.2 Configuration automatique des bases tenants
```sql
-- Le script créera automatiquement les bases tenant_XXXX lors de la création
-- Exemple pour le premier tenant:
CREATE DATABASE tenant_1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON tenant_1.* TO 'saas_user'@'localhost';
```

---

## 🚀 Étape 3: Déploiement de l'Application

### 3.1 Clonage du projet
```bash
cd /var/www
sudo git clone https://github.com/votre-repo/ebook-saas-platform.git
sudo chown -R www-data:www-data ebook-saas-platform
cd ebook-saas-platform
```

### 3.2 Installation des dépendances
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 3.3 Configuration de l'environnement
```bash
cp .env.example .env
sudo nano .env
```

### 3.4 Variables d'environnement critiques
```bash
APP_NAME="E-Book SaaS Platform"
APP_ENV=production
APP_KEY=base64:votre_cle_aleatoire
APP_DEBUG=false
APP_URL=https://app.votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saas_main
DB_USERNAME=saas_user
DB_PASSWORD=votre_mot_de_passe

DB_TENANT_CONNECTION=mysql
DB_TENANT_HOST=127.0.0.1
DB_TENANT_PORT=3306
DB_TENANT_USERNAME=saas_user
DB_TENANT_PASSWORD=votre_mot_de_passe

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Configuration multi-tenants
TENANT_DATABASE_PREFIX=tenant_
TENANT_SUBDOMAIN_DOMAIN=votre-domaine.com

# Stripe
STRIPE_KEY=pk_live_votre_cle_stripe
STRIPE_SECRET=sk_live_votre_cle_secrete_stripe
STRIPE_WEBHOOK_SECRET=whsec_votre_webhook_secret

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-fournisseur.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@domaine.com
MAIL_PASSWORD=votre_mot_de_passe_email
MAIL_ENCRYPTION=tls

# Stockage
FILESYSTEM_DISK=public
AWS_ACCESS_KEY_ID=votre_cle_aws
AWS_SECRET_ACCESS_KEY=votre_secret_aws
AWS_DEFAULT_REGION=eu-west-3
AWS_BUCKET=votre_bucket_s3
AWS_USE_PATH_STYLE_ENDPOINT=true
```

### 3.5 Optimisation de Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --class=PlanSeeder
php artisan storage:link
php artisan optimize
```

---

## 🌐 Étape 4: Configuration Nginx

### 4.1 Configuration du domaine principal
```bash
sudo nano /etc/nginx/sites-available/votre-domaine.com
```

```nginx
server {
    listen 80;
    server_name votre-domaine.com www.votre-domaine.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name votre-domaine.com www.votre-domaine.com;

    ssl_certificate /etc/letsencrypt/live/votre-domaine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/votre-domaine.com/privkey.pem;

    root /var/www/ebook-saas-platform/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 4.2 Configuration des sous-domaines (wildcard)
```bash
sudo nano /etc/nginx/sites-available/app.votre-domaine.com
```

```nginx
server {
    listen 80;
    server_name *.votre-domaine.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name *.votre-domaine.com;

    ssl_certificate /etc/letsencrypt/live/votre-domaine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/votre-domaine.com/privkey.pem;

    root /var/www/ebook-saas-platform/public;
    index index.php index.html;

    # Log pour le debugging
    access_log /var/log/nginx/tenant_access.log;
    error_log /var/log/nginx/tenant_error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Passer le sous-domaine à Laravel
        fastcgi_param HTTP_HOST $host;
    }

    # Configuration pour les uploads
    client_max_body_size 100M;

    location ~ /\.ht {
        deny all;
    }
}
```

### 4.3 Activation des sites
```bash
sudo ln -s /etc/nginx/sites-available/votre-domaine.com /etc/nginx/sites-enabled/
sudo ln -s /etc/nginx/sites-available/app.votre-domaine.com /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🔒 Étape 5: Configuration SSL (Let's Encrypt)

### 5.1 Installation de Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 5.2 Génération des certificats
```bash
# Pour le domaine principal
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com

# Pour le wildcard (nécessite validation DNS)
sudo certbot certonly --manual --preferred-challenges dns -d "*.votre-domaine.com" -d votre-domaine.com
```

### 5.3 Renouvellement automatique
```bash
sudo crontab -e
# Ajouter cette ligne:
0 12 * * * /usr/bin/certbot renew --quiet
```

---

## ⚙️ Étape 6: Configuration des Services

### 6.1 Configuration PHP-FPM
```bash
sudo nano /etc/php/8.2/fpm/pool.d/www.conf
```

```ini
; Optimisation pour la production
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

; Augmenter les limites pour les uploads
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 512M
max_execution_time = 300
```

### 6.2 Configuration Redis
```bash
sudo nano /etc/redis/redis.conf
```

```ini
# Mode production
maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

### 6.3 Configuration des Workers Laravel
```bash
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ebook-saas-platform/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/ebook-saas-platform/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

---

## 📊 Étape 7: Monitoring et Logs

### 7.1 Configuration des logs
```bash
sudo nano /etc/logrotate.d/laravel
```

```
/var/www/ebook-saas-platform/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        /usr/bin/php /var/www/ebook-saas-platform/artisan log:clear
    endscript
}
```

### 7.2 Monitoring basique
```bash
# Script de monitoring santé
sudo nano /usr/local/bin/check-saas-health.sh
```

```bash
#!/bin/bash
# Vérifier si le site répond
if curl -f -s https://app.votre-domaine.com/health > /dev/null; then
    echo "✅ SaaS Platform OK"
else
    echo "❌ SaaS Platform DOWN"
    # Envoyer alerte (email, Slack, etc.)
fi

# Vérifier les workers
if pgrep -f "artisan queue:work" > /dev/null; then
    echo "✅ Queue Workers OK"
else
    echo "❌ Queue Workers DOWN"
    sudo supervisorctl restart lar-worker:*
fi
```

```bash
sudo chmod +x /usr/local/bin/check-saas-health.sh
# Ajouter au cron toutes les 5 minutes
*/5 * * * * /usr/local/bin/check-saas-health.sh >> /var/log/saas-health.log
```

---

## 🔄 Étape 8: Mises à Jour et Maintenance

### 8.1 Script de déploiement
```bash
sudo nano /usr/local/bin/deploy-saas.sh
```

```bash
#!/bin/bash
cd /var/www/ebook-saas-platform

echo "🔄 Début du déploiement..."

# Maintenance mode
php artisan down

# Pull des dernières modifications
git pull origin main

# Installation des dépendances
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Nettoyage du cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Migration de la base
php artisan migrate --force

# Optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Redémarrage des workers
sudo supervisorctl restart lar-worker:*

# Fin maintenance
php artisan up

echo "✅ Déploiement terminé!"
```

### 8.2 Backup automatique
```bash
sudo nano /usr/local/bin/backup-saas.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/saas"

# Créer le répertoire de backup
mkdir -p $BACKUP_DIR

# Backup de la base principale
mysqldump -u saas_user -p saas_main > $BACKUP_DIR/saas_main_$DATE.sql

# Backup des bases tenants (boucle sur tous les tenants)
for DB in $(mysql -u saas_user -p -e "SHOW DATABASES LIKE 'tenant_%'" | grep tenant_); do
    mysqldump -u saas_user -p $DB > $BACKUP_DIR/$DB\_$DATE.sql
done

# Backup des fichiers
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/ebook-saas-platform/storage/app

# Nettoyage (garder 7 jours)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "✅ Backup terminé: $DATE"
```

---

## 🚨 Étape 9: Sécurité

### 9.1 Pare-feu
```bash
sudo ufw enable
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
```

### 9.2 Permissions des fichiers
```bash
sudo chown -R www-data:www-data /var/www/ebook-saas-platform
sudo find /var/www/ebook-saas-platform -type f -exec chmod 644 {} \;
sudo find /var/www/ebook-saas-platform -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/ebook-saas-platform/storage
sudo chmod -R 775 /var/www/ebook-saas-platform/bootstrap/cache
```

### 9.3 Configuration fail2ban
```bash
sudo apt install -y fail2ban
sudo nano /etc/fail2ban/jail.local
```

```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[sshd]
enabled = true
port = ssh
filter = sshd
logpath = /var/log/auth.log

[nginx-http-auth]
enabled = true
filter = nginx-http-auth
logpath = /var/log/nginx/error.log
maxretry = 3
```

---

## 📈 Étape 10: Performance

### 10.1 Configuration OPcache
```bash
sudo nano /etc/php/8.2/mods-available/opcache.ini
```

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=0
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.load_comments=1
```

### 10.2 Configuration MySQL
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

```ini
[mysqld]
innodb_buffer_pool_size = 2G
innodb_log_file_size = 256M
innodb_flush_method = O_DIRECT
query_cache_size = 64M
query_cache_type = 1
max_connections = 200
```

---

## ✅ Vérification Finale

### Tests post-déploiement
```bash
# 1. Vérifier que le site répond
curl -I https://votre-domaine.com
curl -I https://app.votre-domaine.com

# 2. Vérifier la connexion à la base
php artisan tinker
>>> DB::connection()->getPdo();

# 3. Vérifier Redis
php artisan tinker
>>> Redis::ping();

# 4. Vérifier les queues
php artisan queue:failed

# 5. Vérifier le stockage
php artisan storage:link
```

### Monitoring en production
- **Uptime**: UptimeRobot ou Pingdom
- **Performance**: New Relic ou DataDog
- **Logs**: ELK Stack (Elasticsearch + Logstash + Kibana)
- **Alertes**: Slack/Email pour les erreurs critiques

---

## 🎯 Prochaines Étapes

1. **Monitoring avancé**: Configuration Grafana + Prometheus
2. **CDN**: CloudFlare pour les assets statiques
3. **Load Balancing**: Multiple serveurs avec HAProxy
4. **Containerisation**: Docker + Kubernetes
5. **CI/CD**: GitHub Actions pour le déploiement automatique

---

## 📞 Support

En cas de problème:
1. Vérifier les logs: `/var/log/nginx/`, `/var/www/ebook-saas-platform/storage/logs/`
2. Redémarrer les services: `sudo systemctl restart nginx php8.2-fpm redis-server`
3. Vérifier le statut: `sudo systemctl status nginx php8.2-fpm redis-server`

**🎉 Votre plateforme SaaS est maintenant en production!**
