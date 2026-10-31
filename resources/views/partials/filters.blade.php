<aside>
    <form class="d-flex mb-3" role="search" action="{{ route('products.index') }}">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
            name="search" value="{{ is_string(request('search')) ? request('search') : '' }}">
        <button class="d-flex align-items-center btn btn-primary" type="submit" aria-label="Search products">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2"
                fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
    </form>

    <form action="{{ route('products.index') }}">
        <div class="accordion mb-3" id="accordion-filters">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Category
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show"
                    data-bs-parent="#accordion-filters">
                    <div class="accordion-body">

                        <div class="form-check mb-2">


                            <input class="form-check-input" type="radio" value="eyeglasses" name="category" id="category-eyeglasses"
                            {{ request('category') == 'eyeglasses' ? 'checked' : '' }}>
                            <label class="form-check-label" for="category-eyeglasses">
                                Eye Glasses
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="sunglasses" name="category" id="category-sunglasses"
                            {{ request('category') == 'sunglasses' ? 'checked' : '' }}>
                            <label class="form-check-label" for="category-sunglasses">
                                Sun Glasses
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        Product Type
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse show"
                    data-bs-parent="#accordion-filters">
                    <div class="accordion-body">


                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="type" value="featured" id="type-featured"
                            {{ request('type') == 'featured' ? 'checked' : '' }}>
                            <label class="form-check-label" for="type-featured">
                                Featured
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" value="sale" id="type-sale"
                            {{ request('type') == 'sale' ? 'checked' : '' }}>
                            <label class="form-check-label" for="type-sale">
                                On Sale
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="d-flex align-items-center btn btn-primary mb-4">
            <svg class="me-1" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg> Apply Filters
        </button>
    </form>


</aside>