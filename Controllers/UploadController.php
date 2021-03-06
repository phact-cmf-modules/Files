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

class UploadController extends BackendController
{
    use UploadTrait;

    public function delete()
    {
        $pk = isset($_POST['deletePk']) ? $_POST['deletePk'] : null;
        $class = isset($_POST['class']) ? $_POST['class'] : null;
        $name = isset($_POST['name']) ? $_POST['name'] : null;

        if ($pk && $class && $name) {
            $model = new $class;
            $manager = $model->{$name};
            $relatedClass = $manager->getModel()->className();
            if ($related = $relatedClass::objects()->filter(['pk' => $pk])->get()){
                $related->delete();
            }
        }
    }

    public function sort()
    {
        $pkList = isset($_POST['pkList']) ? $_POST['pkList'] : null;
        $name = isset($_POST['name']) ? $_POST['name'] : null;
        $class = isset($_POST['class']) ? $_POST['class'] : null;
        $field = isset($_POST['sortField']) ? $_POST['sortField'] : null;

        if ($pkList && $field && $class && $name) {
            $model = new $class;
            $manager = $model->{$name};
            $relatedClass = $manager->getModel()->className();
            if ($relatedClass instanceof FileSortableInterface) {
                $relatedClass::beforeSort($pkList);
            }
            foreach($pkList as $position => $pk) {
                $relatedClass::objects()->filter(['pk' => $pk])->update([
                    $field => $position
                ]);
            }
            if ($relatedClass instanceof FileSortableInterface) {
                $relatedClass::afterSort($pkList);
            }
        }
    }

    public function saveModel($path)
    {
        $pk = isset($_POST['pk']) ? $_POST['pk'] : null;
        $class = isset($_POST['class']) ? $_POST['class'] : null;
        $name = isset($_POST['name']) ? $_POST['name'] : null;
        $fileField = isset($_POST['fileField']) ? $_POST['fileField'] : null;

        $model = null;

        if ($pk) {
            $model = $class::objects()->filter(['pk' => $pk])->get();
        }

        if ($model && $name) {
            $manager = $model->{$name};
            $relatedClass = $manager->getModel()->className();
            $related = new $relatedClass();
            $related->{$fileField} = new LocalFile($path);
            $related->{$manager->toField} = $pk;
            $related->save();
        }
        
        return true;
    }
}
