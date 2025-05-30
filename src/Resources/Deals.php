<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Entity;
use Devio\Pipedrive\Resources\Traits\ListsAttachedFiles;
use Devio\Pipedrive\Resources\Traits\ListsProducts;
use Devio\Pipedrive\Resources\Traits\Searches;
use Illuminate\Support\Arr;

class Deals extends Entity
{
    use ListsAttachedFiles;
    use ListsProducts;
    use Searches;

    /**
     * Get the deals summary
     *
     * @param array $options
     *
     * @return Response
     */
    public function summary(array $options = []): Response
    {

        return $this->request->get('summary', $options);
    }

    /**
     * Get the deals timeline.
     *
     * @param string $start_date
     * @param string $interval
     * @param int    $amount
     * @param string $field_key
     * @param array  $options
     * @return Response
     */
    public function timeline(
        string $start_date,
        string $interval,
        int $amount,
        string $field_key,
        array $options = []
    ): Response {
        $options = array_merge(
            ['start_date' => $start_date, 'interval' => $interval, 'amount' => $amount, 'field_key' => $field_key],
            $options
        );

        return $this->request->get('timeline', $options);
    }

    /**
     * Add a participant to a deal.
     *
     * @param int $id
     * @param int $person_id
     *
     * @return Response
     */
    public function addParticipant(int $id, int $person_id): Response
    {
        return $this->request->post(':id/participants', ['id' => $id, 'person_id' => $person_id]);
    }

    /**
     * Get the participants of a deal.
     *
     * @param int   $id
     * @param array $options
     *
     * @return Response
     */
    public function participants(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/participants', $options);
    }

    /**
     * Delete a participant from a deal.
     *
     * @param int $id
     * @param int $deal_participant_id
     *
     * @return Response
     */
    public function deleteParticipant(int $id, int $deal_participant_id): Response
    {
        return $this->request->delete(':id/participants/:deal_participant_id', ['id' => $id, 'deal_participant_id' => $deal_participant_id]);
    }

    /**
     * Add a product to the deal.
     *
     * @param int   $id
     * @param int   $product_id
     * @param       $item_price
     * @param int   $quantity
     * @param array $options
     * @return Response
     */
    public function addProduct(
        int $id,
        int $product_id,
        $item_price,
        int $quantity,
        array $options = []
    ): Response {
        $options = array_merge(
            ['id' => $id, 'product_id' => $product_id, 'item_price' => $item_price, 'quantity' => $quantity],
            $options
        );

        return $this->request->post(':id/products', $options);
    }

    /**
     * Update the details of an attached product.
     *
     * @param int   $id
     * @param int   $deal_product_id
     * @param       $item_price
     * @param int   $quantity
     * @param array $options
     * @return Response
     */
    public function updateProduct(
        int $id,
        int $deal_product_id,
        $item_price,
        int $quantity,
        array $options = []
    ): Response {
        $options = array_merge(
            ['id' => $id, 'deal_product_id' => $deal_product_id, 'item_price' => $item_price, 'quantity' => $quantity],
            $options
        );

        return $this->request->put(':id/products/:deal_product_id', $options);
    }

    /**
     * Delete an attached product from a deal.
     *
     * @param int $id
     * @param int $product_attachment_id
     *
     * @return Response
     */
    public function deleteProduct(int $id, int $product_attachment_id): Response
    {
        return $this->request->delete(
            ':id/products/:product_attachment_id',
            ['id' => $id, 'product_attachment_id' => $product_attachment_id]
        );
    }

    /**
     * Duplicate a deal.
     *
     * @param int $id The deal id
     *
     * @return Response
     */
    public function duplicate(int $id): Response
    {
        return $this->request->post(':id/duplicate', ['id' => $id]);
    }

    /**
     * Get the email messages for a deal.
     *
     * @param int $id The deal id
     *
     * @return Response
     */
    public function mailMessages(int $id): Response
    {
        return $this->request->get(':id/mailMessages', ['id' => $id]);
    }

    /**
     * Get the files for a deal.
     *
     * @param int $id The deal id
     *
     * @return Response
     */
    public function files(int $id): Response
    {
        return $this->request->get(':id/files', ['id' => $id]);
    }
}
