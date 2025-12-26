<?php

namespace App\Console\Commands;

use App\Models\Automation;
use App\Models\Device;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class AutomationChecker extends Command
{
    protected $signature = 'automation:check';
    protected $description = 'Check and run scheduled automations';

    protected Client $client;
    protected string $loggerUrl;
    protected array $loggerHeaders;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client();
        $this->loggerUrl = rtrim(env('UNILOG_URL', 'https://unilog.my.id/api/logger'), '/');
        $this->loggerHeaders = [
            'X-API-KEY' => env('UNILOG_API_KEY', 'your_api_key_here'),
        ];
    }

    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta')->format('H:i');
        $automations = Automation::where('time', $now)->get();

        if ($automations->isEmpty()) {
            $this->info("Tidak ada automation saat ini ({$now}).");
            $this->sendLog("No automations to run", "INFO", "No automations scheduled at {$now}");
            return 0;
        }

        foreach ($automations as $automation) {
            $device = Device::find($automation->device_id);
            $state = $automation->state ?? 'off';
            $stateValue = $state === 'on' ? 1 : 0;

            if (!$device) {
                $this->error("Automation {$automation->id} gagal: Device tidak ditemukan");
                $this->sendLog(
                    "Device not found for automation {$automation->id}",
                    "CRITICAL",
                    "Automation ID {$automation->id} gagal karena device tidak ditemukan"
                );
                continue;
            }

            $query = http_build_query([
                'token' => $device->token,
                $automation->pin => $stateValue
            ]);
            $url = rtrim(env('BLYNK_SERVER', 'https://blynk.cloud/external/api/'), '/') . "/update?" . $query;

            try {
                $response = $this->client->get($url);
                $this->info("Automation {$automation->id} berhasil, HTTP code: {$response->getStatusCode()}");

                $this->sendLog(
                    "Turn {$state} automation",
                    "WARNING",
                    "Automation ID {$automation->id} set pin {$automation->pin} to {$state}"
                );
            } catch (\Exception $e) {
                $this->error("Automation {$automation->id} gagal: {$e->getMessage()}");
                $this->sendLog(
                    "Failed to turn {$state} automation",
                    "CRITICAL",
                    "Automation ID {$automation->id} gagal set pin {$automation->pin} to {$state}. Error: {$e->getMessage()}",
                    500
                );
            }
        }

        return 0;
    }

    /**
     * Helper function untuk kirim log ke UniLog
     */
    protected function sendLog(string $event, string $level, string $message, int $statusCode = 200): void
    {
        try {
            $response = $this->client->post($this->loggerUrl, [
                'headers' => $this->loggerHeaders,
                'json' => [
                    'event' => $event,
                    'action' => 'POST',
                    'level' => $level,
                    'message' => $message,
                    'status_code' => $statusCode,
                    'tags' => ['automation', 'device', 'blynk']
                ]
            ]);

            if ($response->getStatusCode() === 201) {
                $this->info("Log berhasil dikirim: {$event}");
            } else {
                $this->error("Gagal mengirim log: {$event}, HTTP: {$response->getBody()}");
            }
        } catch (\Exception $e) {
            $this->error("Exception saat mengirim log: {$e->getMessage()}");
        }
    }
}
