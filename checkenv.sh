#!/bin/bash

if php -m | grep -q sqlsrv; then
    echo "sqlsrv module installed"
else
    echo "sqlsrv module NOT installed"
    echo "exiting..."
    exit 1
fi

if php -m | grep -q pdo_sqlsrv; then
    echo "pdo_sqlsrv module installed"
else
    echo "pdo_sqlsrv module NOT installed"
    echo "exiting..."
    exit 1
fi

echo

#Check connection to Master Database
#Check if Database exists
#Create Database if needed

php src/checkenv.php
