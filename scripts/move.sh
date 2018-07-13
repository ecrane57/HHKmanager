#!/bin/bash

src="/var/www/html/hhk/demo/$1"
dest="/var/www/html/hhk/$1"

# check if site exists
if [ ! -d $src ]
then
	echo "The source does not exist...exiting"
	exit
fi

if [ -d $dest ]
then
	echo "The destination already exists...exiting"
	exit
fi

cmd="mv $src $dest"

echo "<password>" | su -c "$cmd" -m "hhkapp"
echo "<password>" | su -c "chown hhkapp:webdev $dest -R" -m "hhkapp"
echo "<password>" | su -c "chmod 775 $dest -R" -m "hhkapp"
echo "<password>" | su -c "chmod 777 $dest/conf -R" -m "hhkapp"