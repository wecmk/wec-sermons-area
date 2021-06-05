<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\AttachmentMetadata;
use App\Services\Filesystem\FilesystemService;
use Symfony\Component\Intl\Exception\NotImplementedException;

final class AttachmentMetadataPersister implements ContextAwareDataPersisterInterface
{
    private ContextAwareDataPersisterInterface $decorated;
    private FilesystemService $filesystemService;

    public function __construct(ContextAwareDataPersisterInterface $decorated, FilesystemService $filesystemService)
    {
        $this->decorated = $decorated;
        $this->filesystemService = $filesystemService;
    }

    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    public function persist($data, array $context = [])
    {
        $result = $this->decorated->persist($data, $context);

        if (
            $data instanceof AttachmentMetadata && (
                ($context['collection_operation_name'] ?? null) === 'post' ||
                ($context['graphql_operation_name'] ?? null) === 'create'
            )
        ) {
            $this->filesystemService->create($data);
        }
        if (
            $data instanceof AttachmentMetadata && (
                ($context['collection_operation_name'] ?? null) === 'delete' ||
                ($context['graphql_operation_name'] ?? null) === 'delete'
            )
        ) {
            throw new NotImplementedException("Deleting attachments from disk is not yet supported");
        }

        return $result;
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }
}
