<?php defined('SYSPATH') OR die('No direct script access.');

class Model_GM extends Model {

  public function distort( $data = array() )
  {
    $command = '%binary_path%convert -virtual-pixel transparent %input_path%%filename% -matte +distort Perspective';

    $coords = '\'%blox%,%bloy% %bldx%,%bldy% %tlox%,%tloy% %tldx%,%tldy% %trox%,%troy% %trdx%,%trdy% %brox%,%broy% %brdx%,%brdy%\'';

    $out = '-background transparent -layers merge +repage -bordercolor transparent -border %borderX%x%borderY% -crop %width%x%height%-10-10 %output_path%%num%.png';

    return $this->_execute($command.' '.$coords.' '.$out, $data);
  }

  public function merge( $data = array() )
  {
    $shell_command = '%binary_path%composite %overlay_path%%num%.png -dissolve 85 -quality 100  %image_path%%offset%.jpg %output_path%%num%.jpg';

    return $this->_execute($shell_command, $data);
  }
}