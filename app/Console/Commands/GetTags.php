<?php

namespace App\Console\Commands;

use App\Models\Tag;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class GetTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get tags from Shikimori API';

    /**
     * Execute the console command.
     */
    public function handle(Client $client)
    {
        $this->info('start');
        $responseData = json_decode($client->get('https://shikimori.one/api/genres',['verify' => false])->getBody());
        foreach ($responseData as $tagData) {
            if ($tagData->entry_type == "Anime") {
                $tag = new Tag();
                $tag->name = $tagData->name;
                $tag->russian_name = $tagData->russian;
                $tag->save();
            }

        }
        $this->info('end');
    }
}
