#!/bin/bash
#########################################################################
#                                                                       #
# Creates release with a ZIP file which contains source                 #
# code and documentation                                                #
#                                                                       #
# @author: Sven Varkel <sven.varkel@eepohs.com                          #
# @version: 1.0                                                         #
#                                                                       #
#########################################################################

release="$1"
version=`git describe --abbrev=0 --tags`
appdir=`pwd`
currentdate=`date`
year=`date +"%Y"`
timestamp=`date +"%Y%m%d%H%M%S"`
timestampshort=`date +"%Y%m%d%H%M"`
branch="branches/${release}_${timestamp}"
tmpdir="../tmp/estpay_${release}/estpay_${version}_${timestampshort}/"
filename="Eepohs_Estpay_${version}.zip"
outputdir="../output/${release}/estpay_${version}_${timestampshort}"
output="${outputdir}/${filename}"
latestdir="../output/${release}/latest"

# Subroutine for getting release files from subversion
create_release(){
    echo "Checking out release branch ${release}"
    git checkout ${release}
    mkdir -p ${tmpdir}/htdocs
    echo "Copying files from release branch ${release} to ${tmpdir}"
    cp -R ../htdocs/* ${tmpdir}/htdocs
    cp -R ../htdocs/js ${tmpdir}/htdocs/js
    cp -R ../documentation ${tmpdir}/documentation
    cp -R ../htdocs/skin $tmpdir/htdocs/skin
    cp -R ../tools $tmpdir/tools
    return 0
}

# Subroutine for creating archive of installation package
make_zip(){
    echo "Creating output directory ${outputdir}"
    mkdir -p ${outputdir}

    echo "Creating folder with latest release: ${latestdir}"
    rm -rf ${latestdir}
    mkdir -p ${latestdir}
    cp -R ${tmpdir}/* ${latestdir}

    echo "Making ZIP archive"
    cd ${tmpdir}/

    zip -q -r -9 ${appdir}/${output} *

    echo "Archive $filename created in ${outputdir}"

     echo "Set working directory to ${appdir}"
    cd ${appdir}/

    return 0
}

replace_releasedto(){
    echo "Replacing \$ReleasedTo\$ with ${releasedTo} in ${tmpdir}"
    grep -Rl "\$ReleasedTo\\$" ../tmp/* | xargs perl -p -i -e "s/\\\$ReleasedTo\\$/$releasedTo/g"
    echo "Replacing \$version\$ with $version in ${tmpdir}"
    grep -Rl "\$version\\$" ../tmp/* | xargs perl -p -i -e "s/\\\$version\\$/$version/g"
    echo "Replacing \$year\$ with ${year} in $tmpdir"
    grep -Rl "\$year\\$" ../tmp/* | xargs perl -p -i -e "s/\\\$year\\$/$year/g"
    return 0
}
# Subroutine for cleaning up temporary folder
cleanup(){
    echo "Check out master"
    git checkout master

    #echo "Remove temporary branch"
    #git branch -D ${branch}

    echo "Cleaning up ${tmpdir}"
    rm -rf ${tmpdir}

    return 0
}
case "$1" in
"")
    echo "Please specify release branch name where to create a release from"
    echo "Example: ./create_release.sh release-1.0"
    exit 1
    ;;
*)
    echo "Creating release $1 of version $version"
    cleanup
    create_release
    replace_releasedto
    make_zip
    cleanup
    ;;
esac
exit 0