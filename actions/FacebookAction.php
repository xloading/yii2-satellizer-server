<?php

namespace wfcreations\satellizer\actions;

use yii\base\Action;

class FacebookAction extends Action {

    public function run() {
        if (Yii::$app->getRequest()->isPost) {
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
                $advertiser = \common\models\ar\Advertiser::findOne(['facebook' => $profile['id']]);

                if ($advertiser) {
                    throw new \yii\web\ConflictHttpException('There is already a Facebook account that belongs to you', 409);
                }

                $token = explode(' ', Yii::$app->getRequest()->getHeaders()->getHeaders()->get('Authorization'))[1];
                $payload = (array) JWT::decode($token, '123456789', ['HS256']);

                $advertiser = \common\models\ar\Advertiser::find($payload['sub']);
                $advertiser->facebook = $profile['id'];
                //
                $advertiser->save();

                return ['token' => $this->createToken($advertiser)];
            } else {
                $advertiser = \common\models\ar\Advertiser::findOne(['facebook' => $profile['id']]);

                if ($advertiser) {
                    return ['token' => $this->createToken($advertiser)];
                }

                $advertiser = new \common\models\ar\Advertiser();
                $advertiser->email = $profile['email'];
                $advertiser->facebook = $profile['id'];
                $advertiser->save();

                return ['token' => $this->createToken($advertiser)];
            }
        }
    }

}
