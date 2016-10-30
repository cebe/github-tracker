<?php
namespace app\components;

use app\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $_client;

    public function __construct(ClientInterface $client)
    {
        $this->_client = $client;
    }

    public function handle()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->getSession()->setFlash('error', [
                Yii::t('app', 'You are already logged in, log out first to login with a different account.')
            ]);
            return;
        }

        $attributes = $this->_client->getUserAttributes();
        $id = ArrayHelper::getValue($attributes, 'id');
        $name = ArrayHelper::getValue($attributes, 'login');
        $email = ArrayHelper::getValue($attributes, 'email');

        if ($this->_client->getId() !== 'github') {
            throw new InvalidCallException('Only Github Auth is allowed.');
        }

        /* @var User $user */
        $user = User::find()->where([
            'github_id' => $id,
        ])->one();

        if ($user) { // login
            $this->updateUserInfo($user);
            Yii::$app->user->login($user);
        } else { // signup
            $user = new User([
                'name' => $name,
                'email' => $email,
                'github_id' => $id,
            ]);

            if ($user->save()) {
                Yii::$app->user->login($user);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app', 'Unable to save user: {errors}', [
                        'client' => $this->_client->getTitle(),
                        'errors' => json_encode($user->getErrors()),
                    ]),
                ]);
            }
        }
    }

    /**
     * @param User $user
     */
    private function updateUserInfo(User $user)
    {
        $attributes = $this->_client->getUserAttributes();
        $user->name = ArrayHelper::getValue($attributes, 'login');
        $user->email = ArrayHelper::getValue($attributes, 'email');
        $user->save();
    }
}
