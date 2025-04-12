<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tag;
use App\Models\Basic;
use App\Models\Report;
use Illuminate\Support\Facades\Request;

class Subheader extends Component
{
    public $slug;
    public $tagData;
    public $basics;

    public $search = ''; // <== properti baru
    public $searchResults = []; // <== untuk menyimpan hasil pencarian

    public function mount($slug = null)
    {
        $this->slug = $slug ?? Request::query('slug'); // ambil dari query jika tidak langsung dikirim
        $this->basics = Basic::getAllAsArray();
        $this->tagData = Tag::where('slug', $this->slug)->first();
    }


    protected $listeners = ['slugChanged' => 'updateSlug'];

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            $this->searchResults = Report::where('title', 'like', '%' . $this->search . '%')
                ->with('tags') // opsional
                ->limit(10)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

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

        return view('livewire.subheader', [
            'tags' => $tags,
            'searchResults' => $this->searchResults,
        ]);
    }
}
