<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ImageService
{
    public function saveBase64Image($base64Image, $path, $filename)
    {
        $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $base64Image = str_replace(' ', '+', $base64Image);
        $imageData = base64_decode($base64Image);

        $fileName = time() . '_' . $filename;

        $filePath = public_path($path . '/' . $fileName);

        try {
            file_put_contents($filePath, $imageData);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar imagem: ' . $e->getMessage());
        }

        return $path . '/' . $fileName;
    }

    public function deleteImages($filePath, $fileName)
    {
        $path = public_path("$filePath/{$fileName}");

        try {
            File::delete($path);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir imagem: ' . $e->getMessage());
        }
    }

    public function updateImage($base64Image, $path, $filename, $existingImagePath = null)
    {
        if ($existingImagePath) {
            $this->deleteImages($path, basename($existingImagePath));
        }

        return $this->saveBase64Image($base64Image, $path, $filename);
    }
}
