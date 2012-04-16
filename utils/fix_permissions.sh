#
# This script fixes permissions on Magento subtree
#
# Usage:
# cd to magento vhost dir
# run script by issuing scripts/fix_permissions.sh command
#
# Author Sven Varkel <sven.varkel@eepohs.com>
# Copyright 2012 Eepohs OÜ

os="Linux"

if uname -a | grep -q "Darwin"
then
    os="OSX"
fi

if [ $os == "Linux" ]
then
    echo "Working in Linux"
    sudo chown -R www-data:dev .
    find . -type d | sudo xargs chmod g+ws
    find . -type f | sudo xargs chmod g+w
    find . -type f | sudo xargs chmod o-w
    find . -type d | sudo xargs chmod o-ws
    sudo chmod a+x utils/*.sh
fi

if [ $os == "OSX" ]
then
    echo "Working in OSX"
    sudo chgrp -R localaccounts .
    find . -type d | sudo xargs chmod g+ws
    find . -type f | sudo xargs chmod g+w
    find . -type f | sudo xargs chmod o-w
    find . -type d | sudo xargs chmod o-ws
    sudo chmod a+x utils/*.sh
fi
