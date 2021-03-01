<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\AttachmentPostAction;
use App\Entity\AttachmentInput;

/**
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     normalizationContext={
 *         "groups"={"media_object_read"}
 *     },
 *     collectionOperations={
 *         "post"={
 *             "controller"=AttachmentPostAction::class,
 *             "deserialize"=true,
 *             "security"="is_granted('ROLE_USER')",
 *             "validation_groups"={"Default", "media_object_create"},
 *             "input" = AttachmentInput::class
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "controller"=AttachmentGetAction::class,
 *             "deserialize"=true,
 *             "formats"={"mp3"={"audio/mpeg"}},
 *         },
 *         "put"={
 *             "controller"=AttachmentPutAction::class,
 *             "deserialize"=false,
 *             "security"="is_granted('ROLE_USER')",
 *             "formats"={"binary"={"application/octet-stream"}},
 *             "validation_groups"={"Default", "media_object_create"},
 *             "content"={
 *                 "application/octet-stream"={
 *                     "schema"={
 *                        "type"="object"
 *                     }
 *                 }
 *             },
 *             "openapi_context"={
 *                 "summary" = "Submit binary of attachment (swagger specification is broken but api works)",
 *                 "parameters"={
 *                     {
 *                         "name" = "Content-Range",
 *                         "in" = "header",
 *                         "type" = "string",
 *                         "description" = "The content range of the submitted data. Omit if full binary data is submitted",
 *                         "example" = "bytes 0-500/1000",
 *                         "required" = false
 *                     }
 *                 },
 *                 "responses"={
 *                     "200" =
 *                     {
 *                         "description" = "",
 *                         "content" = {
 *                              "application/json"
 *                         }
 *                     },
 *                    "201" =
 *                     {
 *                         "description" = "When the uploaded file is completed. Defined by uploading the last byte either by a single upload or because Content-Range is bytes x-(y-1)/y",
 *                         "content" = {
 *                              "application/json"
 *                         }
 *                     },
 *                    "308" =
 *                     {
 *                         "description" = "The request was accepted. You may continue to upload the next chunk",
 *                         "content" = {
 *                              "application/json"
 *                         }
 *                     }
 *                 }
 *             }
 *         }
 *     }
 * )
 */
class Attachment
{
}
