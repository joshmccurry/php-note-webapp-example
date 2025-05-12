#!/bin/sh
echo "Running sleep script..."
running=1

while [ $running -eq 1 ]
do
    echo "Sleeping..."
    cd /home/site/wwwroot
    php add.php
    rand=$(php sleep.php)
    dat=$(($rand % 2))
    if [ $dat -eq 0 ]; then
        echo "Exiting Sleep..."
        break
    else
        echo "Sleeping again..."
    fi
done
