<?php defined('SYSPATH') or die('No direct script access.');
ini_set('max_execution_time', 300);
class Controller_App extends Controller {

  protected $_tracking_data;

  protected $_source_image;

  protected $_fb_user_id;

  protected $_users_path;

  public $layout;

  public $view;

  public function before()
  {
    $this->_users_path = APPPATH.'data'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR;

    $this->layout = View::factory('layout')->set(array(
      'content' => '',
    ));
  }

  public function after()
  {
    $this->layout->set(array(
      'content' => $this->view,
    ));

    $this->response->body($this->layout);
  }

  public function action_index()
  {
    $this->view = View::factory('index')->set(array(
      'flash' => Session::instance()->get_once('flash'),
    ));
  }

  protected function _create_user_folders()
  {
    try
    {
      $flag = TRUE;

      // Do necessary folder setup for the user
      if ( ! file_exists($this->_users_path.$this->_fb_user_id) )
      {
        if ( ! mkdir($this->_users_path.$this->_fb_user_id) ) { $flag = FALSE; }

        if ( ! mkdir($this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image') ) { $flag = FALSE; }

        if ( ! mkdir($this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'distorted') ) { $flag = FALSE; }

        if ( ! mkdir($this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'merged') ) { $flag = FALSE; }
      }

      $video_path = DOCROOT.'media'.DIRECTORY_SEPARATOR.'video'.DIRECTORY_SEPARATOR.$this->_fb_user_id;

      if ( ! file_exists($video_path) )
      {
        if ( ! mkdir($video_path) ) { $flag = FALSE; }

        if ( ! chmod($video_path, 0777) ) { $flag = FALSE; }
      }

      if ( $flag === FALSE )
      {
        throw new Exception('Folder structure couldnt be created.');
      }
    }
    catch ( Exception $e )
    {
      Session::instance()->set('flash', $e->getMessage());

      $this->redirect('/');
    }
  }

  protected function _get_facebook_image()
  {
    try
    {
      // Get the users facebook profile picture
      $picture_url = 'http://graph.facebook.com/'.$this->_fb_user_id.'/picture?type=large';

      $image_data = file_get_contents($picture_url);

      if ( $image_data === FALSE )
      {
        throw new Exception('Facebook profile picture couldnt be found.');
      }

      $result = file_put_contents( $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'profile.jpg', $image_data );

      if ( $result === FALSE )
      {
        throw new Exception('Facebook profile picture couldnt be created.');
      }
    }
    catch (Exception $e)
    {
      Session::instance()->set('flash', $e->getMessage());

      $this->redirect('/');
    }
  }

  protected function _create_source_image()
  {
    $this->_im_resize = Model::factory('IM')->init(array(
      'binary_path' => Kohana::$config->load('app.im_path'),
      'width' => 190,
      'height' => 190,
      'input_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR,
      'input_file' => 'profile',
      'input_extension' => 'jpg',
      'output_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR,
      'output_file' => 'profile_resized',
      'output_extension' => 'jpg',
    ));

    $this->_im_resize->resize();

    $this->_im_merge = Model::factory('IM')->init(array(
      'binary_path' => Kohana::$config->load('app.im_path'),
      'image_path' => APPPATH.'data'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR,
      'image_file' => 'blank_profile',
      'image_extension' => 'png',
      'overlay_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR,
      'overlay_file' => 'profile',
      'overlay_extension' => 'jpg',
      'output_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR,
      'output_file' => 'source',
      'output_extension' => 'png',
      'x_offset' => 345,
      'y_offset' => 170,
      'offset' => '',
      'num' => '',
    ));

    $this->_im_merge->merge();
  }


  public function action_setup()
  {
    $post = $this->request->post();

    if ( empty($post) )
    {
      $this->redirect('/');
    }

    // Validate post data
    if ( ! isset($post['user_id']) && empty($post['user_id']) )
    {
      Session::instance()->set('flash', 'Couldnt retrieve Facebook User ID');

      $this->redirect('/');
    }

    $this->_fb_user_id = $post['user_id'];

    $this->_create_user_folders();

    $this->_get_facebook_image();

    $this->_create_source_image();

    try
    {
      $this->_tracking_data = $this->_get_tracking_data();

      $this->_source_image = $this->_get_source_image(array(
        'input_extension' => 'png',
        'input_file' => 'source',
        'input_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR,
      ));

      $this->_im_distort = Model::factory('IM')->init(array(
        'binary_path' => Kohana::$config->load('app.im_path'),
        'width' => $this->_source_image->width,
        'height' => $this->_source_image->height,
        'input_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR,
        'input_file' => 'source',
        'input_extension' => 'png',
        'output_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'distorted'.DIRECTORY_SEPARATOR,
        'output_file' => '',
        'output_extension' => 'png',
        'blox' => 0,                            // bottom-left-origin-x
        'bloy' => $this->_source_image->height, // bottom-left-origin-y
        'tlox' => 0,                            // top-left-origin-x
        'tloy' => 0,                            // top-left-origin-y
        'trox' => $this->_source_image->width,  // top-right-origin-x
        'troy' => 0,                            // top-right-origin-y
        'brox' => $this->_source_image->width,  // bottom-right-origin-x
        'broy' => $this->_source_image->height, // bottom-right-origin-y
      ));

      $this->_im_merge = Model::factory('IM')->init(array(
        'binary_path' => Kohana::$config->load('app.im_path'),
        'image_path' => APPPATH.'data'.DIRECTORY_SEPARATOR.'video'.DIRECTORY_SEPARATOR.'frames'.DIRECTORY_SEPARATOR,
        'image_file' => '',
        'image_extension' => 'jpg',
        'overlay_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'distorted'.DIRECTORY_SEPARATOR,
        'overlay_file' => '',
        'overlay_extension' => 'png',
        'output_path' => $this->_users_path.$this->_fb_user_id.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'merged'.DIRECTORY_SEPARATOR,
        'output_file' => '',
        'output_extension' => 'jpg',
        'x_offset' => 0,
        'y_offset' => 0,
      ));

      // $total_token = Profiler::start('davos', 'total');

      $this->_distort_merge();

      // Profiler::stop($total_token);

      $ffmpeg = Model::factory('FFmpeg');

      $ffmpeg->init(array(
        'binary_path' => Kohana::$config->load('app.ffmpeg_path'),
        'filename' => 'source.mp4',
        'input_path' => $this->_users_path,
        'output_path' => DOCROOT.'media'.DIRECTORY_SEPARATOR.'video'.DIRECTORY_SEPARATOR,
        'output_file' => 'soundless',
        'output_extension' => 'mp4',
        'bit_rate' => '2400k',
      ));

      $ffmpeg->combine_frames(array(
        'user_id' => $this->_fb_user_id,
        'codec' => 'libx264', //'mpeg4'
      ));

      $this->_add_audio();

      $this->_create_other_formats();

      // $stats = Profiler::stats(array($total_token));

      $this->redirect('/app/video/'.$this->_fb_user_id);
    }
    catch (Exception $e)
    {
      Session::instance()->set('flash', $e->getMessage());

      $this->redirect('/');
    }
  }

  protected function _add_audio()
  {
    $ffmpeg = Model::factory('FFmpeg');

    $ffmpeg->init(array(
      'binary_path' => Kohana::$config->load('app.ffmpeg_path'),
      'input_path' => DOCROOT.'media'.DIRECTORY_SEPARATOR.'video'.DIRECTORY_SEPARATOR.$this->_fb_user_id.DIRECTORY_SEPARATOR,
      'input_file' => 'soundless',
      'input_extension' => 'mp4',
      'output_path' => DOCROOT.'media'.DIRECTORY_SEPARATOR.'video'.DIRECTORY_SEPARATOR.$this->_fb_user_id.DIRECTORY_SEPARATOR,
      'output_file' => 'output',
      'output_extension' => 'mp4',
      'audio_path' => APPPATH.'data'.DIRECTORY_SEPARATOR.'audio'.DIRECTORY_SEPARATOR,
      'audio_file' => 'audio',
      'audio_extension' => 'mp3',
      'vcodec' => 'libx264',
      'acodec' => 'libfaac',
      'audio_frequency' => '48000', // -ar
    ));

    $ffmpeg->add_audio();
  }

  protected function _create_other_formats()
  {
    $ffmpeg = Model::factory('FFmpeg');

    $ffmpeg->init(array(
      'binary_path' => Kohana::$config->load('app.ffmpeg_path'),
      'input_path' => DOCROOT.'media'.DIRECTORY_SEPARATOR.'video'.DIRECTORY_SEPARATOR.$this->_fb_user_id.DIRECTORY_SEPARATOR,
      'input_file' => 'output',
      'input_extension' => 'mp4',
      'output_path' => DOCROOT.'media'.DIRECTORY_SEPARATOR.'video'.DIRECTORY_SEPARATOR,
      'bit_rate' => '2400k',
    ));

    $ffmpeg->convert(array(
      'user_id' => $this->_fb_user_id,
      'output_extension' => 'ogg',
      'codec' => 'libtheora',
    ));

    $ffmpeg->convert(array(
      'user_id' => $this->_fb_user_id,
      'output_extension' => 'webm',
      'codec' => 'libvpx',
    ));
  }

  public function action_video()
  {
    if ( ! $id = $this->request->param('id', FALSE) )
    {
      die('No Facebook User ID Set');
    }

    $this->view = View::factory('video')->set(array(
      'id' => $id,
    ));
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

      $this->_im_distort->distort($coords);

      $this->_im_merge->merge(array(
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

    if ( ! isset($data['input_file']) || empty($data['input_file']) ) {
      throw new Exception('No input file set for image');
    }

    if ( ! isset($data['input_extension']) || empty($data['input_extension']) ) {
      throw new Exception('No input extension set for image');
    }

    $image = Model::factory('Image')->init(array(
      'input_extension' => $data['input_extension'],
      'input_file' => $data['input_file'],
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