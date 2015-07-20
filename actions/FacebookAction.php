<?php

namespace wfcreations\satellizer\actions;

use Yii;
use yii\base\Action;
use GuzzleHttp\Client;
use wfcreations\satellizer\Satellizer;

class FacebookAction extends Action {

    public function run() {
        if (Yii::$app->getRequest()->isPost) {
            Yii::$app->getRequest()->parsers = [
                'application/json' => 'yii\web\JsonParser',
            ];

            $user_class = Satellizer::getComponent()->identityClass;

            $params = [
                'code' => Yii::$app->getRequest()->getBodyParam('code'),
                'client_id' => Yii::$app->getRequest()->getBodyParam('clientId'),
                'redirect_uri' => Yii::$app->getRequest()->getBodyParam('redirectUri'),
                'client_secret' => Satellizer::getComponent()->facebook['clientSecret'],
            ];

            $cliente = new Client();
            $accessToken = $cliente->get(Satellizer::getComponent()->facebook['accessTokenUrl'], ['query' => $params])->json();

            $profile = $cliente->get(Satellizer::getComponent()->facebook['graphApiUrl'], ['query' => $accessToken])->json();

            if (Yii::$app->getRequest()->getHeaders()->get('Authorization')) {
                $user = $user_class::findOne(['facebook' => $profile['id']]);

                if ($user) {
                    throw new \yii\web\ConflictHttpException('There is already a Facebook account that belongs to you', 409);
                }

                $token = explode(' ', Yii::$app->getRequest()->getHeaders()->getHeaders()->get('Authorization'))[1];
                $payload = (array) Satellizer::getComponent()->decodeToken($token);

                $user = $user_class::find($payload['sub']);
                $user->facebookLink($profile);
                $user->save();
            } else {
                $user = $user_class::findOne(['facebook' => $profile['id']]);

                if ($user) {
                    return ['token' => Satellizer::getComponent()->createToken($user)];
                }

                $user = Yii::createObject($user_class);
                $user->facebookLink($profile);
                $user->save();
            }

            return ['token' => Satellizer::getComponent()->createToken($user)];
        }
    }

}
