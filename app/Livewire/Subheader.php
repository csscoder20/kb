<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tag;
use App\Models\Basic;

class Subheader extends Component
{
    public $slug;
    public $tagData;
    public $basics;

    public function mount($slug = null)
    {
        $this->slug = $slug;
        $this->basics = Basic::getAllAsArray();
        $this->tagData = Tag::where('slug', $slug)->first();
    }

    protected $listeners = ['slugChanged' => 'updateSlug'];

    public function updateSlug($slug)
    {
        $this->slug = $slug;
        $this->tagData = Tag::where('slug', $slug)->first();
    }

    public function render()
    {
        $tags = Tag::withCount('reports')
            ->with(['reports' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        return view('livewire.subheader', ['tags' => $tags]);
    }
}
