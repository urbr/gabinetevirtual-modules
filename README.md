Install Gabinete Virtual
===============

Descrição: Instalação básica do Gabinete Virtual utilizando o Drupal 7

Instalação:
===============
	1  - Download Drupal tarball in drupal.org
	2  - Extract tarball in /var/www/
		Command: tar -zxvf drupal-7.xx.tar.gz
	3  - Go to drupal directory 
		Command: cd drupal-7.xx
	4  - Install composer
		Command: curl -sS https://getcomposer.org/installer | php
	5  - Donwload composer.json for Gabinete Virtual
		Command: wget https://raw.githubusercontent.com/urucumbrasil/modules-gabinetevirtual/master/install/composer.json
	6  - Donwload Gabinete Virtual
		Command: php composer.phar install
	7  - Restore DUMP 'database.tar.gz'. 
		Command: tar -zxvf database.tar.gz 
		Command: psql dbname < db.sql
	8  - Enable modules "Formulário Oficina de Criação" e "Gabinete Virtual" in Drupal Configuration.
	9  - Configure parameters in section Gabinete Virtual on Configuration page.
	10 - Install theme
		Command: ln -sf ../modules/urucumbrasil/modules-gabinetevirtual/themes/ sites/all/themes/urucumbrasil

Mais Informações
===============
Software maintained by Urucum Brasil
http://www.urucumbrasil.com.br
