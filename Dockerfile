FROM centos:centos7
# 配置SSH
RUN  yum -y install openssh-server openssh-clients passwd   httpd net-tools mysql   systemctl mysql-server start mariadb-server
RUN  rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
RUN  rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
RUN  yum -y install php72w php72w-common php72w-fpm php72w-opcache php72w-gd php72w-mysqlnd php72w-mbstring php72w-pecl-redis php72w-pecl-memcached php72w-devel
RUN  sed -i "s/#PermitRootLogin no/PermitRootLogin yes/g" /etc/ssh/sshd_config
RUN  sed -i "s/#Port 22/Port 22/g" /etc/ssh/sshd_config
RUN sed -i "s/PermitRootLogin without-password/PermitRootLogin yes/g" /etc/ssh/sshd_config
RUN ssh-keygen -t rsa -P '' -f ~/.ssh/id_rsa && \
    cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys && \
    chmod 0600 ~/.ssh/authorized_keys

RUN echo root|passwd --stdin root
RUN mkdir -p /var/run/sshd && /usr/bin/ssh-keygen -A && systemctl enable sshd
RUN sed -i 's/#ServerName www.example.com:80/ServerName 0.0.0.0:80/' /etc/httpd/conf/httpd.conf
RUN echo "<?php phpinfo(); ?>">/var/www/html/version_php.php
COPY / /var/www/html/
RUN chmod 777 -R /var/www
ENV API_IP_PORT=""

WORKDIR  /var/www/html
RUN chmod +x init.sh
RUN chmod +x /etc/rc.d/rc.local
RUN echo "/var/www/html/init.sh" >> /etc/rc.d/rc.local && cat  /etc/rc.d/rc.local
RUN yum clean all

RUN mkdir -p /var/lib/mysql && chmod 777  /var/lib/mysql

# 数据库持久化
VOLUME  /var/lib/mysql/
# 文件持久化
VOLUME /var/www/html/upload
ENTRYPOINT  /sbin/init
