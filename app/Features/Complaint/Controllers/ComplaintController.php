<?php

namespace App\Features\Complaint\Controllers;

use App\Features\Complaint\Mail\ComplaintReplyMail;
use App\Features\Complaint\Models\Complaint;
use App\Features\Complaint\Requests\ReplyComplaintRequest;
use App\Features\Complaint\Requests\StoreComplaintRequest;
use App\Features\Complaint\Resources\ComplaintResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ComplaintController extends Controller
{
    /**
     * Store a new complaint.
     */
    public function store(
        StoreComplaintRequest $request
    ): JsonResponse {
        $complaint = Complaint::create([
            'user_id' => $request->user()->id,
            'shop_id' => $request->validated('shop_id'),
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
            'status' => 'pending',
        ]);

        $complaint->load([
            'user',
            'shop',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الشكوى بنجاح.',
            'data' => new ComplaintResource($complaint),
        ], 201);
    }

    /**
     * Get customer's complaints.
     */
    public function myComplaints(
        Request $request
    ): JsonResponse {
        $complaints = Complaint::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'shop:id,shop_name',
            ])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الشكاوى بنجاح.',
            'data' => ComplaintResource::collection($complaints),
        ]);
    }

    /**
     * Get all complaints for admin.
     */
    public function index(): JsonResponse
    {
        $complaints = Complaint::query()
            ->with([
                'user:id,first_name,last_name,email',
                'shop:id,shop_name',
            ])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الشكاوى بنجاح.',
            'data' => ComplaintResource::collection($complaints),
        ]);
    }

    /**
     * Reply to a complaint.
     */
    public function reply(
        ReplyComplaintRequest $request,
        int $id
    ): JsonResponse {
        $complaint = Complaint::query()
            ->with([
                'user',
                'shop',
            ])
            ->findOrFail($id);

        $complaint->update([
            'admin_reply' => $request->validated('admin_reply'),
            'status' => 'replied',
            'replied_at' => now(),
        ]);

        /*
         * Send the reply to the customer's email.
         */
        Mail::to($complaint->user->email)
            ->send(new ComplaintReplyMail($complaint));

        return response()->json([
            'success' => true,
            'message' => 'تم الرد على الشكوى وإرسال الرد إلى البريد الإلكتروني.',
            'data' => new ComplaintResource($complaint->fresh([
                'user',
                'shop',
            ])),
        ]);
    }

    /**
     * Delete a complaint.
     */
    public function destroy(int $id): JsonResponse
    {
        $complaint = Complaint::findOrFail($id);

        $complaint->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الشكوى بنجاح.',
            'data' => null,
        ]);
    }
}