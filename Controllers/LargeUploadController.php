<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 10/11/16 09:13
 */

namespace Modules\Files\Controllers;

use Modules\Admin\Controllers\BackendController;
use Modules\Files\Interfaces\FileSortableInterface;
use Modules\Files\Traits\UploadTrait;
use Phact\Storage\Files\LocalFile;

class LargeUploadController extends BackendController
{
    use UploadTrait;

    public function saveModel($path)
    {
        $pk = isset($_POST['pk']) ? $_POST['pk'] : null;
        $class = isset($_POST['class']) ? $_POST['class'] : null;
        $name = isset($_POST['name']) ? $_POST['name'] : null;

        $model = null;

        if ($pk) {
            $model = $class::objects()->filter(['pk' => $pk])->get();
        }

        if ($model && $name) {
            $model->{$name} = new LocalFile($path);
            $model->save();
        }
        
        return true;
    }

    public function delete()
    {
        $pk = isset($_POST['pk']) ? $_POST['pk'] : null;
        $class = isset($_POST['class']) ? $_POST['class'] : null;
        $name = isset($_POST['name']) ? $_POST['name'] : null;

        if ($class && $name) {
            $model = $class::objects()->filter(['pk' => $pk])->get();
            if (!$model) {
                return;
            }
            $model->{$name} = null;
            $model->save();
        }
    }
}