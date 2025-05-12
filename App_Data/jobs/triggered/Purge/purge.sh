#!/bin/bash
echo "Running purge script..."
cd /home/site/wwwroot
php purge.php
echo ""
echo "Purge script completed."
# 
# This script is used to clear notes in a web application. It runs a PHP script located at /home/site/wwwroot/purge.php.
# The script is executed in the background, and its output is not displayed in the terminal.