<?php
/**
 * @author mult1mate
 * Date: 06.02.16
 * Time: 16:40
 */

namespace app\assets;

use yii\web\AssetBundle;

class TasksAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'manager_actions.js',
    ];
}