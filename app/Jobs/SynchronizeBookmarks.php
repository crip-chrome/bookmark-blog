<?php namespace App\Jobs;

use App\Bookmark;
use App\Services\Bookmark\BookmarkTagsService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SynchronizeBookmarks
 * @package App\Jobs
 */
class SynchronizeBookmarks implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    private $user_id;

    /**
     * Create a new job instance.
     * @param array $data
     * @param int $user_id
     */
    public function __construct(array $data, int $user_id)
    {
        $this->data = $data;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $ids = [];

        $this->readChildren($ids, [$this->data]);

        Bookmark::whereNotIn('page_id', $ids)->where('user_id', $this->user_id)->delete();
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     */
    public function failed(Exception $exception)
    {
        \Mail::send('layouts.emails.queue_error', ['data' => json_encode($this->data)], function ($m) {
            $m->to(config('app.admin'), 'Admin')->subject('Error in synchronize bookmarks job queue');
            $m->bcc('tahq69@gmail.com', 'thq69');
        });
    }

    /**
     * @param {&array} $ids
     * @param {array} $source
     */
    private function readChildren(&$ids, $source)
    {
        foreach ($source as $children) {
            $ids[] = $children['id'];

            $bookmark = Bookmark::updateOrCreate([
                'page_id' => $children['id'],
                'user_id' => $this->user_id,
            ], [
                'parent_id' => $children['parentId'],
                'date_added' => $children['dateAdded'],
                'title' => $children['title'],
                'index' => $children['index'],
                'url' => array_key_exists('url', $children) ? $children['url'] : '',
            ]);

            if (isset($children['children']) && count($children['children']) > 0) {
                $this->readChildren($ids, $children['children']);
            } else {
                // sync Tags only if it is tree lowest element
                (new BookmarkTagsService())->syncTags($bookmark);
            }
        }
    }
}
