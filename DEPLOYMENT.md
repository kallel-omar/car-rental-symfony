# Deployment Guide

This document provides instructions for deploying the Car Rental Management System to production environments.

## 📋 Pre-Deployment Checklist

- [ ] All tests passing locally
- [ ] Code reviewed and merged to main branch
- [ ] Environment variables configured
- [ ] Database backups created
- [ ] SSL certificate obtained (if not already present)
- [ ] Domain/hosting provider ready
- [ ] Team notified of deployment

## 🚀 Deployment Options

### Option 1: Traditional Server (Apache/Nginx)

#### Prerequisites
- PHP 8.0 or higher
- MySQL/MariaDB 5.7 or higher
- Composer installed
- Git installed
- SSH access to server

#### Steps

1. **Connect to your server**
   ```bash
   ssh user@your-domain.com
   ```

2. **Clone the repository**
   ```bash
   cd /var/www
   git clone https://github.com/kallel-omar/car-rental-symfony.git car-rental
   cd car-rental
   ```

3. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install --production
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with production values
   nano .env
   ```

5. **Set permissions**
   ```bash
   chmod -R 755 public
   chmod -R 777 var/log var/cache
   chown -R www-data:www-data /var/www/car-rental
   ```

6. **Create database**
   ```bash
   php bin/console doctrine:database:create --env=prod
   php bin/console doctrine:migrations:migrate --env=prod --no-interaction
   ```

7. **Build assets**
   ```bash
   npm run build
   php bin/console asset-map:compile
   ```

8. **Clear cache**
   ```bash
   php bin/console cache:clear --env=prod
   ```

9. **Configure web server**

   **For Nginx:**
   ```nginx
   server {
       listen 80;
       server_name your-domain.com;
       
       root /var/www/car-rental/public;
       index index.php;
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       
       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastfastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

   **For Apache:**
   ```apache
   <VirtualHost *:80>
       ServerName your-domain.com
       DocumentRoot /var/www/car-rental/public
       
       <Directory /var/www/car-rental/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

10. **Enable HTTPS (SSL)**
    ```bash
    # Using Let's Encrypt with Certbot
    sudo certbot certonly --webroot -w /var/www/car-rental/public -d your-domain.com
    ```

11. **Restart web server**
    ```bash
    # For Nginx
    sudo systemctl restart nginx
    
    # For Apache
    sudo systemctl restart apache2
    ```

### Option 2: Docker Deployment

#### Prerequisites
- Docker installed
- Docker Compose installed
- Docker Hub account (optional, for image hosting)

#### Steps

1. **Build Docker image**
   ```bash
   docker build -t car-rental:latest .
   ```

2. **Run with Docker Compose**
   ```bash
   docker-compose -f compose.yaml up -d
   ```

3. **Run migrations**
   ```bash
   docker-compose exec app php bin/console doctrine:migrations:migrate --env=prod
   ```

4. **Load fixtures (optional)**
   ```bash
   docker-compose exec app php bin/console doctrine:fixtures:load --env=prod
   ```

#### Production Docker Compose
See `compose.yaml` for configuration. Update environment variables in `compose.override.yaml` for production.

### Option 3: Platform as a Service (PaaS)

#### Heroku Deployment

1. **Install Heroku CLI**
   ```bash
   curl https://cli-assets.heroku.com/install.sh | sh
   ```

2. **Login to Heroku**
   ```bash
   heroku login
   ```

3. **Create app**
   ```bash
   heroku create your-app-name
   ```

4. **Set environment variables**
   ```bash
   heroku config:set APP_ENV=prod
   heroku config:set DATABASE_URL=your_db_url
   ```

5. **Deploy**
   ```bash
   git push heroku main
   ```

6. **Run migrations**
   ```bash
   heroku run php bin/console doctrine:migrations:migrate
   ```

#### Vercel/Netlify
Not recommended for PHP/Symfony applications. Use traditional servers, Docker, or specialized PHP hosting.

## 🔐 Security Configuration

### Environment Variables
Ensure these are set in production:
```
APP_ENV=prod
APP_DEBUG=false
DATABASE_URL=production_database_url
MAILER_DSN=production_email_config
```

### File Permissions
```bash
chmod -R 755 public
chmod -R 700 var/
chmod -R 700 config/
```

### Web Server Security
- Disable directory listing
- Hide sensitive files (.env, .git)
- Enable HTTPS/SSL
- Set security headers
- Configure firewall

## 📊 Monitoring & Maintenance

### Application Logs
```bash
tail -f var/log/prod.log
```

### Database Backups
```bash
# Daily automated backup
mysqldump -u user -p database > backup_$(date +%Y%m%d).sql
```

### Monitoring Tools
- New Relic
- DataDog
- Sentry (error tracking)
- ELK Stack (logging)

## 🔄 Continuous Deployment

### GitHub Actions Workflow
Set up automated deployments on push to main:

1. Configure deployment secrets in GitHub
2. Create `.github/workflows/deploy.yml`
3. Push changes to trigger deployment

## 📝 Post-Deployment

1. **Verify application**
   - Test login functionality
   - Check database connectivity
   - Verify email sending (if applicable)
   - Test file uploads

2. **Monitor logs**
   ```bash
   tail -f var/log/prod.log
   ```

3. **Check performance**
   - Response times
   - Error rates
   - Database queries

4. **Set up alerts**
   - High error rates
   - Disk space warnings
   - Database connection failures

## 🚨 Troubleshooting

### Common Issues

**500 Internal Server Error**
- Check logs: `var/log/prod.log`
- Verify database connection
- Check file permissions

**Database Connection Failed**
- Verify DATABASE_URL in .env
- Check MySQL/MariaDB is running
- Verify credentials

**Assets not loading**
- Run: `php bin/console asset-map:compile`
- Check Nginx/Apache configuration
- Clear browser cache

**Permission Denied**
- Check file ownership: `chown -R www-data:www-data /var/www/car-rental`
- Check permissions: `chmod -R 755 public`

## 📞 Support

For deployment issues:
1. Check logs in `var/log/prod.log`
2. Review this guide
3. Open an issue on GitHub
4. Contact server provider support

---

**Last Updated**: June 2026
