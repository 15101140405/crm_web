<?php
/**
 * 
 * 图片convert转换类
 * 一般需要传入绝对路径和指定参数
 * $image = new ImagicUtil;
 * $image->preResize("/alidata/www/phpwind/jyh/trunk/lib/common/123.jpg", "/alidata/www/phpwind/jyh/trunk/lib/common/123_dest.jpg", "1024x768");
 *
 */

class ImagicUtil {

    /*
     * 按像素改变图片大小
     * $src：原图地址
     * $dst: 保存图片地址
     * $pixelMap：指定像素参数
     * convert -resize 1024x768 logo.jpg logo_1024x768.jpg
     */
    function preResize($src, $dst, $pixelMap)
    {
        exec("convert -resize {$pixelMap} {$src} {$dst}");
    }

    /*
     * 按像素比例改变图片大小
     * $src：原图地址
     * $dst: 保存图片地址
     * $sampleMap：指定压缩比例
     * convert -sample 50%x50% logo.jpg logo_half.jpg
     */
    function preSample($src, $dst, $sampleMap)
    {
        exec("convert -sample {$sampleMap} {$src} {$dst}");
    }

    /*
     函数说明：jpg按质量压缩
     参数说明：
      $src:原图地址,string型
      $dst:保存图片的地址,string型
      $q:压缩比率
      此函数在安全模式下不能运行
      convert -quality 0.5 hua.jpg hua_quality.jpg
    */
    function preQuality($src, $dst, $q)
    {
        exec("convert -quality {$q} {$src} {$dst}");
    }

    /*
     函数说明：对比度处理
     函数参数：
      $type:表示增加或减少对比度,逻辑型,true:增加; false:减少
      $apply:表示作用区域,逻辑型,true:局部作用; false:全局作用
      $w,$h,$x,$y:当$apply为true,来确定区域坐标,int型
      $src:原图片位置,string型
      $dst:处理后的目标图片存储位置,string型
    */
    function contrast($type, $apply, $src, $dst, $w=0, $h=0, $x=0, $y=0, $f=true)
    {
        if($type)
            $s = 9;
        else
            $s = 0;

        if($f)
            $image = new Imagick($src);
        else
            $image = $src;
        if($apply)
        {
            $region = $image->getImageRegion($w, $h, $x, $y);
            $region->contrastImage($s);
            $image->compositeImage($region, $region->getImageCompose(), $x, $y);
            $region->destroy();
        }
        else
            $image->contrastImage($s);
        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：将字母和数字生成png图片
     函数参数：
      $text:需要生成图片的文字,string型
      $color:文字颜色,string型
      $szie:文字大小,int型
      $font:字体,string型
      $type:返回类型,逻辑型,true:返回图片地址; false:返回图片资源
      $src:保存图片的地址,string型
    */
    function text($text, $color, $size, $font, $type=false, $src='')
    {
        $font = "include/font/" . $font . ".ttf";
        $draw = new ImagickDraw();
        $draw->setGravity( Imagick::GRAVITY_CENTER );
        $draw->setFont($font);
        $draw->setFontSize($size);
        $draw->setFillColor( new ImagickPixel($color) );

        $im = new imagick();
        $properties = $im->queryFontMetrics( $draw, $text );
        $im->newImage( intval($properties['textWidth']+5), intval($properties['textHeight']+5), new ImagickPixel('transparent') );
        $im->setImageFormat('png');
        $im->annotateImage($draw, 0, 0, 0, $text);

        if($type)
        {
            $im->writeImage($src);
            return $src;
        }
        else
            return $im;
    }

    /*
     函数说明：加水印
     函数参数：
      $text:水印文字,string型
      $color:文字颜色,string型
      $szie:文字大小,int型
      $font:字体,string型
      $src:原图地址,string型
      $dst:保存图片的地址,string型
      $x,$y:水印位置,int型
    */
    function mark($text, $color, $size, $font, $src, $dst, $x, $y)
    {
        $im = text($text, $color, $size, $font);
        $image = new Imagick($src);
        $image->compositeImage($im, Imagick::COMPOSITE_OVER, $x, $y);
        $image->writeImage($dst);
        $im->destroy();
        $image->destroy();
    }

    /*
     函数说明：模糊处理
     函数参数：
      $radius:模糊程度,int型
      $apply:表示作用区域,逻辑型,true:局部作用; false:全局作用
      $w,$h,$x,$y:当$apply为true,来确定区域坐标,int型
      $src:原图地址,string型
      $dst:保存图片的地址,string型
    */
    function gaussianblur($radius, $apply, $src, $dst, $x=0, $y=0, $w=0, $h=0)
    {
        if($apply && $x==0 && $y==0 && $w==0 && $h==0)
            $apply = false;
        $image = new Imagick($src);
        if($apply)
        {
            $region = $image->getImageRegion($w, $h, $x, $y);
            $region->blurImage($radius, $radius);
            $image->compositeImage($region, $region->getImageCompose(), $x, $y);
            $region->destroy();
        }
        else
            $image->blurImage($radius, $radius);
        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：锐化处理
     函数参数：
      $radius:锐化程度,int型
      $apply:表示作用区域,逻辑型,true:局部作用; false:全局作用
      $w,$h,$x,$y:当$apply为true,来确定区域坐标,int型
      $src:原图地址,string型
      $dst:保存图片的地址,string型
    */
    function sharpen($radius, $apply, $src, $dst, $x=0, $y=0, $w=0, $h=0)
    {
        if($apply && $x==0 && $y==0 && $w==0 && $h==0)
            $apply = false;
        $image = new Imagick($src);
        if($apply)
        {
            $region = $image->getImageRegion($w, $h, $x, $y);
            $region->sharpenImage($radius, $radius);
            $image->compositeImage($region, $region->getImageCompose(), $x, $y);
            $region->destroy();
        }
        else
            $image->sharpenImage($radius, $radius);
        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：突起效果
     函数参数：
      $raise:突起度,int型
      $apply:表示作用区域,逻辑型,true:局部作用; false:全局作用
      $w,$h,$x,$y:当$apply为true,来确定区域坐标,int型
      $src:原图地址,string型
      $dst:保存图片的地址,string型
    */
    function raise($raise, $apply, $src, $dst, $x=0, $y=0, $w=0, $h=0)
    {
        if($apply && $x==0 && $y==0 && $w==0 && $h==0)
            $apply = false;
        $image = new Imagick($src);
        if($apply)
        {
            if($w > (2*$raise) && $h > (2*$raise))
            {
                $region = $image->getImageRegion($w, $h, $x, $y);
                $region->raiseImage($raise, $raise, 0, 0, true);
                $image->compositeImage($region, $region->getImageCompose(), $x, $y);
                $region->destroy();
            }
        }
        else
        {
            $info = $image->getImageGeometry();
            if($info["width"] > (2*$raise) && $info["height"] > (2*$raise))
            {
                $image->raiseImage($raise, $raise, 0, 0, true);
            }
        }
        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：边框效果
     函数参数：
      $frame_width:边框宽度,int型
      $frame_height:边框宽度,int型
      $bevel:边框角度,int型
      $color:边框颜色,string型
      $apply:表示作用区域,逻辑型,true:局部作用; false:全局作用
      $w,$h,$x,$y:当$apply为true,来确定区域坐标,int型
      $src:原图地址,string型
      $dst:保存图片的地址,string型
    */
    function frame($frame_width, $frame_height, $bevel, $color, $apply, $src, $dst, $x=0, $y=0, $w=0, $h=0)
    {
        if($apply && $x==0 && $y==0 && $w==0 && $h==0)
            $apply = false;
        $image = new Imagick($src);
        $framecolor = new ImagickPixel($color);
        if($apply)
        {
            $region = $image->getImageRegion($w, $h, $x, $y);
            $region->frameImage($framecolor, $frame_width, $frame_height, $bevel, $bevel);
            $image->compositeImage($region, $region->getImageCompose(), $x, $y);
            $region->destroy();
        }
        else
            $image->frameImage($framecolor, $frame_width, $frame_height, $bevel, $bevel);
        $image->writeImage($dst);
        $framecolor->destroy();
        $image->destroy();
    }

    /*
     函数说明：油画效果
     函数参数：
      $radius:油画效果参数
      $apply:表示作用区域,逻辑型,true:局部作用; false:全局作用
      $w,$h,$x,$y:当$apply为true,来确定区域坐标,int型
      $src:原图地址,string型
      $dst:保存图片的地址,string型
    */
    function oilpaint($radius, $apply, $src, $dst, $x=0, $y=0, $w=0, $h=0)
    {
        if($apply && $x==0 && $y==0 && $w==0 && $h==0)
            $apply = false;
        $image = new Imagick($src);
        if($apply)
        {
            $region = $image->getImageRegion($w, $h, $x, $y);
            $region->oilPaintImage($radius);
            $image->compositeImage($region, $region->getImageCompose(), $x, $y);
            $region->destroy();
        }
        else
            $image->oilPaintImage($radius);
        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：发散效果
     函数参数：
      $radius:发散效果参数
      $apply:表示作用区域,逻辑型,true:局部作用; false:全局作用
      $w,$h,$x,$y:当$apply为true,来确定区域坐标,int型
      $src:原图地址,string型
      $dst:保存图片的地址,string型
    */
    function spread($radius, $apply, $src, $dst, $x=0, $y=0, $w=0, $h=0)
    {
        if($apply && $x==0 && $y==0 && $w==0 && $h==0)
            $apply = false;
        $image = new Imagick($src);
        if($apply)
        {
            $region = $image->getImageRegion($w, $h, $x, $y);
            $region->spreadImage($radius);
            $image->compositeImage($region, $region->getImageCompose(), $x, $y);
            $region->destroy();
        }
        else
            $image->spreadImage($radius);
        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：倾斜效果
     参数说明：
      $src:原图地址,string型
      $dst:保存图片的地址,string型
      $color:背景颜色,string型
      $angle:倾斜角度,int型
    */
    function polaroidEffect($src, $dst, $color, $angle=0)
    {
        if(abs($angle) != 15)
        {
            $srcs = array($src, $src, $src, $src);
            $bg = new ImagickDraw();
            $images = new Imagick($srcs);
            $format = $images->getImageFormat();

            $maxwidth = 0;
            $maxheight = 0;

            foreach($images as $key => $im)
            {
                $im->setImageFormat("png");
                $im->setImageBackgroundColor( new ImagickPixel("black") );

                $angle = mt_rand(-20, 20);
                if($angle == 0)
                    $angle = -1;

                $im->polaroidImage($bg, $angle);
                $info = $im->getImageGeometry();

                $maxwidth = max($maxwidth, $info["width"]);
                $maxheight = max($maxheight, $info["height"]);
            }
            $image = new Imagick();
            $image->newImage($maxwidth, $maxheight, new ImagickPixel($color));

            foreach($images as $key => $im)
            {
                $image->compositeImage($im, $im->getImageCompose(), 0, 0);
            }
            $image->setImageFormat($format);
            $bg->destroy();
            $images->destroy();
        }
        else
        {
            $image = new Imagick($src);
            $format = $image->getImageFormat();
            $image->frameImage(new ImagickPixel("white"), 6, 6, 0, 0);
            $image->frameImage(new ImagickPixel("gray"), 1, 1, 0, 0);
            $image->setImageFormat("png");
            $shadow = $image->clone();
            $shadow->setImageBackgroundColor( new ImagickPixel("black") );
            $shadow->shadowImage(50, 3, 0, 0);
            $shadow->compositeImage($image, $image->getImageCompose(), 0, 0);

            $shadow->rotateImage(new ImagickPixel($color), $angle);
            $info = $shadow->getImageGeometry();

            $image->destroy();
            $image = new Imagick();
            $image->newImage($info["width"], $info["height"], new ImagickPixel($color));
            $image->compositeImage($shadow, $shadow->getImageCompose(), 0, 0);
            $image->setImageFormat($format);
            $shadow->destroy();
        }

        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：生成手绘图片
     参数说明：
      $src:原图地址,string型
      $dst:保存图片的地址,string型
      $color:画笔背景颜色,string型
      $size:画笔尺寸,int型
      $brushpath:画笔轨迹,array型
    */
    function brushpng($src, $dst, $color, $size, $brushpath)
    {
        $image = new Imagick($src);
        $info = $image->getImageGeometry();
        $image->destroy();

        if(file_exists($dst))
            $image = new Imagick($dst);
        else
        {
            $image = new Imagick();
            $image->newImage($info["width"], $info["height"], "transparent", "png");
            //$image->setImageFormat("png");
        }

        $draw = new ImagickDraw();
        $pixel = new ImagickPixel();
        $pixel->setColor("transparent");
        $draw->setFillColor($pixel);
        $pixel->setColor($color);
        $draw->setStrokeColor($pixel);
        $draw->setStrokeWidth($size);
        $draw->setStrokeLineCap(imagick::LINECAP_ROUND);
        $draw->setStrokeLineJoin(imagick::LINEJOIN_ROUND);
        $draw->polyline($brushpath);

        $image->drawImage($draw);

        $image->writeImage($dst);
        $pixel->destroy();
        $draw->destroy();
        $image->destroy();
    }

    /*
     函数说明：合并图片
     参数说明：
      $src:原图地址,string型
      $dst:保存图片的地址,string型
      $png:需要合并的png图片地址,string型
    */
    function dobrush($src, $dst, $png)
    {
        $image = new Imagick($src);
        if(file_exists($png))
        {
            $imagepng = new Imagick($png);
            $imagepng->setImageFormat("png");
            $image->compositeImage($imagepng, $imagepng->getImageCompose(), 0, 0);
            $imagepng->destroy();
        }
        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：旋转图片
     参数说明：
      $src:原图地址,string型
      $dst:保存图片的地址,string型
      $angle:旋转角度,int型
    */
    function rotate($src, $dst, $angle)
    {
        $image = new Imagick($src);
        $image->rotateImage(new ImagickPixel(), $angle);
        $image->writeImage($dst);
        $image->destroy();
    }

    /*
     函数说明：图片亮度处理
     参数说明：
      $src:原图地址,string型
      $dst:保存图片的地址,string型
      $n:亮度比,float型
      $s_x,$s_y,$e_x,$e_y:起始点和结束点,int型
      $type:true表示存储图片,false表示返回处理后的Imagick对象
    */
    function brightness($src, $dst, $n,  $s_x=0, $e_x=0, $s_y=0, $e_y=0, $type=true)
    {
        $im = new Imagick($src);
        $info = $im->getImageGeometry();
        $w = $info["width"];
        $h = $info["height"];
        $format = $im->getImageFormat();

        if($s_x == 0 && $s_y == 0 && $e_x == 0 && $e_y == 0)
        {
            $e_x = $w;
            $e_y = $h;
        }

        $image = new Imagick();
        $image->newImage($w, $h, "transparent");

        $draw = new ImagickDraw();

        for($x=0; $x<$w; $x++)
        {
            for($y=0; $y<$h; $y++)
            {
                $p = $im->getImagePixelColor($x, $y);
                $rgb = $p->getColor();
                if( $x>=$s_x && $x<$e_x && $y>=$s_y && $y<$e_y )
                {
                    $rgb["r"] = $rgb["r"]+$rgb["r"]*$n;
                    $rgb["g"] = $rgb["g"]+$rgb["g"]*$n;
                    $rgb["b"] = $rgb["b"]+$rgb["b"]*$n;

                    $rgb["r"] = min(255, $rgb["r"]);
                    $rgb["r"] = max(0, $rgb["r"]);
                    $rgb["g"] = min(255, $rgb["g"]);
                    $rgb["g"] = max(0, $rgb["g"]);
                    $rgb["b"] = min(255, $rgb["b"]);
                    $rgb["b"] = max(0, $rgb["b"]);
                }
                $p->setColor("rgb({$rgb["r"]},{$rgb["g"]},{$rgb["b"]})");
                $draw->setFillColor($p);
                $draw->point($x, $y);
            }
        }

        $image->drawImage($draw);
        $image->setImageFormat($format);

        if($type)
            $image->writeImage($dst);
        else
            return $image;
    }

    /*
     函数说明：图片灰度处理
     参数说明：
      $src:原图地址,string型
      $dst:保存图片的地址,string型
    */
    function grayscale($src, $dst, $apply, $x=0, $y=0, $w=0, $h=0)
    {
        if($apply && $x==0 && $y==0 && $w==0 && $h==0)
            $apply = false;
        $image = new Imagick($src);
        if($apply)
        {
            $region = $image->getImageRegion($w, $h, $x, $y);
            $clone = $region->clone();
            $clone = $region->fximage('p{0,0}');
            $region->compositeImage($clone, imagick::COMPOSITE_DIFFERENCE,0,0);
            $region->modulateImage(100, 0 , 0);
            $image->compositeImage($region, $region->getImageCompose(), $x, $y);
        }
        else
        {
            $clone = $image->clone();
            $clone = $image->clone();
            $clone = $image->fximage('p{0,0}');
            $image->compositeImage($clone,imagick::COMPOSITE_DIFFERENCE,0,0);
            $image->modulateImage(100, 0 , 0);
        }
        $image->writeImage($dst);
        $image->clear();
        $image->destroy();
    }
}

