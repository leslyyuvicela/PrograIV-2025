<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Ramsey\Uuid\Uuid;

class FirebaseStorageService
{
    protected $storage;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'))
        ->withDefaultStorageBucket('donamiga-7a745.firebasestorage.app');
        $this->storage = $factory->createStorage();
    }

    public function uploadImage($file)
    {   
        $uuid = Uuid::uuid4()->toString();
        $bucket = $this->storage->getBucket();
        $fileName =  'Fundraisings/Images/'.uniqid() . '.' . $file->getClientOriginalExtension();
        $bucket->upload(
            fopen($file->getRealPath(), 'r'),
            [
                'name' => $fileName,
                'metadata' => [
                    'firebaseStorageDownloadTokens' => $uuid,
                ],
                'predefinedAcl' => 'publicRead',

            ]
        );
        // Return the public URL of the uploaded file
        return "https://storage.googleapis.com/{$bucket->name()}/o/" . urlencode($fileName) . '?alt=media&token=' . $uuid;
    }

    public function uploadVideo($file)
    {
        $bucket = $this->storage->getBucket();
        $filePath =  'Fundraisings/Videos/'.uniqid() . '.' . $file->getClientOriginalExtension();
        $bucket->upload(
            fopen($file->getRealPath(), 'r'),
            [
                'name' => $filePath,
                'predefinedAcl' => 'publicRead',
            ]
        );
        // Return the public URL of the uploaded file
        return "https://storage.googleapis.com/{$bucket->name()}/{$filePath}";
    }

    public function uploadDocument($file)
    {
        $bucket = $this->storage->getBucket();
        $filePath = 'Fundraisings/Documents/'.uniqid() . '.' . $file->getClientOriginalExtension();
        $bucket->upload(
            fopen($file->getRealPath(), 'r'),
            [
                'name' => $filePath,
                'predefinedAcl' => 'publicRead',
            ]
        );
        // Return the public URL of the uploaded file
        return "https://storage.googleapis.com/{$bucket->name()}/{$filePath}";
    }
}