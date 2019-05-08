# Dockerfile
FROM mattrayner/lamp:latest-1804

MAINTAINER James Mahy <james.mahy@cevo.co.uk>

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
EXPOSE 443

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]