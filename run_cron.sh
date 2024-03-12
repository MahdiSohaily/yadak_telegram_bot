#!/bin/bash

while true
do
    php cron.php
    sleep 60  # 300 seconds = 5 minutes
done
