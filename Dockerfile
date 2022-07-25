FROM ubuntu:20.04

ARG APP_PHP_VERSION
ENV PHP_VERSION="$APP_PHP_VERSION"
RUN echo "Building with PHP version $PHP_VERSION"

ENV DEBIAN_FRONTEND "noninteractive"

# Install dependencies and basic utils
RUN apt-get update
RUN apt-get -y install curl
RUN apt-get -y install dropbear
RUN apt-get -y install htop
RUN apt-get -y install iputils-ping
RUN apt-get -y install tmux
RUN apt-get -y install tree
RUN apt-get -y install vim

# PHP dependencies as per
# https://github.com/stock2shop/app/blob/master/scripts/docker/amd64/base/Dockerfile
RUN apt-get install -yq software-properties-common
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install -yq php${PHP_VERSION}
RUN apt-get install -yq mcrypt php${PHP_VERSION}-mcrypt
RUN apt-get install -yq \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-oauth \
    php${PHP_VERSION}-soap \
    php${PHP_VERSION}-ssh2
RUN apt-get install -yq php${PHP_VERSION}-xdebug

# bashrc
COPY .bashrc /root/.bashrc

# tmux.conf
COPY .tmux.conf /root/.tmux.conf

# Remove default keys
RUN rm /etc/dropbear/dropbear_dss_host_key /etc/dropbear/dropbear_rsa_host_key
# Add authorized keys
RUN mkdir -p /root/.ssh
COPY id_rsa.pub /root/id_rsa.pub
RUN cat /root/id_rsa.pub > /root/.ssh/authorized_keys
RUN rm /root/id_rsa.pub

# Add run script
COPY docker-cmd.sh /docker-cmd.sh
RUN chmod +x /docker-cmd.sh
CMD /docker-cmd.sh
