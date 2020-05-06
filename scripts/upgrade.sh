#!/bin/bash

pwd=""
version="/var/www/manage/storage/app/hhk/$1/"
site="/var/www/html/hhk/$2"

echo "Version: $version"
echo "Site: $site"

# check if version exists
if [ ! -d $version ]
then
	echo "The version does not exist...exiting"
	exit
fi

# check if site exists
if [ ! -d $site ]
then
	echo "The site does not exist...exiting"
	exit
fi

rsync="rsync -acO --exclude=.git --exclude=install"

if [ ! -d "${site}/house" ]; then
	rsync+= " --exclude=house"
fi

if [ ! -d "${site}/volunteer" ]; then
	rsync+= " --exclude=volunteer"
fi

rsync+=" $version $site"

#echo "Rsync command: $rsync"

echo $pwd | su -c "$rsync" -m "hhkapp"
echo $pwd | su -c "chown hhkapp:webdev $site -R" -m "hhkapp"
echo $pwd | su -c "chmod 775 $site -R" -m "hhkapp"
