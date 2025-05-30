<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;

class Mailbox extends Resource
{
    /**
     * Get the Mail threads details by ID.
     *
     * @param int $id Mail threads ID to find.
     *
     * @return Response
     */
    public function find(int $id): Response
    {
        return $this->request->get('mailThreads/:id', ['id' => $id]);
    }

    /**
     * Delete Mail threads by ID.
     *
     * @param int $id Mail threads ID to delete.
     *
     * @return Response
     */
    public function delete(int $id): Response
    {
        return $this->request->delete('mailThreads/:id', ['id' => $id]);
    }

    /**
     * Update Mail threads by ID.
     *
     * @param int   $id
     * @param array $values
     *
     * @return Response
     */
    public function update(int $id, array $values): Response
    {
        $values['id'] = $id;

        return $this->request->put('mailThreads/:id', $values);
    }

    /**
     * Get list of mail threads
     *
     * @param string $folder
     * @param array  $options
     *
     * @return Response
     */
    public function mailThreads(string $folder, array $options = []): Response
    {
        $options['folder'] = $folder;

        return $this->request->get('mailThreads', $options);
    }

    /**
     * Get mail messages inside specified mail thread by ID.
     *
     * @param int   $id Mail threads ID to find messages.
     * @param array $params
     *
     * @return Response
     */
    public function mailMessages(int $id, array $params = []): Response
    {
        $params['id'] = $id;

        return $this->request->get('mailThreads/:id/mailMessages', $params);
    }

    /**
     * Get a specific email message by email ID.
     *
     * @param mixed $id Email ID to find a message.
     * @return Response
     */
    public function mailSpecificMessage(mixed $id): Response
    {
        return $this->request->get('mailMessages/:id', ['id' => $id]);
    }
}
