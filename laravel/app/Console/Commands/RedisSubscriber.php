<?php

namespace App\Console\Commands;

use App\Enums\LiveSessionStatus;
use App\Enums\RedisMessageType;
use App\Models\LiveSession;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

#[Signature('redis:subscribe')]
#[Description('Subscribe to Redis channel and receive messages')]
class RedisSubscriber extends Command
{
    protected string $channelName = 'liveset-events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Subscribing...');

        Redis::connection('subscriber')->subscribe([$this->channelName], function (string $message, string $channel) {
            $this->info("Received message from channel $channel");
            $json_message = json_decode($message, true);

            if (!$json_message || !isset($json_message['type'])) {
                $this->error('Invalid message format');
                return;
            }

            DB::reconnect();

            $type = $json_message['type'];
            $payload = is_string($json_message['payload'])
                ? json_decode($json_message['payload'], true)
                : $json_message['payload'];

            switch ($type) {
                case RedisMessageType::TRAINING_PROGRAM_CREATED->value:
                    $this->handleTrainingProgramCreatedUpdated($payload, RedisMessageType::TRAINING_PROGRAM_CREATED);
                    break;
                case RedisMessageType::TRAINING_PROGRAM_UPDATED->value:
                    $this->handleTrainingProgramCreatedUpdated($payload, RedisMessageType::TRAINING_PROGRAM_UPDATED);
                    break;
                case RedisMessageType::TRAINING_PROGRAM_DELETED->value:
                    $this->handleTrainingProgramDeleted($payload);
                    break;

                case RedisMessageType::LIVE_SESSION_STOPPED->value:
                    $this->handleLiveSessionStopped($payload);
                    break;
                default:
                    break;
            }
        });
    }

    protected function handleTrainingProgramCreatedUpdated(array $payload, RedisMessageType $type): void
    {
        $id = $payload['id'];
        $title = $payload['title'];
        $description = $payload['description'];
        $isPrivate = $payload['is_private'];
        $trainingTime = $payload['training_time'];

        $user = User::find($payload['user_id']);
        if ($user === null) {
            $this->error('User not found');
            return;
        }

        $exercisesQty = count($payload['program_exercises']);

        if ($type === RedisMessageType::TRAINING_PROGRAM_CREATED) {
            TrainingProgram::create([
                'title' => $title,
                'description' => $description,
                'is_private' => $isPrivate,
                'training_time' => $trainingTime,
                'user_id' => $user->id,
                'exercises_qty' => $exercisesQty,
            ]);

            $this->info("Training program $title created");
        } else {
            $training = TrainingProgram::find($id);
            if (!$training) {
                $this->error('Training program not found');
                return;
            }
            $training->update([
                'title' => $title,
                'description' => $description,
                'is_private' => $isPrivate,
                'training_time' => $trainingTime,
                'user_id' => $user->id,
                'exercises_qty' => $exercisesQty,
            ]);

            $this->info("Training program $title updated");
        }
    }

    protected function handleTrainingProgramDeleted(array $payload): void
    {
        $id = $payload['id'];

        $trainingProgram = TrainingProgram::find($id);
        if (!$trainingProgram) {
            $this->error('Training program not found');
            return;
        }

        $trainingProgram->delete();
        $this->info("Training program $id deleted");
    }

    protected function handleLiveSessionStopped(array $payload): void
    {
        $uniqueUrl = $payload['unique_url'];
        $liveSession = LiveSession::where('unique_url', $uniqueUrl)->first();
        if (!$liveSession) {
            $this->error('Invalid unique url');
            return;
        }

        $liveSession->status = LiveSessionStatus::completed;
        $liveSession->save();

        $this->info("Live session $liveSession->id ended successfully");
    }
}
