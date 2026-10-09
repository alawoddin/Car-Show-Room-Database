<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChatController extends Controller
{
     // Display all client conversations
    public function AdminChat()
    {
        $conversations = ChatConversation::with([
            'user',
            'latestMessage',
        ])
            ->orderByDesc('last_message_at')
            ->get();

        return view('admin.chat.all_chat', compact('conversations'));
    }

    // Open a specific client's conversation
    public function AdminChatShow(int $id)
    {
        $conversation = ChatConversation::with('user')
            ->findOrFail($id);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Mark messages sent by clients as read
        $conversation->messages()
            ->where('sender_id', $conversation->user_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.chat.chat_show', compact(
            'conversation',
            'messages'
        ));
    }

    // Send a message to a client
    public function AdminChatSend(Request $request, $id)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = ChatConversation::findOrFail($id);

        DB::transaction(function () use ($request, $conversation) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $request->user()->id,
                'message' => $request->message,
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
