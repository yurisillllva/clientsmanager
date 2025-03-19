<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendWelcomeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:welcome-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Client::where('created_at', '<=', now()->subMinutes(30))
        ->where('welcome_email_sent', false)
        ->with('user')
        ->each(function ($client) {
            Mail::to($client->email)->send(new WelcomeEmail($client));
            $client->update(['welcome_email_sent' => true]);
        });
    }
}
