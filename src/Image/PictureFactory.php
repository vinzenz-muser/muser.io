<?php

declare(strict_types=1);

namespace App\Image;

use Contao\CoreBundle\Image\PictureFactory as ContaoPictureFactory;
use Contao\FilesModel;
use Contao\StringUtil;

class PictureFactory
{
    protected $pictureFactory;
    protected $projectDir;

    public function __construct(ContaoPictureFactory $pictureFactory, string $projectDir)
    {
        $this->pictureFactory = $pictureFactory;
        $this->projectDir = $projectDir;
    }

    public function getByUuid($uuid, $size = null): ?array
    {
        $model = FilesModel::findByUuid($uuid);

        if (!$model) {
            return null;
        }

        $size = StringUtil::deserialize($size);
        $path = sprintf('%s/%s', $this->projectDir, $model->path);

        return $this->pictureFactory->create($path, $size)->getImg($this->projectDir);
    }

    public function getByUuids($uuids, $size = null): array
    {
        $images = [];
        $uuids = (array) StringUtil::deserialize($uuids);

        foreach ($uuids as $uuid) {
            $images[] = $this->getByUuid($uuid, $size);
        }

        return $images;
    }
}
