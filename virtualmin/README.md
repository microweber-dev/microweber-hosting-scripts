# Hosting scripts for deploying on Virtualmin


Follow those steps to have your Virtualmin server deploy new Microweber installation on new account creation. 

Make sure you cover the [server requirements](https://github.com/microweber/microweber#general-requirements "") and you can install Microweber normally, before employing those automated methods.

## Install Virtualmin

If you haven't installed and setted up Virtualmin, you can do so

Download and run the Vitualmin install script by executing this command 

```sh
wget http://software.virtualmin.com/gpl/scripts/install.sh
chmod +x ./install.sh
./install.sh
```

Install PHP requirements
```
yum install php-mcrypt*
```

## Setup Virtualmin

If you go on http://example.com:10000 you can access the Virtualmin control panel.
Setup your installation and when you are ready can go for the install scripts setup.


## Download latest version of this repository

```sh
cd /usr/share/
git clone git@github.com:microweber/microweber-hosting-scripts.git 

```

