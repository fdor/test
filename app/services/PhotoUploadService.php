<?php

namespace app\services;

use Yii;
use yii\web\UploadedFile;
use yii\base\Exception;

class PhotoUploadService
{
    private string $uploadPath;
    private int $maxFileSize;
    private array $allowedExtensions;

    public function __construct()
    {
        $this->uploadPath = Yii::getAlias('@webroot/uploads');
        $this->maxFileSize = Yii::$app->params['maxPhotoSize'] ?? 2 * 1024 * 1024; // 2MB по умолчанию
        $this->allowedExtensions = Yii::$app->params['allowedPhotoExtensions'] ?? ['jpg', 'jpeg', 'png'];
    }

    /**
     * Загрузка фото книги
     *
     * @param int $bookId
     * @param UploadedFile|null $photo
     * @return string|null Имя файла или null если файл не загружен
     * @throws Exception
     */
    public function uploadBookPhoto(int $bookId, ?UploadedFile $photo): ?string
    {
        if (!$photo) {
            return null;
        }

        $this->validatePhoto($photo);
        $this->ensureUploadDirectory();

        $fileName = $this->generateFileName($bookId, $photo);
        $filePath = $this->uploadPath . '/' . $fileName;

        if (!$photo->saveAs($filePath)) {
            throw new Exception('Не удалось сохранить файл: ' . $fileName);
        }

        return $fileName;
    }

    /**
     * Удаление фото книги
     *
     * @param string|null $fileName
     * @return bool
     */
    public function deleteBookPhoto(?string $fileName): bool
    {
        if (!$fileName) {
            return true;
        }

        $filePath = $this->uploadPath . '/' . $fileName;

        if (!file_exists($filePath)) {
            return true;
        }

        return unlink($filePath);
    }

    /**
     * Валидация фото
     *
     * @param UploadedFile $photo
     * @throws Exception
     */
    private function validatePhoto(UploadedFile $photo): void
    {
        if ($photo->size > $this->maxFileSize) {
            throw new Exception('Размер файла превышает максимально допустимый: ' . $this->formatFileSize($this->maxFileSize));
        }

        if (!in_array(strtolower($photo->extension), $this->allowedExtensions, true)) {
            throw new Exception('Недопустимое расширение файла. Разрешены: ' . implode(', ', $this->allowedExtensions));
        }

        $allowedMimeTypes = $this->getAllowedMimeTypes();
        if (!in_array($photo->type, $allowedMimeTypes, true)) {
            throw new Exception('Недопустимый тип файла. Разрешены только изображения.');
        }
    }

    /**
     * Получение допустимых MIME типов на основе расширений
     *
     * @return array
     */
    private function getAllowedMimeTypes(): array
    {
        $mimeTypesMap = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ];

        $allowedMimeTypes = [];
        foreach ($this->allowedExtensions as $extension) {
            $extension = strtolower($extension);
            if (isset($mimeTypesMap[$extension])) {
                $allowedMimeTypes[] = $mimeTypesMap[$extension];
            }
        }

        return array_unique($allowedMimeTypes);
    }

    /**
     * Проверка и создание директории для загрузки
     *
     * @throws Exception
     */
    private function ensureUploadDirectory(): void
    {
        if (!is_dir($this->uploadPath)) {
            if (!mkdir($this->uploadPath, 0755, true) && !is_dir($this->uploadPath)) {
                throw new Exception('Не удалось создать директорию для загрузки: ' . $this->uploadPath);
            }
        }

        if (!is_writable($this->uploadPath)) {
            throw new Exception('Директория для загрузки недоступна для записи: ' . $this->uploadPath);
        }
    }

    /**
     * Генерация имени файла
     *
     * @param int $bookId
     * @param UploadedFile $photo
     * @return string
     */
    private function generateFileName(int $bookId, UploadedFile $photo): string
    {
        return 'book_' . $bookId . '_' . time() . '.' . strtolower($photo->extension);
    }

    /**
     * Форматирование размера файла
     *
     * @param int $bytes
     * @return string
     */
    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
