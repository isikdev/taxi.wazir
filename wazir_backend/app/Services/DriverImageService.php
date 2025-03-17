<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DriverImageService
{
    /**
     * Базовая директория для хранения файлов водителей
     */
    protected $baseDirectory = 'drivers';

    /**
     * Категории изображений для водителя
     */
    protected $categories = [
        'documents' => ['passport_front', 'passport_back', 'license_front', 'license_back', 'license_photo'],
        'car' => ['car_front', 'car_back', 'car_left', 'car_right'],
        'interior' => ['car_interior_front', 'car_interior_back']
    ];

    /**
     * Сохраняет изображение для водителя в соответствующей категории
     *
     * @param UploadedFile $file Загруженный файл
     * @param string $driverIdentifier Идентификатор водителя
     * @param string $imageField Название поля изображения
     * @return string Путь к сохраненному файлу относительно storage/app/public
     */
    public function saveImage(UploadedFile $file, string $driverIdentifier, string $imageField): string
    {
        // Определить категорию изображения
        $category = $this->determineCategory($imageField);
        
        // Сформировать структуру директорий
        $directory = $this->getDirectory($driverIdentifier, $category);
        
        // Сгенерировать уникальное имя файла
        $filename = $this->generateFilename($file, $imageField);
        
        // Сохранить файл в public storage
        $path = $file->storeAs($directory, $filename, 'public');
        
        return $path;
    }

    /**
     * Обрабатывает массив файлов для водителя
     *
     * @param array $files Массив файлов
     * @param string $driverIdentifier Идентификатор водителя
     * @return array Массив сохраненных путей к файлам
     */
    public function processImages(array $files, string $driverIdentifier): array
    {
        $paths = [];
        
        foreach ($files as $field => $file) {
            if ($file instanceof UploadedFile) {
                $paths[$field] = $this->saveImage($file, $driverIdentifier, $field);
            }
        }
        
        return $paths;
    }

    /**
     * Возвращает полный URL к изображению
     *
     * @param string $path Путь к изображению
     * @return string Полный URL
     */
    public function getImageUrl(string $path = null): string
    {
        if (empty($path)) {
            return '';
        }
        
        return Storage::disk('public')->url($path);
    }

    /**
     * Определяет категорию изображения
     *
     * @param string $imageField Название поля изображения
     * @return string Категория изображения
     */
    protected function determineCategory(string $imageField): string
    {
        foreach ($this->categories as $category => $fields) {
            if (in_array($imageField, $fields)) {
                return $category;
            }
        }
        
        // По умолчанию, если категория не определена
        return 'misc';
    }
    
    /**
     * Формирует путь к директории
     *
     * @param string $driverIdentifier Идентификатор водителя
     * @param string $category Категория изображения
     * @return string Путь к директории для сохранения
     */
    protected function getDirectory(string $driverIdentifier, string $category): string
    {
        return "{$this->baseDirectory}/{$driverIdentifier}/{$category}";
    }
    
    /**
     * Генерирует уникальное имя файла
     *
     * @param UploadedFile $file Загруженный файл
     * @param string $prefix Префикс для имени файла
     * @return string Имя файла
     */
    protected function generateFilename(UploadedFile $file, string $prefix): string
    {
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            $extension = 'jpg';
        }
        
        $randomString = Str::random(8);
        return "{$prefix}_{$randomString}." . strtolower($extension);
    }
    
    /**
     * Удаляет изображение
     *
     * @param string $path Путь к изображению
     * @return bool Успешность удаления
     */
    public function deleteImage(string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        
        return Storage::disk('public')->delete($path);
    }
    
    /**
     * Перемещает временные файлы в постоянное хранилище
     *
     * @param array $tempPaths Массив временных путей
     * @param string $driverIdentifier Идентификатор водителя
     * @return array Массив обновленных путей
     */
    public function moveTemporaryFilesToStorage(array $tempPaths, string $driverIdentifier): array
    {
        $updatedPaths = [];
        
        foreach ($tempPaths as $field => $tempPath) {
            if (empty($tempPath)) {
                continue;
            }
            
            // Получаем информацию о временном файле
            $tempDiskPath = 'public/' . $tempPath;
            if (!Storage::exists($tempDiskPath)) {
                continue;
            }
            
            // Определяем категорию изображения
            $category = $this->determineCategory($field);
            
            // Получаем имя файла из пути
            $filename = basename($tempPath);
            
            // Формируем новый путь
            $newDirectory = $this->getDirectory($driverIdentifier, $category);
            $newPath = "{$newDirectory}/{$filename}";
            
            // Создаем директорию если её нет
            Storage::disk('public')->makeDirectory($newDirectory);
            
            // Перемещаем файл
            if (Storage::disk('public')->copy($tempPath, $newPath)) {
                $updatedPaths[$field] = $newPath;
                // Удаляем временный файл
                Storage::disk('public')->delete($tempPath);
            } else {
                // Если копирование не удалось, используем старый путь
                $updatedPaths[$field] = $tempPath;
            }
        }
        
        return $updatedPaths;
    }
} 