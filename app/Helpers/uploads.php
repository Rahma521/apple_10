<?php

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


    function getMediaCollectionName($modelData): string
        {
            return Str::plural(Str::lcfirst(class_basename($modelData)));
        }
// FOR DASHBOARD CONTROLLERS
    function handleMediaUploads($files, $modelData, bool $clearExisting = false): void
    {
        $collectionName = getMediaCollectionName($modelData);
        if ($clearExisting) {
            $modelData->clearMediaCollection($collectionName);
        }
        if (!is_array($files)) {
            $modelData->addMedia($files)->toMediaCollection($collectionName);
        }
        foreach ($files as $file) {
            $modelData->addMedia($file)->toMediaCollection($collectionName);
        }
    }

    function handleMultiMediaUploads(array $files, $modelData, bool $clearExisting = false): void
    {
        // Use the file key (e.g., main_banner, logo) as the collection name
        foreach ($files as $key => $file) {
            $collectionName = $key;

            if ($clearExisting) {
                $modelData->clearMediaCollection($collectionName);
            }

            $modelData->addMedia($file)->toMediaCollection($collectionName);
        }
    }

    function handleMultiMedia(array $files, $modelData, bool $clearExisting = false, string $collectionName = null): void
    {
        foreach ($files as $key => $file) {
            // If a collection name is provided, use it; otherwise, use the key as the collection name
            $currentCollectionName = $collectionName ?? $key;

            if ($clearExisting) {
                $modelData->clearMediaCollection($currentCollectionName);
            }

            // Handle single or multiple files
            if (is_array($file)) {
                foreach ($file as $singleFile) {
                    if ($singleFile instanceof Symfony\Component\HttpFoundation\File\UploadedFile) {
                        $modelData->addMedia($singleFile)->toMediaCollection($currentCollectionName);
                    } else {
                        throw new \InvalidArgumentException('Each file must be an instance of UploadedFile.');
                    }
                }
            } else {
                if ($file instanceof Symfony\Component\HttpFoundation\File\UploadedFile) {
                    $modelData->addMedia($file)->toMediaCollection($currentCollectionName);
                } else {
                    throw new \InvalidArgumentException('File must be an instance of UploadedFile.');
                }
            }
        }
    }


function clearMedia($modelData): void
    {
        $collectionName = getMediaCollectionName($modelData);
        $modelData->clearMediaCollection($collectionName);
    }

    function clearMediaByModelType(string $modelType,int $modelId): void
    {
       $data =Media::where('model_type', $modelType)->where('model_id', $modelId)->delete();
    }

