find . -name ".env"
nano .env
ls -la | grep .env

docker build -t laravel .

docker run -d --name laravel-app02 --mount source=laravel-data,target=/home/laravel-app -p 8000:80 laravel

docker exec -it laravel-app02 composer create-project --prefer-dist laravel/laravel /home/laravel-app

docker run -d --name laravel-app02 --mount source=laravel-data,target=/home/laravel-app -p 8000:80 --network laravel-network laravel


docker run -d --name xampp01 --mount source=laravel-data,target=/opt/lampp/htdocs -p 8081:80 sheraz762/xampp-fedora /bin/bash -c "/opt/lampp/lampp start && tail -f /dev/null"


docker logs xampp02
docker logs laravel-app


docker inspect legacy-xampp | grep "Network"
docker inspect legacy | grep "Network"


docker network disconnect bridge xampp02

docker network create laravel-app02
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


