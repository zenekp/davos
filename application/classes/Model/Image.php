<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Image extends Model {

  public function init( $data=array() )
  {
    if ( ! isset($data['input_path']) || empty($data['input_path']) ) {
      throw new Exception('No input path set for image');
    }

    if ( ! isset($data['filename']) || empty($data['filename']) ) {
      throw new Exception('No input file set for image');
    }

    $input_path = $data['input_path'];

    $filename = $data['filename'];

    if ( ! file_exists($input_path.$filename) ) {
      throw new Exception('image input file '.$input_path.$filename.' does not exist!');
    }

    $size = getimagesize($input_path.$filename);

    $this->_data['width'] = ( isset($size[0]) && ! empty($size[0]) ) ? $size[0] : FALSE;
    $this->_data['height'] = ( isset($size[1]) && ! empty($size[1]) ) ? $size[1] : FALSE;

    // Check the dimensions are valid
    if ( $this->_data['width'] === FALSE || $this->_data['height'] === FALSE ) {
      throw new Exception('Could not get image dimensions for '.$input_path.$filename.'!');
    }

    return $this;
  }
}