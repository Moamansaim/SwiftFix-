<?php

namespace App\Features\Contact\Controllers;

use App\Features\Contact\Mail\ContactReplyMail;
use App\Features\Contact\Models\ContactMessage;
use App\Features\Contact\Requests\ContactMessageRequest;
use App\Features\Contact\Requests\ContactReplyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ContactController
{
    /**
     * Store a new contact message.
     *
     * This method handles the submission of a contact form.
     *
     * The incoming request is validated using ContactMessageRequest
     * before creating a new contact message in the database.
     *
     * The following information is stored:
     * - Full name of the sender.
     * - Email address of the sender.
     * - Subject of the message.
     * - Message content.
     *
     * @param ContactMessageRequest $request
     *
     * @return JsonResponse
     *         Returns a JSON response containing a success message
     *         and the newly created contact message with HTTP status 201.
     */
    public function store(
        ContactMessageRequest $request
    ): JsonResponse {
        $contactMessage = ContactMessage::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'تم إرسال رسالتك بنجاح.',
            'data' => $contactMessage,
        ], 201);
    }

    /**
     * Retrieve all contact messages.
     *
     * This method retrieves all messages submitted through
     * the contact form from the database.
     *
     * The messages are ordered by their creation date in
     * descending order, so the most recently submitted message
     * appears first.
     *
     * This endpoint is intended for authorized users, such as
     * administrators, to review messages received from users.
     *
     * @return JsonResponse
     *         Returns a JSON response containing all contact messages
     *         with HTTP status 200.
     */
    public function index(): JsonResponse
    {
        $contactMessages = ContactMessage::latest()->get();

        return response()->json([
            'data' => $contactMessages,
        ], 200);
    }

    /**
     * Send a reply to a contact message via email.
     *
     * The recipient's email address is retrieved directly from
     * the selected contact message in the database.
     *
     * The React frontend only needs to provide the contact message ID
     * and the reply content.
     *
     * @param ContactReplyRequest $request
     *        The validated request containing the reply content.
     *
     * @param int $id
     *        The unique identifier of the contact message.
     *
     * @return JsonResponse
     *         Returns a JSON response containing a success message
     *         after the email has been sent.
     */
    public function reply(
        ContactReplyRequest $request,
        int $id
    ): JsonResponse {
        $contactMessage = ContactMessage::findOrFail($id);

        Mail::to($contactMessage->email)->send(
            new ContactReplyMail(
                reply: $request->reply
            )
        );

        $contactMessage->update([
            'status' => 'replied',
        ]);

        return response()->json([
            'message' => 'تم إرسال الرد إلى البريد الإلكتروني بنجاح.',
        ], 200);
    }



    /**
     * Delete a contact message.
     *
     * This method finds a contact message by its ID and permanently
     * removes it from the database.
     *
     * If no contact message exists with the given ID, Laravel throws
     * a ModelNotFoundException through findOrFail(), which is handled
     * by Laravel's exception handling system.
     *
     * @param int $id
     *        The unique identifier of the contact message to delete.
     *
     * @return JsonResponse
     *         Returns a JSON response containing a success message
     *         with HTTP status 200 after successful deletion.
     */
    public function destroy(int $id): JsonResponse
    {
        $contactMessage = ContactMessage::findOrFail($id);

        $contactMessage->delete();

        return response()->json([
            'message' => 'تم حذف الرسالة بنجاح.',
        ], 200);
    }
}