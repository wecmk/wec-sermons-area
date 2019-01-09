#!/bin/bash

apt install -y mariadb-server mariadb-client binutils
systemctl start mysql

# Configure mariadb
PASSWORD=`strings /dev/urandom | grep -o '[[:alnum:]]' | head -n 30 | tr -d '\n'; echo`
mysql -u root -e "update mysql.user set Password=PASSWORD('$PASSWORD') where user='root';"

mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '$PASSWORD';"
mysql -u root -e "CREATE database members;"
mysql -u root -e "GRANT ALL PRIVILEGES ON members.* TO 'members'@'localhost';"

mysql -u root -e "flush privileges;"

echo "Your MySQL root password is: $PASSWORD"
