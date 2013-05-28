<?php defined('SYSPATH') OR die('No direct script access.');

class Model_GM extends Model {

  public function distort( $data = array() )
  {
    $command = '%binary_path%convert -virtual-pixel transparent %input_path%%input_file%.%input_extension% -matte +distort Perspective';

    $coords = '\'%blox%,%bloy% %bldx%,%bldy% %tlox%,%tloy% %tldx%,%tldy% %trox%,%troy% %trdx%,%trdy% %brox%,%broy% %brdx%,%brdy%\'';

    $out = '-background transparent -layers merge +repage -bordercolor transparent -border %borderX%x%borderY% -crop %width%x%height%-10-10 %output_path%%output_file%%num%.%output_extension%';

    return $this->_execute($command.' '.$coords.' '.$out, $data);
  }

  public function merge( $data = array() )
  {
    $shell_command = '%binary_path%composite %overlay_path%%overlay_file%%num%.%overlay_extension% -dissolve 85 -quality 100 -geometry +%x_offset%+%y_offset% %image_path%%image_file%%offset%.%image_extension% %output_path%%output_file%%num%.%output_extension%';

    return $this->_execute($shell_command, $data);
  }

  public function resize( $data = array() )
  {
    $shell_command = '%binary_path%convert %input_path%%input_file%.%input_extension% -resize %width%x%height% %output_path%%output_file%.%output_extension%';

    return $this->_execute($shell_command, $data);
  }
}