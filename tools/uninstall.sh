#!/bin/bash
################################################################################
#                                                                              #
# Uninstalls Eepohs Estpay extension                                           #
# Note - this script deletes all files and directories of Estpay extension     #
# All files will be removed!                                                   #
#                                                                              #
# @license http://www.wasabi.ee/w/2009/05/wasabi-commercial-software-license/  #
# @version 1.1                                                           #
# @author Wasabi OÜ                                                            #
# @copyright 2012 Eepohs OÜ http://www.eepohs.com/                           #
#                                                                              #
################################################################################

magebase=$1
DIR=$(cd $(dirname "$0"); pwd)
installlog=`cat ${DIR}/install.log`

# This script removes all files installed by Estpay
uninstall_estpay(){
    for f in $installlog; do
        rm -f ${magebase}/${f}
        echo "Removed ${magebase}/${f}"
    done
}

check_folders(){
    if [ -d $magebase/app ]
    then
        return 1
    else
        return 0
    fi
}
case "$1" in
    "")
        echo "Usage: please specify your Magento installation root folder. It can be absolute path or relative path to current folder"
        exit 1
    ;;
    *)
        if  ! check_folders; then
            uninstall_estpay
        else
            echo "Please specify correct Magento root folder. For example ../ or /home/vhosts/yourdomain.com/htdocs/magento"
        fi
    ;;
esac