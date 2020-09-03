<?php

namespace App\Jobs;

use App\CsvReader;
use App\Entry;
use App\Notifications\UserNotification;
use App\User;
use DB;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Storage;

class ProcessCsvFile implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const BATCH_SIZE = 50;
    /**
     * @var string
     */
    private $filepath;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $originalName;

    /**
     * Create a new job instance.
     *
     * @param string $filepath
     * @param string $originalName
     * @param int $userId
     */
    public function __construct(string $filepath, string $originalName, int $userId)
    {
        $this->filepath = $filepath;
        $this->userId = $userId;
        $this->originalName = $originalName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("processing new CSV file");
        $start = microtime(true);
        $csvReader = new CsvReader(storage_path('app/' . $this->filepath), self::BATCH_SIZE);
        $notification = null;
        $user = User::find($this->userId);
        try {
            $record_count = $csvReader->recordCount(); // TODO check what will happen when file deleted
            $user->notify(new UserNotification("We're importing your $record_count balance entries. Sit tight."));

            // I know it's crazy, but it's more accurate to report what we actually inserted
            // who knows maybe something modified the total records since we last read it above!
            $record_count = 0;
            $aggregated_balance = 0;
            DB::transaction(function () use (&$record_count, $user, $csvReader) {
                $now = now();
                foreach ($csvReader->batches() as $batch) { // TODO check what will happen when file deleted
                    $batch_count = count($batch);
                    $record_count += $batch_count;

                    for ($i = 0; $i < $batch_count; $i++) {
                        $batch[$i] = $this->addEntrylKeys($batch[$i], $this->userId, $now);
                        $user->total_cents += $batch[$i]['amount_cents'];
                    }

                    Entry::insert($batch);
                    $user->save();
                }
            }, 5);

            $elapsed_seconds = round(microtime(true) - $start, 1);
            $message = "Successfully imported {$record_count} records from file {$this->originalName} in {$elapsed_seconds} seconds";
            $notification = new UserNotification($message, 'info', true, $user->total_cents);

        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $elapsed_seconds = round(microtime(true) - $start, 1);
            $message = "Failed importing file {$this->originalName} after {$elapsed_seconds} seconds";
            $notification = new UserNotification($message, 'error', true);
        } finally {
            $user->unlock();
            $user->notify($notification);
        }

        // Assumes only one upload can occur at a time
        Storage::deleteDirectory(storage_path('app/uploads/' . $this->userId));
    }

    function addEntrylKeys(array $array, $userId, $now)
    {
        return [
            'label' => $array[0],
            'amount_cents' => $array[1] * 100,
            'date_time' => $array[2],
            'user_id' => $userId,
            'created_at' => $now,
            'updated_at' => $now
        ];
    }
}
