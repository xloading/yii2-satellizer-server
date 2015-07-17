<?php

namespace wfcreations\satellizer;

use Yii;
use yii\base\Component;
use wfcreations\satellizer\models\User;

class Satellizer extends Component {

    public static $componentName = 'satellizer';
    public $identityClass;

    public function init() {
        parent::init();

        if ($this->identityClass === null) {
            $this->identityClass = User::className();
        }
    }

    /**
     * @return Satellizer get Satelizer component
     */
    public static function getComponent() {
        return Yii::$app->{static::$componentName};
    }

}
