echo `date` >>  /var/www/html/school/crm_web/execute_daily.log 
echo "this is a test">> /var/www/html/school/crm_web/execute_daily.log
curl http://www.cike360.com/school/crm_web/portal/index.php?r=report/Dayreport
