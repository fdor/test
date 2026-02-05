<?php

namespace app\jobs;

use app\models\Book;
use app\models\Subscription;
use app\services\SmsSender;
use Exception;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SmsJob extends BaseObject implements JobInterface
{
    public int $bookId;

    /**
     * Отправка смс
     *
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws Exception
     */
    public function execute($queue)
    {
        $smsSender = Yii::createObject(SmsSender::class);
        $phones = Subscription::findPhonesToSendSms($this->bookId);

        if (!$book = Book::findOne($this->bookId)) {
            Yii::error('Book not found, id = ' . $this->bookId);
            return;
        }

        $authors = [];
        foreach ($book->authors as $author) {
            $authors[] = $author->getFullName();
        }

        foreach ($phones as $phone) {
            try {
                $message = 'У автора ' . implode(', ', $authors) . ' вышла новая книга ' . $book->title;
                $smsSender->send($phone, $message);
                Yii::info('Отправлена смс на номер ' . $phone . ', текст: ' . $message);
            } catch (Exception $exception) {
                Yii::error($exception->getMessage());
            }
        }
    }
}