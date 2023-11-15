sleep 46800

supervisorctl stop all

[program:stop-request]
command=sh /var/www/phpBotCommand/stop_supervisor.sh
autostart=true
autorestart=true
stderr_logfile=/var/log/myscript.err.log
stdout_logfile=/var/log/myscript.out.log
