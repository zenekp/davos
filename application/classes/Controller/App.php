<?php defined('SYSPATH') or die('No direct script access.');

class Controller_App extends Controller {

  protected $_tracking_data;

  protected $_source_image;

  public function action_index()
  {
    try
    {
      $filename = 'source.png';
      $binary_path  = '/opt/local/bin/';
      $input_path = '/var/www/davos/application/data/user/12345/image/';
      $video_path = '/var/www/davos/application/data/video/frames/';
      $distorted_path = '/var/www/davos/application/data/user/12345/image/distorted/';
      $merged_path = '/var/www/davos/application/data/user/12345/image/merged/';

      $this->_tracking_data = $this->_get_tracking_data();

      $this->_source_image = $this->_get_source_image(array(
        'filename' => $filename,
        'input_path' => $input_path,
      ));

      $this->_gm_distort = Model::factory('GM')->init(array(
        'binary_path' => $binary_path,
        'width' => $this->_source_image->width,
        'height' => $this->_source_image->height,
        'filename' => $filename,
        'input_path' => $input_path,
        'output_path' => $distorted_path,
        'blox' => 0,                            // bottom-left-origin-x
        'bloy' => $this->_source_image->height, // bottom-left-origin-y
        'tlox' => 0,                            // top-left-origin-x
        'tloy' => 0,                            // top-left-origin-y
        'trox' => $this->_source_image->width,  // top-right-origin-x
        'troy' => 0,                            // top-right-origin-y
        'brox' => $this->_source_image->width,  // bottom-right-origin-x
        'broy' => $this->_source_image->height, // bottom-right-origin-y
      ));

      $this->_gm_merge = Model::factory('GM')->init(array(
        'binary_path' => $binary_path,
        'image_path' => $video_path,
        'overlay_path' => $distorted_path,
        'output_path' => $merged_path,
      ));

      $total_token = Profiler::start('davos', 'total');

      $this->_distort_merge();

      Profiler::stop($total_token);

      $ffmpeg = Model::factory('FFmpeg');

      $ffmpeg->init(array(
        'binary_path' => '/opt/local/bin/',
        'filename' => 'source.mp4',
        'input_path' => '/var/www/davos/application/data/user/',
        'output_path' => '/var/www/davos/application/data/user/',
        'output_extension' => 'mp4',
        'bit_rate' => '\2400k',
      ));

      $ffmpeg->combine_frames(array(
        'user_id' => '12345',
      ));

      $stats = Profiler::stats(array($total_token));

      var_dump($stats);
      exit;
    }
    catch (Exception $e)
    {
      die($e->getMessage());
    }
  }

  protected function _distort_merge()
  {
    $coord_keys = array(
      'tldx', // top-left-distorted-x
      'tldy', // top-left-distorted-y
      'trdx', // top-right-distorted-x
      'trdy', // top-left-distorted-y
      'bldx', // bottom-left-distorted-x
      'bldy', // bottom-left-distorted-y
      'brdx', // bottom-right-distorted-x
      'brdy', // bottom-right-distorted-y
    );

    foreach ( $this->_tracking_data as $i => $data )
    {
      if ( ! is_array($data) )
      {
        continue;
      }

      $coords = array_combine($coord_keys, $data);

      $coords['borderX'] = ($coords['tldx'] > $coords['bldx']) ? $coords['bldx'] : $coords['tldx'];

      $coords['borderY'] = ($coords['tldy'] > $coords['trdy']) ? $coords['trdy'] : $coords['tldy'];

      $num = $i + 1;

      $num = $this->_pad_filename($num);

      $offset = $this->_pad_filename($num + 1);

      $coords['num'] = $num;

      $this->_gm_distort->distort($coords);

      $this->_gm_merge->merge(array(
        'num' => $num,
        'offset' => $offset,
      ));
    }
  }

  protected function _pad_filename( $num )
  {
    if ( $num < 10 )
    {
      $num = '0000'.$num;
    }
    elseif ( $num < 100 )
    {
      $num = '000'.$num;
    }
    elseif ( $num < 1000 )
    {
      $num = '00'.$num;
    }
    elseif ( $num < 10000 )
    {
      $num = '0'.$num;
    }

    return $num;
  }

  protected function _get_source_image( $data = array() )
  {
    if ( ! isset($data['input_path']) || empty($data['input_path']) ) {
      throw new Exception('No input path set for image');
    }

    if ( ! isset($data['filename']) || empty($data['filename']) ) {
      throw new Exception('No input file set for image');
    }

    $image = Model::factory('image')->init(array(
      'filename' => $data['filename'],
      'input_path' => $data['input_path'],
    ));

    return $image;
  }

  protected function _get_tracking_data()
  {
    $tracking_data = Kohana::$config->load('tracking.data');
    if ( ! is_array($tracking_data) ) {
      throw new Exception('no input tracking data found!');
    }

    return $tracking_data;
  }
}