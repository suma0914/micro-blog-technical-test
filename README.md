# Micro-blog Technical Test

## About

A part-implemented micro blogging app with API


## Provides

* Centos 7 Box
* LEMP Stack
  * Nginx
  * PHP-FPM
  * SQLite

## Requires

* Ansible 1.8.1+
* Vagrant 1.8+
* Internet access for additional software download and installs

## Depends on

List of project specific system requirements.
* See requirements.yml

## Setup

### Install / Update Ansible-Galaxy Roles

Run the following command to update your ansible galaxy roles (sudo if necessary):
```
sudo ansible-galaxy install -r requirements.yml --ignore-errors
```

### Add following line to your host machine /etc/hosts:

```
192.168.100.120 micro-blog.dev
```

If you are having issues with Ansible / Vagrant or would prefer to use your own web server please refer to the link below on how to configure Silex:
http://silex.sensiolabs.org/doc/master/web_servers.html

## Usage

### Dev

Start up the vagrant box:

```
vagrant up
```

### Nginx

* visit 192.168.100.120 for Nginx test page
* visit http://micro-blog.dev/api/posts to verify the micro-blog is configured correctly
