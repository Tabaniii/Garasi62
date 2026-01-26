<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class CleanupInvalidChats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up invalid chats from cache (chats without messages)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up invalid chats...');
        
        $cleanedCount = 0;
        
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            $cacheKey = 'user_chats_' . $user->id;
            $chatsData = Cache::get($cacheKey, []);
            
            if (empty($chatsData)) {
                continue;
            }
            
            $validChats = [];
            $invalidCount = 0;
            
            foreach ($chatsData as $chatId => $chatCacheData) {
                // Check if there are messages in cache for this chat
                $messagesCacheKey = 'chat_messages_' . $chatId;
                $messages = Cache::get($messagesCacheKey, []);
                
                // Get last message
                $lastMessage = $chatCacheData['last_message'] ?? '';
                
                // If no messages exist and no last_message, mark as invalid
                if (empty($messages) && empty($lastMessage)) {
                    $invalidCount++;
                    continue;
                }
                
                // Only include if there's a valid last_message
                if (empty($lastMessage)) {
                    $invalidCount++;
                    continue;
                }
                
                $validChats[$chatId] = $chatCacheData;
            }
            
            if ($invalidCount > 0) {
                Cache::put($cacheKey, $validChats, now()->addDays(7));
                $cleanedCount += $invalidCount;
                $this->info("Cleaned {$invalidCount} invalid chats for user: {$user->name} (ID: {$user->id})");
            }
        }
        
        if ($cleanedCount > 0) {
            $this->info("Total invalid chats cleaned: {$cleanedCount}");
        } else {
            $this->info('No invalid chats found.');
        }
        
        return 0;
    }
}




