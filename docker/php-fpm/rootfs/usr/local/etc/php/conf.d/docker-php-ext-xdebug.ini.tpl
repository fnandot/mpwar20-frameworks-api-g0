zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20180731/xdebug.so

;Debugging
xdebug.remote_enable = ${XDEBUG_ENABLE};
xdebug.remote_autostart = ${XDEBUG_REMOTE_AUTOSTART};
xdebug.remote_connect_back = ${XDEBUG_CONNECT_BACK};
xdebug.remote_host = ${XDEBUG_REMOTE_HOST};
xdebug.remote_port = ${XDEBUG_REMOTE_PORT};
xdebug.idekey = "${XDEBUG_IDE_KEY}"

;Profiling
xdebug.profiler_enable = 0;
xdebug.profiler_enable_trigger = 1;
xdebug.profiler_output_dir = "/tmp/xdebug";

xdebug.max_nesting_level = 500;

