<?php

namespace App\Twig\Components;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class File
{
    use DefaultActionTrait;
    
    #[LiveAction]
    public function upload(Request $request, LoggerInterface $logger): void
    {
        $file     = $request->files->get('my_file');
        $multiple = $request->files->all('multiple');

        $logger->info('File upload', [
            'file'     => $this->describeUploadedFile($file),
            'multiple' => array_map(
                fn (UploadedFile $uploadedFile) => $this->describeUploadedFile($uploadedFile),
                $multiple
            ),
        ]);
    }

    private function describeUploadedFile(?UploadedFile $file): ?array
    {
        if (null === $file) {
            return null;
        }

        return [
            'clientOriginalName' => $file->getClientOriginalName(),
            'size'               => $file->getSize(),
            'mimeType'           => $file->getMimeType(),
            'error'              => $file->getError(),
        ];
    }

    // Live Components do not natively support returning file responses directly from 
    // a LiveAction. However, you can implement file downloads by redirecting to a route 
    // that handles the file response.
    
    #[LiveAction]
    public function download(UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        // $url = $urlGenerator->generate('app_file_download');
        // return new RedirectResponse($url);

        return new RedirectResponse('www.example.com');
    }
}
