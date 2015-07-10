#!/bin/sh

# read mysql access stuff
DA_MYSQL=/usr/local/directadmin/conf/mysql.conf
DA_USER=`cat $DA_MYSQL | grep user= | cut -d= -f2`
DA_PASS=`cat $DA_MYSQL | grep passwd= | cut -d= -f2`

NEW_DB="${username}_mw"
NEW_USER="${username}@localhost"

# create database
mysql -u $DA_USER -p$DA_PASS --execute="CREATE DATABASE $NEW_DB"

#create user
mysql -u $DA_USER -p$DA_PASS --execute="GRANT ALL on $NEW_DB.* to $NEW_USER identified by '${passwd}'"

#create default user
NEW_USER="${username}_mw@localhost" 
mysql -u $DA_USER -p$DA_PASS --execute="GRANT ALL on $NEW_DB.* to $NEW_USER identified by '${passwd}'"