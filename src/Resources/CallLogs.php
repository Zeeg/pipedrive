<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;

class CallLogs extends Resource
{
    protected array $enabled = ['all', 'find', 'add', 'delete', 'addRecording'];

    /**
     * Upload a recording file (audio) to a callLog
     *
     * @param int          $id
     * @param \SplFileInfo $file
     *
     * @return Response
     */
    public function addRecording(int $id, \SplFileInfo $file): Response
    {
        return $this->request->post(':id/recordings', ['id' => $id, 'file' => $file]);
    }
}
