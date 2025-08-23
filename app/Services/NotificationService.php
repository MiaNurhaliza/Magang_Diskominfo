<?php

namespace App\Services;

use App\Models\Biodata;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send email notification when registration status changes
     */
    public function sendStatusNotification(Biodata $biodata, string $oldStatus = null)
    {
        try {
            $user = $biodata->user;
            
            if (!$user || !$user->email) {
                Log::warning('Cannot send notification: User or email not found for pendaftar ID ' . $biodata->id);
                return false;
            }

            $status = $biodata->status;
            
            // Only send notification if status actually changed
            if ($oldStatus && $oldStatus === $status) {
                Log::info("Status unchanged ({$status}), skipping notification for user: {$user->email}");
                return false;
            }

            Log::info("Attempting to send notification for status change: {$oldStatus} -> {$status} to {$user->email}");

            switch ($status) {
                case 'diterima':
                    $this->sendAcceptedNotification($user, $biodata);
                    break;
                    
                case 'ditolak':
                    $this->sendRejectedNotification($user, $biodata);
                    break;
                    
                case 'jadwal_dialihkan':
                    $this->sendRescheduledNotification($user, $biodata);
                    break;
                    
                default:
                    Log::info("No notification configured for status: {$status}");
                    return false;
            }
            
            Log::info("Status notification sent successfully to {$user->email} for status: {$status}");
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send status notification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Send acceptance notification
     */
    private function sendAcceptedNotification($user, $biodata)
    {
        Mail::send('emails.status.accepted', [
            'user' => $user,
            'pendaftar' => $biodata
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                   ->subject('Selamat! Pendaftaran Magang Anda Diterima - Diskominfo Bukittinggi');
        });
    }

    /**
     * Send rejection notification
     */
    private function sendRejectedNotification($user, $biodata)
    {
        Mail::send('emails.status.rejected', [
            'user' => $user,
            'pendaftar' => $biodata
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                   ->subject('Pemberitahuan Status Pendaftaran Magang - Diskominfo Bukittinggi');
        });
    }

    /**
     * Send rescheduled notification
     */
    private function sendRescheduledNotification($user, $biodata)
    {
        Mail::send('emails.status.rescheduled', [
            'user' => $user,
            'pendaftar' => $biodata
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                   ->subject('Pemberitahuan Perubahan Jadwal Magang - Diskominfo Bukittinggi');
        });
    }
}
