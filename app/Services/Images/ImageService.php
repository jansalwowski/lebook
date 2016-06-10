<?php


namespace App\Services\Images;


use App\Contracts\ImageableContract;
use App\Models\Photo;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService {

    /**
     * @var UploadedFile
     */
    private $file;
    /**
     * @var
     */
    private $photoable;
    private $filename;

    public function __construct(ImageableContract $photoable, UploadedFile $file)
    {
        $this->file = $file;
        $this->photoable = $photoable;
    }

    public function save($params = [])
    {
        $photo = $this->makePhoto();

        $this->photoable->addPhoto($photo);

        $this->file->move($photo->baseDir(), $this->filename);

        if( !array_key_exists('watermark', $params) || $params['watermark'] == true ){
            $this->addWatermark($photo);
        }

        $this->makeThumbnail($photo);
    }

    public function makePhoto($params = [])
    {
        $params = array_merge($params, [
            'path'          => $this->filename(),
//            'original_name' => $this->file->getClientOriginalName(),
//            'extension'     => $this->file->getClientOriginalExtension(),
//            'mime'          => $this->file->getClientMimeType(),
        ]);

        return new Photo($params);
    }

    public function filename()
    {
        $filename = sha1(time() . str_random() . $this->file->getClientOriginalName());
        $extension = $this->file->getClientOriginalExtension();
        $this->filename = "{$filename}.{$extension}";

        return $this->filename;
    }

    /**
     * @param UploadedFile $file
     */
    protected function refactorImage(Photo $photo)
    {
        $modified = false;
        $encoded = false;

        $image = Image::make($photo->path);

        $quality = 90;

        if($image->height() > 900) {
            $image->resize(null, 900, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $modified = true;
        }

        if($image->width() > 1400) {
            $image->resize(1400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $modified = true;
        }

        if(strtolower($image->mime()) != 'jpg') {
            $image->encode('jpg', $quality);
            $modified = true;
            $encoded = true;
        }

        if($image->filesize() / 1024 > 250) {
            $param = ($image->filesize() / 1024 / 250 / 2) * log($image->filesize());
            $quality = 90 - 10 * $param;
            $quality = $quality >= 60 && $quality <= 100 ? $quality : 60;
            $modified = true;
        }

        if($modified) {
            if($encoded) {
                if(\File::isFile($photo->path)) {
                    \File::delete($photo->path);
                }
                $newName = explode('.', $photo->name)[0] . '.jpg';
                $photo->name = $newName;
                $photo->save();
            }
            $image->save($photo->path, $quality);
        }
    }

    /**
     * @param $photo
     */
    protected function makeThumbnail($photo, $params = [])
    {
        Image::make($photo->path)
            ->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($photo->thumbnail_path);
    }

    public function addWatermark($photo)
    {
        $img = Image::make($photo->path);
        $img->insert(public_path(). '/img/watermark.png', 'bottom-right', 10, 10);
        $img->save($photo->path);
    }

}