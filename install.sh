chmod -Rf 0777 configs
chmod -Rf 0777 app/config
chmod -Rf 0777 app/storage
chmod -Rf 0777 app/storage/logs
chmod -Rf 0777 app/storage/logs/query-logs
chmod -Rf 0777 public/install
echo "Default permission updated successfully!";
echo "***********Nginx service process start ***************";
chown root nginx_root.sh
chmod u=rwx,go=xr nginx_root.sh
gcc nginx_root.c -o nginx_root
chown root nginx_root
chmod u=rwx,go=xr,+s nginx_root
echo "***********Nginx service created successfully!!*******";
