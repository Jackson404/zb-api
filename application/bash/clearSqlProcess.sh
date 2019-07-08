#!/bin/bash
#---------------------------
# Author:zhengwenqiang
# Date: 20170714
# Description: clean sleep process
#
#---------------------------
IP=172.16.11.52
Username=root
Password=root
[ -f SleepSID ] && rm -f SleepSID
mysqladmin -u$Username -p$Password processlist | sed -r 's/\s//g' | awk -F "|" '{if($6=="Sleep"){ print $2}}'>SleepSID

while read sID
do
    echo $sID
    mysql -h$IP -u$Username -p$Password -P3306 -e "kill $sID"
done<SleepSID