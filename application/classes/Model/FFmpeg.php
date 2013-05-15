<?php defined('SYSPATH') OR die('No direct script access.');

class Model_FFmpeg extends Model {

  public function combine_frames( $data = array() )
  {
    $shell_command = '%binary_path%ffmpeg -i %input_path%%user_id%/image/merged/%5d.jpg -b %bit_rate% -vcodec mpeg4 %output_path%%user_id%/video/output.%output_extension%';

    return $this->_execute($shell_command, $data);
  }

  public function extract_frames( $data = array() )
  {
    $shell_command = '%binary_path%ffmpeg -i %input_path%%filename% -r %frame_rate% -qscale 1 %output_path%%5d.%output_extension%';

    return $this->_execute($shell_command, $data);
  }

  public function extract_audio( $data = array() )
  {
    $shell_command = '%binary_path%ffmpeg -i %input_path%%filename% -vn -ac 2 -ar 44100 -ab 320k -f mp3 %output_path%audio.%output_extension%';

    return $this->_execute($shell_command, $data);
  }
}