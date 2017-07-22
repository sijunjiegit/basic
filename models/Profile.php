<?php

namespace app\models;

use Yii\db\ActiveRecord;

class Profile extends ActiveRecord
{
    public static function tableName()
    {
	return "{{%profile}}";	 	
    }
}
