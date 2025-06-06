<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;

class Files extends Resource
{
    /**
     * Disabled abstract methods.
     */
    protected array $disabled = ['deleteBulk'];

    /**
     * Create a remote file and link it to an item.
     *
     * @param string $file_type
     * @param string $title
     * @param string $item_type
     * @param int    $item_id
     * @param string $remote_location
     *
     * @return Response
     */
    public function createRemote(
        string $file_type,
        string $title,
        string $item_type,
        int $item_id,
        string $remote_location
    ): Response {
        return $this->request->post(
            'remote',
            ['file_type' => $file_type, 'title' => $title, 'item_type' => $item_type, 'item_id' => $item_id, 'remote_location' => $remote_location]
        );
    }

    /**
     * Link a remote file to an item.
     *
     * @param string $item_type
     * @param int    $item_id
     * @param string $remote_id
     * @param string $remote_location
     * @return Response
     */
    public function linkRemote(
        string $item_type,
        int $item_id,
        string $remote_id,
        string $remote_location
    ): Response {
        return $this->request->post(
            'remoteLink',
            ['item_type' => $item_type, 'item_id' => $item_id, 'remote_id' => $remote_id, 'remote_location' => $remote_location]
        );
    }

    /**
     * Initializes a file download.
     *
     * @param int $id
     *
     * @return Response
     */
    public function download(int $id): Response
    {
        return $this->request->get(':id/download', ['id' => $id]);
    }
}
