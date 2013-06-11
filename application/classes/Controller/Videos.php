<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Videos extends Controller_App {

  public function action_combine_frames()
  {
    $ffmpeg = Model::factory('FFmpeg');

    $ffmpeg->init(array(
      'binary_path' => Kohana::$config->load('app.ffmpeg_path'),
      'input_path' => '/var/www/davos/application/data/user/',
      'output_path' => '/var/www/davos/application/data/user/',
      'output_extension' => 'mp4',
      'bit_rate' => '\2400k',
    ));

    $ffmpeg->combine_frames(array(
      'user_id' => '12345',
    ));
  }

	public function action_extract_frames()
	{
	  $ffmpeg = Model::factory('FFmpeg');

    $ffmpeg->init(array(
      'binary_path' => Kohana::$config->load('app.ffmpeg_path'),
      'filename' => 'source.mp4',
      'input_path' => '/var/www/davos/application/data/video/',
      'output_path' => '/var/www/davos/application/data/video/frames/',
      'output_extension' => 'jpg',
      'frame_rate' => 25,
    ));

    $ffmpeg->extract_frames();
	}

	public function action_extract_audio()
	{
    $ffmpeg = Model::factory('FFmpeg');

    $ffmpeg->init(array(
      'binary_path' => Kohana::$config->load('app.ffmpeg_path'),
      'filename' => 'source.mp4',
      'input_path' => '/var/www/davos/application/data/video/',
      'output_path' => '/var/www/davos/application/data/audio/',
      'output_extension' => 'mp3',
    ));

    $ffmpeg->extract_audio();
	}
}