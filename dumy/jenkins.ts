 java --version

sudo dnf install java-17-openjdk -y
sudo wget -O /etc/yum.repos.d/jenkins.repo https://pkg.jenkins.io/redhat-stable/jenkins.repo

sudo rpm --import https://pkg.jenkins.io/redhat-stable/jenkins.io-2023.key

systemctl start jenkins
systemctl enable jenkins
Created symlink /etc/systemd/system/multi-user.target.wants/jenkins.service → /usr/lib/systemd/system/jenkins.service.
systemctl status jenkins


 ss -antpl | grep 8080
LISTEN 0      50                 *:8080             *:*          

sudo cat /var/lib/jenkins/secrets/initialAdminPassword
4893e95b73de4a37982583c0605261d4

access jenkins  url page
http://172.18.0.1:8080/
