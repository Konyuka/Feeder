<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Feed;
use App\Models\Source;
use App\Models\Article;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function rssFeed(Request $request)
    {

        $feed = $request->all();
        $subscriptionId = $feed['subscription_id'] ?? null;

        if ($subscriptionId !== null) {
            $rss = Feed::where('subscription_id', $subscriptionId)->first();

            if ($rss) {
                $source = Source::findOrFail($rss->source_id);
                foreach ($feed['new_entries'] as $article) {
                    if (isset($article['title']) && $article['title'] != null && isset($article['description']) && $article['description'] != null) {
                        $post = Article::where('title', $article['title'])->first();
                        if (!$post) {
                            Article::firstOrCreate([
                                'author' => $article['additional_details']['authors'] ? $article['additional_details']['authors'][0]['name'] : $source->name,
                                'title' => $article['title'],
                                'description' => $article['description'],
                                'image_url' => $article['image']['url'],
                                'content' => $article['content']['html'],
                                'article_url' => $article['link'],
                                'published' => Carbon::parse($article['timestamp'])->toIso8601String(),
                                'status_id' => 1,
                                'source_id' => $rss->source_id,
                                'category_id' => $rss->category_id,
                                'country_id' => $source->country_id,
                                'shuffle_id' => random_int(100000, 999999)
                            ]);
                        }

                    }
                }
            }
        }


    }
}
