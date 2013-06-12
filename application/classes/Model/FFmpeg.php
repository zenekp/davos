<?php defined('SYSPATH') OR die('No direct script access.');

class Model_FFmpeg extends Model {

  public function combine_frames( $data = array() )
  {
    $shell_command = '%binary_path%ffmpeg -i %input_path%%user_id%/image/merged/%5d.jpg -y -b %bit_rate% -vcodec %codec% %output_path%%user_id%/%output_file%.%output_extension%';

    return $this->_execute($shell_command, $data);
  }

  public function extract_frames( $data = array() )
  {
    $shell_command = '%binary_path%ffmpeg -i %input_path%%filename% -y -r %frame_rate% -qscale 1 %output_path%%5d.%output_extension%';

    return $this->_execute($shell_command, $data);
  }

  public function extract_audio( $data = array() )
  {
    $shell_command = '%binary_path%ffmpeg -i %input_path%%filename% -y -vn -ac 2 -ar 44100 -ab 320k -f mp3 %output_path%audio.%output_extension%';

    return $this->_execute($shell_command, $data);
  }

  public function convert( $data = array() )
  {
    $shell_command = '%binary_path%ffmpeg -i %input_path%%input_file%.%input_extension% -y -b %bit_rate% -vcodec %codec% %output_path%%user_id%/output.%output_extension%';

    return $this->_execute($shell_command, $data);
  }

  public function add_audio( $data = array() )
  {
    $shell_command = '%binary_path%ffmpeg -i %input_path%%input_file%.%input_extension% -i %audio_path%%audio_file%.%audio_extension% -y -vcodec %vcodec% -acodec %acodec% -ar %audio_frequency% -ab 56k -ac 2 %output_path%/%output_file%.%output_extension%';

    return $this->_execute($shell_command, $data);
  }
}