<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%mts_size}}".
 *
 * @property integer $id
 * @property string $name
 */
class ScreenSize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mts_size}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('tracking', 'ID'),
            'name' => Yii::t('tracking', 'Name'),
        ];
    }

    public static function dropDownItems()
    {
        $cacheKey = 'screensizeList';
        $items = Yii::$app->cache->get([$cacheKey]);
        if ($items === false) {
            $query = static::find();

            $list = $query->asArray()->all();
            $items = [];
            foreach ($list as $item){
                $items[$item['id']] = $item['name'];
            }

            Yii::$app->cache->set([$cacheKey], $items, 0);
        }
        return $items;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->cache->delete(['screensizeList']);
    }
    /**
     * @inheritdoc
     * @return ScreenSizeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ScreenSizeQuery(get_called_class());
    }
}
