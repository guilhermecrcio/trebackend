## Source do teste

src/App/src

## Rotas

config/routes.php

## Middlewares

config/pipeline.php

## Configuração do banco de dados

config/autoload/eloquent.global.php
Deletar a configuração em cache php bin/clear-config-cache.php

## DUMP do banco de dados

config/tray.sql

Criar banco de dados antes do restore
CREATE DATABASE tray;
CREATE USER tray WITH PASSWORD '7r4y14102018';
GRANT ALL PRIVILEGES ON DATABASE tray TO tray;

## Tarefa para envio de e-mail

src/App/src/Task/EmailVendas.php

Trocar a configuração do SMTP para o envio do e-mail
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'guilherme.crcio@gmail.com';
$mail->Password = 'secret';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;