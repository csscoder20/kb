@php
$isDefault = is_null($tagData);
$textColor = $isDefault ? 'color: #667c99;' : 'color: #fff;';
@endphp
<header id="subHeader" class="mb-4 py-3" style="background-color: {{ $tagData?->color ?? '#f0f8ff' }}">
    <div class="container px-lg-5 mb-4">
        <div class="alert alert-dismissible fade show" role="alert">
            <h2 class="text-xl font-semibold text-center" style="{{ $textColor }}">
                {{ $tagData?->name ?? $basics['title'] }}
            </h2>
            <p class="text-sm text-center" style="{{ $textColor }}">
                {{ $tagData?->description ?? $basics['description'] }}
            </p>
            <button type="button" class="btn-close text-primary" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <div class="container px-lg-5 mb-4">
        <div class="doc_banner_content">
            @if (!Request::is('/', 'newpost'))
            <form action="#" class="header_search_form">
                <div class="header_search_form_info">
                    <div class="form-group">
                        <div class="input-wrapper input-group flex-nowrap">
                            <div class="kolomPencarian d-flex w-100">
                                <span class="input-group-text" id="searchboxIcon"><i class="bi bi-search"></i></span>
                                <input type="search" id="searchbox" autocomplete="off" name="search"
                                    placeholder="Search here ..." wire:model="search" />
                                <div class="header_search_form_panel">
                                    <ul id="listPencarian" class="list-unstyled">
                                    </ul>
                                </div>
                            </div>

                            <select class="search-expand-types custom-select" name="post_type" id="search_post_type"
                                wire:model="slug" style="opacity: 0; position: absolute; pointer-events: none;">
                                <option value="allposts">All</option>
                                @foreach($tags as $tag)
                                <option value="{{ $tag->slug }}">{{ $tag->alias ?? $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</header>