<?php

use yii\db\Migration;
use app\models\UploadImage;

/**
 * Handles the creation of table `upload_images`.
 */
class m180104_115516_create_upload_images_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $data = '';
        $tableOptions = '';
        switch ($this->db->driverName) {
            case 'mysql':
                $data = 'MEDIUMBLOB NOT NULL';
                $tableOptions = 'ENGINE=InnoDB';
                break;
            default:
                $data = $this->binary()->notNull();
        }

        $this->createTable(UploadImage::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'description' => $this->text(),
            'filename' => $this->text(),
            'mimetype' => $this->text(),
            'url' => $this->text(),
            'data' => $data,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(UploadImage::tableName());
    }
}
