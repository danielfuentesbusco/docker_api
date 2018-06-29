FROM ubuntu:latest
MAINTAINER Daniel Fuentes Busco <daniel.fuentes.busco@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update

RUN apt-get -y upgrade && apt-get install dialog apt-utils -y && apt-get -y install apache2 
#RUN apt-get -y upgrade && apt-get install dialog apt-utils -y
RUN apt-get -y install php7.2 tzdata
RUN apt-get -y install php7.2-mbstring php7.2-json php7.2-xmlrpc php7.2-curl php7.2-cli php7.2-intl php7.2-common php7.2-soap libapache2-mod-php7.2 libphp7.2-embed 

RUN ln -fs /usr/share/zoneinfo/America/Santiago /etc/localtime
RUN dpkg-reconfigure --frontend noninteractive tzdata

# Enable apache mods.
RUN a2enmod php7.2
RUN a2enmod rewrite

# Update the PHP.ini file, enable <? ?> tags and quieten logging.
RUN sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php/7.2/apache2/php.ini
RUN sed -i "s/error_reporting = .*$/error_reporting = E_ERROR | E_WARNING | E_PARSE/" /etc/php/7.2/apache2/php.ini

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# Expose apache.
EXPOSE 80

# Copy this repo into place.
ADD .  /var/www/html/

# Update the default apache site with the config we created.
ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND