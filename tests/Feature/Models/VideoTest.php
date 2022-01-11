<?php

namespace Tests\Feature\Models;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VideoTest extends TestCase
{

    use DatabaseMigrations;


    public function testList()
    {
        factory(Video::class)->create();
        $videos = Video::all();
        $this->assertCount(1, $videos);
        $videosKeys = array_keys($videos->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'title',
                'description',
                'year_launched',
                'opened',
                'rating',
                'duration',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $videosKeys
        );
    }

    public function testCreate()
    {
        $video = Video::create([
            'title' => 'Title A',
            'description' => 'Descricao A',
            'year_launched' => '2022',
            'rating' => 'L',
            'duration' => '60',
        ]);
        $video->refresh();

        $this->assertEquals(36, strlen($video->id));
        $this->assertEquals('Title A', $video->title);
        $this->assertEquals('Descricao A', $video->description);
        $this->assertEquals('2022', $video->year_launched);
        $this->assertFalse($video->opened);
        $this->assertEquals('60', $video->duration);
        $this->assertEquals('L', $video->rating);

        $video = Video::create([
            'title' => 'Title A',
            'description' => 'Descricao A',
            'year_launched' => '2022',
            'rating' => 'L',
            'duration' => '60',
            'opened' => true
        ]);
        $video->refresh();

        $this->assertTrue($video->opened);

        $video = Video::create([
            'title' => 'Title A',
            'description' => 'Descricao A',
            'year_launched' => '2022',
            'rating' => 'L',
            'duration' => '60',
            'opened' => false
        ]);
        $video->refresh();

        $this->assertFalse($video->opened);
    }

    public function testUpdate()
    {
        $video = factory(Video::class)->create([
            'title' => 'Title'
        ]);

        $data = [
            'title' => 'Title A',
            'description' => 'Descricao A',
            'year_launched' => '2022',
            'rating' => 'L',
            'duration' => '60',
            'opened' => true
        ];

        $video->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $video->{$key});
        }
    }

    public function testDelete()
    {
        $video = factory(Video::class)->create();
        $video->delete();

        $this->assertNull(Video::find($video->id));

        $video->restore();
        $this->assertNotNull(Video::find($video->id));
    }
}
