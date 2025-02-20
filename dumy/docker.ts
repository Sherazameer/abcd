
============================================
add container id in docker image
docker commit f7ea41166472 my-ictfax-image:latest
=============================

sudo ss -ltnp
sudo netstat -tulnp
sudo lsof -i :80
=====================================================

nano /home/ictvision/project/angular-docker/Dockerfile

docker build -t angular-docker .

======================
examples container start 
docker start xyzec
docker exec -it xyzec bash
dockere
docker login
sheraz762
docker run -it sheraz762/ictfax-18
docker start 1c5ce943f944
change name
docker tag dddd sheraz762/ictfax-18
docker tag ictfax-docker sheraz762/ictfax-19

docker push sheraz762/ictfax-18:latest

docker rename 919db5dcc946 ictfax-19
docker commit -m "angular-v19" 919db5dcc946 sheraz762/ictfax-19



docker exec -it 2562a661cf3a /bin/sh
docker exec -it 2562a661cf3a /bin/bash
docker stop <container_id>
run docker image
docker run -p 4200:4200 angular-docker
run container-id
docker exec -it 3fe7f10a3671 ng serve --host 0.0.0.0 --port 4200
=====================================================================


docker restart xyzec
 ls -l /var/run/docker.sock
sudo systemctl restart docker



apt install Scurl
apt install wget
wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
apt-get install apt-transport-https ca-certificates curl software-properties-common
apt-get install docker-ce

dnf install git -y
source ~/.bashrc
nvm install 18
npm install -g npm@10.9.1

wget https://go.microsoft.com/fwlink/?LinkID=760868 -O code.deb
apt-cache show code
dpkg -i code.deb
apt --fix-broken install

=============================

sudo yum install docker-compose-plugin
docker compose version

docker-compose up --build


docker-compose up
docker-compose logs
docker-compose down
check opratingsystem
cat /etc/os-release

docker volume ls 
docker network ls

docker network inspect bridge
=====================================================
ip addr show
hostname -I

docker swarm init
docker swarm join --token <token> <manager-node-IP>:2377

docker service create --name my_nginx --replicas 3 -p 80:80 nginx
docker service ls
docker service ps my_nginx

docker service scale my_nginx=5

docker service update --image nginx:alpine my_nginx
docker service rm my_nginx
docker swarm leave
docker swarm leave --force
docker node ls

docker swarm init --advertise-addr 192.168.1.23
docker swarm join-tokin manager
docker swarm join-tokin worker
docker info
docker service create --name ourservice --replicas 3 -p 80:80 httpd

docker service inspect ourservice
docker service inspect ourservice --pretty
docker service rm ourservice




=======================================
docker volume create laravel-app02
docker volume ls 
docker inspect batman 
docker volume rm batman
docker volume ls
docker volume prune
docker run -it -d --network dddd_network --name dddd --mount source=dddd_volume,target=/data -p 4200:4200 sheraz762/ictfax-19 ng serve --host 0.0.0.0 --port 4200
docker run -it -d --network dddd_network --name dddd --mount source=dddd_volume,target=/data -p 4200:4200 sheraz762/ictfax-19 ng serve --host 0.0.0.0 --port 4200
docker service create --name ictfax --replicas 3 -p 4200:4200 sheraz762/ictfax-19 ng serve --host 0.0.0.0 --port 4200
docker run -it -d --name python --mount type=bind,source=/home/ictvision/project/batman,target=/app ubuntu

docker run -it -d --name avengers --volume ironman:/app ubuntu
docker run -it -d --name avengers2 --mount source=spiderman,target=/app,readonly ubuntu
================================================

docker network ls id
docker network create --driver bridge alphaid01
attach network 
docker run -it -d --network alphaid01 --name xyzec sheraz762/ictfax-19
attach volume
docker run -it -d --network alphaid01 --name xyzec01 --mount source=batman,target=/data sheraz762/ictfax-19
docker run -it -d --name xyzec --mount source=batman,target=/data -p 4200:4200 sheraz762/ictfax-19 ng serve --host 0.0.0.0 --port 4200

docker run -it -d --network alphaid --name xyzec --mount source=batman,target=/data -p 4200:4200 sheraz762/ictfax-19 ng serve --host 0.0.0.0 --port 4200

docker network disconnect alphaid container_name_or_id

docker run -p 4200:4200 sheraz762/ictfax-19
docker network connect alphaid elastic_jones
docker network disconnect alphaid elastic_jones

docker exec -it 032dd69fc1e5 ng serve --host 0.0.0.0 --port 4200

disconnect
docker network disconnect alphaid01 upbeat_satoshi
docker run -it -d --network alphaid01 --name attach sheraz762/ictfax-18
docker exec -it attach bash
apt install iputils-ping
apt install iputils-ping
ping 172.19.0.2
============================================
docker run -it -d --network host --name hostcontainer ubuntu
docker network create --driver overlay flash--net
cat /etc/sysconfig/docker
 sudo nano /etc/sysconfig/docker
docker swarm init

docker network create --driver overlay flash--net
docker service create --name yourservice --network flash--net --replicas 2 ubuntu 
docker service create --name yourservice --network flash--net --replicas 2 nginx 
docker service rm yourservice
===================================================================================


mkdir ~/myapp && cd ~/myapp


[ictvision@192 myapp]$ docker build -t my-project ./my-project
docker run -d --name my-laravel-app \ -p 8000:8000 \ --network laravel-network \ my-project
docker run -d --name my-laravel-app -p 8000:8000 --network laravel-network my-project


docker exec -it my-laravel-app php artisan config:clear
docker exec -it my-laravel-app php artisan cache:clear

apt-get update && apt-get install -y mariadb-client


docker run -d --name laravel-app --network laravel-network -p 8000:80 --volumes-from xampp01 laravel
docker run -d --name laravel-app --network laravel-network -p 8000:80 laravel


find . -name ".env"
nano .env
ls -la | grep .env

docker build -t laravel .

docker run -d --name laravel-app02 --mount source=laravel-data,target=/home/laravel-app -p 8000:80 laravel
docker exec -it laravel-app02 composer create-project --prefer-dist laravel/laravel /home/laravel-app
docker run -d --name laravel-app02 --mount source=laravel-data,target=/home/laravel-app -p 8000:80 --network laravel-network laravel
docker run -d --name xampp01 --mount source=laravel-data,target=/opt/lampp/htdocs -p 8081:80 --network laravel-network sheraz762/xampp-fedora /bin/bash -c "/opt/lampp/lampp start && tail -f /dev/null"

=======================================
Step 3: Run the Laravel Container


docker run -d \
  --name laravel-app02 \
  --mount source=laravel-data,target=/home/laravel-app02 \
  -p 8000:80 \
  --network laravel-network \
  sheraz762/laravel-fedora \
  php -S 0.0.0.0:80 -t /home/laravel-app02/public
===================================================
Step 4: Run the XAMPP Container

docker run -d \
  --name  Legacy \
  --mount source=Legacy-volume,target=/opt/lampp/htdocs \
  -p 8081:80 \
  --network Legacy-network \
  sheraz762/xampp-fedora \
  /bin/bash -c "/opt/lampp/lampp start && tail -f /dev/null"
  ===================================================================
docker logs xampp02
docker logs laravel-app02
docker inspect xampp02 | grep "Network"
docker inspect laravel-app02 | grep "Network"
docker network disconnect bridge xampp02
docker network create legacy-network 
docker volume create Legacy-volume 
docker network create odyssey-network 
docker volume create odyssey-volume 
docker run -d   --name  legacy-xampp   --mount source=legacy-volume,target=/opt/lampp/htdocs   -p 8081:80   --network legacy-network   sheraz762/xampp-fedora   /bin/bash -c "/opt/lampp/lampp start && tail -f /dev/null"

docker run --network legacy-network --name legacy --mount source=legacy-volume,target=/home/legacy -p 8000:80 sheraz762/laravel-fedora

docker run --network laravel-network --name laravel-app02 --mount source=laravel-data,target=/home/laravel-app02 -p 8000:80 sheraz762/laravel-fedora
docker run --network laravel-network --name laravel-app01 -p 8000:80 sheraz762/laravel-fedora

docker run --net my-network --name xampp02 -d -p 8081:80 sheraz762/xampp-fedora
docker run --net my-network --name laravel-app -d -p 8000:80 laravel

docker network create my-network

docker run --network my-network --name laravel-app03 -p 8000:80 sheraz762/laravel-fedora
docker run --network my-network --name xampp03 -p 8081:80 sheraz762/xampp-fedora

docker exec -it laravel-app03 php artisan migrate
/opt/lampp/bin/mysql -u root -p.



CREATE USER 'root'@'%' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON laravel.* TO 'laravel_user'@'%';
FLUSH PRIVILEGES;

GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '' WITH GRANT OPTION;
FLUSH PRIVILEGES;


CREATE USER 'project'@'172.21.0.2' IDENTIFIED BY 'sarfrazkhan';

GRANT ALL PRIVILEGES ON laravel2.* TO 'project'@'172.21.0.2';

FLUSH PRIVILEGES;

DROP USER 'root'@'localhost';

DB_CONNECTION=mysql
DB_HOST=xampp03
DB_PORT=3306
DB_DATABASE=laravel2
DB_USERNAME=root
DB_PASSWORD=
CREATE DATABASE laravel2;
SET PASSWORD FOR 'username'@'host' = PASSWORD('new_password');
SET PASSWORD FOR 'sheraz'@'ameer' = PASSWORD('sherazameer');


wget "https://downloads.sourceforge.net/project/xampp/XAMPP%20Linux/8.2.4/xampp-linux-x64-8.2.4-0-installer.run?ts=gAAAAABnXSC...&use_mirror=onboardcloud"
RUN curl -O https://downloadsapachefriends.global.ssl.fastly.net/xampp/8.2.12/xampp-linux-x64-8.2.12-0-installer.run && \

xampp-linux-x64-8.2.4-0-installer.run?ts=gAAAAABnXSC...&use_mirror=onboardcloud
mv 'xampp-linux-x64-8.2.4-0-installer.run?ts=gAAAAABnXSC...&use_mirror=onboardcloud' xampp-linux-x64-8.2.4-0-installer.run
chmod a+x xampp-linux-x64-8.2.4-0-installer.run
chmod +x xampp-linux-x64-8.2.4-0-installer.run
sudo ./xampp-linux-x64-8.2.4-0-installer.run

sudo dnf install libxcrypt-compat

http://127.0.0.1


sudo firewall-cmd --add-service=http --permanent
sudo firewall-cmd --add-service=https --permanent
sudo firewall-cmd --reload
sudo firewall-cmd --state

sudo /opt/lampp/lampp start
cd /opt/lampp && sudo ./manager-linux-x64.run


container
ping -c 4 google.com
docker cp xampp-linux-x64-8.2.4-0-installer.run festive_engelbart:/root/
chmod +x /root/xampp-linux-x64-8.2.4-0-installer.run
/root/xampp-linux-x64-8.2.4-0-installer.run
./root/xampp-linux-x64-8.2.4-0-installer.run --mode unattended
sudo netstat -tuln | grep ':80'
sudo netstat -tuln
sudo lsof -i :8080
chmod +x /usr/bin/hostname

which hostname
nano /opt/lampp/etc/extra/httpd-xampp.conf

<Directory "/opt/lampp/phpmyadmin">
    AllowOverride AuthConfig Limit
    Require all granted
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>

dnf install -y coreutils
dnf install -y inetutils
whereis hostname
dnf install -y procps-ng
docker inspect serene --format='{{.State.ExitCode}}'

fedora
docker run -it --name serene -p 8081:80 -p 3307:3306 xampp-docker /bin/bash
docker run -d -p 8081:80 -p 3307:3306 --name serene xampp-docker /bin/bash -c "/opt/lampp/lampp start && tail -f /dev/null"
docker run -d -p 8081:80 --name xampp-fedora --hostname hostname xampp-docker /bin/bash -c "/opt/lampp/lampp start && tail -f /dev/null"
hiden files check
ls -la 
./lampp status
./lampp start
./lampp stop
/opt/lampp/sbin/mysqld --skip-grant-tables &
 /opt/lampp/bin/mysql -u root
 /opt/lampp/bin/mysql -h 172.21.0.2 -u root -p
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '' WITH GRANT OPTION;

 SELECT User, Host FROM mysql.user;


docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' festive_engelbart
[ictvision@192 ~]$ docker run -d -p 80:80 -p 443:443 --name festive_engelbart fedora


copy into main directory
docker cp xampp-linux-x64-8.2.4-0-installer.run xampp:/root/

docker run --name myXampp -p 41061:22 -p 41062:80 -d -v ~/my_web_pages:/www tomsik68/xampp
docker run --name myXampp -p -p 41062:80 -d  tomsik68/xampp



docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' xampp03

/opt/lampp/sbin/mysqld --skip-grant-tables --skip-networking &


docker xampp to laravel setup

/opt/lampp/bin/mysql -u root

SELECT User, Host FROM mysql.user;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '' WITH GRANT OPTION;

GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;

FLUSH PRIVILEGES;

DB_CONNECTION=mysql
DB_HOST=172.21.0.2
DB_PORT=3306
DB_DATABASE=laravel2
DB_USERNAME=root
DB_PASSWORD=

docker restart laravel-app02
docker exec -it laravel-app02 php artisan migrate

