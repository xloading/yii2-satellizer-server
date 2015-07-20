<?php

namespace wfcreations\satellizer\actions;

use Yii;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use yii\base\Action;
use wfcreations\satellizer\Satellizer;

class FacebookAction extends Action {

    protected function createToken($user) {
        $payload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return JWT::encode($payload, Config::get('app.token_secret'));
    }

    public function run() {
        if (Yii::$app->getRequest()->isPost) {
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
                $payload = (array) JWT::decode($token, Satellizer::getComponent()->jwtKey, ['HS256']);

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
