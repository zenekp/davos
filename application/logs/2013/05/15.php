<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2013-05-15 06:08:35 --- EMERGENCY: ErrorException [ 4 ]: syntax error, unexpected T_CONSTANT_ENCAPSED_STRING, expecting ')' ~ APPPATH/classes/Model/FFmpeg.php [ 13 ] in :
2013-05-15 06:08:35 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in :
2013-05-15 06:08:52 --- EMERGENCY: ErrorException [ 2 ]: Attempt to assign property of non-object ~ APPPATH/classes/Model.php [ 9 ] in /private/var/www/davos/application/classes/Model.php:9
2013-05-15 06:08:52 --- DEBUG: #0 /private/var/www/davos/application/classes/Model.php(9): Kohana_Core::error_handler(2, 'Attempt to assi...', '/private/var/ww...', 9, Array)
#1 /private/var/www/davos/application/classes/Controller/Application.php(18): Model->initialise(Array)
#2 /private/var/www/davos/system/classes/Kohana/Controller.php(84): Controller_Application->action_source_video()
#3 [internal function]: Kohana_Controller->execute()
#4 /private/var/www/davos/system/classes/Kohana/Request/Client/Internal.php(97): ReflectionMethod->invoke(Object(Controller_Application))
#5 /private/var/www/davos/system/classes/Kohana/Request/Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#6 /private/var/www/davos/system/classes/Kohana/Request.php(990): Kohana_Request_Client->execute(Object(Request))
#7 /private/var/www/davos/web/index.php(118): Kohana_Request->execute()
#8 {main} in /private/var/www/davos/application/classes/Model.php:9
2013-05-15 06:09:14 --- EMERGENCY: ErrorException [ 8 ]: Undefined variable: ffmpegCommand ~ APPPATH/classes/Model/FFmpeg.php [ 36 ] in /private/var/www/davos/application/classes/Model/FFmpeg.php:36
2013-05-15 06:09:14 --- DEBUG: #0 /private/var/www/davos/application/classes/Model/FFmpeg.php(36): Kohana_Core::error_handler(8, 'Undefined varia...', '/private/var/ww...', 36, Array)
#1 /private/var/www/davos/application/classes/Controller/Application.php(20): Model_FFmpeg->split()
#2 /private/var/www/davos/system/classes/Kohana/Controller.php(84): Controller_Application->action_source_video()
#3 [internal function]: Kohana_Controller->execute()
#4 /private/var/www/davos/system/classes/Kohana/Request/Client/Internal.php(97): ReflectionMethod->invoke(Object(Controller_Application))
#5 /private/var/www/davos/system/classes/Kohana/Request/Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#6 /private/var/www/davos/system/classes/Kohana/Request.php(990): Kohana_Request_Client->execute(Object(Request))
#7 /private/var/www/davos/web/index.php(118): Kohana_Request->execute()
#8 {main} in /private/var/www/davos/application/classes/Model/FFmpeg.php:36
2013-05-15 06:21:52 --- EMERGENCY: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ APPPATH/classes/Model/FFmpeg.php [ 24 ] in :
2013-05-15 06:21:52 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in :
2013-05-15 07:03:47 --- EMERGENCY: ErrorException [ 4 ]: syntax error, unexpected ';' ~ APPPATH/classes/Controller/Videos.php [ 24 ] in :
2013-05-15 07:03:47 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in :
2013-05-15 07:03:53 --- EMERGENCY: ErrorException [ 4 ]: syntax error, unexpected ',' ~ APPPATH/config/tracking.php [ 1 ] in :
2013-05-15 07:03:53 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in :
2013-05-15 09:53:27 --- EMERGENCY: ErrorException [ 4 ]: syntax error, unexpected ';', expecting ')' ~ APPPATH/classes/Controller/App.php [ 110 ] in :
2013-05-15 09:53:27 --- DEBUG: #0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main} in :