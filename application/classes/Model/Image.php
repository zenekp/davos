<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Image extends Model {

  public function init( $data=array() )
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


    $input_path = $data['input_path'];

    $input_file = $data['input_file'];

    $input_extension = $data['input_extension'];

    if ( ! file_exists($input_path.$input_file.'.'.$input_extension) ) {
      throw new Exception('image input file '.$input_path.$input_file.'.'.$input_extension.' does not exist!');
    }

    $size = getimagesize($input_path.$input_file.'.'.$input_extension);

    $this->_data['width'] = ( isset($size[0]) && ! empty($size[0]) ) ? $size[0] : FALSE;
    $this->_data['height'] = ( isset($size[1]) && ! empty($size[1]) ) ? $size[1] : FALSE;

    // Check the dimensions are valid
    if ( $this->_data['width'] === FALSE || $this->_data['height'] === FALSE ) {
      throw new Exception('Could not get image dimensions for '.$input_path.$input_file.'.'.$input_extension.'!');
    }

    return $this;
  }
}