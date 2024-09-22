<?php 

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "link_log".
 * @property int $link_id
 * @property string $ip_address
 * @property int $created_at
 */
class LinkLog extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'link_log';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
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
            [['link_id', 'ip_address'], 'required'],
            [['ip_address'], 'string', 'max' => 45],
            [['link_id'], 'exist', 'skipOnError' => true, 'targetClass' => Link::class, 'targetAttribute' => ['link_id' => 'id']]
        ];
    }
    
    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link_id' => 'Ссылка',
            'ip_address' => 'IP адрес',
            'created_at' => 'Создано',
        ];
    }
}