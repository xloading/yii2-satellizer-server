<?php

namespace wfcreations\satellizer\actions;

use Yii;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use yii\base\Action;
use wfcreations\satellizer\Satellizer;
use wfcreations\satellizer\models\User;

class FacebookAction extends Action {

    public function run() {

        if (Yii::$app->getRequest()->isPost) {
            $user_class = Satellizer::getComponent()->identityClass;

            $accessTokenUrl = 'https://graph.facebook.com/v2.3/oauth/access_token';
            $graphApiUrl = 'https://graph.facebook.com/v2.3/me';

            $params = [
                'code' => Yii::$app->getRequest()->getBodyParam('code'),
                'client_id' => Yii::$app->getRequest()->getBodyParam('clientId'),
                'redirect_uri' => Yii::$app->getRequest()->getBodyParam('redirectUri'),
                'client_secret' => 'ed725d39f9638484672d21b58d786dfa'
            ];

            $cliente = new Client();
            $accessToken = $cliente->get($accessTokenUrl, ['query' => $params])->json();

            $profile = $cliente->get($graphApiUrl, ['query' => $accessToken])->json();

            if (Yii::$app->getRequest()->getHeaders()->get('Authorization')) {
                $user = $user_class::findOne(['facebook' => $profile['id']]);

                if ($user) {
                    throw new \yii\web\ConflictHttpException('There is already a Facebook account that belongs to you', 409);
                }

                $token = explode(' ', Yii::$app->getRequest()->getHeaders()->getHeaders()->get('Authorization'))[1];
                $payload = (array) JWT::decode($token, '123456789', ['HS256']);

                $user = $user_class::find($payload['sub']);
                $user->facebook = $profile['id'];

                //
                $user->save();

                return ['token' => $this->createToken($user)];
            } else {
                $user = $user_class::findOne(['facebook' => $profile['id']]);

                if ($user) {
                    return ['token' => $this->createToken($user)];
                }

                $user = Yii::createObject($user_class);
                $user->email = $profile['email'];
                $user->facebook = $profile['id'];
                $user->save();

                return ['token' => $this->createToken($user)];
            }
        }
    }

}
