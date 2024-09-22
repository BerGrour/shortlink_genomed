Инструкция как развернуть проект:

1. В нужной директории: `git clone https://github.com/BerGrour/shortlink_genomed.git`
2. Выполнить `composer install`
3. Создать БД "link_genomed"
4. Выполнить миграции `./yii migrate`
5. Поставить `extension=gd` для работы с изображениями (для qr-кода)
6. Создать директорию /web/qrcode/
