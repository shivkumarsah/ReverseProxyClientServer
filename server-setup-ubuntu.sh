#!/bin/sh

# Script to compile nginx on ubuntu with lua support.

NGX_VERSION='1.6.2'
LUAJIT_VERSION='2.0.3'
LUAJIT_MAJOR_VERSION='2.0'
NGX_DEVEL_KIT_VERSION='0.2.19'
LUA_NGINX_MODULE_VERSION='0.9.15'

NGINX_INSTALL_PATH='/etc/nginx'
mkdir ${NGINX_INSTALL_PATH}

# Download dependencies
apt-get install build-essential
apt-get install libssl-dev
apt-get install libpcre3 libpcre3-dev

# Download
if [ ! -f ./nginx-${NGX_VERSION}.tar.gz ]; then
    wget http://nginx.org/download/nginx-${NGX_VERSION}.tar.gz
fi

if [ ! -f ./LuaJIT-${LUAJIT_VERSION}.tar.gz ]; then
    wget http://luajit.org/download/LuaJIT-${LUAJIT_VERSION}.tar.gz
fi

if [ ! -f ./ngx_devel_kit-${NGX_DEVEL_KIT_VERSION}.tar.gz ]; then
    wget https://github.com/simpl/ngx_devel_kit/archive/v${NGX_DEVEL_KIT_VERSION}.tar.gz \
        -O ngx_devel_kit-${NGX_DEVEL_KIT_VERSION}.tar.gz
fi

if [ ! -f ./lua-nginx-module-${LUA_NGINX_MODULE_VERSION}.tar.gz ]; then
    wget https://github.com/openresty/lua-nginx-module/archive/v${LUA_NGINX_MODULE_VERSION}.tar.gz \
        -O lua-nginx-module-${LUA_NGINX_MODULE_VERSION}.tar.gz
fi


# Extract
if [ ! -d ./nginx-${NGX_VERSION} ]; then
    tar xvf nginx-${NGX_VERSION}.tar.gz
fi

if [ ! -d ./LuaJIT-${LUAJIT_VERSION} ]; then
    tar xvf LuaJIT-${LUAJIT_VERSION}.tar.gz
fi

if [ ! -d ./ngx_devel_kit-${NGX_DEVEL_KIT_VERSION} ]; then
    tar xvf ngx_devel_kit-${NGX_DEVEL_KIT_VERSION}.tar.gz
fi

if [ ! -d ./lua-nginx-module-${LUA_NGINX_MODULE_VERSION} ]; then
    tar xvf lua-nginx-module-${LUA_NGINX_MODULE_VERSION}.tar.gz
fi

# Install luajit
cd ./LuaJIT-${LUAJIT_VERSION} && sudo make install && cd ..

NGX_DEVEL_KIT_PATH=$(pwd)/ngx_devel_kit-${NGX_DEVEL_KIT_VERSION}
LUA_NGINX_MODULE_PATH=$(pwd)/lua-nginx-module-${LUA_NGINX_MODULE_VERSION}

# Compile And Install Nginx
cd ./nginx-${NGX_VERSION} && \
    LUAJIT_LIB=/usr/local/lib/lua LUAJIT_INC=/usr/local/include/luajit-${LUAJIT_MAJOR_VERSION} \
    ./configure --prefix=${NGINX_INSTALL_PATH} --conf-path=${NGINX_INSTALL_PATH}/nginx.conf --pid-path=/var/run/nginx.pid \
    --sbin-path=/usr/sbin/nginx --lock-path=/var/run/nginx.lock \
    --with-ld-opt='-Wl,-rpath,/usr/local/lib/lua' \
    --add-module=${NGX_DEVEL_KIT_PATH} \
    --add-module=${LUA_NGINX_MODULE_PATH} \
    && make -j2 && sudo make install