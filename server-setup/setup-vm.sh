#!/bin/bash

IS_DEV=false

CURRENTDIR=`pwd`

PHPVERSION=7.2

if [ -d "/vagrant/" ]; then
    IS_DEV=true
fi

if [ $IS_DEV ]; then
    DIRPATH='/vagrant'
else
    DIRPATH=`pwd`
fi

add-apt-repository ppa:ondrej/php
add-apt-repository ppa:ondrej/apache2
add-apt-repository ppa:webupd8team/y-ppa-manager
add-apt-repository universe
add-apt-repository -y ppa:certbot/certbot
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
curl -sL https://deb.nodesource.com/setup_11.x | sudo -E bash -
apt-get install apt-transport-https
echo "deb https://artifacts.elastic.co/packages/6.x/apt stable main" | sudo tee -a /etc/apt/sources.list.d/elastic-6.x.list

apt update -y


apt install y-ppa-manager
apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 40976EAF437D05B5
apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 3B4FE6ACC0B21F32


apt-get install -y nodejs

apt install -y vim htop lsof nload apache2 cronolog zip unzip
apt install -y software-properties-common
apt install -y yarn
apt install -y php$PHPVERSION php$PHPVERSION-intl php$PHPVERSION-xml php$PHPVERSION-curl php$PHPVERSION-mbstring php$PHPVERSION-zip php$PHPVERSION-mysql


apt install -y libapache2-mod-php$PHPVERSION libapache2-mod-xsendfile

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer

apt upgrade -y


if [ !$IS_DEV ]; then
    apt-get install -y python-certbot-apache 
    apt-get install -y elasticsearch
    systemctl enable elasticsearch.service
    systemctl start elasticsearch.service
fi

# Symlink the Symfony web root to www (for standardisation of VirtulHost)
ln -s /data/web/members.wecmk.org/project/public /data/web/members.wecmk.org/www

cp $DIRPATH/server-setup/templates/members.wecmk.org.conf /etc/apache2/sites-available/

mkdir -p /data/web/members.wecmk.org/certificates
cd /data/web/members.wecmk.org/certificates
openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout members.wecmk.org.key -out members.wecmk.org.crt -subj '/CN=members.wecmk.org/O=Dev./C=GB'
cd $CURRENTDIR

if [ $IS_DEV ]; then
    sed -i 's/www-data/vagrant/g' /etc/apache2/envvars
fi

a2ensite members.wecmk.org
a2enmod headers
a2enmod ssl
a2enmod rewrite
systemctl reload apache2






cat <<text

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-----------------------------------------------------------
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Great - the server is nearly set up. Just a few last 
things you will need to manually do...

ssh into the virtual machine:
vagrant ssh

then run:



sudo apt install -y phpmyadmin
sudo mysql_secure_installation


Your MySQL Root password is $PASSWORD


++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
|  ____                _      _    _                    _  |
| |  _ \ ___  __ _  __| |    / \  | |__   _____   _____| | |
| | |_) / _ \/ _` |/ _` |   / _ \ | '_ \ / _ \ \ / / _ \ | |
| |  _ <  __/ (_| | (_| |  / ___ \| |_) | (_) \ V /  __/_| |
| |_| \_\___|\__,_|\__,_| /_/   \_\_.__/ \___/ \_/ \___(_) |
|                                                          |
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

text
    
