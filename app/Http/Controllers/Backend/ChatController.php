<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Display all customer conversations
    public function AdminChat()
    {
        $conversations = ChatConversation::with([
            'user',
            'latestMessage',
        ])
            ->withCount([
                'messages as unread_count' => function ($query) {
                    $query->whereColumn(
                        'chat_messages.sender_id',
                        'chat_conversations.user_id'
                    )->where('chat_messages.is_read', false);
                }
            ])
            ->orderByDesc('last_message_at')
            ->get();

        $totalUnread = $conversations->sum('unread_count');

        return view('admin.chat.all_chat', compact(
            'conversations',
            'totalUnread'
        ));
    }


    // Load a customer's messages without leaving /admin/chat
    public function LoadChatMessages(int $id)
    {
        $conversation = ChatConversation::with('user')
            ->findOrFail($id);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Mark unread messages from this customer as read
        $conversation->messages()
            ->where('sender_id', $conversation->user_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'name' => $conversation->user->name ?? 'Customer',
                'email' => $conversation->user->email ?? '',
            ],

            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name ?? 'User',
                    'message' => $message->message,
                    'created_at' => $message->created_at
                        ->format('d M Y, h:i A'),
                ];
            })->values(),
        ]);
    }





    // Send a message to a customer
    public function AdminChatSend(Request $request, int $id)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = ChatConversation::findOrFail($id);

        DB::transaction(function () use (
            $request,
            $validated,
            $conversation
        ) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $request->user()->id,
                'message' => $validated['message'],
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
        ]);
    }
}
