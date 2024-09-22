<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "links".
 * 
 * @property int $id
 * @property string $original_url
 * @property string $token
 * @property string $short_url
 * @property int $clicks
 * @property int $created_at
 * @property int $updated_at
 */
class Link extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'links';
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('UNIX_TIMESTAMP()'),
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['original_url'], 'required'],
            [['original_url'], 'safe'],
            [['token', 'short_url'], 'string', 'max' => 255],
            [['clicks', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'original_url' => 'Оригинальная ссылка',
            'token' => 'Токен',
            'short_url' => 'Короткая ссылка',
            'clicks' => 'Количество переходов',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено'
        ];
    }
}