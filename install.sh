#!/bin/sh

################################################################################
#
# Interactive service installer for proxy server application
# this generates nginx config files and an /etc/init.d/nginx script, and installs them
# this scripts should be run as root
#
################################################################################

RED='\033[0;31m' # Red Color
GREEN='\033[0;32m' # Green Color
YELLOW='\033[1;33m' # Yellow Color
NC='\033[0m' # No Color

die () {
    echo "${RED}ERROR${NC}: $1. Aborting!"
    exit 1
}
success () {
    echo "${GREEN}SUCCESS${NC}: $1."
}

#check for root user
if [ "$(id -u)" -ne 0 ] ; then
    die "You must run this script as root user"
fi

#Initial defaults
ROOT_PATH=$(pwd)
_NGINX_PORT=8080
_SSL_ENABLE='no'
SSL_ENABLE=no
_SSL_CERT=''
_SSL_KEY=''
uri='$uri'
args='$args'
SERVER_FILE="configs/server.conf"
PROXY_HOST="http://localhost:8080"

#Read the nginx port
read -p "Please select the installer port no. for this instance: [$_NGINX_PORT] " NGINX_PORT
if ! echo $NGINX_PORT | egrep -q '^[0-9]+$' ; then
    echo "Selecting default: $_NGINX_PORT"
    NGINX_PORT=$_NGINX_PORT
fi

#Read the ssl options
read -p "Use HTTPS (SSL): [no/yes] " SSL_ENABLE
if [ $SSL_ENABLE = yes ] ; then
    echo "\nYou must have valid certificate and certificate key to configure HTTPS.\n"
    #Read SSL certificates
    read -p "Please enter the SSL Certificate file: " SSL_CERT
    if [ -z "$SSL_CERT" ] ; then
        echo "Selecting default: $_SSL_CERT"
        SSL_CERT=$_SSL_CERT
    fi

    #Read SSL certificates key
    read -p "Please enter the SSL Certificate key file: " SSL_KEY
    if [ -z "$SSL_KEY" ] ; then
        echo "Selecting default: $_SSL_KEY"
        SSL_KEY=$_SSL_KEY
    fi

    if [ $NGINX_PORT = 443 ] ; then
        PROXY_HOST="https://localhost/"
    else
        PROXY_HOST="https://localhost:$NGINX_PORT"
    fi
else
    echo "Selecting default: $_SSL_ENABLE"
    SSL_ENABLE=$_SSL_ENABLE
    if [ $NGINX_PORT = 80 ] ; then
        PROXY_HOST="http://localhost/"
    else
        PROXY_HOST="http://localhost:$NGINX_PORT"
    fi
fi

#Update filesystem permission 
chmod -Rf 0777 configs
chmod -Rf 0777 app/config
chmod -Rf 0777 app/storage
chmod -Rf 0777 app/storage/logs
chmod -Rf 0777 app/storage/logs/query-logs
chmod -Rf 0777 public/install
success "Default permission updated successfully"

#create nginx service script
if [ ! -f /etc/init.d/nginx ]; then
    cp nginx/nginx /etc/init.d/nginx
    chmod 0777 /etc/init.d/nginx
    success "Nginx service created successfully"
else
    success "Nginx service already created"
fi

#create nginx systemctl-service script (Redhat/CentOS7+)
if [ ! -f /etc/systemd/system/nginx.service ]; then
    cp nginx/nginx.service /etc/systemd/system/nginx.service
    chmod 0777 /etc/systemd/system/nginx.service
    success "Nginx Unit service created successfully"
else
    success "Nginx Unit service already created"
fi

#create nginx service controller as root
chown root nginx/nginx_root.sh
chmod u=rwx,go=xr nginx/nginx_root.sh
gcc nginx/nginx_root.c -o nginx/nginx_root
chown root nginx/nginx_root
chmod u=rwx,go=xr,+s nginx/nginx_root
success "Nginx service handler created successfully";


if [ ! -d /etc/nginx/conf.d ]; then
    mkdir /etc/nginx/conf.d
fi
if [ ! -d /etc/nginx/snippets ]; then
    mkdir /etc/nginx/snippets
fi

TIME=`date +%b-%d-%y`
mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf-backup-$TIME
cp nginx/fastcgi-php.conf /etc/nginx/snippets/fastcgi-php.conf
cp nginx/nginx.conf /etc/nginx/nginx.conf
cp nginx/auth.conf /etc/nginx/conf.d/auth.conf
#cp nginx/server.conf configs/server.conf
sudo ln -sf "$(pwd)/configs" /etc/nginx/conf.d/configs
success "Nginx server configuration updated"


if [ $SSL_ENABLE = yes ]; then
    cat > ${SERVER_FILE} <<EOT
    server {
        listen $NGINX_PORT ssl;
        server_name localhost;

        ssl_certificate $SSL_CERT;
        ssl_certificate_key $SSL_KEY;

        root $ROOT_PATH/public;
        index index.php index.html index.htm;

        location / {
            try_files $uri $uri/ /index.php?$args;
        }

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass 127.0.0.1:9000;
            include fastcgi_params;
        }
    }
EOT
else
    cat > ${SERVER_FILE} <<EOT
    server {
        listen $NGINX_PORT;
        server_name localhost;

        root $ROOT_PATH/public;
        index index.php index.html index.htm;

        location / {
            try_files $uri $uri/ /index.php?$args;
        }
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass 127.0.0.1:9000;
            include fastcgi_params;
        }
    }
EOT
fi
#######################################################################


echo "\nConfiguration summary: \n"
echo "Host                         : $PROXY_HOST"
echo "Port no.                     : $NGINX_PORT"
echo "HTTPS enable                 : $SSL_ENABLE"
echo "SSL certificate              : $SSL_CERT"
echo "SSL certificate key file     : $SSL_KEY"
echo "Application root path        : $ROOT_PATH"
echo "\n"
service nginx restart || die "Failed to restart nginx service..."

if command -v firewall-cmd >/dev/null 2>&1; then
    echo "${YELLOW}Firewall not installed, please install and configure zone=public if required.${NC}"
else
    firewall-cmd --zone=public --add-port=$NGINX_PORT/tcp --permanent  || die "Failed to save firewall setings..."
    filewall-cmd --reload || die "Failed to save firewall setings..."
fi

if command status iptables >/dev/null 2>&1; then
    echo "${YELLOW}Iptables not enable, please install and configure if required${NC}"
else
    iptables -I INPUT -p tcp --dport $NGINX_PORT -j ACCEPT
    service iptables save || die "Failed to save iptables setings..."
    service iptables restart || die "Failed to start iptables service..."
fi

#tada
echo "${GREEN}Installation completed!!${NC}"
exit 0