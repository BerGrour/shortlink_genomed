<?php

namespace app\controllers;

use app\models\LinkLog;
use Endroid\QrCode\Writer\PngWriter;
use app\models\Link;
use Yii;
use yii\web\Controller;
use Endroid\QrCode\QrCode;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class LinkController extends Controller
{
    /**
     * Действие по проверке url и созданию его экземпляра
     * @return array format_json
     */
    public function actionValidateUrl()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $url = Yii::$app->request->post('url');

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return ['error' => 'Неверный формат URL.'];
        }

        $headers = @get_headers($url);
        if ($headers && strpos($headers[0], '200') == false) {
            return ['error' => 'Данный URL не доступен.'];
        }
 
        $link = new Link();
        $link->original_url = $url;
        // Генерация короткой ссылки:
        do {
            $link->token = Yii::$app->security->generateRandomString(6);
        } while (Link::find()->where(['token' => $link->token])->exists());

        $link->short_url = Yii::$app->urlManager->createAbsoluteUrl(['link/view', 'token' => $link->token]);

        if ($link->save()) {
            $qrCodeUrl = $this->generateQRCode($link->short_url, $link->token);

            return [
                'success' => true,
                'shortUrl' => $link->short_url,
                'qrCode' => $qrCodeUrl,
            ];
        }
        return ['error' => 'Ошибка при сохранении URL'];
    }

    /**
     * Метод генерирующий QR-код для указанной ссылки
     * @param string $short_url
     * @throws \Exception
     * @return bool|string
     */
    public function generateQRCode($short_url, $token)
    {
        $qrCode = new QrCode($short_url);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        $filePath = Yii::getAlias('@webroot/qrcodes/') . $token . '.png';

        $writer = new PngWriter();

        try {
            $result = $writer->write($qrCode);
            $result->saveToFile($filePath);
        } catch (\Exception $e) {
            Yii::error('Ошибка генерации QR-кода: ' . $e->getMessage());
            throw new \Exception('Ошибука при создании QR-кода.');
        }

        return Yii::getAlias('@web/qrcodes/' . $token . '.png');
    }

    public function actionView($token)
    {
        $link = $this->findModel($token);
        $link->clicks++;
        $link->save(false);

        // Запись IP в логи
        $ip = Yii::$app->request->userIP;
        $link_log = new LinkLog();
        $link_log->ip_address = $ip;
        $link_log->link_id = $link->id;
        $link_log->save();

        return $this->redirect($link->original_url);
    }

    protected function findModel($token)
    {
        if (($model = Link::findOne(['token' => $token])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}