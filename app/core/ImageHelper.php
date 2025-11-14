<?php
// /app/core/ImageHelper.php

class ImageHelper {
    /**
     * Redimensionar imagen manteniendo proporción
     */
    public static function resizeImage($sourcePath, $destinationPath, $maxWidth = 800, $maxHeight = 600, $quality = 85) {
        // Verificar si GD está disponible
        if (!extension_loaded('gd')) {
            // Si GD no está disponible, simplemente copiar el archivo sin redimensionar
            if (!copy($sourcePath, $destinationPath)) {
                throw new Exception('Error al copiar la imagen (GD no disponible)');
            }
            return true;
        }

        // Obtener información de la imagen
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            throw new Exception('Imagen inválida');
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Si la imagen ya es más pequeña que el máximo, solo copiarla
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            if (!copy($sourcePath, $destinationPath)) {
                throw new Exception('Error al copiar la imagen');
            }
            return true;
        }

        // Calcular nuevas dimensiones manteniendo proporción
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        $newWidth = round($originalWidth * $ratio);
        $newHeight = round($originalHeight * $ratio);

        // Crear imagen desde el archivo fuente
        $sourceImage = null;
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new Exception('Tipo de imagen no soportado');
        }

        if (!$sourceImage) {
            throw new Exception('Error al cargar la imagen');
        }

        // Crear nueva imagen con las dimensiones calculadas
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        // Mantener transparencia para PNG
        if ($mimeType === 'image/png') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }

        // Redimensionar
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        // Guardar imagen redimensionada
        $success = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $success = imagejpeg($resizedImage, $destinationPath, $quality);
                break;
            case 'image/png':
                $success = imagepng($resizedImage, $destinationPath, 9); // Máxima compresión
                break;
            case 'image/gif':
                $success = imagegif($resizedImage, $destinationPath);
                break;
        }

        // Liberar memoria
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        if (!$success) {
            throw new Exception('Error al guardar la imagen redimensionada');
        }

        return true;
    }

    /**
     * Generar nombre único para archivo
     */
    public static function generateUniqueFilename($originalFilename, $prefix = 'comprobante_') {
        $extension = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));
        $timestamp = time();
        $random = bin2hex(random_bytes(8));
        return $prefix . $timestamp . '_' . $random . '.' . $extension;
    }

    /**
     * Validar tipo de archivo de imagen
     */
    public static function isValidImageType($filename) {
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, $allowedTypes);
    }

    /**
     * Validar tamaño de archivo
     */
    public static function isValidFileSize($fileSize, $maxSizeMB = 5) {
        $maxSizeBytes = $maxSizeMB * 1024 * 1024; // Convertir MB a bytes
        return $fileSize <= $maxSizeBytes;
    }
}
