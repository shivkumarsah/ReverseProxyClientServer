#!/bin/sh
chmod -Rf 0777 configs
chmod -Rf 0777 app/config
chmod -Rf 0777 app/storage
chmod -Rf 0777 app/storage/logs
chmod -Rf 0777 app/storage/logs/query-logs
chmod -Rf 0777 public/install
echo "Default permission updated successfully!";
chown root nginx/nginx_root.sh
chmod u=rwx,go=xr nginx/nginx_root.sh
gcc nginx/nginx_root.c -o nginx/nginx_root
chown root nginx/nginx_root
chmod u=rwx,go=xr,+s nginx/nginx_root
echo "Nginx service created successfully!";

TIME=`date +%b-%d-%y`
mkdir /etc/nginx/conf.d
mkdir /etc/nginx/snippets
mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf-backup-$TIME
cp nginx/nginx.conf /etc/nginx/nginx.conf
cp nginx/fastcgi-php.conf /etc/nginx/snippets/fastcgi-php.conf
cp nginx/auth.conf /etc/nginx/conf.d/auth.conf
echo "Nginx server configuration updated"

#Create Nginx /etc/init.d/nginx script
#Assign 777 permission on /etc/init.d/nginx
#Update service path in nginx-root.sh script
#Create soft link of configs to /etc/nginx/conf.d/
#Handle /auth redirection issue