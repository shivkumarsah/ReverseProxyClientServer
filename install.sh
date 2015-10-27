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
    printf "${RED}ERROR${NC}: $1. Aborting!\n"
    exit 1
}
success () {
    printf "${GREEN}SUCCESS${NC}: $1.\n"
}

#check for root user
if [ "$(id -u)" -ne 0 ] ; then
    die "You must run this script as root user"
fi

#Initial defaults
ROOT_PATH=$(pwd)
_NGINX_DOMAIN='localhost'
_NGINX_PORT=8080
_SSL_ENABLE='no'
SSL_ENABLE=no
PROTOCOL='http'
_SSL_CERT=''
_SSL_KEY=''
uri='$uri'
args='$args'
SERVER_FILE="configs/server.conf"
PROXY_HOST="http://localhost:8080"

#Read the domain name
read -p "Please enter domain/server name for this instance (Example: domain.com): [$_NGINX_DOMAIN] " NGINX_DOMAIN
if [ ! $NGINX_DOMAIN ] ; then
    echo "Selecting default domain: $_NGINX_DOMAIN"
    NGINX_DOMAIN=$_NGINX_DOMAIN
fi

#Read the nginx port
read -p "Please select the installer port no. for this instance: [$_NGINX_PORT] " NGINX_PORT
if ! echo $NGINX_PORT | egrep -q '^[0-9]+$' ; then
    echo "Selecting default: $_NGINX_PORT"
    NGINX_PORT=$_NGINX_PORT
fi

#Read the ssl options
read -p "Use HTTPS (SSL): [no/yes] " SSL_ENABLE
if [ $SSL_ENABLE = yes ] ; then
    PROTOCOL='https'
    printf "\nYou must have valid certificate and certificate key to configure HTTPS.\n"
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
    #SETTING_TEXT="baseProtocol = '$PROTOCOL';\ncertificatePem = '$SSL_CERT';\ncertificateKey = '$SSL_KEY';"
    #echo $SETTING_TEXT > public/install/ssl.ini
    SETTING_FILE="public/install/ssl.ini"
    cat <<EOM >$SETTING_FILE
baseProtocol = '$PROTOCOL';
certificatePem = '$SSL_CERT';
certificateKey = '$SSL_KEY';
EOM
else
    echo "Selecting default: $_SSL_ENABLE"
    SSL_ENABLE=$_SSL_ENABLE
    if [ $NGINX_PORT = 80 ] ; then
        PROXY_HOST="http://localhost/"
    else
        PROXY_HOST="http://localhost:$NGINX_PORT"
    fi
fi

printf "\n\n"

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
rm /etc/nginx/conf.d/configs
ln -sf "$(pwd)/configs" /etc/nginx/conf.d/configs
success "Nginx server configuration updated"


if [ $SSL_ENABLE = yes ]; then
    cat > ${SERVER_FILE} <<EOT
    server {
        listen $NGINX_PORT ssl;
        server_name $NGINX_DOMAIN;

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
        server_name $NGINX_DOMAIN;

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


printf "\n\nConfiguration summary: \n"
echo "Host                         : $PROXY_HOST"
echo "Port no.                     : $NGINX_PORT"
echo "HTTPS enable                 : $SSL_ENABLE"
echo "SSL certificate              : $SSL_CERT"
echo "SSL certificate key file     : $SSL_KEY"
echo "Application root path        : $ROOT_PATH"
printf "\n"

#if ! command -V firewall-cmd >/dev/null 2>&1; then
if service firewalld status >/dev/null 2>&1; then
    firewall-cmd --zone=public --add-port=$NGINX_PORT/tcp --permanent ||  printf "${RED}ERROR${NC}: Failed to save firewall setings... Aborting!\n"
    firewall-cmd --reload || printf "${RED}ERROR${NC}: Failed to save firewall setings... Aborting!\n"
else
    printf "${YELLOW}Firewall not enabled or installed, You must install and configure zone=public and add-port $NGINX_PORT.${NC}\n"
fi
#firewall-cmd --zone=public --add-port=$NGINX_PORT/tcp --permanent ||  printf "${RED}ERROR${NC}: Failed to save firewall setings... Aborting!\n"
#firewall-cmd --reload || printf "${RED}ERROR${NC}: Failed to save firewall setings... Aborting!\n"


#if command status iptables >/dev/null 2>&1; then
#    printf "${YELLOW}Iptables not enable, please install and configure if required${NC}\n"
#else
#    iptables -I INPUT -p tcp --dport $NGINX_PORT -j ACCEPT
#    service iptables save || die "Failed to save iptables setings..."
#    service iptables restart || die "Failed to start iptables service..."
#fi

service nginx restart || die "Failed to restart nginx service..."

#tada
printf "\n\n${GREEN}Installation completed!!${NC}\n\n\n"
exit 0
