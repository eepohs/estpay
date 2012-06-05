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

uninstall_banklink(){
    echo "Uninstalling Banklink module code and configuration"
    rm -rf $magebase/app/code/local/Wasabi/Banklink
    rm -rf $magebase/app/etc/modules/Wasabi_Banklink.xml
    echo "Uninstalling Banklink layout and templates"
    rm -rf $magebase/app/design/frontend/default/default/layout/banklink.xml
    rm -rf $magebase/app/design/frontend/default/default/template/banklink
    rm -rf $magebase/app/design/adminhtml/default/default/template/banklink
    echo "Uninstalling Banklink skin"
    rm -rf $magebase/skin/frontend/default/banklink
    echo "Uninstalling Banklink locale files"
    rm -rf $magebase/app/locale/en_US/Wasabi_Banklink.csv
    rm -rf $magebase/app/locale/et_EE/Wasabi_Banklink.csv
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
            uninstall_banklink
        else
            echo "Please specify correct Magento root folder. For example ../ or /home/vhosts/yourdomain.com/htdocs/magento"
        fi
    ;;
esac