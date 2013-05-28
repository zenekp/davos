<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Model extends Kohana_Model {

  protected $_debug = FALSE;

  protected $_data = array();

  public function init( $data=array() )
  {
    foreach ( $data as $key => $value )
    {
      $this->_data[$key] = $value;
    }

    return $this;
  }

  protected function _execute( $shell_command = NULL, $data = array() )
  {
    if ( is_null($shell_command) )
    {
      return FALSE;
    }

    if ( $this->_debug === TRUE )
    {
      $shell_command .= ' 2>&1 1> /dev/null';
    }

    $data = array_merge($this->_data, $data);

    foreach ($data as $key => $value ) {
      $shell_command = str_replace('%'.$key.'%', $value, $shell_command);
    }

    $start = microtime();

    // Split the source video into an appropriate number of frames
    $return = shell_exec($shell_command);

    $end = microtime();

    if ( $this->_debug === TRUE )
    {
      var_dump($return, $shell_command);
    }

    if ( $this->_debug === TRUE )
    {
      echo 'Split script took '.($start - $end).' seconds';
    }
  }

  public function __set( $name, $value )
  {
    if ( isset($this->_data[$name]) )
    {
      $this->_data[$name] = $value;

      return TRUE;
    }

    return FALSE;
  }

  public function __get( $name )
  {
    if ( isset($this->_data[$name]) )
    {
      return $this->_data[$name];
    }

    return FALSE;
  }
}