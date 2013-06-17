<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
  'im_path' => '/usr/bin/', // server config
  'ffmpeg_path' => '/usr/bin/', // server config
  'audio_codec' => 'libfdk_aac' // server audio codec
  'ogg_codec' => 'libogg' // server ogg codec
  'im_path' => '/opt/local/bin/', // local config
  'ffmpeg_path' => '/opt/local/bin/', // local config
  'audio_codec' => 'libfaac' // local audio codec
  'ogg_codec' => 'libtheora' // local ogg codec
);