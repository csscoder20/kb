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
    <div class="doc_banner_content">
        <form action="#" class="header_search_form">
            <div class="header_search_form_info">
                <div class="form-group">
                    <div class="input-wrapper">
                        <i class="bi bi-search"></i>
                        <input type='search' id="searchbox" autocomplete="off" name="search"
                            placeholder="Search the Doc" />
                        <div class="header_search_form_panel">
                            <ul class="list-unstyled">
                                <li>Help Desk
                                    <ul class="list-unstyled search_item">
                                        <li><span>Configuration</span><a href="#">How to edit host and
                                                port?</a></li>
                                        <li><span>Configuration</span><a href="#">The dev Property</a></li>
                                    </ul>
                                </li>
                                <li>Support
                                    <ul class="list-unstyled search_item">
                                        <li><span>Pages</span><a href="#">The asyncData Method</a></li>
                                    </ul>
                                </li>
                                <li>Documentation
                                    <ul class="list-unstyled search_item">
                                        <li><span>Getting Started</span><a href="#">The asyncData Method</a>
                                        </li>
                                        <li><span>Getting Started</span><a href="#">The asyncData Method</a>
                                        </li>
                                        <li><span>Getting Started</span><a href="#">The asyncData Method</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <select class="search-expand-types custom-select" name="post_type" id="search_post_type">
                            <option selected disabled>Pilih Tag</option>
                            @foreach($tags as $tag)
                            <option value="{{ $tag->slug }}">{{ $tag->alias ?? $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</header>