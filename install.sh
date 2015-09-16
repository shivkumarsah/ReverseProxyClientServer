#!/bin/sh
if [ $(id -u) = "0" ]; then
    chmod -Rf 0777 configs
    chmod -Rf 0777 app/config
    chmod -Rf 0777 app/storage
    chmod -Rf 0777 app/storage/logs
    chmod -Rf 0777 app/storage/logs/query-logs
    chmod -Rf 0777 public/install
    echo "\nSuccess - Default permission updated successfully!";

    if [ ! -f /etc/init.d/nginx ]; then
        cp nginx/nginx /etc/init.d/nginx
        chmod 0777 /etc/init.d/nginx
        echo "\nSuccess - Nginx service created successfully!"
    else
        echo "\nSuccess - Nginx service already created."
    fi

    chown root nginx/nginx_root.sh
    chmod u=rwx,go=xr nginx/nginx_root.sh
    gcc nginx/nginx_root.c -o nginx/nginx_root
    chown root nginx/nginx_root
    chmod u=rwx,go=xr,+s nginx/nginx_root
    echo "\nSuccess - Nginx service handler created successfully!";

    if [ ! -d /etc/nginx/conf.d ]; then
        TIME=`date +%b-%d-%y`
        mkdir /etc/nginx/conf.d
    fi
    if [ ! -d /etc/nginx/snippets ]; then
        mkdir /etc/nginx/snippets
    fi
    mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf-backup-$TIME
    cp nginx/nginx.conf /etc/nginx/nginx.conf
    cp nginx/fastcgi-php.conf /etc/nginx/snippets/fastcgi-php.conf
    cp nginx/auth.conf /etc/nginx/conf.d/auth.conf
    sudo ln -sf "$(pwd)/configs" /etc/nginx/conf.d/configs
    echo "\nSuccess - Nginx server configuration updated"

    #Create Nginx /etc/init.d/nginx script
    #Assign 777 permission on /etc/init.d/nginx
    #Update service path in nginx-root.sh script
    #Create soft link of configs to /etc/nginx/conf.d/
    #Handle /auth redirection issue
    echo "\n"
else
	echo "\n\nError - Please execute this script from root user\n\n"
fi