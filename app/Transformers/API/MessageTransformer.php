<?php

namespace App\Transformers\API;

use App\Models\Message;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Message $message)
    {
        return [
            'id'         => $message->id,
            'firstName'  => $message->firstName,
            'lastName'   => $message->lastName,
            'email'      => $message->email,
            'handphone'  => $message->handphone,
            'subject'    => $message->subject,
            'message'    => $message->message,
        ];
    }

}
