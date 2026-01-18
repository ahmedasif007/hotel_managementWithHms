# Deployment Guide - Hotel Management System

## Overview
This guide covers deploying the Hotel Management System to various environments including Docker, AWS, DigitalOcean, and traditional servers.

---

## Table of Contents
1. [Docker Deployment](#docker-deployment)
2. [AWS Deployment](#aws-deployment)
3. [DigitalOcean Deployment](#digitalocean-deployment)
4. [Traditional Server Deployment](#traditional-server-deployment)
5. [Environment Configuration](#environment-configuration)
6. [Database Backup & Recovery](#database-backup--recovery)
7. [Monitoring & Logging](#monitoring--logging)

---

## Docker Deployment

### Prerequisites
- Docker and Docker Compose installed
- `.env` file configured

### Quick Start
```bash
# Clone repository
git clone <repository-url>
cd hms-laravel

# Create .env file
cp .env.example .env
# Edit .env with your values

# Start services
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --seed

# Access application
# Web: http://localhost
# API: http://localhost/api
# PHPMyAdmin: http://localhost:8080
```

### Docker Services
- **app**: PHP-FPM application container
- **webserver**: Nginx web server
- **db**: MySQL 8.0 database
- **redis**: Redis cache service
- **phpmyadmin**: Database management (optional)

### Common Commands
```bash
# View logs
docker-compose logs -f app

# Access app container
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan <command>

# Stop services
docker-compose down

# Rebuild containers
docker-compose up --build -d
```

---

## AWS Deployment

### Architecture
- **EC2**: Application server
- **RDS**: Managed MySQL database
- **ElastiCache**: Redis for caching
- **S3**: File storage
- **CloudFront**: CDN
- **Route 53**: DNS management
- **ACM**: SSL certificates

### Step 1: Prepare AWS Resources
```bash
# Create VPC and security groups
# Create RDS MySQL instance
# Create ElastiCache Redis cluster
# Create S3 bucket for uploads
```

### Step 2: Launch EC2 Instance
```bash
# Ubuntu 20.04 LTS AMI recommended
# t3.medium or larger instance type
# 20GB EBS volume minimum

# Install dependencies
sudo apt-get update
sudo apt-get install -y php8.2-fpm php8.2-mysql php8.2-redis nginx composer git

# Clone application
cd /var/www
sudo git clone <repository-url> hms-laravel
cd hms-laravel
sudo composer install --no-dev

# Configure environment
sudo cp .env.production .env
# Edit .env with RDS/ElastiCache endpoints
sudo php artisan key:generate
sudo php artisan migrate --force
```

### Step 3: Configure Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;

    root /var/www/hms-laravel/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Step 4: SSL Certificate
```bash
# Install Certbot
sudo apt-get install -y certbot python3-certbot-nginx

# Issue certificate
sudo certbot certonly --nginx -d your-domain.com

# Auto-renew
sudo systemctl enable certbot.timer
```

### Step 5: Queue Configuration
```bash
# Install supervisor for queue processing
sudo apt-get install -y supervisor

# Create supervisor config
sudo nano /etc/supervisor/conf.d/hms-queue.conf

# Add configuration:
[program:hms-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/hms-laravel/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/hms-queue.log

# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start hms-queue:*
```

---

## DigitalOcean Deployment

### Using App Platform (Easiest)
```bash
# Connect GitHub repository
# Configure build command: composer install --no-dev
# Configure run command: gunicorn app:app
# Set environment variables from .env.production
# Deploy automatically on push
```

### Using Droplet
```bash
# Create Droplet (Ubuntu 20.04)
# SSH into droplet
ssh root@your-ip

# Install dependencies
apt-get update
apt-get install -y php8.2-fpm php8.2-mysql nginx composer git redis-server supervisor

# Clone and setup (same as AWS)
cd /var/www
git clone <repository-url> hms-laravel
cd hms-laravel
composer install --no-dev

# Configure firewall
ufw allow 22
ufw allow 80
ufw allow 443
ufw enable
```

---

## Traditional Server Deployment

### Requirements
- PHP 8.2+
- MySQL 8.0+
- Redis (optional)
- Nginx or Apache
- Composer
- SSH access

### Installation
```bash
# SSH into server
ssh user@your-server.com

# Navigate to web root
cd /var/www/html

# Clone repository
git clone <repository-url> hms-laravel
cd hms-laravel

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
chown -R www-data:www-data .
chmod -R 755 storage bootstrap/cache

# Create environment file
cp .env.production .env
# Edit with your database credentials

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup cron job
crontab -e
# Add: * * * * * cd /var/www/html/hms-laravel && php artisan schedule:run >> /dev/null 2>&1
```

---

## Environment Configuration

### Development (.env.local)
```env
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
MAIL_MAILER=log
```

### Testing (.env.testing)
```env
APP_ENV=testing
APP_DEBUG=true
DB_DATABASE=hms_test
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
```

### Production (.env.production)
```env
APP_ENV=production
APP_DEBUG=false
DB_HOST=your-rds-endpoint.rds.amazonaws.com
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
MAIL_MAILER=mailgun
MAIL_HOST=smtp.mailgun.org
```

---

## Database Backup & Recovery

### Automated Backups
```bash
# Create backup script
vim /usr/local/bin/backup-hms.sh

#!/bin/bash
BACKUP_DIR="/backups/hms"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR
mysqldump -u root -p'password' hms_prod > $BACKUP_DIR/hms_$DATE.sql
gzip $BACKUP_DIR/hms_$DATE.sql

# Keep only 7 days of backups
find $BACKUP_DIR -type f -mtime +7 -delete

# Schedule with crontab
# 0 2 * * * /usr/local/bin/backup-hms.sh

# Make executable
chmod +x /usr/local/bin/backup-hms.sh
```

### Manual Recovery
```bash
# List backups
ls -la /backups/hms/

# Restore from backup
gunzip < /backups/hms/hms_20240101_020000.sql.gz | mysql -u root -p hms_prod

# Verify data
mysql -u root -p hms_prod -e "SELECT COUNT(*) as reservations FROM reservations;"
```

---

## Monitoring & Logging

### Application Logs
```bash
# Tail logs in real-time
tail -f /var/www/hms-laravel/storage/logs/laravel.log

# Check for errors
grep ERROR /var/www/hms-laravel/storage/logs/laravel.log | tail -20
```

### Health Checks
```bash
# Test API health
curl http://your-domain.com/api/health

# Monitor with uptime service
# Use services like: Uptime Robot, StatusPage, New Relic
```

### Performance Monitoring
```bash
# New Relic integration
composer require newrelic/newrelic-php-agent

# DataDog integration
composer require datadog/dd-trace

# Configure in .env
NEW_RELIC_APP_NAME=HMS
NEW_RELIC_LICENSE_KEY=your-key
```

### Error Tracking
```bash
# Sentry integration
composer require sentry/sentry-laravel

# Configure in .env
SENTRY_DSN=your-dsn-key

# Test
php artisan sentry:test
```

---

## SSL/TLS Setup

### Let's Encrypt with Certbot
```bash
# Install
sudo apt-get install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot certonly --nginx -d your-domain.com

# Auto-renew
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer

# Verify
openssl x509 -in /etc/letsencrypt/live/your-domain.com/cert.pem -text -noout
```

---

## Security Checklist

- [ ] Change default passwords
- [ ] Enable firewall
- [ ] Install SSL certificate
- [ ] Configure CORS properly
- [ ] Enable rate limiting
- [ ] Setup VPN for admin access
- [ ] Enable database encryption
- [ ] Configure backups
- [ ] Setup monitoring
- [ ] Enable access logs
- [ ] Configure security headers
- [ ] Disable debug mode in production

---

## Troubleshooting

### Application won't start
```bash
# Check logs
tail -f storage/logs/laravel.log

# Check permissions
chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Database connection failed
```bash
# Test connection
mysql -h your-host -u username -p database_name

# Check environment variables
grep DB_ .env

# Verify RDS security group
# Check inbound rule for port 3306 from your IP
```

### Queue not processing
```bash
# Check supervisor status
sudo supervisorctl status

# Restart queue workers
sudo supervisorctl restart hms-queue:*

# Check queue table
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

---

## Performance Optimization

### Caching Strategy
```php
// Cache database queries
Cache::remember('rooms.available', 3600, function() {
    return Room::where('status', 'available')->get();
});
```

### Database Optimization
```sql
-- Add indexes
ALTER TABLE reservations ADD INDEX idx_check_in(check_in_date);
ALTER TABLE invoices ADD INDEX idx_issue_date(issue_date);
```

### Asset Optimization
```bash
# Minify assets
npm run production

# Enable gzip compression in Nginx
gzip on;
gzip_types text/plain text/css text/javascript application/json;
```

---

## Support & Resources

- **Documentation**: https://laravel.com/docs
- **GitHub Issues**: Report bugs and feature requests
- **Email**: support@hotel.com
- **Status Page**: https://status.hotel.com

---

**Last Updated:** 2024
**Version:** 1.0.0
