Install Gabinete Virtual
===============

Descrição: Instalação básica do Gabinete Virtual utilizando o Drupal 7

Instalação:
===============
	1 - Download Drupal tarball in drupal.org
	2 - Extract tarball in /var/www/
		Command: tar -zxvf drupal-7.xx.tar.gz
	3 - Go to drupal directory 
		Command: cd drupal-7.xx
	4 - Install composer
		Command: curl -sS https://getcomposer.org/installer | php
	5 - Donwload composer.json for Gabinete Virtual
		Command: curl https://raw.githubusercontent.com/urucumbrasil/modules-gabinetevirtual/master/composer.json
	6 - Donwload Gabinete Virtual
		Command: php composer.phar install
	7 - Restaurar o DUMP do banco de dados que encontra-se na raiz com o nome 'database.tar.gz'. 
		Comando: psql dbname < db.sql
	8 - Enable modules "Formulário Oficina de Criação" e "Gabinete Virtual" in Drupal Configuration.

	9 - Configure parameters in section Gabinete Virtual on Configuration page.

Mais Informações
===============
Software maintained by Urucum Brasil
http://www.urucumbrasil.com.br
