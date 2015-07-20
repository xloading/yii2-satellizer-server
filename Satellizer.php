<?php

namespace wfcreations\satellizer;

use Yii;
use yii\base\Component;

class Satellizer extends Component {

    public static $componentName = 'satellizer';
    public $identityClass = '\wfcreations\satellizer\models\User';
    public $tokenLifetime = 2 * 7 * 24 * 60 * 60;
    public $jwtKey;
    public $facebook = [
    ];
    public $google = [
    ];
    public $linkedin = [
    ];
    public $twitter = [
    ];
    public $foursquare = [
    ];
    public $github = [
    ];

    /**
     * @return Satellizer get Satelizer component
     */
    public static function getComponent() {
        return Yii::$app->{static::$componentName};
    }

    public function init() {
        $this->initFacebookDefaults();
        $this->initGoogleDefaults();
        $this->initLinkedinDefaults();
        $this->initTwitterDefaults();
        $this->initFoursquareDefaults();
        $this->initGithubDefaults();
    }

    public function initFacebookDefaults() {
        if (!isset($this->facebook['accessTokenUrl'])) {
            $this->facebook['accessTokenUrl'] = 'https://graph.facebook.com/v2.3/oauth/access_token';
        }
        if (!isset($this->facebook['graphApiUrl'])) {
            $this->facebook['graphApiUrl'] = 'https://graph.facebook.com/v2.3/me';
        }
    }

    public function initGoogleDefaults() {
        if (!isset($this->google['accessTokenUrl'])) {
            $this->google['accessTokenUrl'] = 'https://accounts.google.com/o/oauth2/token';
        }
        if (!isset($this->google['peopleApiUrl'])) {
            $this->google['peopleApiUrl'] = 'https://www.googleapis.com/plus/v1/people/me/openIdConnect';
        }
    }

    public function initLinkedinDefaults() {
        if (!isset($this->linkedin['accessTokenUrl'])) {
            $this->linkedin['accessTokenUrl'] = 'https://www.linkedin.com/uas/oauth2/accessToken';
        }
        if (!isset($this->linkedin['peopleApiUrl'])) {
            $this->linkedin['peopleApiUrl'] = 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address)';
        }
    }

    public function initTwitterDefaults() {
        if (!isset($this->twitter['requestTokenUrl'])) {
            $this->twitter['requestTokenUrl'] = 'https://api.twitter.com/oauth/request_token';
        }
        if (!isset($this->twitter['accessTokenUrl'])) {
            $this->twitter['accessTokenUrl'] = 'https://api.twitter.com/oauth/access_token';
        }
        if (!isset($this->twitter['profileUrl'])) {
            $this->twitter['profileUrl'] = 'https://api.twitter.com/1.1/users/show.json?screen_name=';
        }
    }

    public function initFoursquareDefaults() {
        if (!isset($this->foursquare['accessTokenUrl'])) {
            $this->foursquare['accessTokenUrl'] = 'https://foursquare.com/oauth2/access_token';
        }
        if (!isset($this->foursquare['userProfileUrl'])) {
            $this->foursquare['userProfileUrl'] = 'https://api.foursquare.com/v2/users/self';
        }
    }

    public function initGithubDefaults() {
        if (!isset($this->github['accessTokenUrl'])) {
            $this->github['accessTokenUrl'] = 'https://github.com/login/oauth/access_token';
        }
        if (!isset($this->github['userApiUrl'])) {
            $this->github['userApiUrl'] = 'https://api.github.com/user';
        }
    }

}
