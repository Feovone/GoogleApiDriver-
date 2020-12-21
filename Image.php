<?php
require __DIR__ . '/vendor/autoload.php';
require "quick.php";
class Image
{
    private array $categories = array();
    private function mailsParser()
    {
            $handle = fopen("emails.txt","r");
            $i = 0;
            while ($i++ !== 2 && ($buffer = fgets($handle, 4096)) !== false) {
            $emails[$i] = explode(',', $buffer);
}
        /*$data = file_get_contents("emails.txt");
        $emails = explode("\r\n", $data);
        foreach ($emails as $key => $mail) {
            $emails[$key] = explode(',', $mail);
        }*/
        return $emails;
    }

    private function wordsSelector()
    {
        $handle = file("word_rus.txt");
        return $words = array($handle[5000],$handle[10000],$handle[15000],$handle[20000],$handle[25000]);
    }
    private function imagePrepare($emails)
    {
        $font = 'D:\\1\\arial.ttf';
        $dir = scandir("photos");
        $words = $this->wordsSelector();
        $max = count($words);
       // $part = ceil($max / 3);
        $i = 2;
        //$checksize = 0;
        foreach ($emails as $key => $email) {
            $count = count($email);
            for ($m = 1; $m < $count; $m++) {
                for (; $i < 12;) {
                    /*if($checksize > 19529728000) {
                        break 3;
                    }*///16100884000
                    //$selectedWords = array(rand(0, $part), rand($part, $part * 2), rand($part * 2, $max));
                    $im = imagecreatefromjpeg("photos\\" . $dir[$i]);
                    $size = getimagesize("photos\\" . $dir[$i]);
                    $countColor = $this->color($im, $size);
                    for ($y = 1; $y < 6; $y++) {
                        $round  = 0.15*$y;
                        imagefttext($im
                            , ceil(0.033 * $size[1]), 0, $size[0] *$round, $size[1]*$round, imageColorAllocate($im,
                                255, 255, 255),
                            $font, $words[$y - 1]);
                    }
                    $email[$m]=str_replace("\r\n","", $email[$m]);
                    Imagejpeg($im, 'temp\\' .mb_substr($dir[$i],0,-4).$email[$m] .$countColor. '.jpg', 100);

                    //$checksize += filesize('temp\\' . $email[$m] . '.jpg');
                    /*if ($i == 406) {
                        $i = 2;
                    }*/
                   // $this->categoryAdd($key, $m, $countColor);
                    imagedestroy($im);
                    $i++;
                    break;
                }
            }
        }
    }

    private function categoryAdd($key, $m, $countColor)
    {
        $category = $this->categoryParse($countColor);
        if(!isset($this->categories[$key][$m])){
            $this->categories[$key][$m]=array();
        }
        $this->categories[$key][$m] = $category;
    }

    private function categoryParse($countColor)
    {
        $data = file_get_contents("categories.txt");
        $categories = explode("\n", $data);
        $maxIndex = count($categories);
        $part = $maxIndex / 7;
        $randPart = rand(0, 6);
        $index = $randPart * $part + $countColor;
        return $categories[$index];
    }

    private function color($im, $size)
    {
        $countColor = 0;
        $size[0] = $size[0] / 2 - 1;
        $size[1] = $size[1] / 2 - 1;
        for ($i = 0; $i < 3; $i++) {
            $color = imagecolorat($im, $size[0] * $i, $size[1] * $i);
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = $color & 0xFF;
            $countColor += $r + $g + $b;
        }
        return $countColor;
    }
    private function publish($emails){
        $dir = scandir("temp");
        $dirs = scandir("photos");
            $i=0;
            $j=2;
            for($m=0;$m<count($emails);$m++){
                for($e=1;$e<count($emails[$m]);$e++){
                    for(;$i<count($dir)-2;){
                        $im= imagecreatefromjpeg("photos\\" . $dirs[$j]);
                        $size = getimagesize("photos\\" . $dirs[$j]);
                        $countColor = $this->color($im, $size);
                        $this->categoryAdd($m, $e, $countColor);

                        insertFile($this->categories[$m][$e],"image/jpeg",'temp\\'.$emails[$m][$e].".jpg",$emails[$m][$e]);
                        echo "m:$m e:$e i:$i\r\n";


                        $i++;
                        $j++;
                        imagedestroy($im);
                        if($i==count($dir)-2){break 3;}
                        if($j==407){$j=2;}
                    break;
                }
            }

        }
    }
    private function publishShort(){
        $dir = scandir("temp");
        $countDir=count($dir);
        for($i = 2;$i<$countDir;$i++){
                insertFile("image/jpeg",'temp\\'.$dir[$i],mb_substr($dir[$i],0,-4));
    }
    }
public function main(){

        $start = microtime(true);
        $emails = $this->mailsParser();
        $this->imagePrepare($emails);
        $timeImage = microtime(true) - $start;
        $this->publishShort();
        $timePub = microtime(true) - $start - $timeImage;
        echo "imagePrepare ".$timeImage." sec\r\n" ;
        echo "publishImg ".$timePub." sec";
    }

}
$class = new Image();
$class->main();